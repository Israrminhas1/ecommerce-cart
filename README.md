# E-commerce Shopping Cart

A modern, full-featured e-commerce shopping cart application built with Laravel 12, Vue 3, Inertia.js, and Tailwind CSS.

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Vue 3 + Inertia.js
- **Styling:** Tailwind CSS
- **Database:** MySQL
- **Queue:** Database Driver
- **Authentication:** Laravel Breeze

## Features

### Customer Features
- Product browsing with category filtering
- Real-time product search with suggestions
- Shopping cart with quantity management
- Wishlist functionality
- Order placement and history
- User authentication

### Admin Features
- Dashboard with sales statistics
- Product management (CRUD)
- Order management with status updates
- Sales reports generation
- Low stock alerts

### Background Jobs
- **Low Stock Notifications:** Automatic email alerts when product stock falls below threshold
- **Daily Sales Report:** Scheduled report sent daily at 6:00 PM

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd ecommerce-cart
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure Database

Update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_cart
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

This will create all tables and seed:
- 8 product categories
- 30+ sample products
- Admin user
- Customer user

### 7. Build Frontend Assets

```bash
npm run dev
```

For production:

```bash
npm run build
```

### 8. Start the Application

```bash
php artisan serve
```

Visit `http://localhost:8000`

## Test Credentials

### Admin User
- **Email:** admin@admin.com
- **Password:** password

### Customer User
- **Email:** customer@test.com
- **Password:** password

## Queue Setup

The application uses database queue driver for background jobs.

### Start Queue Worker

```bash
php artisan queue:work
```

For production, use a process manager like Supervisor.

### Scheduler Setup

Add to crontab for scheduled jobs:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Manual Job Dispatch

Dispatch daily sales report manually:

```bash
php artisan report:daily
```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/              # Admin controllers
│   │   ├── CartController.php
│   │   ├── CartItemController.php
│   │   ├── CategoryController.php
│   │   ├── OrderController.php
│   │   ├── ProductController.php
│   │   ├── SearchController.php
│   │   └── WishlistController.php
│   ├── Middleware/
│   │   └── AdminMiddleware.php
│   └── Requests/               # Form validation
├── Jobs/
│   ├── SendDailySalesReport.php
│   └── SendLowStockNotification.php
├── Mail/
│   ├── DailySalesReport.php
│   └── LowStockAlert.php
├── Models/
│   ├── Cart.php
│   ├── CartItem.php
│   ├── Category.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Product.php
│   └── Wishlist.php
├── Observers/
│   └── ProductObserver.php
└── Services/
    ├── CartService.php
    ├── OrderService.php
    └── StockService.php

resources/js/
├── Components/
│   ├── Shop/                   # Product cards, cart items, etc.
│   └── UI/                     # Reusable UI components
├── Layouts/
│   ├── AdminLayout.vue
│   ├── AuthenticatedLayout.vue
│   └── ShopLayout.vue
└── Pages/
    ├── Admin/                  # Admin pages
    ├── Cart/                   # Shopping cart
    ├── Categories/             # Category browsing
    ├── Checkout/               # Checkout flow
    ├── Orders/                 # Order history
    ├── Products/               # Product listing/detail
    ├── Search/                 # Search results
    └── Wishlist/               # Wishlist page
```

## Routes

### Public Routes

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/` | Home page (products) |
| GET | `/products` | Product listing |
| GET | `/products/{slug}` | Product detail |
| GET | `/categories` | Category listing |
| GET | `/categories/{slug}` | Category products |
| GET | `/search` | Search results |
| GET | `/search/suggestions` | Search autocomplete |

### Authenticated Routes

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/cart` | View cart |
| POST | `/cart/items` | Add to cart |
| PATCH | `/cart/items/{id}` | Update quantity |
| DELETE | `/cart/items/{id}` | Remove item |
| DELETE | `/cart` | Clear cart |
| GET | `/checkout` | Checkout page |
| POST | `/orders` | Place order |
| GET | `/orders` | Order history |
| GET | `/orders/{id}` | Order detail |
| GET | `/wishlist` | View wishlist |
| POST | `/wishlist/{product}` | Toggle wishlist |

### Admin Routes

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/admin` | Dashboard |
| GET | `/admin/products` | Product list |
| GET | `/admin/products/create` | Create product |
| POST | `/admin/products` | Store product |
| GET | `/admin/products/{id}/edit` | Edit product |
| PATCH | `/admin/products/{id}` | Update product |
| DELETE | `/admin/products/{id}` | Delete product |
| GET | `/admin/orders` | Order list |
| GET | `/admin/orders/{id}` | Order detail |
| PATCH | `/admin/orders/{id}` | Update status |
| GET | `/admin/reports` | Reports page |
| POST | `/admin/reports/generate` | Generate report |

## Configuration

### Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `LOW_STOCK_THRESHOLD` | Stock level for alerts | 10 |
| `ADMIN_EMAIL` | Admin notification email | admin@admin.com |
| `QUEUE_CONNECTION` | Queue driver | database |

### Mail Configuration

Development uses log driver. Logs are written to:

```
storage/logs/mail.log
```

For production, configure SMTP in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Database Schema

### Core Tables

- `users` - User accounts with admin flag
- `categories` - Product categories
- `products` - Product catalog
- `carts` - User shopping carts
- `cart_items` - Cart line items
- `orders` - Completed orders
- `order_items` - Order line items
- `wishlists` - User wishlists
- `low_stock_notifications` - Notification tracking

## Development

### Run Development Server

```bash
npm run dev
php artisan serve
```

### Run Tests

```bash
php artisan test
```

### Code Formatting

```bash
./vendor/bin/pint
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
