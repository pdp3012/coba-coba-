# Panduan Optimasi Parameter Frequency Analyzer

## Pengantar
Dokumen ini memberikan panduan lengkap untuk mengoptimalkan parameter Frequency Analyzer berdasarkan kondisi pasar, timeframe, dan jenis aset yang berbeda.

## Parameter Utama dan Pengaruhnya

### 1. FFT Length (Panjang FFT)
**Pengaruh**: Panjang data yang digunakan untuk analisis frekuensi
**Rekomendasi berdasarkan Timeframe**:
- **1m, 5m, 15m**: 32-64 (deteksi siklus jangka pendek)
- **30m, 1h**: 64-128 (deteksi siklus menengah)
- **D (Daily)**: 64-128 (deteksi siklus harian)
- **W (Weekly)**: 128-256 (deteksi siklus mingguan)
- **M (Monthly)**: 128-256 (deteksi siklus bulanan)

**Formula Optimasi**:
```
FFT Length = Timeframe dalam menit × 2
Contoh: 1 jam = 60 menit × 2 = 120 (gunakan 128)
```

### 2. Number of Dominant Cycles
**Pengaruh**: Jumlah siklus yang akan dianalisis
**Rekomendasi berdasarkan Volatilitas**:
- **Volatilitas Rendah** (S&P 500, IHSG Blue Chips): 3-5 siklus
- **Volatilitas Sedang** (Mid Caps, Forex Majors): 5-7 siklus
- **Volatilitas Tinggi** (Cryptocurrency, Small Caps): 7-10 siklus

**Tips**: Mulai dengan 5 siklus, tambah jika pasar menunjukkan pola kompleks

### 3. Cycle Strength Threshold
**Pengaruh**: Filter untuk siklus yang lemah
**Rekomendasi berdasarkan Kondisi Pasar**:
- **Trending Market**: 0.05-0.15 (lebih sensitif)
- **Sideways Market**: 0.15-0.25 (moderat)
- **Volatile Market**: 0.25-0.35 (kurang sensitif)

**Formula Optimasi**:
```
Threshold = 0.1 + (Volatility Index × 0.05)
Contoh: VIX 20 = 0.1 + (20 × 0.05) = 0.2
```

### 4. Signal Sensitivity
**Pengaruh**: Kekuatan sinyal yang dihasilkan
**Rekomendasi berdasarkan Risk Tolerance**:
- **Conservative**: 0.5-0.6 (sinyal lebih lemah, lebih sedikit false signal)
- **Moderate**: 0.6-0.8 (balance antara sensitivitas dan akurasi)
- **Aggressive**: 0.8-1.0 (sinyal lebih kuat, lebih banyak false signal)

## Optimasi Berdasarkan Jenis Pasar

### 1. Pasar Saham Indonesia (IHSG)

#### Blue Chips (BBCA, BBRI, ASII)
```
FFT Length: 128
Dominant Cycles: 5
Cycle Threshold: 0.15
Signal Sensitivity: 0.7
Timeframe: D (Daily)
```

#### Mid Caps (GOTO, ICBP, INDF)
```
FFT Length: 64
Dominant Cycles: 7
Cycle Threshold: 0.2
Signal Sensitivity: 0.75
Timeframe: 1h atau D
```

#### Small Caps (High Volatility)
```
FFT Length: 32
Dominant Cycles: 8
Cycle Threshold: 0.25
Signal Sensitivity: 0.8
Timeframe: 15m atau 1h
```

### 2. Pasar Global (S&P 500, NASDAQ)

#### Large Cap Stocks (AAPL, MSFT, GOOGL)
```
FFT Length: 128
Dominant Cycles: 4
Cycle Threshold: 0.1
Signal Sensitivity: 0.65
Timeframe: D atau W
```

#### ETFs (SPY, QQQ, IWM)
```
FFT Length: 64
Dominant Cycles: 5
Cycle Threshold: 0.15
Signal Sensitivity: 0.7
Timeframe: 1h atau D
```

### 3. Cryptocurrency (BTC, ETH, Altcoins)

#### Bitcoin (BTC)
```
FFT Length: 64
Dominant Cycles: 8
Cycle Threshold: 0.2
Signal Sensitivity: 0.75
Timeframe: 1h atau 4h
```

#### Altcoins (High Volatility)
```
FFT Length: 32
Dominant Cycles: 10
Cycle Threshold: 0.3
Signal Sensitivity: 0.8
Timeframe: 15m atau 1h
```

## Optimasi Berdasarkan Kondisi Pasar

