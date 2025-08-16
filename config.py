import os
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

# Telegram Bot Configuration
TELEGRAM_TOKEN = os.getenv('TELEGRAM_TOKEN')
TELEGRAM_CHAT_ID = os.getenv('TELEGRAM_CHAT_ID')

# Alpha Vantage API Configuration
ALPHA_VANTAGE_API_KEY = os.getenv('ALPHA_VANTAGE_API_KEY')
ALPHA_VANTAGE_BASE_URL = "https://www.alphavantage.co/query"

# Bot Configuration
CHECK_INTERVAL = 60  # Check every 60 seconds
ALERT_COOLDOWN = 300  # 5 minutes cooldown between alerts for same stock

# Supported Markets
SUPPORTED_MARKETS = {
    'IHSG': '^JKSE',  # Jakarta Composite Index
    'SPX': '^GSPC',   # S&P 500
    'CRYPTO': 'BTCUSD'  # Bitcoin as example
}

# Default alert settings
DEFAULT_ALERT_TYPES = ['above', 'below', 'cross']