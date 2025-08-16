import time
from typing import Dict, List, Optional, Tuple
from dataclasses import dataclass
from datetime import datetime
import logging
from stock_data import StockDataHandler

logger = logging.getLogger(__name__)

@dataclass
class PriceAlert:
    id: str
    symbol: str
    target_price: float
    alert_type: str  # 'above', 'below', 'cross'
    user_id: str
    created_at: datetime
    last_triggered: Optional[datetime] = None
    is_active: bool = True
    message: str = ""

class AlertManager:
    def __init__(self):
        self.alerts: Dict[str, PriceAlert] = {}
        self.stock_handler = StockDataHandler()
        self.alert_history: List[Dict] = []
        
    def add_alert(self, user_id: str, symbol: str, target_price: float, 
                  alert_type: str, message: str = "") -> str:
        """
        Add a new price alert
        """
        alert_id = f"{user_id}_{symbol}_{int(time.time())}"
        
        alert = PriceAlert(
            id=alert_id,
            symbol=symbol,
            target_price=target_price,
            alert_type=alert_type,
            user_id=user_id,
            created_at=datetime.now(),
            message=message
        )
        
        self.alerts[alert_id] = alert
        logger.info(f"Added alert: {symbol} {alert_type} {target_price}")
        return alert_id
    
    def remove_alert(self, alert_id: str, user_id: str) -> bool:
        """
        Remove an alert (only by the user who created it)
        """
        if alert_id in self.alerts and self.alerts[alert_id].user_id == user_id:
            del self.alerts[alert_id]
            logger.info(f"Removed alert: {alert_id}")
            return True
        return False
    
    def get_user_alerts(self, user_id: str) -> List[PriceAlert]:
        """
        Get all alerts for a specific user
        """
        return [alert for alert in self.alerts.values() if alert.user_id == user_id]
    
    def check_alerts(self) -> List[Tuple[PriceAlert, Dict]]:
        """
        Check all active alerts and return triggered ones
        """
        triggered_alerts = []
        
        for alert in self.alerts.values():
            if not alert.is_active:
                continue
                
            # Check cooldown (5 minutes between alerts for same stock)
            if (alert.last_triggered and 
                (datetime.now() - alert.last_triggered).seconds < 300):
                continue
            
            current_price = self.stock_handler.get_stock_price(alert.symbol)
            if current_price is None:
                continue
            
            if self._is_alert_triggered(alert, current_price):
                # Get full stock info for the alert
                stock_info = self.stock_handler.get_stock_info(alert.symbol)
                if stock_info:
                    triggered_alerts.append((alert, stock_info))
                    alert.last_triggered = datetime.now()
                    
                    # Log the trigger
                    self.alert_history.append({
                        'alert_id': alert.id,
                        'symbol': alert.symbol,
                        'target_price': alert.target_price,
                        'current_price': current_price,
                        'alert_type': alert.alert_type,
                        'triggered_at': datetime.now(),
                        'user_id': alert.user_id
                    })
        
        return triggered_alerts
    
    def _is_alert_triggered(self, alert: PriceAlert, current_price: float) -> bool:
        """
        Check if an alert should be triggered based on current price
        """
        if alert.alert_type == 'above':
            return current_price >= alert.target_price
        elif alert.alert_type == 'below':
            return current_price <= alert.target_price
        elif alert.alert_type == 'cross':
            # For cross alerts, we need to track previous state
            # This is simplified - in production you'd want more sophisticated logic
            return abs(current_price - alert.target_price) < 0.01
        
        return False
    
    def get_alert_summary(self, user_id: str) -> Dict:
        """
        Get summary of user's alerts
        """
        user_alerts = self.get_user_alerts(user_id)
        
        summary = {
            'total_alerts': len(user_alerts),
            'active_alerts': len([a for a in user_alerts if a.is_active]),
            'alerts_by_symbol': {},
            'recent_triggers': []
        }
        
        # Group by symbol
        for alert in user_alerts:
            if alert.symbol not in summary['alerts_by_symbol']:
                summary['alerts_by_symbol'][alert.symbol] = []
            summary['alerts_by_symbol'][alert.symbol].append({
                'id': alert.id,
                'target_price': alert.target_price,
                'alert_type': alert.alert_type,
                'is_active': alert.is_active,
                'created_at': alert.created_at.strftime('%Y-%m-%d %H:%M:%S')
            })
        
        # Get recent triggers for this user
        user_triggers = [h for h in self.alert_history if h['user_id'] == user_id]
        user_triggers.sort(key=lambda x: x['triggered_at'], reverse=True)
        summary['recent_triggers'] = user_triggers[:5]  # Last 5 triggers
        
        return summary