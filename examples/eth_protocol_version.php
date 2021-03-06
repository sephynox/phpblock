<?php

require_once(__DIR__ . '/../vendor/autoload.php');

$Ethereum = new PHPBlock\Network\Ethereum\Client();

$Ethereum->ethProtocolVersion()
    ->then(function (string $version) {
        echo "The Protocol Version is: " . $version . "\n";
    });

$Ethereum->run();
