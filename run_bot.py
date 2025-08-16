#!/usr/bin/env python3
"""
Stock Alert Bot Runner
Simple script to run the Telegram bot
"""

import asyncio
import logging
from telegram_bot import main

if __name__ == "__main__":
    # Configure logging
    logging.basicConfig(
        level=logging.INFO,
        format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
    )
    
    print("🚀 Starting Stock Alert Bot...")
    print("📱 Bot will be available on Telegram")
    print("⏰ Checking alerts every 60 seconds")
    print("💡 Use /help for commands")
    
    try:
        asyncio.run(main())
    except KeyboardInterrupt:
        print("\n🛑 Bot stopped by user")
    except Exception as e:
        print(f"❌ Error running bot: {e}")
        logging.error(f"Bot error: {e}")