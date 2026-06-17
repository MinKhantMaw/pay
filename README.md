# Pay

Pay is a Laravel-based e-money wallet and transfer application with web UI and API support.

It provides user wallet management, wallet-to-wallet transfers, scan-and-pay flows, transaction history, notifications, and a separate admin panel.

## Key Features

- User registration and login
- Wallet creation and balance tracking
- Internal transfers between users with transaction records
- Scan-and-pay workflow for QR-style transfers
- Notification system with real-time broadcast support
- User profile and password management
- Admin panel for managing users, wallets, and notifications
- API endpoints for mobile/web client integration

## Built With

- Laravel 11
- PHP 8.2+
- Laravel Passport / Sanctum for API authentication
- Laravel UI / Blade for frontend views
- Pusher / Laravel Echo for realtime notifications
- Yajra DataTables for admin list pages
- Simple QrCode for receive QR flow

## Installation

1. Clone the repository

   ```bash
   git clone https://github.com/MinKhantMaw/pay.git
   cd pay
   ```

2. Install PHP dependencies

   ```bash
   composer install
   ```

3. Install JavaScript dependencies

   ```bash
   npm install
   ```

4. Copy environment file

   ```bash
   cp .env.example .env
   ```

5. Generate application key

   ```bash
   php artisan key:generate
   ```

6. Configure `.env`

   - `APP_URL`
   - `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
   - `BROADCAST_DRIVER` (for realtime notifications)
   - `PUSHER_APP_ID`, `PUSHER_APP_KEY`, `PUSHER_APP_SECRET`, `PUSHER_APP_CLUSTER`

7. Run database migrations

   ```bash
   php artisan migrate
   ```

8. Build frontend assets

   ```bash
   npm run build
   ```

9. Install Passport keys if using API auth

   ```bash
   php artisan passport:install
   ```

10. Start the application

    ```bash
    php artisan serve
    ```

## Usage

### Web Routes

- `/` - Authenticated dashboard
- `/profile` - User profile page
- `/wallet` - Wallet details
- `/transfers` - Transfer money to another user
- `/transactions` - Transaction history
- `/receive-qr` - Receive QR code page
- `/scan-and-pay` - Scan and pay flow
- `/notification` - Notification list

### API Routes

- `POST /api/register` - Register a new user
- `POST /api/login` - Authenticate and generate token
- `GET /api/profile` - Fetch authenticated user profile
- `POST /api/logout` - Logout
- `GET /api/transaction` - List user transactions
- `GET /api/transaction/{id}` - Transaction details
- `GET /api/notification` - List notifications
- `GET /api/notification/{id}` - Notification detail
- `GET /api/to-account-verify` - Verify recipient by phone
- `GET /api/transfer/confirm` - Confirm transfer data
- `POST /api/transfer/complete` - Complete transfer
- `GET /api/scan-and-pay-form` - Fetch scan-and-pay recipient details
- `GET /api/scan-and-pay/confirm` - Confirm scan-and-pay data
- `POST /api/scan-and-pay/complete` - Complete scan-and-pay transfer

### Admin Panel

Admin routes are mounted under `/admin` and require `admin_user` authentication. The admin panel includes management views for:

- admin users
- end users
- wallets
- notifications

## Environment Notes

- The app uses both `auth:api` and `auth:sanctum` middleware in API route definitions.
- Real-time notifications rely on broadcast configuration.
- The transfer and scan-and-pay flows require a minimum transfer amount of `1000 MMK`.

## Useful Commands

- `php artisan migrate` — apply database migrations
- `php artisan db:seed` — seed sample data if seeds are available
- `php artisan passport:install` — create Passport clients
- `npm run dev` — start Vite development server
- `npm run build` — compile frontend assets for production

## License

This project is licensed under the MIT License.
