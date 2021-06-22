# Aplikasi Screening Web (Server)

## Cara menggunakan:
- Clone repo ini
- Pastikan **[Composer](https://getcomposer.org/download/)** sudah terinstall
- Copy file **.env.example** dan paste dengan nama **.env**
- Ubah pengaturan dalam file .env dengan menggunakan teks editor terutama pengaturan database
- Buka terminal/cmd dan arahkan ke dalam folder hasil clone
- Ketik **_composer update_** dan tunggu hinggal proses selesai
- Selanjutnya ketik **_php artisan migrate:fresh --seed_**
- Tes jalankan dengan mengetik **_php artisan server_** dan akses melalui browser dengan alamat: **_[http://localhost:8000](http://localhost:8000)_**