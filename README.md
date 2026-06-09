# ☸ ລະບົບ Buddhist EMS (Buddhist Enterprise Management System)
> ລະບົບຈັດການບໍລິຫານວຽກງານພຸດທະສາສະໜາ ແລະ ຄຸ້ມຄອງຊັບພະຍາກອນພາຍໃນວັດ (Temple CRS)

ລະບົບ **Buddhist EMS** ເປັນລະບົບເວັບແອັບພລິເຄຊັນທີ່ພັດທະນາຂຶ້ນດ້ວຍ **Laravel** ແລະ **Livewire** ເພື່ອຊ່ວຍອຳນວຍຄວາມສະດວກໃນການບໍລິຫານຈັດການວຽກງານຕ່າງໆ ພາຍໃນອົງການພຸດທະສາສະໜາສຳພັນ ແຫ່ງ ສປປ ລາວ (ອພສ) ຫຼື ວັດວາອາຮາມ ໃຫ້ມີຄວາມເປັນລະບຽບຮຽບຮ້ອຍ, ໂປ່ງໃສ ແລະ ທັນສະໄໝ.

---

## 🌟 ຄຸນສົມບັດຫຼັກຂອງລະບົບ (System Features)

ລະບົບຖືກແບ່ງອອກເປັນ 2 ພາກສ່ວນຫຼັກ ຄື: **ໜ້າເວັບໄຊສາທາລະນະ (Public Frontend)** ແລະ **ລະບົບຄຸ້ມຄອງຫຼັງບ້ານ (Admin Panel)**.

### 1. ລະບົບຫຼັງບ້ານສຳລັບຜູ້ບໍລິຫານ (Admin Panel)

*   **ລະບົບຄຸ້ມຄອງບຸກຄະລາກອນ (Personnel Management)**:
    *   ຈັດການຂໍ້ມູນພະສົງ, ສາມະເນນ ແລະ ຄະນະກຳມະການວັດ.
    *   ບັນທຶກປະຫວັດ, ໜ້າທີ່ຮັບຜິດຊອບ, ສະຖານະ, ເບີໂທລະສັບ ແລະ ຮູບພາບ.
*   **ລະບົບຄຸ້ມຄອງເອກະສານ (Document Management System - DMS)**:
    *   ອັບໂຫຼດ ແລະ ຈັດເກັບເອກະສານທາງການ, ມະຕິຕົກລົງ, ກົດລະບຽບ ແລະ ຄຳສັ່ງຕ່າງໆ.
    *   ແບ່ງໝວດໝູ່ເອກະສານເພື່ອໃຫ້ຄົ້ນຫາໄດ້ງ່າຍ.
    *   ລະບົບດາວໂຫຼດເອກະສານທີ່ປອດໄພ.
*   **ລະບົບຂ່າວສານ ແລະ ບົດຄວາມ (News & Article CMS)**:
    *   ຂຽນ ແລະ ຈັດການບົດຄວາມ, ຂ່າວສານປະຊາສຳພັນ, ກິດຈະກຳຂອງວັດ.
    *   ຈັດການສະໄລ້ຮູບພາບ (Hero Slides) ຢູ່ໜ້າທຳອິດຂອງເວັບໄຊ.
*   **ລະບົບຄຸ້ມຄອງການເງິນ (Financial & Accounting Management)**:
    *   ບັນທຶກລາຍຮັບ ແລະ ລາຍຈ່າຍຢ່າງລະອຽດ (ວັນທີ, ປະເພດ, ໝວດໝູ່, ຄຳອະທິບາຍ, ມູນຄ່າ ແລະ ເລກທີອ້າງອີງ).
    *   ຈັດການໝວດໝູ່ການເງິນ (Category Management).
    *   ສະແດງກຣາຟແນວໂນ້ມການເງິນລາຍເດືອນ ແລະ ອັດຕາສ່ວນລາຍຮັບ-ລາຍຈ່າຍແບບ Interactive.
    *   **ລະບົບອອກບົດລາຍງານ PDF (Official PDF Report)**:
        *   ສ້າງລາຍງານການເງິນເປັນໄຟລ໌ PDF ຕາມຮູບແບບ ແລະ ມາດຕະຖານເອກະສານທາງການລາວ.
        *   ລະບົບຈັດວາງຂອບເຈ້ຍ (Margins) ແລະ ຟອນ Phetsarath OT ຢ່າງສວຍງາມ ແລະ ເປັນລະບຽບ.
        *   ສະແດງສ່ວນທ້າຍເອກະສານ (Footer) ທີ່ເປັນຂໍ້ມູນຕິດຕໍ່ຂອງຫ້ອງການ **ສະເພາະໜ້າສຸດທ້າຍເທົ່ານັ້ນ** (ບໍ່ສະແດງຊ້ຳກັນທຸກໜ້າ) ເພື່ອໃຫ້ຖືກຕ້ອງຕາມຮູບແບບເອກະສານທາງການ.
