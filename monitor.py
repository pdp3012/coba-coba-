#!/usr/bin/env python3
"""
Monitoring Script untuk Stock Alert Bot
Memantau kesehatan dan performa bot
"""

import os
import sys
import time
import psutil
import requests
import json
from datetime import datetime, timedelta
from pathlib import Path

class BotMonitor:
    def __init__(self):
        self.bot_process = None
        self.log_file = "bot_daemon.log"
        self.pid_file = "/tmp/stock_bot_daemon.pid"
        
    def find_bot_process(self):
        """Find bot process by name or PID file"""
        # Check PID file first
        if os.path.exists(self.pid_file):
            try:
                with open(self.pid_file, 'r') as f:
                    pid = int(f.read().strip())
                process = psutil.Process(pid)
                if "python" in process.cmdline()[0] and "run_bot" in " ".join(process.cmdline()):
                    self.bot_process = process
                    return True
            except (ValueError, psutil.NoSuchProcess):
                pass
        
        # Search by process name
        for proc in psutil.process_iter(['pid', 'name', 'cmdline']):
            try:
                cmdline = " ".join(proc.info['cmdline'])
                if "run_bot" in cmdline or "telegram_bot" in cmdline:
                    self.bot_process = proc
                    return True
            except (psutil.NoSuchProcess, psutil.AccessDenied):
                continue
        
        return False
    
    def get_system_info(self):
        """Get system resource information"""
        cpu_percent = psutil.cpu_percent(interval=1)
        memory = psutil.virtual_memory()
        disk = psutil.disk_usage('/')
        
        return {
            'cpu_percent': cpu_percent,
            'memory_percent': memory.percent,
            'memory_available': memory.available // (1024**3),  # GB
            'disk_percent': disk.percent,
            'disk_free': disk.free // (1024**3)  # GB
        }
    
    def get_bot_info(self):
        """Get bot process information"""
        if not self.bot_process:
            return None
        
        try:
            cpu_percent = self.bot_process.cpu_percent()
            memory_info = self.bot_process.memory_info()
            create_time = datetime.fromtimestamp(self.bot_process.create_time())
            uptime = datetime.now() - create_time
            
            return {
                'pid': self.bot_process.pid,
                'cpu_percent': cpu_percent,
                'memory_mb': memory_info.rss // (1024**2),
                'uptime': str(uptime).split('.')[0],
                'status': self.bot_process.status()
            }
        except psutil.NoSuchProcess:
            return None
    
    def check_logs(self, hours=1):
        """Check recent log entries"""
        if not os.path.exists(self.log_file):
            return []
        
        try:
            current_time = time.time()
            cutoff_time = current_time - (hours * 3600)
            
            logs = []
            with open(self.log_file, 'r') as f:
                for line in f:
                    try:
                        # Parse timestamp from log line
                        timestamp_str = line.split(' - ')[0]
                        timestamp = datetime.strptime(timestamp_str, '%Y-%m-%d %H:%M:%S,%f')
                        
                        if timestamp.timestamp() > cutoff_time:
                            logs.append(line.strip())
                    except:
                        continue
            
            return logs[-50:]  # Last 50 log entries
        except Exception as e:
            return [f"Error reading logs: {e}"]
    
    def check_telegram_api(self, token):
        """Check Telegram API connectivity"""
        try:
            url = f"https://api.telegram.org/bot{token}/getMe"
            response = requests.get(url, timeout=10)
            
            if response.status_code == 200:
                data = response.json()
                return {
                    'status': 'OK',
                    'bot_name': data['result']['first_name'],
                    'username': data['result']['username']
                }
            else:
                return {
                    'status': 'ERROR',
                    'error': f"HTTP {response.status_code}"
                }
        except Exception as e:
            return {
                'status': 'ERROR',
                'error': str(e)
            }
    
    def check_alpha_vantage_api(self, api_key):
        """Check Alpha Vantage API connectivity"""
        try:
            url = "https://www.alphavantage.co/query"
            params = {
                'function': 'GLOBAL_QUOTE',
                'symbol': 'AAPL',
                'apikey': api_key
            }
            
            response = requests.get(url, params=params, timeout=10)
            
            if response.status_code == 200:
                data = response.json()
                if 'Global Quote' in data:
                    return {
                        'status': 'OK',
                        'response_time': response.elapsed.total_seconds()
                    }
                else:
                    return {
                        'status': 'ERROR',
                        'error': 'Invalid response format'
                    }
            else:
                return {
                    'status': 'ERROR',
                    'error': f"HTTP {response.status_code}"
                }
        except Exception as e:
            return {
                'status': 'ERROR',
                'error': str(e)
            }
    
    def generate_report(self):
        """Generate comprehensive monitoring report"""
        print("🔍 Stock Alert Bot - Monitoring Report")
        print("=" * 50)
        print(f"📅 Generated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
        print()
        
        # System Information
        print("💻 System Information:")
        sys_info = self.get_system_info()
        print(f"   CPU Usage: {sys_info['cpu_percent']:.1f}%")
        print(f"   Memory Usage: {sys_info['memory_percent']:.1f}% ({sys_info['memory_available']:.1f} GB available)")
        print(f"   Disk Usage: {sys_info['disk_percent']:.1f}% ({sys_info['disk_free']:.1f} GB free)")
        print()
        
        # Bot Process Information
        print("🤖 Bot Process Information:")
        if self.find_bot_process():
            bot_info = self.get_bot_info()
            if bot_info:
                print(f"   Status: 🟢 Running (PID: {bot_info['pid']})")
                print(f"   CPU Usage: {bot_info['cpu_percent']:.1f}%")
                print(f"   Memory Usage: {bot_info['memory_mb']:.1f} MB")
                print(f"   Uptime: {bot_info['uptime']}")
            else:
                print("   Status: 🔴 Process not responding")
        else:
            print("   Status: 🔴 Not running")
        print()
        
        # API Status
        print("🌐 API Status:")
        
        # Load environment variables
        from dotenv import load_dotenv
        load_dotenv()
        
        telegram_token = os.getenv('TELEGRAM_TOKEN')
        alpha_vantage_key = os.getenv('ALPHA_VANTAGE_API_KEY')
        
        if telegram_token:
            telegram_status = self.check_telegram_api(telegram_token)
            if telegram_status['status'] == 'OK':
                print(f"   Telegram API: 🟢 OK ({telegram_status['bot_name']} @{telegram_status['username']})")
            else:
                print(f"   Telegram API: 🔴 ERROR - {telegram_status['error']}")
        else:
            print("   Telegram API: ⚠️ Token not configured")
        
        if alpha_vantage_key:
            alpha_status = self.check_alpha_vantage_api(alpha_vantage_key)
            if alpha_status['status'] == 'OK':
                print(f"   Alpha Vantage API: 🟢 OK ({alpha_status['response_time']:.2f}s)")
            else:
                print(f"   Alpha Vantage API: 🔴 ERROR - {alpha_status['error']}")
        else:
            print("   Alpha Vantage API: ⚠️ API key not configured")
        print()
        
        # Recent Logs
        print("📝 Recent Logs (Last Hour):")
        logs = self.check_logs(1)
        if logs:
            for log in logs[-10:]:  # Show last 10 logs
                print(f"   {log}")
        else:
            print("   No recent logs found")
        print()
        
        # Recommendations
        print("💡 Recommendations:")
        if not self.find_bot_process():
            print("   🔴 Start the bot using: python run_bot.py")
        
        if sys_info['memory_percent'] > 80:
            print("   ⚠️ High memory usage - consider restarting bot")
        
        if sys_info['disk_percent'] > 90:
            print("   ⚠️ Low disk space - check log files")
        
        if not telegram_token or not alpha_vantage_key:
            print("   ⚠️ Missing API credentials - check .env file")
        
        print()
        print("=" * 50)

def main():
    """Main function"""
    monitor = BotMonitor()
    monitor.generate_report()

if __name__ == "__main__":
    main()