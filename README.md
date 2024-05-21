1. composer create-project --prefer-dist laravel/laravel .
2. composer require laravel/ui ^3.4"
3. composer require laravelcollective/html ^6.3
4. composer require qiwi/bill-payments-php-sdk ^0.2.2
5. composer require socialiteproviders/telegram ^4.1
6. composer require spatie/laravel-permission ^5.5
7. composer require webpatser/laravel-countries ^1.5
8. composer require ssda-1/proxies
9. php artisan migrate
10. php artisan vendor:publish --tag=proxies-config
11. php artisan vendor:publish --tag=proxies-public
12. php artisan vendor:publish --tag=proxies-auth --force
13. \Ssda1\proxies\Http\Middleware\SetLanguage::class указать в $middlewareGroups
14. \Ssda1\proxies\Models\User::class указать в auth.providers.users.model

