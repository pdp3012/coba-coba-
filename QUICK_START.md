# 🚀 Quick Start Guide - Stock Alert Bot

## ⚡ Setup dalam 5 Menit

### 1. 📱 Dapatkan Credentials
- **Telegram Bot Token**: Chat dengan @BotFather, kirim `/newbot`
- **Alpha Vantage API Key**: Daftar gratis di [alphavantage.co](https://www.alphavantage.co/)

### 2. 🔧 Setup Otomatis
```bash
# Jalankan setup otomatis
./deploy.sh
```

### 3. 📝 Update Credentials
Edit file `.env`:
```env
TELEGRAM_TOKEN=your_real_token_here
ALPHA_VANTAGE_API_KEY=your_real_api_key_here
```

### 4. 🚀 Jalankan Bot
```bash
# Cara sederhana
./start_bot.sh

# Atau manual
source venv/bin/activate
python run_bot.py
```

### 5. 📱 Test Bot
- Chat dengan bot Anda di Telegram
- Kirim `/start` untuk memulai
- Kirim `/help` untuk bantuan

## 🎯 Fitur Utama

### ✅ Alert Types
- **Above**: Alert saat harga naik di atas target
- **Below**: Alert saat harga turun di bawah target  
- **Cross**: Alert saat harga menyentuh target

### 📊 Markets Supported
- **IHSG**: ^JKSE (Jakarta Composite)
- **S&P 500**: ^GSPC
- **US Stocks**: AAPL, GOOGL, MSFT, TSLA
- **Crypto**: BTCUSD, ETHUSD
- **Indonesian Stocks**: BBCA, BBRI, ASII

### 🔔 Contoh Alert
```
/alert AAPL above 150 "Apple naik di atas $150"
/alert ^JKSE below 7000 "IHSG turun di bawah 7000"
/alert BTCUSD cross 50000 "Bitcoin menyentuh $50k"
```

## 🛠️ Management

### 📋 Management Console
```bash
./manage_bot.sh
```

### 🔍 Monitoring
```bash
source venv/bin/activate
python monitor.py
```

### 💾 Backup & Restore
```bash
# Create backup
python backup.py create "before_update"

# List backups  
python backup.py list

# Restore backup
python backup.py restore backup_name.zip
```

## 🚨 Troubleshooting

### Bot Tidak Merespon
1. Cek token Telegram di `.env`
2. Pastikan bot sudah di-start dengan `/start`
3. Jalankan `python test_bot.py`

### Alert Tidak Terkirim
1. Cek API key Alpha Vantage
2. Pastikan symbol saham valid
3. Verifikasi format alert

### Error Rate Limit
1. Bot otomatis menunggu 12 detik antara requests
2. Free tier: 5 requests per minute
3. Upgrade ke premium jika diperlukan

## 📞 Support

- **Documentation**: README.md
- **Testing**: `python test_bot.py`
- **Logs**: `bot.log` atau `bot_daemon.log`
- **Monitoring**: `python monitor.py`

## 🎉 Selamat Trading!

Bot siap digunakan! Gunakan dengan bijak dan selalu lakukan analisis fundamental sebelum trading.

---

**Next Steps**: 
- Baca README.md untuk informasi lengkap
- Setup cron jobs untuk automated management
- Customize alert settings sesuai kebutuhan