# StoreForYou

A modern Laravel e-commerce application built with Livewire and Flux UI components.

## Features

- **User Authentication**: Secure user registration and login with Laravel Fortify
- **Product Management**: Complete CRUD operations for products
- **Category Management**: Organize products with categories
- **Modern UI**: Beautiful interface built with Livewire Flux components
- **Responsive Design**: Works seamlessly on desktop and mobile devices

## Tech Stack

- **Backend**: Laravel 12.x
- **Frontend**: Livewire 3.x with Flux UI
- **Database**: SQLite (development)
- **Styling**: Tailwind CSS
- **Authentication**: Laravel Fortify

## Installation

1. Clone the repository:
```bash
git clone https://github.com/YOUR_USERNAME/StoreForYou.git
cd StoreForYou
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Set up environment:
```bash
cp .env.example .env
php artisan key:generate
```

5. Set up database:
```bash
touch database/database.sqlite
php artisan migrate
php artisan db:seed
```

6. Build assets:
```bash
npm run build
```

7. Start the development server:
```bash
php artisan serve
```

## Development

For development with hot reloading:

```bash
npm run dev
```

This will start:
- Laravel development server
- Queue worker
- Vite development server with hot reloading

## Testing

Run the test suite:

```bash
php artisan test
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
