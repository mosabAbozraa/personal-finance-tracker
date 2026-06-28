# FinTrack

A personal finance tracker REST API built with Laravel — wallets, categories, role-based access, and real-time updates.

## Overview

FinTrack is a backend-focused portfolio project built to go deeper into Laravel's ecosystem: proper API authentication, role-based permissions, and real-time communication over WebSockets, rather than just basic CRUD.

## Features

- **Authentication** — token-based auth via Laravel Sanctum
- **Role-based access** — permissions managed with Spatie Permission
- **Wallets** — create, update, and manage multiple wallets per user
- **Categories** — organize transactions by custom categories
- **Real-time updates** — live data sync via Laravel Reverb (WebSockets)

## Tech Stack

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)

## Getting Started

```bash
git clone https://github.com/YOUR_USERNAME/fintrack.git
cd fintrack
composer install
cp .env.example .env
php artisan key:generate
# set your DB credentials in .env
php artisan migrate --seed
php artisan serve

# for real-time features
php artisan reverb:start
```
