# 🛡️ CryptoVax: Secure National Vaccine Management

![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue)
![Laravel](https://img.shields.io/badge/Laravel-Framework-red)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-orange)
![Security](https://img.shields.io/badge/Security-RSA%20%7C%20ECC%20%7C%20HMAC-brightgreen)

## 📖 About The Project
This web application is intended to make national vaccination operations smooth, secure, and automated. It provides a seamless portal for both administrators managing the vaccine supply chain and citizens booking their vaccination appointments. 

Beyond its core functionality, this system has been fortified with **custom, from-scratch cryptographic protocols** to ensure maximum data privacy. The database operates on a "Zero-Plaintext" policy, meaning no sensitive citizen data is ever stored in a readable format.

---

## 🔐 Advanced Security Implementations (CSE447 Custom Features)
This project features heavy security modifications, bypassing standard framework wrappers to implement raw cryptography:

* **Zero-Plaintext Database:** All sensitive columns (`name`, `nid`, `dob`, `phone`) have been physically removed and replaced with encrypted ciphertexts.
* **Algorithm Rotation (RSA & ECC):** User data is encrypted using **RSA** upon registration. When a profile is updated, the system re-encrypts the data using **Elliptic Curve Cryptography (ECC)** to introduce fresh cipher randomness.
* **Data Integrity (HMAC):** Every sensitive database row is sealed with a custom Hash-based Message Authentication Code (HMAC). Tampering with the ciphertext in the database triggers an automated integrity warning.
* **Custom Password Hashing:** Standard bcrypt is replaced with a custom-built, heavily looped SHA-256 algorithm utilizing a random 32-byte salt stored independently.
* **Two-Factor Authentication (2FA):** Access requires validating a 6-digit OTP sent securely via SMTP to the user's verified email address.
* **Session Hijacking Prevention:** Sessions are dynamically bound to the user's IP and Browser hardware fingerprint.

---

## ⚙️ Core Features

### 🏢 Admin Panel (For Higher Authority)
* **Center Management:** Admin manages Vaccine Centers.
* **Supply Chain:** Admin supplies vaccines (vials) to the centers.
* **Staffing:** Admin assigns and authorizes Healthcare Practitioners.

### 👤 Vaccine Candidates (Citizen Portal)
* **Registration:** Register securely on the platform.
* **Applications:** Apply for specific vaccines based on availability.
* **Scheduling:** Set and manage appointments.
* **Documentation:** Download digital vaccine cards and certificates.
* **Vaccine Diary:** Create end-to-end encrypted medical diary entries/posts.

---

## 🛠️ System Dependencies
To run this project, ensure your environment meets the following requirements:

* **PHP:** `8.2^`
    * Required PHP Extensions: `gd`, `mbstring`, `zip`, `gmp` (GNU Multiple Precision for Cryptography)
* **MySQL:** `8.0^`
* **Node.js:** `18.20.3^`
* **NPM:** `10.7.0^`
* *Note: Other missing dependencies will be prompted when the `composer install` command is run.*

---

## 🚀 Installation Steps

Run the following commands in your project directory to get the application up and running:

1. **Environment Setup:**
   ```bash
   cp .env.example .env

Make sure to open .env and add your database credentials and SMTP email credentials (required for 2FA).

Install PHP Dependencies:

Bash
composer install
Generate App Key:

Bash
php artisan key:generate
Run Migrations & Seeders:

Bash
php artisan migrate
php artisan db:seed
Install & Compile Frontend Assets:

Bash
npm install
npm run dev 
# Use `npm run build` for production
🧪 Testing Accounts & Credentials
To explore the system, you can use the following seeded accounts:

Admin Account:

Email: admin@test.com

Password: password

Operator Account:

Email: operator@test.com

Password: password

Note: Applicant accounts can be registered right away via the registration page, and no manual verification is needed for basic services.
