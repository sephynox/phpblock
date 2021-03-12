<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use PHPBlock\Network\Ethereum\Model\Gwei;
use PHPBlock\Network\Ethereum\Model\Tag;
use PHPBlock\Network\Ethereum\Model\Transaction;
use PHPBlock\Network\Ethereum\Type\Hash32;

use function PHPBlock\Helper\pass;

$Ethereum = new PHPBlock\Network\Ethereum\Client();

$Ethereum->ethAccounts()
    ->then(function (array $hexAddresses) use ($Ethereum) {
        $to = $hexAddresses[array_rand($hexAddresses)];
        $from = $hexAddresses[array_rand($hexAddresses)];
        $tag = new Tag(Tag::LATEST);

        return pass($Ethereum->ethGetBalance($to, $tag), $to, $from);
    })->then(function ($data) use ($Ethereum) {
        [$balance, $to, $from] = $data;

        if ($balance instanceof Gwei) {
            # With bcmath extension
            if ($balance->toEth() > 1) {
                $amount = new Gwei(Gwei::ethToGwei('1'));
                $transaction = Transaction::make($to, $from, $amount);
            } else {
                throw new \Exception("Insufficient balance!\n");
            }
        } else {
            #Without bcmath extension
            if ($balance > 1000000000000000000) {
                $transaction = Transaction::make($to, $from, '1000000000000000000');
            } else {
                throw new \Exception("Insufficient balance!\n");
            }
        }

        $Ethereum->ethSendTransaction($transaction)
            ->then(function (Hash32 $hash32) {
                echo "Transaction Hash: " . $hash32 . "\n";
            });
    }, function (\Exception $exception) {
        echo $exception->getMessage();
    });

$Ethereum->run();
