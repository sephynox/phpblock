# PHPBlock
A lightweight asynchronous PHP server/client for blockchain networks. 
Currently in development. Not all endpoints are available. Help welcome!

## Build Status
[![PHP Composer](https://github.com/sephynox/phpblock/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/sephynox/phpblock/actions/workflows/php.yml)

## Supported Platforms

* Ethereum

### Future Platforms

* Cardano

## Documentation

* [Quickstart Example](#quickstart-example)
* [Install](#install)
* [Testing](#Testing)
* [License](#License)
  
TODO
### Quickstart Example
Call the Ethereum `eth_protocolversion` endpoint.
```php
<?php

require_once('./vendor/autoload.php');

$Ethereum = new PHPBlock\Network\Ethereum\Client();

$Ethereum->protocolVersion()
    ->then(function (string $version) {
        echo "The protocol version is: " . $version . "\n";
    });

$Ethereum->run();
```
## Install
Get started quickly [using Composer](https://getcomposer.org).
```
$ composer require sephynox/phpblock
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
