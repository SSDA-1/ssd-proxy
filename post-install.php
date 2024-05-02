// post-install.php
$configPath = __DIR__ . '/../../../bootstrap/providers.php';
$provider = "ssda1\proxies\Providers\ProxyServiceProvider::class";

$config = file_get_contents($configPath);
if (!str_contains($config, $provider)) {
    $config = str_replace("'providers' => [", "'providers' => [\n        $provider,", $config);
    file_put_contents($configPath, $config);
}
