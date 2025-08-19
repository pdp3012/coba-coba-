# Frequency Analyzer - Pine Script Indicator

## Deskripsi
Frequency Analyzer adalah indikator teknikal canggih yang menggunakan analisis Fast Fourier Transform (FFT) untuk mengidentifikasi pola siklus dan frekuensi dalam pergerakan harga. Indikator ini dirancang untuk memberikan sinyal trading berdasarkan analisis frekuensi yang dapat mendeteksi pola tersembunyi yang tidak terlihat dengan indikator konvensional.

## Fitur Utama

### 1. Analisis FFT (Fast Fourier Transform)
- Implementasi algoritma Cooley-Tukey untuk analisis frekuensi
- Panjang FFT yang dapat disesuaikan (16-256)
- Deteksi siklus dominan berdasarkan power spectrum

### 2. Deteksi Siklus
- Identifikasi otomatis siklus harga yang dominan
- Threshold yang dapat disesuaikan untuk kekuatan siklus
- Hingga 10 siklus dominan dapat dideteksi

### 3. Sinyal Trading
- Sinyal bullish/bearish berdasarkan posisi dalam siklus
- Sensitivitas sinyal yang dapat disesuaikan
- Tiga level sinyal: Bullish, Bearish, dan Neutral

### 4. Multi-Timeframe
- Dukungan untuk berbagai timeframe (1m, 5m, 15m, 30m, 1h, D, W, M)
- Custom timeframe dengan periode yang dapat disesuaikan
- Analisis berdasarkan timeframe yang dipilih

### 5. Visualisasi
- Plot sinyal utama dengan warna yang berbeda
- Garis threshold untuk level sinyal
- Tabel informasi real-time
- Garis siklus (opsional)

### 6. Sistem Alert
- Alert otomatis untuk sinyal bullish/bearish
- Frekuensi alert yang dapat dikontrol
- Pesan alert yang informatif

## Parameter Input

### Timeframe Settings
- **Timeframe**: Pilih timeframe untuk analisis (1, 5, 15, 30, 60, D, W, M)
- **Use Custom Timeframe**: Aktifkan untuk menggunakan periode custom
- **Custom Period Length**: Panjang periode custom (5-100)

### FFT Analysis Settings
- **FFT Length**: Panjang data untuk analisis FFT (16-256)
- **Number of Dominant Cycles**: Jumlah siklus dominan yang akan dideteksi (1-10)
- **Cycle Strength Threshold**: Threshold untuk kekuatan siklus (0.01-1.0)

### Signal Settings
- **Signal Sensitivity**: Sensitivitas sinyal (0.1-1.0)
- **Enable Alerts**: Aktifkan sistem alert
- **Show Cycle Lines**: Tampilkan garis siklus

### Visual Settings
- **Bullish Signal Color**: Warna untuk sinyal bullish
- **Bearish Signal Color**: Warna untuk sinyal bearish
- **Neutral Signal Color**: Warna untuk sinyal neutral
- **Cycle Line Color**: Warna untuk garis siklus

## Cara Kerja

### 1. Pengumpulan Data
Indikator mengumpulkan data harga berdasarkan timeframe yang dipilih dan menyimpannya dalam buffer untuk analisis FFT.

### 2. Analisis FFT
- Data harga dikonversi ke domain frekuensi menggunakan FFT
- Power spectrum dihitung untuk setiap frekuensi
- Siklus dominan diidentifikasi berdasarkan kekuatan sinyal

### 3. Deteksi Siklus
- Siklus dengan power spectrum tertinggi dipilih sebagai dominan
- Threshold diterapkan untuk memfilter siklus yang lemah
- Periode siklus dihitung dari frekuensi

### 4. Generasi Sinyal
- Posisi harga saat ini dalam siklus dihitung
- Sinyal dihasilkan berdasarkan posisi dalam siklus:
  - **Bullish**: Posisi 0-25% atau 75-100% dari siklus
  - **Bearish**: Posisi 40-60% dari siklus
  - **Neutral**: Posisi lainnya

