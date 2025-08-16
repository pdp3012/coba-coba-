#!/bin/bash

# 🚀 Stock Alert Bot - Start Script
# Script sederhana untuk menjalankan bot

echo "🚀 Starting Stock Alert Bot..."
echo "================================"

# Check if virtual environment exists
if [ ! -d "venv" ]; then
    echo "❌ Virtual environment not found!"
    echo "🔧 Please run setup first:"
    echo "   ./deploy.sh"
    exit 1
fi

# Check if .env file exists and has real credentials
if [ ! -f ".env" ]; then
    echo "❌ .env file not found!"
    echo "🔧 Please create .env file with your credentials"
    exit 1
fi

# Check if credentials are set
source .env
if [ "$TELEGRAM_TOKEN" = "your_telegram_bot_token_here" ] || [ "$ALPHA_VANTAGE_API_KEY" = "your_alpha_vantage_api_key_here" ]; then
    echo "❌ Please update .env file with your real credentials!"
    echo "   - TELEGRAM_TOKEN: Get from @BotFather"
    echo "   - ALPHA_VANTAGE_API_KEY: Get from alphavantage.co"
    exit 1
fi

# Activate virtual environment
echo "🔧 Activating virtual environment..."
source venv/bin/activate

# Test bot first
echo "🧪 Testing bot..."
python test_bot.py

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Bot test passed! Starting bot..."
    echo ""
    echo "📱 Bot will be available on Telegram"
    echo "⏰ Checking alerts every 60 seconds"
    echo "💡 Use /help for commands"
    echo ""
    echo "🛑 Press Ctrl+C to stop the bot"
    echo ""
    
    # Start the bot
    python run_bot.py
else
    echo ""
    echo "❌ Bot test failed! Please check the errors above"
    exit 1
fi