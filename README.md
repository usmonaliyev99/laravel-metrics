# laravel-metrics

![Latest Version on Packagist](https://img.shields.io/packagist/v/usmonaliyev/laravel-metrics.svg)
![Total Downloads](https://img.shields.io/packagist/dt/usmonaliyev/laravel-metrics.svg)
![Packagist License](https://img.shields.io/packagist/l/usmonaliyev/laravel-metrics)

This composer package that provides a simple and efficient way to measure and analyze the execution time and speed of database queries in your Laravel application.

It works seamlessly with Redis as the underlying database to store and retrieve query metrics.

## Installation

You can install the package via Composer:

```bash
composer require usmonaliyev/laravel-metrics
```

## Requirements

- [php](https://php.net): `^7.4|^8.1`
- [predis/predis](https://packagist.org/packages/predis/predis): `^2.1`

## Configuration

After installing the package, you'll need to publish the configuration file.

```bash
php artisan vendor:publish --provider="Usmonaliyev\LaravelMetrics\LaravelMetricServiceProvider" --tag="config"
```

This will create a `metric.php` file in your config directory.
Open this file and customize the configuration options as needed.

## Contributing

If you discover any issues or want to contribute, feel free to create an issue or submit a pull request. Your contributions are always welcome!

## License

The Laravel Metrics package is open-sourced software licensed under the [MIT](https://choosealicense.com/licenses/mit/) license.

## Communication

[![Gmail account](https://img.shields.io/badge/Gmail-D14836?style=for-the-badge&logo=gmail&logoColor=white)](https://mail.google.com/mail/u/0/#inbox?compose=CllgCJTMXlTvXZtwqllWKkrmkrnwMBVGBmbkbbpZvxVRvpGmcSZZprrWfMrCvhsPMRbDZKTbWGq)
[![Telegram account](https://img.shields.io/badge/Telegram-2CA5E0?style=for-the-badge&logo=telegram&logoColor=white)](https://t.me/usmonaliyev99)


