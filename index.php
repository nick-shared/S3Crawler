<?php

require __DIR__ . '/vendor/autoload.php';
use Mutant\S3Crawler\App\Helpers\S3Crawler;
use Mutant\File\App\Helpers\File;

$s3crawler = new S3Crawler();

$input_filename = $argv[1]; // get filename for file of names as command line argument
$input_file = new File($input_filename); // open that file
$success_file = new File("./s3crawlersuccess.txt");
$keep_errs = false;

// Determines whether to create error file and keep failed requests
if (isset($argv[2]) && $argv[2] == 'keep_errs') {
    $keep_errs = true;
    $fail_file = new File("./s3crawlerfail.txt");
}

// Determines whether to create error file and keep failed requests
if (isset($argv[2]) && is_numeric($argv[2]) ) {
    $iterator = 0;
    $iterator_max = $argv[2];
}


while (!$input_file->eof()) {
    $next_name = $input_file->getLineDataAndAdvance(); //get next name from input file
    echo $next_name . "\n";
    $results = $s3crawler->run($next_name); // run the crawler

    // allows you to return early from processing the file
    if( isset($iterator) && ($iterator++ >= $iterator_max -1) ) break;

    // process results
    foreach ($results as $result) {

        if ($result->status == 'success') {
            $success_file->appendAndAdvance($result->bucketname);
            continue;
        }

        if ($keep_errs) {
            $out = [
                "bucketname" => $result->bucketname,
                "error" => $result->error_messages
            ];

            $out = json_encode($out);
            $fail_file->appendAndAdvance($out);
        }
    }
}
