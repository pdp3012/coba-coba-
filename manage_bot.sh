#!/bin/bash

# 🚀 Stock Alert Bot - Management Script
# Script untuk mengelola bot dengan mudah

echo "🚀 Stock Alert Bot - Management Console"
echo "======================================="

# Function to show menu
show_menu() {
    echo ""
    echo "📋 Available Commands:"
    echo "1. Start Bot"
    echo "2. Stop Bot"
    echo "3. Restart Bot"
    echo "4. Bot Status"
    echo "5. Monitor Bot"
    echo "6. Create Backup"
    echo "7. List Backups"
    echo "8. Restore Backup"
    echo "9. Export Alerts"
    echo "10. Test Bot"
    echo "11. View Logs"
    echo "12. Setup Cron Jobs"
    echo "0. Exit"
    echo ""
    read -p "Choose an option (0-12): " choice
}

# Function to start bot
start_bot() {
    echo "🚀 Starting bot..."
    if pgrep -f "run_bot.py" > /dev/null; then
        echo "⚠️ Bot is already running!"
        return
    fi
    
    source venv/bin/activate
    nohup python run_bot.py > bot.log 2>&1 &
    echo "✅ Bot started in background (PID: $!)"
    echo "📝 Logs saved to bot.log"
}

# Function to stop bot
stop_bot() {
    echo "🛑 Stopping bot..."
    pkill -f "run_bot.py"
    if [ $? -eq 0 ]; then
        echo "✅ Bot stopped"
    else
        echo "⚠️ No bot process found"
    fi
}

# Function to restart bot
restart_bot() {
    echo "🔄 Restarting bot..."
    stop_bot
    sleep 2
    start_bot
}

# Function to check bot status
check_status() {
    echo "📊 Bot Status:"
    if pgrep -f "run_bot.py" > /dev/null; then
        PID=$(pgrep -f "run_bot.py")
        echo "🟢 Bot is running (PID: $PID)"
        
        # Check uptime
        if [ -f "bot.log" ]; then
            echo "📝 Log file exists"
            echo "📊 Log size: $(du -h bot.log | cut -f1)"
        fi
    else
        echo "🔴 Bot is not running"
    fi
}

# Function to monitor bot
monitor_bot() {
    echo "🔍 Running bot monitor..."
    source venv/bin/activate
    python monitor.py
}

# Function to create backup
create_backup() {
    echo "📦 Creating backup..."
    source venv/bin/activate
    read -p "Enter backup description (optional): " desc
    python backup.py create "$desc"
}

# Function to list backups
list_backups() {
    echo "📋 Listing backups..."
    source venv/bin/activate
    python backup.py list
}

# Function to restore backup
restore_backup() {
    echo "📥 Restoring backup..."
    source venv/bin/activate
    python backup.py list
    echo ""
    read -p "Enter backup name to restore: " backup_name
    python backup.py restore "$backup_name"
}

# Function to export alerts
export_alerts() {
    echo "📤 Exporting alerts..."
    source venv/bin/activate
    read -p "Enter filename (default: alerts_export.json): " filename
    filename=${filename:-alerts_export.json}
    python backup.py export "$filename"
}

# Function to test bot
test_bot() {
    echo "🧪 Testing bot..."
    source venv/bin/activate
    python test_bot.py
}

# Function to view logs
view_logs() {
    echo "📝 Viewing logs..."
    if [ -f "bot.log" ]; then
        echo "Last 20 lines of bot.log:"
        echo "=========================="
        tail -20 bot.log
    else
        echo "❌ No log file found"
    fi
}

# Function to setup cron jobs
setup_cron() {
    echo "⏰ Setting up cron jobs..."
    if [ "$EUID" -eq 0 ]; then
        ./cron_setup.sh
    else
        echo "❌ This requires root privileges. Run with sudo:"
        echo "   sudo ./cron_setup.sh"
    fi
}

# Main loop
while true; do
    show_menu
    
    case $choice in
        1)
            start_bot
            ;;
        2)
            stop_bot
            ;;
        3)
            restart_bot
            ;;
        4)
            check_status
            ;;
        5)
            monitor_bot
            ;;
        6)
            create_backup
            ;;
        7)
            list_backups
            ;;
        8)
            restore_backup
            ;;
        9)
            export_alerts
            ;;
        10)
            test_bot
            ;;
        11)
            view_logs
            ;;
        12)
            setup_cron
            ;;
        0)
            echo "👋 Goodbye!"
            exit 0
            ;;
        *)
            echo "❌ Invalid option. Please choose 0-12."
            ;;
    esac
    
    echo ""
    read -p "Press Enter to continue..."
done