*   **ລະບົບຕັ້ງຄ່າທົ່ວໄປ (System Settings)**:
    *   ກຳນົດຊື່ອົງການຈັດຕັ້ງ (ພາສາລາວ ແລະ ອັງກິດ), ທີ່ຢູ່, ເບີໂທ, ອີເມວ ແລະ ອັບໂຫຼດກາປະທັບ/ໂລໂກ້ຂອງວັດ.
    *   ຂໍ້ມູນເຫຼົ່ານີ້ຈະໄປສະແດງຢູ່ໜ້າເວັບໄຊ ແລະ ສ່ວນຫົວ/ສ່ວນທ້າຍຂອງເອກະສານ PDF ໂດຍອັດຕະໂນມັດ.
*   **ລະບົບຈັດການຜູ້ໃຊ້ (User Management)**:
    *   ຄຸ້ມຄອງບັນຊີຜູ້ໃຊ້ທີ່ສາມາດເຂົ້າລະບົບໄດ້.
    *   ກຳນົດບົດບາດ ແລະ ສິດທິການໃຊ້ງານ (Roles) ເຊັ່ນ: *Super Admin* (ຈັດການໄດ້ທຸກຢ່າງ), *Admin* (ຈັດການຂໍ້ມູນທົ່ວໄປ), ແລະ *Staff* (ບັນທຶກຂໍ້ມູນທົ່ວໄປ).

### 2. ໜ້າເວັບໄຊສາທາລະນະ (Public Frontend)

*   **ໜ້າຫຼັກ (Home)**: ສະແດງສະໄລ້ກິດຈະກຳ, ຂ່າວສານຫຼ້າສຸດ ແລະ ເນື້ອຫາແນະນຳ.
*   **ໜ້າຄະນະກຳມະການ (Committee)**: ສະແດງລາຍຊື່ ແລະ ຮູບພາບຂອງພະສົງ ຫຼື ຄະນະກຳມະການວັດ.
*   **ໜ້າຫ້ອງສະໝຸດ (Library)**: ສະແດງ ແລະ ເປີດໃຫ້ປະຊາຊົນທົ່ວໄປສາມາດດາວໂຫຼດເອກະສານເຜີຍແຜ່ ຫຼື ມະຕິດຕ່າງໆ.
*   **ລະບົບສອງພາສາ (Localization)**: ຮອງຮັບການປ່ຽນພາສາ (ລາວ ແລະ ອັງກິດ) ໄດ້ທັງໜ້າເວັບໄຊທົ່ວໄປ ແລະ ລະບົບຫຼັງບ້ານ.

---

## 🛠️ ເຕັກໂນໂລຢີທີ່ໃຊ້ (Technology Stack)

*   **Backend Framework**: Laravel 13.x (PHP 8.3+)
*   **Frontend Interactivity**: Livewire 4.x (SPA-like performance)
*   **Styling & Design**: Tailwind CSS 4.x & Material Symbols icons
*   **Database**: SQLite (ສາມາດປ່ຽນເປັນ MySQL/PostgreSQL ໄດ້ງ່າຍ)
*   **PDF Engine**: Dompdf (ຜ່ານ `barryvdh/laravel-dompdf`)
*   **Build Tool**: Vite 8.x

---

## 💻 ຂັ້ນຕອນການຕິດຕັ້ງໃນເຄື່ອງພັດທະນາ (Local Installation)

