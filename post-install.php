// post-install.php
$configPath = __DIR__ . '/../../../../bootstrap/providers.php';
$provider = "Ssda1\\proxies\\Providers\\ProxyServiceProvider::class";

$config = file_get_contents($configPath);

if (!str_contains($config, $provider)) {
    $config = str_replace("];", "    $provider,\n];", $config);
    file_put_contents($configPath, $config);
}
