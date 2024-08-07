1. composer create-project --prefer-dist laravel/laravel .
2. composer require laravel/ui ^3.4"
3. composer require laravelcollective/html ^6.3
4. composer require qiwi/bill-payments-php-sdk ^0.2.2
5. composer require socialiteproviders/telegram ^4.1
6. composer require spatie/laravel-permission ^5.5
7. composer require webpatser/laravel-countries ^1.5
8. composer require ssda-1/proxies
9. php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
10. php artisan migrate
11. php artisan vendor:publish --tag=proxies-config
12. php artisan vendor:publish --tag=proxies-public
13. php artisan vendor:publish --tag=proxies-auth --force
14. settings webpatser/laravel-countries
    - add config.app
        'providers' => [
      
            Webpatser\Countries\CountriesServiceProvider,
            Webpatser\Countries\CountriesServiceProvider::class,
            Ssda1\proxies\Providers\ProxyServiceProvider::class,
        ];
      
        'aliases' => [
        
            'Countries' => Webpatser\Countries\CountriesFacade::class,
        
        ];
16. settings spatie/laravel-permission
    - add Kernel.php
        protected $middlewareAliases = [
        
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        
        ];
    - add config.app
        'providers' => [
        
            Spatie\Permission\PermissionServiceProvider::class,
        
        ];
17. settings ssda-1/proxies 
    - add Http\Kernel.php
        protected $middlewareGroups = [
      
           'web' => [
  
              \Ssda1\proxies\Http\Middleware\SetLanguage::class,
  
            ],
          
        ];
    - add config.auth
        'providers' => [
       
           'users' => [
              'driver' => 'eloquent',
              'model' => \Ssda1\proxies\Models\User::class,
              'email_verification' => true,
            ],
       
        ];
    - add Console\Kernel.php
        protected $commands = [
        
            \Ssda1\proxies\Console\Commands\EndProxyCron::class,
            \Ssda1\proxies\Console\Commands\SendProxyExpirationNotifications::class,
        
        ];
18. php artisan package:seed
19. php artisan vendor:publish --tag=laravel-pagination
20. php artisan vendor:publish --tag="telegram-config"
