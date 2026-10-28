<?php

// $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
// $dotenv->load();


$dotenv = new App\Framework\Dotenv();
$dotenv->load('.env');
$container = new App\Framework\Container();
$config = new App\Framework\Config($_ENV);

// $database = new App\Models\Database(DATA_PATH . 'transactions.csv');

$container->set(Database::class, function () {
    return new App\Models\DB($config);
});


$container->set(App\Framework\TemplateViewerInterface::class, function () {
    return new App\Framework\PHPViewTemplate();
});


return $container;
