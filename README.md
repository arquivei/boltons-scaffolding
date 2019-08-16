# Lescript

In this project you'll find the infamous **Lescript** and everything you need to build a 
skeleton use case in the architecture currently used by the Boltons House.

What this hopes to be:

* A way to speed up your coding by taking the usual structural code out of the way

What this is not:

* A production-ready code generator
* A panda

## Installation

`composer require-dev arquivei/boltons-scaffolding`

## Usage

* Set up your configuration file (config.example.json is included to help you)
* In your code:

```php
<?php

    require __DIR__ . '/vendor/autoload.php';

    $lescript = new \Arquivei\BoltonsScaffolding\Lescript($configPath);
    $lescript->makeLeMagique();
```

* If you're using Laravel, there's an even easier way: open your terminal and type

` php artisan arquivei:lescript --config=config_path.json `

* If you're using javascript you may wanna rethink your life

---------------

## Known issues and limitations

* The LogInterface has no namespace assigned to it, you'll have to fix that yourself
* The code might not me properly formatted (CTRL+ALT+L in PHPStorm should fix it)

