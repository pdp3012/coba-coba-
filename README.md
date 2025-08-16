# 🚀 Stock Alert Bot - Telegram Bot untuk Alert Harga Saham

Bot Telegram profesional untuk monitoring harga saham real-time dengan alert otomatis. Mendukung IHSG, S&P 500, dan Crypto dengan 100% gratis menggunakan Alpha Vantage API.

## ✨ Fitur Utama

- 📊 **Real-time Price Monitoring** - Harga saham live dari Alpha Vantage
- 🔔 **Smart Alerts** - Alert otomatis saat harga mencapai target
- 🌏 **Multi-Market Support** - IHSG, S&P 500, Crypto, dan saham individual
- 💰 **100% Gratis** - Tidak ada biaya berlangganan
- 📱 **Telegram Integration** - Notifikasi langsung ke Telegram
- ⚡ **Instant Notifications** - Alert dalam hitungan detik
- 🎯 **Flexible Alert Types** - Above, Below, dan Cross alerts

## 🛠️ Teknologi yang Digunakan

- **Python 3.8+** - Backend utama
- **python-telegram-bot** - Telegram Bot API
- **Alpha Vantage API** - Data saham real-time
- **Asyncio** - Async programming untuk performa optimal
- **Threading** - Background alert checking

## 📋 Prerequisites

Sebelum menjalankan bot, pastikan Anda memiliki:

1. **Python 3.8 atau lebih baru**
2. **Token Telegram Bot** (dari @BotFather)
3. **API Key Alpha Vantage** (gratis dari alphavantage.co)
4. **Chat ID Telegram** (untuk testing)

## 🚀 Cara Setup

### 1. Clone Repository
```bash
git clone <repository-url>
cd stock-alert-bot
```

### 2. Install Dependencies
```bash
pip install -r requirements.txt
```

### 3. Setup Environment Variables
```bash
cp .env.example .env
```

Edit file `.env` dan isi dengan credentials Anda:
```env
TELEGRAM_TOKEN=your_telegram_bot_token_here
TELEGRAM_CHAT_ID=your_chat_id_here
ALPHA_VANTAGE_API_KEY=your_alpha_vantage_api_key_here
```

### 4. Jalankan Bot
```bash
python run_bot.py
```

## 📱 Cara Mendapatkan Credentials

### Telegram Bot Token
1. Chat dengan @BotFather di Telegram
2. Kirim `/newbot`
3. Ikuti instruksi untuk membuat bot
4. Copy token yang diberikan

### Alpha Vantage API Key
1. Kunjungi [alphavantage.co](https://www.alphavantage.co/)
2. Daftar akun gratis
3. Copy API key dari dashboard

### Telegram Chat ID
1. Chat dengan bot Anda
2. Kirim pesan apapun
3. Akses: `https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getUpdates`
4. Cari `chat.id` dalam response

## 🎯 Cara Penggunaan

### Perintah Dasar
- `/start` - Memulai bot dan melihat welcome message
- `/help` - Panduan lengkap penggunaan
- `/status` - Status market saat ini

### Membuat Alert
```
/alert [SYMBOL] [TYPE] [PRICE] [MESSAGE]
```

**Contoh:**
- `/alert AAPL above 150 "Apple naik di atas $150"`
- `/alert ^JKSE below 7000 "IHSG turun di bawah 7000"`
- `/alert BTCUSD cross 50000 "Bitcoin menyentuh $50k"`

**Parameter:**
- `SYMBOL`: Kode saham (AAPL, ^JKSE, BTCUSD, dll)
- `TYPE`: Tipe alert (above, below, cross)
- `PRICE`: Harga target
- `MESSAGE`: Pesan opsional

### Mengelola Alert
- `/my_alerts` - Lihat semua alert Anda
- `/remove [ALERT_ID]` - Hapus alert tertentu
- `/price [SYMBOL]` - Cek harga real-time

## 📊 Symbol yang Didukung

### Indeks Utama
- **IHSG**: `^JKSE` (Jakarta Composite Index)
- **S&P 500**: `^GSPC`
- **NASDAQ**: `^IXIC`
- **DOW**: `^DJI`

### Saham Populer
- **US Stocks**: AAPL, GOOGL, MSFT, TSLA, AMZN
- **Indonesian Stocks**: BBCA, BBRI, ASII, TLKM, ICBP
- **Crypto**: BTCUSD, ETHUSD, ADAUSD

### Format Symbol
- **US Stocks**: AAPL, GOOGL
- **Indices**: ^JKSE, ^GSPC
- **Crypto**: BTCUSD, ETHUSD

## ⚙️ Konfigurasi Lanjutan

### Mengubah Interval Check
Edit `config.py`:
```python
CHECK_INTERVAL = 30  # Check setiap 30 detik
```

### Mengubah Cooldown Alert
```python
ALERT_COOLDOWN = 600  # 10 menit cooldown
```

### Rate Limiting
Bot sudah dikonfigurasi untuk menghormati limit Alpha Vantage:
- Free tier: 5 requests per minute
- Bot akan menunggu 12 detik antara requests

## 🔧 Troubleshooting

### Bot Tidak Merespon
1. Pastikan token Telegram benar
2. Cek apakah bot sudah di-start dengan `/start`
3. Verifikasi chat ID

### Alert Tidak Terkirim
1. Cek API key Alpha Vantage
2. Pastikan symbol saham valid
3. Verifikasi format alert

### Error Rate Limit
1. Bot akan otomatis menunggu jika melebihi limit
2. Tunggu beberapa menit sebelum request baru
3. Upgrade ke Alpha Vantage premium jika diperlukan

## 📈 Tips Trading dengan Bot

### Entry Points
- Gunakan alert "above" untuk breakout
- Gunakan alert "below" untuk support levels
- Gunakan alert "cross" untuk exact levels

### Risk Management
- Set multiple alerts untuk different levels
- Monitor volume bersama dengan price
- Gunakan technical analysis untuk konfirmasi

### Market Hours
- **IHSG**: Senin-Jumat 09:00-16:00 WIB
- **US Market**: Senin-Jumat 09:30-16:00 EST
- **Crypto**: 24/7 trading

## 🚨 Fitur Keamanan

- **User Isolation** - Setiap user hanya bisa akses alert miliknya
- **Input Validation** - Validasi semua input user
- **Rate Limiting** - Mencegah spam dan abuse
- **Error Handling** - Graceful error handling

## 📝 Logging

Bot akan mencatat semua aktivitas:
- Alert creation/deletion
- Price checks
- Error messages
- User interactions

Logs tersimpan dalam console dan bisa di-redirect ke file.

## 🔄 Update dan Maintenance

### Auto-restart
Bot akan otomatis restart jika terjadi error fatal

### Data Persistence
Alert data disimpan dalam memory (akan hilang jika bot restart)
Untuk production, gunakan database seperti SQLite atau PostgreSQL

## 📞 Support

Jika mengalami masalah:
1. Cek logs bot
2. Verifikasi credentials
3. Test dengan symbol sederhana
4. Restart bot jika diperlukan

## 🎉 Selamat Trading!

Bot ini dirancang untuk membantu trader profesional dan pemula dalam monitoring market. Gunakan dengan bijak dan selalu lakukan analisis fundamental sebelum trading.

---

**Disclaimer**: Bot ini hanya untuk informasi dan alert. Semua keputusan trading adalah tanggung jawab pengguna. Trading saham memiliki risiko, investasi dengan bijak.