# Simple Exchange API

The Coin Sales Exchange API is a RESTful backend system built using the Laravel framework. It provides a platform for
selling coins to customers, managing user accounts, processing purchase orders, and facilitating manual transactions.
This README file provides an overview of the project and instructions for setting up and running the API.

## Features

- User account management: Create user accounts, retrieve account information, and update account details.
- Purchase order placement: Allow customers to place purchase orders for coins.
- Transaction processing: Manually process transactions for successful purchase orders.
- Market data: Access ticker information, order book, and recent trades for various coins.
- Authentication and security: Implement JWT authentication for secure API access.

## Requirements

- PHP 7.4 or later
- Composer (https://getcomposer.org)
- MySQL database
- Redis server
- JWT library (e.g., tymon/jwt-auth)

## Installation

1.Clone the repository:

```shell
git clone https://github.com/mdhesari/simple-exchange-api.git

php artisan key:generate
```

2.Install dependencies using Composer:

```shell
cd simple-exchange-api

composer install
```

3.Create a copy of the .env.example file and rename it to .env. Update the configuration values, including the database connection details and Redis settings.

```shell
cp .env.example .env

php artisan key:generate
```

4.Run the database migrations:

```shell
php artisan migrate
```

5.(Optional) Seed the database with sample data:

```shell
php artisan db:seed
```

6.Start the development server:

```shell
php artisan serve
```

The API should now be accessible at http://localhost:8000.

