# FinTrack

A personal finance REST API built with Laravel. FinTrack lets users manage their finances through authenticated wallets, categories, and transactions — with role-based access control and real-time notifications.

## Features

- **Authentication** — Token-based auth using Laravel Sanctum
- **Roles & Permissions** — Role-based access control powered by Spatie Laravel-Permission
- **Wallets** — Create and manage multiple wallets per user
- **Categories** — Organize transactions into custom categories
- **Transactions** — Track income and expenses tied to wallets and categories
- **Real-time Notifications** — Live updates via Laravel Reverb WebSockets *(in progress)*

## Tech Stack

- **Framework:** Laravel
- **Auth:** Laravel Sanctum
- **Authorization:** Spatie Laravel-Permission
- **Real-time:** Laravel Reverb
- **Database:** MySQL

## API Documentation

Full endpoint documentation is available via Postman.

---

Built by [Mosab Abozraa](https://github.com/mosabAbozraa)
