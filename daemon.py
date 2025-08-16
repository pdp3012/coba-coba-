#!/usr/bin/env python3
"""
Daemon Script untuk Stock Alert Bot
Menjalankan bot sebagai background process
"""

import os
import sys
import time
import signal
import logging
import atexit
from pathlib import Path

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('bot_daemon.log'),
        logging.StreamHandler()
    ]
)

logger = logging.getLogger(__name__)

class Daemon:
    def __init__(self, pid_file='/tmp/stock_bot_daemon.pid'):
        self.pid_file = pid_file
        self.running = False
        
    def daemonize(self):
        """Daemonize the process"""
        try:
            # First fork
            pid = os.fork()
            if pid > 0:
                # Exit parent
                sys.exit(0)
        except OSError as err:
            sys.stderr.write(f'fork #1 failed: {err}\n')
            sys.exit(1)
        
        # Decouple from parent environment
        os.chdir('/')
        os.umask(0)
        os.setsid()
        
        try:
            # Second fork
            pid = os.fork()
            if pid > 0:
                # Exit parent
                sys.exit(0)
        except OSError as err:
            sys.stderr.write(f'fork #2 failed: {err}\n')
            sys.exit(1)
        
        # Redirect standard file descriptors
        sys.stdout.flush()
        sys.stderr.flush()
        
        with open('/dev/null', 'r') as f:
            os.dup2(f.fileno(), sys.stdin.fileno())
        
        with open('/dev/null', 'a+') as f:
            os.dup2(f.fileno(), sys.stdout.fileno())
        
        with open('/dev/null', 'a+') as f:
            os.dup2(f.fileno(), sys.stderr.fileno())
        
        # Write pid file
        atexit.register(self.delpid)
        pid = str(os.getpid())
        with open(self.pid_file, 'w+') as f:
            f.write(pid + '\n')
    
    def delpid(self):
        """Delete the pid file"""
        os.remove(self.pid_file)
    
    def start(self):
        """Start the daemon"""
        # Check if daemon is already running
        if os.path.exists(self.pid_file):
            try:
                with open(self.pid_file, 'r') as f:
                    pid = int(f.read().strip())
                os.kill(pid, 0)
                print(f"Daemon already running with PID {pid}")
                return
            except (OSError, ValueError):
                # PID file is stale
                os.remove(self.pid_file)
        
        # Start daemon
        print("Starting daemon...")
        self.daemonize()
        self.run()
    
    def stop(self):
        """Stop the daemon"""
        if not os.path.exists(self.pid_file):
            print("Daemon not running")
            return
        
        try:
            with open(self.pid_file, 'r') as f:
                pid = int(f.read().strip())
            
            os.kill(pid, signal.SIGTERM)
            time.sleep(1)
            
            # Check if process is still running
            try:
                os.kill(pid, 0)
                print(f"Daemon with PID {pid} is still running")
            except OSError:
                print("Daemon stopped")
                if os.path.exists(self.pid_file):
                    os.remove(self.pid_file)
                    
        except (OSError, ValueError) as err:
            print(f"Error stopping daemon: {err}")
    
    def restart(self):
        """Restart the daemon"""
        self.stop()
        time.sleep(1)
        self.start()
    
    def status(self):
        """Check daemon status"""
        if not os.path.exists(self.pid_file):
            print("Daemon not running")
            return
        
        try:
            with open(self.pid_file, 'r') as f:
                pid = int(f.read().strip())
            
            os.kill(pid, 0)
            print(f"Daemon running with PID {pid}")
        except OSError:
            print("Daemon not running (stale PID file)")
            os.remove(self.pid_file)
    
    def run(self):
        """Main daemon loop"""
        logger.info("Daemon started")
        
        # Import and run bot
        try:
            from telegram_bot import main
            import asyncio
            
            # Run bot
            asyncio.run(main())
            
        except Exception as e:
            logger.error(f"Bot error: {e}")
            time.sleep(10)  # Wait before restarting

def main():
    """Main function"""
    daemon = Daemon()
    
    if len(sys.argv) == 2:
        if sys.argv[1] == 'start':
            daemon.start()
        elif sys.argv[1] == 'stop':
            daemon.stop()
        elif sys.argv[1] == 'restart':
            daemon.restart()
        elif sys.argv[1] == 'status':
            daemon.status()
        else:
            print("Unknown command")
            print("Usage: python daemon.py {start|stop|restart|status}")
            sys.exit(2)
        sys.exit(0)
    else:
        print("Usage: python daemon.py {start|stop|restart|status}")
        sys.exit(2)

if __name__ == "__main__":
    main()