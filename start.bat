@echo off
echo Menyiapkan environment Laravel dan Machine Learning...

:: Jalankan Laravel Server di jendela cmd baru
start cmd /k "title Laravel API Server && php artisan serve"

:: Jalankan Machine Learning Python Server di jendela cmd baru
start cmd /k "title Python ML Service && cd ml_service && python app.py"

echo Selesai! Kedua server sedang dijalankan pada jendela terpisah.
exit