## Interpretasi Sinyal

### Sinyal Bullish (Hijau)
- Harga berada di awal atau akhir siklus
- Potensi untuk pergerakan naik
- Waktu yang baik untuk entry long

### Sinyal Bearish (Merah)
- Harga berada di tengah siklus
- Potensi untuk pergerakan turun
- Waktu yang baik untuk entry short

### Sinyal Neutral (Abu-abu)
- Harga berada di area transisi
- Tunggu konfirmasi lebih lanjut
- Hindari entry yang terlalu agresif

## Aplikasi Trading

### 1. Pasar Saham (IHSG)
- Cocok untuk saham dengan volatilitas tinggi
- Deteksi pola siklus harian/mingguan
- Entry timing yang lebih akurat

### 2. Pasar Global (S&P 500)
- Analisis siklus jangka menengah
- Deteksi pola fundamental yang berulang
- Trading berdasarkan siklus ekonomi

### 3. Cryptocurrency
- Deteksi siklus jangka pendek
- Analisis pola volatilitas tinggi
- Timing entry/exit yang presisi

## Strategi Trading

### 1. Trend Following
- Gunakan sinyal bullish untuk entry long dalam uptrend
- Gunakan sinyal bearish untuk entry short dalam downtrend
- Kombinasikan dengan analisis trend

### 2. Mean Reversion
- Entry long pada sinyal bullish di area support
- Entry short pada sinyal bearish di area resistance
- Manfaatkan siklus harga yang berulang

### 3. Breakout Trading
- Tunggu konfirmasi breakout dari level resistance/support
- Gunakan sinyal untuk timing entry yang tepat
- Kombinasikan dengan volume analysis

## Tips Penggunaan

### 1. Optimasi Parameter
- Mulai dengan FFT Length 64 untuk timeframe harian
- Sesuaikan Cycle Strength Threshold berdasarkan volatilitas pasar
- Test parameter pada data historis sebelum live trading

### 2. Konfirmasi Sinyal
- Jangan trading hanya berdasarkan satu sinyal
- Kombinasikan dengan indikator teknikal lainnya
- Perhatikan konteks pasar dan fundamental

### 3. Risk Management
- Gunakan stop loss yang tepat
- Batasi ukuran posisi
- Monitor performa indikator secara berkala

## Keunggulan

1. **Deteksi Pola Tersembunyi**: Mampu mengidentifikasi pola yang tidak terlihat dengan indikator konvensional
2. **Multi-Timeframe**: Dapat digunakan pada berbagai timeframe dan pasar
3. **Sinyal Objektif**: Mengurangi bias emosional dalam trading
4. **Real-time Analysis**: Update secara real-time dengan data terbaru
5. **Customizable**: Parameter yang dapat disesuaikan dengan kebutuhan

## Keterbatasan

1. **Ketergantungan Data Historis**: Memerlukan data historis yang cukup
2. **Latency**: Ada delay dalam perhitungan FFT
3. **False Signals**: Dapat menghasilkan sinyal palsu dalam pasar yang sangat volatile
4. **Complexity**: Memerlukan pemahaman yang baik untuk interpretasi yang tepat

## Kesimpulan

Frequency Analyzer adalah alat yang powerful untuk analisis teknikal yang dapat memberikan keunggulan kompetitif dalam trading. Dengan kombinasi analisis FFT dan deteksi siklus, indikator ini dapat membantu trader mengidentifikasi peluang trading yang tidak terdeteksi oleh metode tradisional.

Namun, seperti semua indikator teknikal, Frequency Analyzer harus digunakan sebagai bagian dari strategi trading yang komprehensif, bukan sebagai satu-satunya basis keputusan trading. Kombinasikan dengan analisis fundamental, risk management yang baik, dan disiplin trading untuk hasil yang optimal.