### ສິ່ງທີ່ຕ້ອງມີກ່ອນ (Prerequisites):
*   PHP 8.3 ຂຶ້ນໄປ ພ້ອມ Extension: `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `sqlite3`, `fileinfo`, `gd`.
*   Composer
*   Node.js (LTS version) ແລະ NPM

### ຂັ້ນຕອນການຕິດຕັ້ງ:

1.  **ດາວໂຫຼດ ແລະ ເປີດໂຟນເດີໂຄງການ**:
    ```bash
    cd templecrs
    ```

2.  **ດຳເນີນການຕິດຕັ້ງ ແລະ ຕັ້ງຄ່າອັດຕະໂນມັດ (One-Command Setup)**:
    ໂຄງການນີ້ໄດ້ຂຽນ Script ສຳລັບການ Setup ໄວ້ໃນ `composer.json` ຮຽບຮ້ອຍແລ້ວ. ທ່ານພຽງແຕ່ດຳເນີນຄຳສັ່ງດຽວ ລະບົບຈະທຳການຕິດຕັ້ງ Dependencies, ສ້າງໄຟລ໌ `.env`, ສ້າງ Database, Generate Key ແລະ Build Assets ໃຫ້ອັດຕະໂນມັດ:
    ```bash
    composer run setup
    ```

3.  **ສ້າງຂໍ້ມູນຕົວຢ່າງ ແລະ ບັນຊີຜູ້ໃຊ້ (Database Seeding)**:
    ເພື່ອສ້າງຂໍ້ມູນຕົວຢ່າງ (ບຸກຄະລາກອນ, ຂ່າວສານ, ເອກະສານ) ແລະ ບັນຊີ Admin ເຂົ້າລະບົບ:
    ```bash
    php artisan db:seed
    ```
    *   **ບັນຊີເຂົ້າລະບົບຫຼ້າສຸດ**:
        *   **Super Admin**: `admin@temple.org` | ລະຫັດຜ່ານ: `password`
        *   **Admin/Manager**: `manager@temple.org` | ລະຫັດຜ່ານ: `password`

4.  **ເປີດໃຊ້ງານ Server ທົດລອງ**:
    ທ່ານສາມາດເປີດທັງ Web Server, Queue Listener ແລະ Vite ພ້ອມກັນໃນຄຳສັ່ງດຽວ:
    ```bash
    composer run dev
    ```
    ຈາກນັ້ນ, ເຂົ້າໄປທີ່: [http://localhost:8000](http://localhost:8000)

---

## 🚀 ຂັ້ນຕອນການ Deploy ໄປຍັງ Production (Deployment)

ເມື່ອຕ້ອງການ Deploy ລະບົບຂຶ້ນ Server ແທ້ (ເຊັ່ນ VPS, Cloud, Shared Hosting ທີ່ຮອງຮັບ SSH):

### 1. ຕັ້ງຄ່າໄຟລ໌ `.env` ສຳລັບ Production
ແກ້ໄຂໄຟລ໌ `.env` ໃຫ້ເປັນຄ່າຄວາມປອດໄພສູງສຸດ:
```env
APP_NAME="Buddhist EMS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# ການເຊື່ອມຕໍ່ຖານຂໍ້ມູນ (ປ່ຽນຕາມ Server ຈິງ)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 2. ຕິດຕັ້ງ Dependencies ແລະ Build Assets
ດຳເນີນຄຳສັ່ງເຫຼົ່ານີ້ໃນ Terminal ຂອງ Server:
```bash
# ຕິດຕັ້ງ PHP packages (ບໍ່ເອົາ dev packages)
composer install --no-dev --optimize-autoloader

# ຕິດຕັ້ງ NPM packages ແລະ compile assets ສຳລັບ production
npm install
npm run build
```

### 3. ສ້າງຖານຂໍ້ມູນ ແລະ Migrate
```bash
php artisan migrate --force
```

### 4. ຕັ້ງຄ່າ Cache ເພື່ອເພີ່ມຄວາມໄວ (Optimization)
ການເຮັດ Cache ຈະຊ່ວຍໃຫ້ Laravel ເຮັດວຽກໄດ້ໄວຂຶ້ນຫຼາຍເທົ່າໃນ Production:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. ຕັ້ງຄ່າສິດທິການເຂົ້າເຖິງໄຟລ໌ (File Permissions)
ເວັບເຊີບເວີ (ເຊັ່ນ Apache ຫຼື Nginx) ຕ້ອງມີສິດຂຽນ/ອ່ານໃນໂຟນເດີ `storage` ແລະ `bootstrap/cache`:
```bash
# ສຳລັບ Ubuntu/Debian ທີ່ໃຊ້ Nginx (www-data)
sudo chown -R www-data:www-data /var/www/templecrs
sudo chmod -R 775 /var/www/templecrs/storage
sudo chmod -R 775 /var/www/templecrs/bootstrap/cache
```

### 6. ຕັ້ງຄ່າ Symbolic Link ສຳລັບ File Uploads
ເພື່ອໃຫ້ສາມາດສະແດງຮູບພາບ ຫຼື ໂລໂກ້ທີ່ອັບໂຫຼດຜ່ານລະບົບຫຼັງບ້ານໄດ້:
```bash
php artisan storage:link
```

### 7. ຕັ້ງຄ່າ Web Server (Nginx Configuration Example)
ກຳນົດໃຫ້ Document Root ຊີ້ໄປທີ່ໂຟນເດີ `/public` ຂອງໂຄງການ:
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/templecrs/public;

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 📝 ໃບອະນຸຍາດ (License)

ລະບົບນີ້ພັດທະນາໂດຍອີງໃສ່ Laravel Framework ພາຍໃຕ້ [MIT license](https://opensource.org/licenses/MIT).

---

## 👨‍💻 ຜູ້ພັດທະນາ (Developer)

*   **ພັດທະນາໂດຍ**: ປອ. ອານັນທະສັກ ພັດທະສີລາ
*   **Facebook**: [Ananthasak Phathasira](https://web.facebook.com/phathasira)
*   **WhatsApp**: [+856 20 9121 3388](https://wa.me/8562091213388) (https://wa.me/8562091213388)
