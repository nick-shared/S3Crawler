<?php

require __DIR__ . '/vendor/autoload.php';
use Mutant\S3Crawler\App\Helpers\S3CrawlerHelper;
use Mutant\File\App\Helpers\File;

$s3crawler = new S3CrawlerHelper();

$input_filename = $argv[1]; // get filename for file of names as command line argument
$input_file = new File($input_filename); // open that file
$success_file = new File("./s3crawlersuccess.txt");


// Determines whether to create error file and keep failed requests
if ($argv[2] == 'keep_errs') {
    $keep_errs = true;
    $fail_file = new File("./s3crawlerfail.txt");
}


while (!$input_file->eof()) {
    $next_name = $input_file->getLineDataAndAdvance(); //get next name from input file
    $results = $s3crawler->run($next_name); // run the crawler

    // process results
    foreach ($results as $result) {

        if ($result->status == 'success') {
            $success_file->appendAndStay($result->bucketname);
            continue;
        }

        if ($keep_errs) {
            $out = [
                "bucketname" => $result->bucketname,
                "error" => $result->error_messages
            ];

            $out = json_encode($out);
            $fail_file->appendAndStay($out);
        }
    }
}
