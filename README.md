#### Usage via command line

1. Clone repo and run composer install.

2. `php index.php <namefile>`

#### Usage in Laravel or other frameworks

1. ` composer require  mutant/s3crawler `

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


3. Add `Mutant\S3Crawler\App\Helpers\S3CrawlerHelper;` to the top of your function.