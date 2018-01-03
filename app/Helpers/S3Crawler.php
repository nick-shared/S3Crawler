<?php
namespace Mutant\S3Crawler\App\Helpers;

use Mutant\Http\App\Helpers\HttpHelper;

class S3Crawler
{

    /**
     * @var array
     */
    public $hosts = [
        'http://s3-us-east-2.amazonaws.com',
        'http://s3-external-1.amazonaws.com',
        'http://s3-us-west-1.amazonaws.com',
        'http://s3-us-west-2.amazonaws.com',
        'http://s3-ca-central-1.amazonaws.com',
        'http://s3-ap-south-1.amazonaws.com',
        'http://s3-ap-northeast-2.amazonaws.com',
        'http://s3-ap-southeast-1.amazonaws.com',
        'http://s3-ap-southeast-2.amazonaws.com',
        'http://s3-ap-northeast-1.amazonaws.com',
        'http://s3.cn-north-1.amazonaws.com.cn',
        'http://s3.cn-northwest-1.amazonaws.com.cn',
        'http://s3-eu-central-1.amazonaws.com',
        'http://s3-eu-west-1.amazonaws.com',
        'http://s3-eu-west-2.amazonaws.com',
        'http://s3-eu-west-3.amazonaws.com',
        'http://s3-sa-east-1.amazonaws.com'
    ];

    /**
     * @param $word
     * @return Pool
     */
    public function run($word)
    {
        $urls = $this->buildUrls($word);  // build/sanitize urls
        $results = HttpHelper::asyncGet($urls); // async get the result

        $s3results = [];
        foreach ($results as $key => $result) {
            $response_state = $result['state'];

            if($response_state == 'fulfilled'){
                $response = $result['value'];
            }
            else{
                $response = null;
            }

            $s3results[] = new S3CrawlerResult($word, $key, $response_state, $response);
        }
        return $s3results;
    }

    /**
     * @param $url_path
     * @return array
     */
    public function buildUrls($word)
    {
        // Sanitize the path
        $word = HttpHelper::sanitizeUrlPath($word);

        // Build the array or URLs
        $out = [];
        foreach ($this->hosts as $host) {
            $out[] = $host . "/{$word}";
        }

        // Sanitize the array of Urls
        $out = HttpHelper::validateUrlsGood($out);
        return $out;
    }


}