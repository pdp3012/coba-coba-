#!/usr/bin/env python3
"""
Backup dan Restore Script untuk Stock Alert Bot
Menyimpan dan memulihkan data bot
"""

import os
import sys
import json
import shutil
import zipfile
from datetime import datetime
from pathlib import Path
import pickle

class BotBackup:
    def __init__(self):
        self.backup_dir = "backups"
        self.data_files = [
            "bot_daemon.log",
            "alert_data.pkl"
        ]
        
        # Create backup directory if not exists
        Path(self.backup_dir).mkdir(exist_ok=True)
    
    def create_backup(self, description=""):
        """Create a backup of bot data"""
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        backup_name = f"bot_backup_{timestamp}"
        
        if description:
            backup_name += f"_{description.replace(' ', '_')}"
        
        backup_path = os.path.join(self.backup_dir, backup_name)
        os.makedirs(backup_path, exist_ok=True)
        
        print(f"🔧 Creating backup: {backup_name}")
        
        # Backup configuration
        if os.path.exists(".env"):
            shutil.copy2(".env", os.path.join(backup_path, ".env"))
            print("   ✅ Configuration backed up")
        
        # Backup logs
        if os.path.exists("bot_daemon.log"):
            shutil.copy2("bot_daemon.log", os.path.join(backup_path, "bot_daemon.log"))
            print("   ✅ Logs backed up")
        
        # Backup alert data (if exists)
        if os.path.exists("alert_data.pkl"):
            shutil.copy2("alert_data.pkl", os.path.join(backup_path, "alert_data.pkl"))
            print("   ✅ Alert data backed up")
        
        # Create backup info file
        backup_info = {
            "backup_name": backup_name,
            "created_at": datetime.now().isoformat(),
            "description": description,
            "files": os.listdir(backup_path)
        }
        
        with open(os.path.join(backup_path, "backup_info.json"), "w") as f:
            json.dump(backup_info, f, indent=2)
        
        # Create zip archive
        zip_path = f"{backup_path}.zip"
        with zipfile.ZipFile(zip_path, 'w', zipfile.ZIP_DEFLATED) as zipf:
            for root, dirs, files in os.walk(backup_path):
                for file in files:
                    file_path = os.path.join(root, file)
                    arcname = os.path.relpath(file_path, backup_path)
                    zipf.write(file_path, arcname)
        
        # Remove temporary directory
        shutil.rmtree(backup_path)
        
        print(f"   ✅ Backup created: {zip_path}")
        return zip_path
    
    def list_backups(self):
        """List all available backups"""
        backups = []
        
        for file in os.listdir(self.backup_dir):
            if file.endswith(".zip"):
                file_path = os.path.join(self.backup_dir, file)
                file_size = os.path.getsize(file_path)
                file_time = datetime.fromtimestamp(os.path.getctime(file_path))
                
                backups.append({
                    "name": file,
                    "size": file_size,
                    "created": file_time,
                    "path": file_path
                })
        
        # Sort by creation time (newest first)
        backups.sort(key=lambda x: x["created"], reverse=True)
        
        if not backups:
            print("📭 No backups found")
            return []
        
        print("📦 Available Backups:")
        print("-" * 80)
        print(f"{'Name':<30} {'Size':<15} {'Created':<20}")
        print("-" * 80)
        
        for backup in backups:
            size_mb = backup["size"] / (1024 * 1024)
            created_str = backup["created"].strftime("%Y-%m-%d %H:%M:%S")
            print(f"{backup['name']:<30} {size_mb:.1f} MB{'':<10} {created_str}")
        
        return backups
    
    def restore_backup(self, backup_name):
        """Restore from a backup"""
        backup_path = os.path.join(self.backup_dir, backup_name)
        
        if not os.path.exists(backup_path):
            print(f"❌ Backup {backup_name} not found")
            return False
        
        print(f"🔧 Restoring from backup: {backup_name}")
        
        # Create temporary directory for extraction
        temp_dir = f"temp_restore_{datetime.now().strftime('%Y%m%d_%H%M%S')}"
        os.makedirs(temp_dir, exist_ok=True)
        
        try:
            # Extract backup
            with zipfile.ZipFile(backup_path, 'r') as zipf:
                zipf.extractall(temp_dir)
            
            # Find backup info
            backup_info_path = None
            for root, dirs, files in os.walk(temp_dir):
                for file in files:
                    if file == "backup_info.json":
                        backup_info_path = os.path.join(root, file)
                        break
                if backup_info_path:
                    break
            
            if backup_info_path:
                with open(backup_info_path, 'r') as f:
                    backup_info = json.load(f)
                print(f"   📅 Backup created: {backup_info['created_at']}")
                if backup_info.get('description'):
                    print(f"   📝 Description: {backup_info['description']}")
            
            # Restore files
            restored_files = []
            
            for root, dirs, files in os.walk(temp_dir):
                for file in files:
                    if file in [".env", "bot_daemon.log", "alert_data.pkl"]:
                        src_path = os.path.join(root, file)
                        dst_path = file
                        
                        # Backup existing file if it exists
                        if os.path.exists(dst_path):
                            backup_name = f"{dst_path}.backup.{datetime.now().strftime('%Y%m%d_%H%M%S')}"
                            shutil.move(dst_path, backup_name)
                            print(f"   💾 Existing {file} backed up as {backup_name}")
                        
                        # Restore file
                        shutil.copy2(src_path, dst_path)
                        restored_files.append(file)
                        print(f"   ✅ Restored: {file}")
            
            print(f"   🎉 Restore completed. {len(restored_files)} files restored.")
            return True
            
        except Exception as e:
            print(f"   ❌ Error during restore: {e}")
            return False
        
        finally:
            # Clean up temporary directory
            if os.path.exists(temp_dir):
                shutil.rmtree(temp_dir)
    
    def delete_backup(self, backup_name):
        """Delete a backup"""
        backup_path = os.path.join(self.backup_dir, backup_name)
        
        if not os.path.exists(backup_path):
            print(f"❌ Backup {backup_name} not found")
            return False
        
        try:
            os.remove(backup_path)
            print(f"✅ Backup {backup_name} deleted")
            return True
        except Exception as e:
            print(f"❌ Error deleting backup: {e}")
            return False
    
    def export_alerts(self, filename="alerts_export.json"):
        """Export current alerts to JSON file"""
        try:
            # Import alert manager
            from alert_manager import AlertManager
            
            manager = AlertManager()
            
            # Get all alerts
            all_alerts = []
            for alert in manager.alerts.values():
                alert_dict = {
                    'id': alert.id,
                    'symbol': alert.symbol,
                    'target_price': alert.target_price,
                    'alert_type': alert.alert_type,
                    'user_id': alert.user_id,
                    'created_at': alert.created_at.isoformat(),
                    'last_triggered': alert.last_triggered.isoformat() if alert.last_triggered else None,
                    'is_active': alert.is_active,
                    'message': alert.message
                }
                all_alerts.append(alert_dict)
            
            # Export to JSON
            export_data = {
                'export_date': datetime.now().isoformat(),
                'total_alerts': len(all_alerts),
                'alerts': all_alerts
            }
            
            with open(filename, 'w') as f:
                json.dump(export_data, f, indent=2)
            
            print(f"✅ Alerts exported to {filename}")
            print(f"   📊 Total alerts: {len(all_alerts)}")
            return True
            
        except Exception as e:
            print(f"❌ Error exporting alerts: {e}")
            return False
    
    def import_alerts(self, filename):
        """Import alerts from JSON file"""
        try:
            if not os.path.exists(filename):
                print(f"❌ File {filename} not found")
                return False
            
            # Import alert manager
            from alert_manager import AlertManager
            from datetime import datetime
            
            manager = AlertManager()
            
            # Read export file
            with open(filename, 'r') as f:
                export_data = json.load(f)
            
            print(f"📥 Importing {export_data['total_alerts']} alerts...")
            
            imported_count = 0
            for alert_data in export_data['alerts']:
                try:
                    # Parse datetime
                    created_at = datetime.fromisoformat(alert_data['created_at'])
                    last_triggered = None
                    if alert_data['last_triggered']:
                        last_triggered = datetime.fromisoformat(alert_data['last_triggered'])
                    
                    # Create alert
                    alert_id = manager.add_alert(
                        user_id=alert_data['user_id'],
                        symbol=alert_data['symbol'],
                        target_price=alert_data['target_price'],
                        alert_type=alert_data['alert_type'],
                        message=alert_data['message']
                    )
                    
                    # Update additional fields
                    if alert_id in manager.alerts:
                        manager.alerts[alert_id].created_at = created_at
                        manager.alerts[alert_id].last_triggered = last_triggered
                        manager.alerts[alert_id].is_active = alert_data['is_active']
                    
                    imported_count += 1
                    
                except Exception as e:
                    print(f"   ⚠️ Error importing alert {alert_data.get('id', 'unknown')}: {e}")
            
            print(f"✅ Import completed: {imported_count}/{export_data['total_alerts']} alerts imported")
            return True
            
        except Exception as e:
            print(f"❌ Error importing alerts: {e}")
            return False

