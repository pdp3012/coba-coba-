import logging
from telegram import Update, InlineKeyboardButton, InlineKeyboardMarkup
from telegram.ext import Application, CommandHandler, MessageHandler, CallbackQueryHandler, filters, ContextTypes
from typing import Dict, List
import asyncio
import schedule
import threading
import time
from datetime import datetime

from config import TELEGRAM_TOKEN, TELEGRAM_CHAT_ID, CHECK_INTERVAL
from alert_manager import AlertManager
from stock_data import StockDataHandler

# Configure logging
logging.basicConfig(
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    level=logging.INFO
)
logger = logging.getLogger(__name__)

class StockAlertBot:
    def __init__(self):
        self.alert_manager = AlertManager()
        self.stock_handler = StockDataHandler()
        self.application = None
        self.is_running = False
        
        # Store user sessions
        self.user_sessions: Dict[int, Dict] = {}
        
    async def start(self, update: Update, context: ContextTypes.DEFAULT_TYPE):
        """Handle /start command"""
        user = update.effective_user
        welcome_message = f"""
🚀 **Selamat Datang di Stock Alert Bot!**

Halo {user.first_name}! Saya adalah bot yang akan membantu Anda memantau harga saham dan memberikan alert otomatis.

**Fitur Utama:**
• 📊 Monitor harga saham real-time
• 🔔 Alert otomatis saat harga mencapai target
• 📈 Support IHSG, S&P 500, dan Crypto
• 💰 100% Gratis menggunakan Alpha Vantage API

**Perintah yang tersedia:**
/help - Bantuan lengkap
/alert - Buat alert baru
/my_alerts - Lihat alert Anda
/price - Cek harga saham
/remove - Hapus alert
/status - Status market

**Contoh penggunaan:**
/alert AAPL above 150 "Apple di atas $150"
/price ^JKSE
        """
        
        await update.message.reply_text(welcome_message, parse_mode='Markdown')
    
    async def help_command(self, update: Update, context: ContextTypes.DEFAULT_TYPE):
        """Handle /help command"""
        help_text = """
📚 **Panduan Lengkap Stock Alert Bot**

**1. Membuat Alert:**
/alert [SYMBOL] [TYPE] [PRICE] [MESSAGE]
• TYPE: above, below, cross
• PRICE: harga target
• MESSAGE: pesan opsional

**Contoh:**
/alert AAPL above 150 "Apple naik di atas $150"
/alert ^JKSE below 7000 "IHSG turun di bawah 7000"
/alert BTCUSD cross 50000 "Bitcoin menyentuh $50k"

**2. Melihat Alert:**
/my_alerts - Semua alert Anda
/status - Status market saat ini

**3. Mengelola Alert:**
/remove [ALERT_ID] - Hapus alert tertentu

**4. Cek Harga:**
/price [SYMBOL] - Harga real-time

**Symbol yang didukung:**
• IHSG: ^JKSE
• S&P 500: ^GSPC  
• Apple: AAPL
• Bitcoin: BTCUSD
• Dan banyak lagi...

**Tips Trading:**
• Gunakan alert untuk entry/exit point
• Monitor volume dan perubahan harga
• Perhatikan support/resistance levels
        """
        
        await update.message.reply_text(help_text, parse_mode='Markdown')
    
    async def create_alert(self, update: Update, context: ContextTypes.DEFAULT_TYPE):
        """Handle /alert command"""
        user_id = str(update.effective_user.id)
        
        if not context.args or len(context.args) < 3:
            await update.message.reply_text(
                "❌ **Format salah!**\n\n"
                "Gunakan: /alert [SYMBOL] [TYPE] [PRICE] [MESSAGE]\n\n"
                "**Contoh:**\n"
                "/alert AAPL above 150 \"Apple naik di atas $150\"\n"
                "/alert ^JKSE below 7000 \"IHSG turun\"",
                parse_mode='Markdown'
            )
            return
        
        symbol = context.args[0].upper()
        alert_type = context.args[1].lower()
        target_price = context.args[2]
        message = " ".join(context.args[3:]) if len(context.args) > 3 else ""
        
        # Validate alert type
        if alert_type not in ['above', 'below', 'cross']:
            await update.message.reply_text(
                "❌ **Tipe alert tidak valid!**\n\n"
                "Tipe yang tersedia: above, below, cross"
            )
            return
        
        # Validate price
        try:
            target_price = float(target_price)
        except ValueError:
            await update.message.reply_text("❌ **Harga target harus berupa angka!**")
            return
        
        # Check if symbol exists by getting current price
        current_price = self.stock_handler.get_stock_price(symbol)
        if current_price is None:
            await update.message.reply_text(
                f"❌ **Symbol {symbol} tidak ditemukan atau tidak tersedia!**\n\n"
                "Pastikan symbol sudah benar dan coba lagi."
            )
            return
        
        # Create alert
        alert_id = self.alert_manager.add_alert(
            user_id=user_id,
            symbol=symbol,
            target_price=target_price,
            alert_type=alert_type,
            message=message
        )
        
        # Send confirmation
        alert_type_emoji = {"above": "📈", "below": "📉", "cross": "🎯"}
        emoji = alert_type_emoji.get(alert_type, "🔔")
        
        confirmation = f"""
{emoji} **Alert Berhasil Dibuat!**

**Symbol:** {symbol}
**Tipe:** {alert_type.upper()}
**Target Harga:** {target_price:,.2f}
**Harga Sekarang:** {current_price:,.2f}
**Pesan:** {message if message else "Tidak ada pesan"}
**ID Alert:** `{alert_id}`

Alert akan aktif dan akan mengirim notifikasi saat kondisi terpenuhi!
        """
        
        await update.message.reply_text(confirmation, parse_mode='Markdown')
    
    async def my_alerts(self, update: Update, context: ContextTypes.DEFAULT_TYPE):
        """Handle /my_alerts command"""
        user_id = str(update.effective_user.id)
        summary = self.alert_manager.get_alert_summary(user_id)
        
        if summary['total_alerts'] == 0:
            await update.message.reply_text(
                "📭 **Anda belum memiliki alert apapun!**\n\n"
                "Gunakan /alert untuk membuat alert pertama Anda."
            )
            return
        
        # Create detailed alert list
        alert_text = f"📋 **Alert Anda ({summary['total_alerts']} total, {summary['active_alerts']} aktif)**\n\n"
        
        for symbol, alerts in summary['alerts_by_symbol'].items():
            alert_text += f"**{symbol}:**\n"
            for alert in alerts:
                status = "🟢" if alert['is_active'] else "🔴"
                alert_type_emoji = {"above": "📈", "below": "📉", "cross": "🎯"}
                emoji = alert_type_emoji.get(alert['alert_type'], "🔔")
                
                alert_text += f"{status} {emoji} {alert['alert_type'].upper()} {alert['target_price']:,.2f}\n"
                alert_text += f"   ID: `{alert['id']}` | Dibuat: {alert['created_at']}\n\n"
        
        # Add recent triggers if any
        if summary['recent_triggers']:
            alert_text += "**🔔 Alert Terbaru:**\n"
            for trigger in summary['recent_triggers']:
                alert_text += f"• {trigger['symbol']} {trigger['alert_type']} {trigger['target_price']:,.2f} → {trigger['current_price']:,.2f}\n"
                alert_text += f"  {trigger['triggered_at'].strftime('%Y-%m-%d %H:%M:%S')}\n\n"
        
        # Split message if too long
        if len(alert_text) > 4000:
            parts = [alert_text[i:i+4000] for i in range(0, len(alert_text), 4000)]
            for i, part in enumerate(parts):
                await update.message.reply_text(f"{part}\n\n*Bagian {i+1}/{len(parts)}*", parse_mode='Markdown')
        else:
            await update.message.reply_text(alert_text, parse_mode='Markdown')
    
    async def check_price(self, update: Update, context: ContextTypes.DEFAULT_TYPE):
        """Handle /price command"""
        if not context.args:
            await update.message.reply_text(
                "❌ **Format salah!**\n\n"
                "Gunakan: /price [SYMBOL]\n\n"
                "**Contoh:**\n"
                "/price AAPL\n"
                "/price ^JKSE\n"
                "/price BTCUSD"
            )
            return
        
        symbol = context.args[0].upper()
        
        # Get stock info
        stock_info = self.stock_handler.get_stock_info(symbol)
        
        if stock_info is None:
            await update.message.reply_text(
                f"❌ **Tidak dapat mendapatkan data untuk {symbol}!**\n\n"
                "Pastikan symbol sudah benar dan coba lagi."
            )
            return
        
        # Format price info
        change_emoji = "📈" if stock_info['change'] >= 0 else "📉"
        change_color = "🟢" if stock_info['change'] >= 0 else "🔴"
        
        price_text = f"""
{change_emoji} **{stock_info['symbol']} - Real-time Price**

**Harga Sekarang:** ${stock_info['price']:,.2f}
**Perubahan:** {change_color} {stock_info['change']:+,.2f} ({stock_info['change_percent']})
**Harga Sebelumnya:** ${stock_info['previous_close']:,.2f}
**Volume:** {stock_info['volume']:,}

⏰ *Diperbarui: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}*
        """
        
        await update.message.reply_text(price_text, parse_mode='Markdown')
    
    async def remove_alert(self, update: Update, context: ContextTypes.DEFAULT_TYPE):
        """Handle /remove command"""
        user_id = str(update.effective_user.id)
        
        if not context.args:
            await update.message.reply_text(
                "❌ **Format salah!**\n\n"
                "Gunakan: /remove [ALERT_ID]\n\n"
                "Gunakan /my_alerts untuk melihat ID alert Anda."
            )
            return
        
        alert_id = context.args[0]
        
        if self.alert_manager.remove_alert(alert_id, user_id):
            await update.message.reply_text(
                f"✅ **Alert berhasil dihapus!**\n\n"
                f"ID: `{alert_id}`"
            )
        else:
            await update.message.reply_text(
                "❌ **Gagal menghapus alert!**\n\n"
                "Pastikan ID alert sudah benar dan alert milik Anda."
            )
    
    async def market_status(self, update: Update, context: ContextTypes.DEFAULT_TYPE):
        """Handle /status command"""
        status = self.stock_handler.get_market_status()
        
        # Get some key indices
        ihsg_price = self.stock_handler.get_stock_price('^JKSE')
        spx_price = self.stock_handler.get_stock_price('^GSPC')
        btc_price = self.stock_handler.get_stock_price('BTCUSD')
        
        status_text = f"""
📊 **Status Market Saat Ini**

**🌏 IHSG (Indonesia):**
Status: {'🟢 Terbuka' if status['IHSG_MARKET_OPEN'] else '🔴 Tutup'}
Harga: {ihsg_price:,.2f if ihsg_price else 'N/A'}

**🇺🇸 S&P 500 (US):**
Status: {'🟢 Terbuka' if status['US_MARKET_OPEN'] else '🔴 Tutup'}
Harga: {spx_price:,.2f if spx_price else 'N/A'}

**₿ Crypto:**
Status: {'🟢 24/7' if status['CRYPTO_MARKET_OPEN'] else '🔴 Tutup'}
BTC: ${btc_price:,.2f if btc_price else 'N/A'}

⏰ *Diperbarui: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}*
        """
        
        await update.message.reply_text(status_text, parse_mode='Markdown')
    
    async def send_alert_notification(self, alert, stock_info):
        """Send alert notification to user"""
        try:
            alert_type_emoji = {"above": "📈", "below": "📉", "cross": "🎯"}
            emoji = alert_type_emoji.get(alert.alert_type, "🔔")
            
            message = f"""
{emoji} **🚨 ALERT TRIGGERED! 🚨**

**Symbol:** {stock_info['symbol']}
**Tipe Alert:** {alert.alert_type.upper()}
**Target Harga:** {alert.target_price:,.2f}
**Harga Sekarang:** {stock_info['price']:,.2f}
**Perubahan:** {stock_info['change']:+,.2f} ({stock_info['change_percent']})

**Pesan:** {alert.message if alert.message else "Tidak ada pesan"}

⏰ *Dibuat: {alert.created_at.strftime('%Y-%m-%d %H:%M:%S')}*
            """
            
            # Send to specific user
            await self.application.bot.send_message(
                chat_id=alert.user_id,
                text=message,
                parse_mode='Markdown'
            )
            
            logger.info(f"Alert sent to user {alert.user_id} for {stock_info['symbol']}")
            
        except Exception as e:
            logger.error(f"Error sending alert notification: {e}")
    
    def check_alerts_loop(self):
        """Background loop to check alerts"""
        while self.is_running:
            try:
                triggered_alerts = self.alert_manager.check_alerts()
                
                for alert, stock_info in triggered_alerts:
                    # Schedule async notification
                    asyncio.run_coroutine_threadsafe(
                        self.send_alert_notification(alert, stock_info),
                        self.application.loop
                    )
                
                time.sleep(CHECK_INTERVAL)
                
            except Exception as e:
                logger.error(f"Error in alert check loop: {e}")
                time.sleep(CHECK_INTERVAL)
    
    async def start_bot(self):
        """Start the bot"""
        self.application = Application.builder().token(TELEGRAM_TOKEN).build()
        
        # Add command handlers
        self.application.add_handler(CommandHandler("start", self.start))
        self.application.add_handler(CommandHandler("help", self.help_command))
        self.application.add_handler(CommandHandler("alert", self.create_alert))
        self.application.add_handler(CommandHandler("my_alerts", self.my_alerts))
        self.application.add_handler(CommandHandler("price", self.check_price))
        self.application.add_handler(CommandHandler("remove", self.remove_alert))
        self.application.add_handler(CommandHandler("status", self.market_status))
        
        # Start alert checking in background
        self.is_running = True
        alert_thread = threading.Thread(target=self.check_alerts_loop, daemon=True)
        alert_thread.start()
        
        logger.info("Bot started successfully!")
        
        # Start the bot
        await self.application.run_polling(allowed_updates=Update.ALL_TYPES)

async def main():
    """Main function"""
    bot = StockAlertBot()
    await bot.start_bot()

if __name__ == '__main__':
    asyncio.run(main())