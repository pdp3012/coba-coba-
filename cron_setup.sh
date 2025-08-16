#!/bin/bash

# 🚀 Stock Alert Bot - Cron Setup Script
# Setup cron jobs untuk automated bot management

echo "🚀 Setting up Cron Jobs for Stock Alert Bot"
echo "============================================"

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo "❌ This script must be run as root (use sudo)"
    exit 1
fi

# Get current user
CURRENT_USER=$(who am i | awk '{print $1}')
if [ -z "$CURRENT_USER" ]; then
    CURRENT_USER=$SUDO_USER
fi

if [ -z "$CURRENT_USER" ]; then
    echo "❌ Cannot determine current user"
    exit 1
fi

echo "✅ Setting up cron for user: $CURRENT_USER"

# Create cron directory if not exists
CRON_DIR="/var/spool/cron/crontabs"
if [ ! -d "$CRON_DIR" ]; then
    CRON_DIR="/var/spool/cron"
fi

# Get current working directory
BOT_DIR=$(pwd)
echo "✅ Bot directory: $BOT_DIR"

# Create cron entries
echo "🔧 Creating cron entries..."

# Function to add cron job
add_cron_job() {
    local schedule="$1"
    local command="$2"
    local description="$3"
    
    echo "   📅 $description"
    echo "      Schedule: $schedule"
    echo "      Command: $command"
    
    # Add to user's crontab
    (crontab -u "$CURRENT_USER" -l 2>/dev/null; echo "$schedule $command") | crontab -u "$CURRENT_USER" -
}

# Add cron jobs
add_cron_job "0 9 * * 1-5" "cd $BOT_DIR && python monitor.py > /tmp/bot_monitor.log 2>&1" "Daily market open monitoring (Mon-Fri 9:00 AM)"
add_cron_job "0 16 * * 1-5" "cd $BOT_DIR && python monitor.py > /tmp/bot_monitor.log 2>&1" "Daily market close monitoring (Mon-Fri 4:00 PM)"
add_cron_job "0 */6 * * *" "cd $BOT_DIR && python backup.py create auto_backup > /tmp/bot_backup.log 2>&1" "Auto backup every 6 hours"
add_cron_job "0 2 * * 0" "cd $BOT_DIR && python backup.py create weekly_backup > /tmp/bot_backup.log 2>&1" "Weekly backup (Sunday 2:00 AM)"
add_cron_job "0 3 * * *" "cd $BOT_DIR && find backups/ -name '*.zip' -mtime +30 -delete > /tmp/bot_cleanup.log 2>&1" "Clean old backups (older than 30 days)"

echo ""
echo "✅ Cron jobs have been set up successfully!"
echo ""
echo "📋 Cron Jobs Summary:"
echo "====================="
echo "• Market monitoring: 9:00 AM & 4:00 PM (Mon-Fri)"
echo "• Auto backup: Every 6 hours"
echo "• Weekly backup: Sunday 2:00 AM"
echo "• Cleanup: Daily at 3:00 AM"
echo ""
echo "📝 To view current cron jobs:"
echo "   crontab -u $CURRENT_USER -l"
echo ""
echo "📝 To edit cron jobs:"
echo "   crontab -u $CURRENT_USER -e"
echo ""
echo "📝 To remove all cron jobs:"
echo "   crontab -u $CURRENT_USER -r"
echo ""
echo "📊 Log files will be created in /tmp/"
echo "   - /tmp/bot_monitor.log"
echo "   - /tmp/bot_backup.log"
echo "   - /tmp/bot_cleanup.log"
echo ""
echo "🎉 Setup complete! Bot will now run automatically based on schedule."