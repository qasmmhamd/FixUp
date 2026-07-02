# 🔧 FixUp

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.4-blue?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-orange?style=for-the-badge&logo=mysql)

### Backend for a Maintenance Services Platform

RESTful backend built with Laravel for connecting customers with maintenance workers through orders, offers, chat, notifications, and wallet management.

</div>

---

# About

FixUp is a graduation project developed by a team. This repository contains the **backend implementation**, which I was responsible for.

The backend exposes REST APIs used by the frontend application and manages authentication, orders, workers, chat, notifications, wallet operations, and the administration dashboard.

---

# Features

## Authentication

- User Registration
- Worker Registration
- Login & Logout
- Google Login
- Email Verification
- Password Reset
- Sanctum Authentication

---

## Orders

- Create Orders
- Cancel Orders
- Complete Orders
- Order Notifications
- Customer Orders
- Worker Orders

---

## Price Offers

- Submit Price Offers
- Accept Offers
- View Worker Offers
- View Accepted Offers

---

## Worker Management

- Worker Registration
- Worker Profile
- Worker Rating
- Worker Filtering
- Worker Services

---

## Chat

- Guided Conversations
- AI Chat
- Message Templates
- Chat Messages
- Real-time Messaging (Laravel Reverb)
- Guided Conversations
- AI Chat Assistant
- Message Templates

---

## Wallet

- Worker Wallet
- Wallet Transactions
- Job Fee Rules
- Admin Wallet Top-up

---

## Notifications

- Push Notifications
- Order Notifications
- Price Offer Notifications

---

## Admin Dashboard

- Manage Workers
- Manage Careers
- Manage Service Areas
- Manage Services
- Wallet Management
- Statistics

---

# Tech Stack

- Laravel 12
- PHP 8.4
- MySQL
- Laravel Sanctum
- Eloquent ORM
- PHPUnit

---

# Project Structure

```
app/
├── Http/
├── Models/
├── Services/
├── Events/
├── Notifications/

database/
├── migrations/
├── factories/
├── seeders/

routes/
tests/
```

---

# Installation

```bash
git clone https://github.com/qasmmhamd/FixUp.git

cd FixUp

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan storage:link

php artisan serve
```

---

# Testing

Run all tests

```bash
php artisan test
```

Run a specific test

```bash
php artisan test --filter=TestName
```

---

# Main API Modules

- Authentication
- Orders
- Price Offers
- Workers
- Profiles
- Chat
- Notifications
- Wallet
- Admin Dashboard

---

# Security

- Authentication using Laravel Sanctum
- Request Validation
- Password Hashing
- Email Verification
- Authorization Middleware

---

# Future Improvements

- Docker Support
- CI/CD Pipeline
- API Documentation
- Increase Test Coverage
- Queue Optimization
- Redis Integration

---

# Author

**Qasim Mohammad**

Backend Developer

GitHub:
https://github.com/qasmmhamd

---

# License

This project was developed as a graduation project.