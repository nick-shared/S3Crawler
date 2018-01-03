<?php
namespace Mutant\S3Crawler\App\Helpers;

use  Mutant\String\App\Helpers\StringHelper as StrHelp;

/**
 * Class S3CrawlerResult
 * @package Mutant\S3Crawler\App\Helpers
 */
class S3CrawlerResult
{

    public $status = null;
    public $error_messages = null;
    public $bucketname = null;
    public $search_word = null;
    public $response = null;
    public $is_truncated = null;
    public $guzzle_response_state = null;

    /**
     * S3CrawlerResult constructor.
     * @param string $search_word
     * @param string $bucketname
     * @param string $guzzle_response_state
     * @param $response
     */
    public function __construct(string $search_word, string $bucketname, string $guzzle_response_state, $response)
    {

        $this->search_word = $search_word;
        $this->bucketname = $bucketname;
        $this->guzzle_response_state = $guzzle_response_state;

        // if the guzzle response was rejected
        if ($guzzle_response_state != 'fulfilled') {
            $this->addError('rejected guzzle http request');
            return;
        }

        // can accept guzzle response
        if (is_a($response, 'GuzzleHttp\Psr7\Response')) {
            $this->response = (string)$response->getBody();
        } // can accept string response
        else if (is_string($response)) {
            $this->response = $response;
        } else {
            $this->addError('A valid response was not passed in during object creation.');
            return;
        }

        if ($this->isFail()) return;
        if ($this->isSuccess()) return;
    }

    /**
     * @return bool
     */
    private function isSuccess()
    {
        $response = $this->response;

        try {
            @$xml = simplexml_load_string($response);
            @$xml_root = $xml->getName();

            if ($xml_root == "ListBucketResult") {
                $this->status = "success";
                $this->is_truncated = (string)$xml->IsTruncated;
            }

        } catch (\Throwable $e) {
            $this->addError("failed loading xml ListBucketResult");
        }

        if ($this->status == 'success') {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @return bool
     */
    private function isFail()
    {
        $response = $this->response;


        if ($response == "null") {
            $this->addError('no response detected');
        }

        try {
            @$xml = simplexml_load_string($response);
            @$xml_root = $xml->getName();
            @$xml_error = $xml->Code;
            if ($xml_root == "Error") {
                $this->addError($xml_error);
            }
        } catch (\Throwable $e) {
            $this->addError('failed loading xml error code');
        }

        if ($this->status == 'fail') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $error
     */
    private function addError(string $error)
    {
        $this->error_messages = $this->error_messages . ' | ' . $error;
        $this->status = "fail";

    }
}