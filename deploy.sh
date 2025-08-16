#!/bin/bash

# 🚀 Stock Alert Bot Deployment Script
# Script otomatis untuk setup dan deploy bot

echo "🚀 Stock Alert Bot - Auto Deployment"
echo "====================================="

# Check if Python is installed
if ! command -v python3 &> /dev/null; then
    echo "❌ Python 3 tidak ditemukan!"
    echo "Silakan install Python 3.8+ terlebih dahulu"
    exit 1
fi

echo "✅ Python 3 ditemukan: $(python3 --version)"

# Check if pip is installed
if ! command -v pip3 &> /dev/null; then
    echo "❌ pip3 tidak ditemukan!"
    echo "Silakan install pip3 terlebih dahulu"
    exit 1
fi

echo "✅ pip3 ditemukan: $(pip3 --version)"

# Create virtual environment
echo "🔧 Membuat virtual environment..."
python3 -m venv venv

# Activate virtual environment
echo "🔧 Mengaktifkan virtual environment..."
source venv/bin/activate

# Upgrade pip
echo "🔧 Upgrade pip..."
pip install --upgrade pip

# Install requirements
echo "🔧 Install dependencies..."
pip install -r requirements.txt

# Check if .env file exists
if [ ! -f .env ]; then
    echo "⚠️ File .env tidak ditemukan!"
    echo "🔧 Membuat file .env dari template..."
    cp .env.example .env
    
    echo ""
    echo "📝 Silakan edit file .env dan isi dengan credentials Anda:"
    echo "   - TELEGRAM_TOKEN: Token bot dari @BotFather"
    echo "   - ALPHA_VANTAGE_API_KEY: API key dari Alpha Vantage"
    echo "   - TELEGRAM_CHAT_ID: Chat ID untuk testing (opsional)"
    echo ""
    echo "Setelah mengisi credentials, jalankan script ini lagi"
    exit 1
fi

# Test the bot
echo "🧪 Testing bot..."
python test_bot.py

if [ $? -eq 0 ]; then
    echo ""
    echo "🎉 Deployment berhasil!"
    echo ""
    echo "🚀 Untuk menjalankan bot:"
    echo "   source venv/bin/activate"
    echo "   python run_bot.py"
    echo ""
    echo "📱 Bot akan tersedia di Telegram"
    echo "💡 Gunakan /help untuk melihat perintah yang tersedia"
else
    echo ""
    echo "❌ Testing gagal! Silakan periksa error di atas"
    echo "🔧 Pastikan semua credentials sudah diisi dengan benar"
fi