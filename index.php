<?php

require __DIR__ . '/vendor/autoload.php';
use Mutant\S3Crawler\App\Helpers\S3CrawlerHelper;

$s3crawler = new S3CrawlerHelper();

do {
    $next_name = fgets($f1);
    $result = $s3crawler->getUrls($next_name);

}
while (!feof($f1));
fclose($f1);
