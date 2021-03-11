# PHPBlock
A lightweight asynchronous `PHP` server/client for blockchain networks.

[![PHP Composer](https://github.com/sephynox/phpblock/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/sephynox/phpblock/actions/workflows/php.yml)

> Currently in development. Not all endpoints are available. Help welcome!

Table of Contents
-----------------
  - [Supported Platforms](#supported-platforms)
    - [Future Platforms](#future-platforms)
  - [Documentation](#documentation)
    - [Quick start Example](#quick-start-example)
    - [Install](#install)
    - [Testing](#testing)
  - [License](#license)
  - [Donate](#donate)

TODO

## Supported Platforms

* Ethereum

### Future Platforms

* Cardano
### Quick Start Example
Call the Ethereum `eth_protocolversion` endpoint.
```php
<?php

require_once('./vendor/autoload.php');

$Ethereum = new PHPBlock\Network\Ethereum\Client();

$Ethereum->ethProtocolVersion()
    ->then(function (string $version) {
        echo "The protocol version is: " . $version . "\n";
    });

$Ethereum->run();
```
## Install
Get started quickly [using Composer](https://getcomposer.org).
```
$ composer require phpblock/core
```
## Testing

In order to run tests, you must first clone this repo and then install the 
dependencies [through Composer](https://getcomposer.org):

```bash
$ composer install
```
You can then use the 
[geth client](https://geth.ethereum.org/docs/install-and-build/installing-geth) 
or [ganache-cli](https://github.com/trufflesuite/ganache-cli) to test 
Ethereum RPC calls. You can also install the 
[ganache client on Windows](https://www.trufflesuite.com/ganache).
```bash
#Using geth (Ubuntu):
sudo add-apt-repository -y ppa:ethereum/ethereum
sudo apt-get update
sudo apt-get install ethereum

#Using geth (OS X & brew):
brew tap ethereum/ethereum
brew install ethereum
brew install ethereum --devel

#Using ganache-cli (npm)
npm install -g ganache-cli
ganache-cli
```

This project uses PHPUnit and PHPBench. To run the test suite, enter the 
project root and run:
```bash
$ composer run-script check
```

## License

MIT, see [LICENSE file](LICENSE).

## Donate
**BTC**: bc1qkqsfuaptqcslwmxh5lz2utxls4pe7wnjhepa2s

**ETH**: 0x00E069d105F61564530859A35FE0D007C3536a35

**ADA**: addr1qywvljkfnyyey38te86tshjscn6yw25c069lf82jfjgv57m3txy8f0nf4wnjwcr8uxmlg9wk7lt6uu7g5w9x077v8lwqgsulw6

**DOGE**: D949UWaLauvKyhX6PNuXGavmMNS6uFcjfS
