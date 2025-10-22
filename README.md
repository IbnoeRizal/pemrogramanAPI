# Repo project tugas ujicoba midtrans api

repository ini dibuat untuk mensimulasikan pembayaran online melalui api dari midtrans, dan hanya mengimplementasikan lingkungan ujicoba.
***

tools yang dibutuhkan untuk menjalankan project ini
- web server
- database server
- composer (package manager php)
- laravel
- php

persiapan
- daftarkan akun ke midtrans
- copy file .env.example dengan nama .env
- tambahkan file .csv ke folder database\seeders\data\passwordwifi.csv
- tambahkan ke file .env (nilai sandbox midtrans didapatkan di midtrans sandbox)
    |variable env| nilai|
    |------------|------|
    |MIDTRANS_SERVER_KEY|sandbox midtrans|
    |MIDTRANS_CLIENT_KEY|sandbox midtrans|
    |MIDTRANS_MERCHANT_ID|sandbox midtrans|
    |MIDTRANS_ISPRODUCTION|false|
    |MIDTRANS_ISSANITIZED|true|
    |MIDTRANS_IS3DS|true|
    |CVSNAMEFILE|path ke file .csv|
  - buat database dan cantumkan nama database tersebut di file .env

jalankan command pada terminal di directory project berada
- composer install
- composer dump-autoload
- php artisan key:generate
- php artisan migrate:fresh --seed

jika menggunakan proyek local
- ganti kode bagian app\Providers\AppServiceProvider.php
   ```php
    if ($this->app->environment('local') || $this->app->environment('production')) {
            URL::forceScheme('https');
       }
   ```
   menjadi
  ```php
   if ($this->app->environment('local') || $this->app->environment('production')) {
            URL::forceScheme('http');
        }
  ```
  
