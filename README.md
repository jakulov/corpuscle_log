# Corpuscle Logger Component #
Tiny robust and easy to use PSR-compatible logger 

Can be installed with composer

    composer require jakulov/corpuscle_log
   

## 1. What's included ##
- File log storage.
- Can store logs with buffer usage. (Flush buffer at end script execution)
- Simple to use with [jakulov/container](https://packagist.org/packages/jakulov/container)
- [PSR compatible](https://packagist.org/packages/psr/log) 


## 2. TODO: ##
- Database log storage (PDO, Mongodb)
- AMQP log storage

## Tests ##

Run:
./run_tests.sh

Tests are also examples for usage library