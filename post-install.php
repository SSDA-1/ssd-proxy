// post-install.php
$configPath = __DIR__ . '/../../../../bootstrap/providers.php';
$provider = "ssda1\\proxies\\Providers\\ProxyServiceProvider::class";

// Загрузка текущего содержимого файла конфигурации
$config = file_get_contents($configPath);

// Проверка, содержит ли файл уже указанный провайдер
if (!str_contains($config, $provider)) {
    // Ищем закрывающую скобку массива и добавляем перед ней новый провайдер
    $config = str_replace("];", "    $provider,\n];", $config);
    file_put_contents($configPath, $config);
}
