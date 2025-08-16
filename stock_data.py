import requests
import time
from typing import Dict, Optional, List
import logging
from config import ALPHA_VANTAGE_API_KEY, ALPHA_VANTAGE_BASE_URL

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class StockDataHandler:
    def __init__(self):
        self.api_key = ALPHA_VANTAGE_API_KEY
        self.base_url = ALPHA_VANTAGE_BASE_URL
        self.last_request_time = 0
        self.min_request_interval = 12  # Alpha Vantage free tier: 5 requests per minute
        
    def _rate_limit_check(self):
        """Ensure we don't exceed API rate limits"""
        current_time = time.time()
        if current_time - self.last_request_time < self.min_request_interval:
            sleep_time = self.min_request_interval - (current_time - self.last_request_time)
            time.sleep(sleep_time)
        self.last_request_time = time.time()
    
    def get_stock_price(self, symbol: str) -> Optional[float]:
        """
        Get current stock price from Alpha Vantage
        """
        try:
            self._rate_limit_check()
            
            params = {
                'function': 'GLOBAL_QUOTE',
                'symbol': symbol,
                'apikey': self.api_key
            }
            
            response = requests.get(self.base_url, params=params, timeout=10)
            response.raise_for_status()
            
            data = response.json()
            
            if 'Global Quote' in data and data['Global Quote']:
                price_str = data['Global Quote'].get('05. price', '0')
                return float(price_str)
            else:
                logger.warning(f"No price data found for {symbol}")
                return None
                
        except requests.exceptions.RequestException as e:
            logger.error(f"Error fetching price for {symbol}: {e}")
            return None
        except (ValueError, KeyError) as e:
            logger.error(f"Error parsing price data for {symbol}: {e}")
            return None
    
    def get_stock_info(self, symbol: str) -> Optional[Dict]:
        """
        Get comprehensive stock information
        """
        try:
            self._rate_limit_check()
            
            params = {
                'function': 'GLOBAL_QUOTE',
                'symbol': symbol,
                'apikey': self.api_key
            }
            
            response = requests.get(self.base_url, params=params, timeout=10)
            response.raise_for_status()
            
            data = response.json()
            
            if 'Global Quote' in data and data['Global Quote']:
                quote = data['Global Quote']
                return {
                    'symbol': quote.get('01. symbol', symbol),
                    'price': float(quote.get('05. price', 0)),
                    'change': float(quote.get('09. change', 0)),
                    'change_percent': quote.get('10. change percent', '0%'),
                    'volume': quote.get('06. volume', '0'),
                    'previous_close': float(quote.get('08. previous close', 0))
                }
            else:
                logger.warning(f"No data found for {symbol}")
                return None
                
        except Exception as e:
            logger.error(f"Error fetching stock info for {symbol}: {e}")
            return None
    
    def get_market_status(self) -> Dict[str, bool]:
        """
        Check if markets are open (simplified check)
        """
        # This is a simplified check - in production you'd want more sophisticated logic
        from datetime import datetime
        now = datetime.now()
        
        # Simple check for US market hours (EST/EDT)
        # For IHSG, you'd need to check Jakarta time
        is_weekend = now.weekday() >= 5
        is_market_hours = 9 <= now.hour <= 16  # Simplified
        
        return {
            'US_MARKET_OPEN': not is_weekend and is_market_hours,
            'IHSG_MARKET_OPEN': not is_weekend,  # Simplified
            'CRYPTO_MARKET_OPEN': True  # Crypto trades 24/7
        }