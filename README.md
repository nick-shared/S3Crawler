#### Usage via command line

1. Clone repo and run composer install.

2. `php index.php <namefile>`

#### Usage in Laravel

1. ` composer require  mutant/s3crawler:dev-master `

 OR Add this to composer.json and run `composer update mutant/s3crawler`
 
```
  "require": {
    "mutant/s3crawler": "dev-master"
  },
```


2. composer.json might need minimum stability set: 
```
  "minimum-stability": "dev",
```


3. use `php artisan mutant:s3-bucket-crawler {--inputfile=}` 
 * Your input file is a line separated word list. There are usable word lists in the wordlist folder of this package.
