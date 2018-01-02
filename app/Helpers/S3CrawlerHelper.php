<?php
namespace Mutant\S3Crawler\App\Helpers;

use Mutant\Http\App\Helpers\HttpHelper;

class S3CrawlerHelper
{

    /**
     * @var array
     */
    public $hosts = [
        'http://s3-eu-west-1.amazonaws.com',
        'http://s3-us-west-1.amazonaws.com',
        'http://s3.amazonaws.com',
        'http://s3-ap-southeast-1.amazonaws.com',
        'http://s3-ap-northeast-1.amazonaws.com'
    ];

    /**
     * @param $word
     * @return Pool
     */
    public function getUrls($word)
    {
        $urls = $this->buildUrls($word);
        $results = HttpHelper::asyncGet($urls);
        return $results;
    }

    /**
     * @param $url_path
     * @return array
     */
    public function buildUrls($url_path)
    {
        // Sanitize the path
        $url_path = HttpHelper::sanitizeUrlPath($url_path);

        // Build the array or URLs
        $out = [];
        foreach ($this->hosts as $host) {
            $out[] = $host . "/{$url_path}";
        }

        // Sanitize the array of Urls
        $out = HttpHelper::validateUrlsGood($out);
        return $out;
    }

    public function isSuccessUrl($response)
    {

    }
}