## Tentang Dashboard Realisasi Investasi

Project ini sebagai Dashboard Realisasi Investasi Pemerintah Provinsi Riau.

## Command Git untuk clone project

-   git clone https://gitlab.com/aliakbarnst92/project-realisasi-investasi-dalak.git

## Command Git untuk push pekerjaan

-   git add .
-   git commit -m "nama-pekerjaan"
-   git pull
-   git push origin master

## Konfigurasi

-   Gunakan PHP Versi >= 8.1
-   'cp .env.example .env' untuk membuat .env
-   storage tambahkan manual
-   update composer 'composer update'
-   jalankan command "php artisan key:generate" untuk mendapatkan key baru

## Command yang diperlukan

-   command migrate database 'php artisan migrate'
-   command membuat controller 'php artisan make:controller Backend\namacontrollerhurufkecilsemuaController --resource'
-   command membuat model dan migrasi 'php artisan make:model namamodelurufkecilsemua --migration'

## Aplikasi Setting

-   Import file sql 'db_realisasi.sql'
-   Username :aliakbar
-   Password :secret

## Good Luck
