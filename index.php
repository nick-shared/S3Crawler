<?php

require __DIR__ . '/vendor/autoload.php';
use Mutant\S3Crawler\App\Helpers\S3CrawlerHelper;
use Mutant\File\App\Helpers\File;
use Mutant\File\App\Helpers\FileHelper;

$s3crawler = new S3CrawlerHelper();

// Create a directory for the output files
FileHelper::createDirectory("./s3files");


$input_filename = $argv[1]; // get filename for file of names as command line argument
$input_file = new File($input_filename); // open that file
$fail_file = new File("./s3crawlerfail.txt");

while (!$input_file->eof()) {
    $next_name = $input_file->getLineDataAndAdvance();
    $results = $s3crawler->run($next_name);

    foreach ($results as $key => $result) {
        $word = $result['S3CrawlerWord'];
        if ($result['S3CrawlerStatus'] == 'success') {
            $filename = "./s3files/$word.txt";
            $success_file = new File($filename);

        }

        if ($result['S3CrawlerStatus'] == 'fail' || $result['S3CrawlerStatus'] == 'undetermined') {
            $errorcode = $result['S3CrawlerErrorCode'];
            $out = [
                "word" => $word,
                "bucket" => $key,
                "errorcode" => $errorcode
            ];

            $out = json_encode($out);
            $fail_file->appendAndStay($out);

        }
    }

}