def main():
    """Main function"""
    if len(sys.argv) < 2:
        print("📦 Stock Alert Bot - Backup & Restore Tool")
        print("=" * 50)
        print("Usage:")
        print("  python backup.py create [description]  - Create backup")
        print("  python backup.py list                  - List backups")
        print("  python backup.py restore <backup>      - Restore backup")
        print("  python backup.py delete <backup>       - Delete backup")
        print("  python backup.py export [filename]     - Export alerts")
        print("  python backup.py import <filename>     - Import alerts")
        return
    
    backup_tool = BotBackup()
    command = sys.argv[1].lower()
    
    if command == "create":
        description = " ".join(sys.argv[2:]) if len(sys.argv) > 2 else ""
        backup_tool.create_backup(description)
    
    elif command == "list":
        backup_tool.list_backups()
    
    elif command == "restore":
        if len(sys.argv) < 3:
            print("❌ Please specify backup name")
            return
        backup_tool.restore_backup(sys.argv[2])
    
    elif command == "delete":
        if len(sys.argv) < 3:
            print("❌ Please specify backup name")
            return
        backup_tool.delete_backup(sys.argv[2])
    
    elif command == "export":
        filename = sys.argv[2] if len(sys.argv) > 2 else "alerts_export.json"
        backup_tool.export_alerts(filename)
    
    elif command == "import":
        if len(sys.argv) < 3:
            print("❌ Please specify filename to import")
            return
        backup_tool.import_alerts(sys.argv[2])
    
    else:
        print(f"❌ Unknown command: {command}")

if __name__ == "__main__":
    main()