### 1. Bull Market (Uptrend)
```
Signal Sensitivity: +0.1 dari baseline
Cycle Threshold: -0.05 dari baseline
Dominant Cycles: +1 dari baseline
```

**Contoh**: Jika baseline Signal Sensitivity 0.7, gunakan 0.8

### 2. Bear Market (Downtrend)
```
Signal Sensitivity: -0.1 dari baseline
Cycle Threshold: +0.05 dari baseline
Dominant Cycles: -1 dari baseline
```

**Contoh**: Jika baseline Signal Sensitivity 0.7, gunakan 0.6

### 3. Sideways Market (Range)
```
Signal Sensitivity: Baseline
Cycle Threshold: Baseline
Dominant Cycles: Baseline
```

## Optimasi Berdasarkan Volatilitas

### 1. Low Volatility (VIX < 15)
```
FFT Length: +25% dari baseline
Cycle Threshold: -0.05 dari baseline
Signal Sensitivity: +0.1 dari baseline
```

### 2. Medium Volatility (VIX 15-25)
```
FFT Length: Baseline
Cycle Threshold: Baseline
Signal Sensitivity: Baseline
```

### 3. High Volatility (VIX > 25)
```
FFT Length: -25% dari baseline
Cycle Threshold: +0.1 dari baseline
Signal Sensitivity: -0.1 dari baseline
```

## Proses Optimasi Bertahap

### Langkah 1: Baseline Setup
1. Mulai dengan parameter default
2. Test pada data historis 6-12 bulan
3. Catat win rate dan profit factor

### Langkah 2: Fine Tuning
1. Optimasi FFT Length (±25%)
2. Optimasi Cycle Threshold (±0.05)
3. Optimasi Signal Sensitivity (±0.1)

### Langkah 3: Market Adaptation
1. Monitor performa setiap 2-4 minggu
2. Sesuaikan parameter berdasarkan perubahan kondisi pasar
3. Dokumentasikan perubahan dan hasilnya

## Template Konfigurasi

### Template Conservative (Low Risk)
```
FFT Length: 128
Dominant Cycles: 4
Cycle Threshold: 0.2
Signal Sensitivity: 0.6
Use Volume Filter: true
Use Trend Filter: true
```

### Template Moderate (Balanced)
```
FFT Length: 64
Dominant Cycles: 6
Cycle Threshold: 0.15
Signal Sensitivity: 0.7
Use Volume Filter: true
Use Trend Filter: true
```

### Template Aggressive (High Risk)
```
FFT Length: 32
Dominant Cycles: 8
Cycle Threshold: 0.1
Signal Sensitivity: 0.8
Use Volume Filter: false
Use Trend Filter: false
```

## Monitoring dan Evaluasi

### Metrics yang Harus Dimonitor
1. **Win Rate**: Target > 55%
2. **Profit Factor**: Target > 1.5
3. **Maximum Drawdown**: Target < 15%
4. **Sharpe Ratio**: Target > 1.0

### Frekuensi Evaluasi
- **Daily**: Monitor sinyal dan posisi
- **Weekly**: Review performa dan parameter
- **Monthly**: Optimasi parameter berdasarkan kondisi pasar
- **Quarterly**: Backtest ulang dengan data terbaru

## Troubleshooting

### Masalah: Terlalu Banyak False Signal
**Solusi**:
- Tingkatkan Cycle Threshold (+0.05)
- Kurangi Signal Sensitivity (-0.1)
- Aktifkan Volume Filter
- Aktifkan Trend Filter

### Masalah: Terlalu Sedikit Signal
**Solusi**:
- Kurangi Cycle Threshold (-0.05)
- Tingkatkan Signal Sensitivity (+0.1)
- Kurangi FFT Length (-25%)
- Nonaktifkan beberapa filter

### Masalah: Signal Terlambat
**Solusi**:
- Kurangi FFT Length (-25%)
- Tingkatkan Signal Sensitivity (+0.1)
- Kurangi Dominant Cycles (-1)

## Kesimpulan

Optimasi parameter Frequency Analyzer adalah proses yang berkelanjutan yang memerlukan:
1. **Pemahaman** pengaruh setiap parameter
2. **Adaptasi** terhadap kondisi pasar yang berubah
3. **Monitoring** performa secara konsisten
4. **Dokumentasi** perubahan dan hasilnya

Gunakan panduan ini sebagai starting point dan sesuaikan dengan gaya trading dan toleransi risiko Anda. Ingat bahwa parameter yang optimal untuk satu pasar mungkin tidak optimal untuk pasar lainnya.