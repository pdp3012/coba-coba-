#!/usr/bin/env python3
"""
Test Script untuk Stock Alert Bot
Menguji semua komponen bot sebelum deployment
"""

import os
import sys
import asyncio
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

def test_environment():
    """Test environment variables"""
    print("🔍 Testing Environment Variables...")
    
    required_vars = [
        'TELEGRAM_TOKEN',
        'ALPHA_VANTAGE_API_KEY'
    ]
    
    missing_vars = []
    for var in required_vars:
        if not os.getenv(var):
            missing_vars.append(var)
    
    if missing_vars:
        print(f"❌ Missing environment variables: {missing_vars}")
        return False
    else:
        print("✅ All environment variables are set")
        return True

def test_imports():
    """Test if all required modules can be imported"""
    print("\n🔍 Testing Module Imports...")
    
    try:
        from stock_data import StockDataHandler
        print("✅ StockDataHandler imported successfully")
        
        from alert_manager import AlertManager
        print("✅ AlertManager imported successfully")
        
        from telegram_bot import StockAlertBot
        print("✅ StockAlertBot imported successfully")
        
        return True
        
    except ImportError as e:
        print(f"❌ Import error: {e}")
        return False

def test_stock_data():
    """Test stock data functionality"""
    print("\n🔍 Testing Stock Data Handler...")
    
    try:
        from stock_data import StockDataHandler
        
        handler = StockDataHandler()
        
        # Test getting a simple stock price
        print("Testing AAPL price fetch...")
        price = handler.get_stock_price('AAPL')
        
        if price:
            print(f"✅ AAPL price: ${price:,.2f}")
        else:
            print("⚠️ Could not fetch AAPL price (API limit or network issue)")
        
        # Test market status
        status = handler.get_market_status()
        print(f"✅ Market status: {status}")
        
        return True
        
    except Exception as e:
        print(f"❌ Stock data test error: {e}")
        return False

def test_alert_manager():
    """Test alert manager functionality"""
    print("\n🔍 Testing Alert Manager...")
    
    try:
        from alert_manager import AlertManager
        from datetime import datetime
        
        manager = AlertManager()
        
        # Test creating an alert
        test_user_id = "test_user_123"
        alert_id = manager.add_alert(
            user_id=test_user_id,
            symbol="AAPL",
            target_price=150.0,
            alert_type="above",
            message="Test alert"
        )
        
        print(f"✅ Alert created with ID: {alert_id}")
        
        # Test getting user alerts
        user_alerts = manager.get_user_alerts(test_user_id)
        print(f"✅ User alerts count: {len(user_alerts)}")
        
        # Test alert summary
        summary = manager.get_alert_summary(test_user_id)
        print(f"✅ Alert summary: {summary['total_alerts']} total alerts")
        
        # Clean up test alert
        manager.remove_alert(alert_id, test_user_id)
        print("✅ Test alert cleaned up")
        
        return True
        
    except Exception as e:
        print(f"❌ Alert manager test error: {e}")
        return False

def test_config():
    """Test configuration loading"""
    print("\n🔍 Testing Configuration...")
    
    try:
        from config import (
            TELEGRAM_TOKEN, 
            ALPHA_VANTAGE_API_KEY, 
            ALPHA_VANTAGE_BASE_URL,
            CHECK_INTERVAL
        )
        
        print(f"✅ Telegram token: {'Set' if TELEGRAM_TOKEN else 'Not set'}")
        print(f"✅ Alpha Vantage API key: {'Set' if ALPHA_VANTAGE_API_KEY else 'Not set'}")
        print(f"✅ Alpha Vantage URL: {ALPHA_VANTAGE_BASE_URL}")
        print(f"✅ Check interval: {CHECK_INTERVAL} seconds")
        
        return True
        
    except Exception as e:
        print(f"❌ Configuration test error: {e}")
        return False

async def test_telegram_bot():
    """Test Telegram bot initialization"""
    print("\n🔍 Testing Telegram Bot...")
    
    try:
        from telegram_bot import StockAlertBot
        
        bot = StockAlertBot()
        print("✅ StockAlertBot instance created")
        
        # Test bot methods exist
        methods = ['start', 'help_command', 'create_alert', 'my_alerts']
        for method in methods:
            if hasattr(bot, method):
                print(f"✅ Method {method} exists")
            else:
                print(f"❌ Method {method} missing")
                return False
        
        return True
        
    except Exception as e:
        print(f"❌ Telegram bot test error: {e}")
        return False

def main():
    """Run all tests"""
    print("🚀 Starting Stock Alert Bot Tests...\n")
    
    tests = [
        ("Environment Variables", test_environment),
        ("Module Imports", test_imports),
        ("Configuration", test_config),
        ("Stock Data Handler", test_stock_data),
        ("Alert Manager", test_alert_manager),
        ("Telegram Bot", lambda: asyncio.run(test_telegram_bot()))
    ]
    
    passed = 0
    total = len(tests)
    
    for test_name, test_func in tests:
        try:
            if test_func():
                passed += 1
            else:
                print(f"❌ {test_name} test failed")
        except Exception as e:
            print(f"❌ {test_name} test error: {e}")
    
    print(f"\n📊 Test Results: {passed}/{total} tests passed")
    
    if passed == total:
        print("🎉 All tests passed! Bot is ready to run.")
        print("\n🚀 To start the bot, run:")
        print("python run_bot.py")
    else:
        print("⚠️ Some tests failed. Please fix the issues before running the bot.")
        return 1
    
    return 0

if __name__ == "__main__":
    sys.exit(main())