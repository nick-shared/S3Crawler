<?php

namespace Mutant\S3Crawler\App\Console\Commands;

use Illuminate\Console\Command;

use Mutant\S3Crawler\App\Helpers\S3Crawler as S3C;
use Mutant\File\App\Helpers\File as MutantFile;
use Mutant\File\App\Helpers\FileHelper as MutantFileHelper;
use Mutant\S3Crawler\App\Models\S3openbucket;
use Mutant\S3Crawler\App\Models\S3failbucket;
use Mutant\S3Crawler\App\Models\S3crawlerprocess as S3Cprocess;

class S3BucketCrawlerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mutant:s3-bucket-crawler {--inputfile=} {';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawls for open s3buckets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // File stuff
        $input_file_name = $this->option('inputfile');
        if (!isset($input_file_name) || !MutantFileHelper::fileExists($input_file_name)) {
            $this->error('Please enter a valid filename!');
            return;
        }
        $input_file = new MutantFile($input_file_name);


        // new crawler
        $s3crawler = new S3C();

        // new proc
        $s3process = S3Cprocess::create([
            "filename" => $input_file_name,
            "process_type" => "S3Crawler"
        ]);


        $last_word = "";
        //process loop
        while (!$input_file->eof()) {

            // get information variables
            $word = $input_file->getLineDataAndAdvance();

            // try to performantly prevent dupes(works great on sorted files)
            // https://stackoverflow.com/questions/18443144/how-to-perform-sort-on-all-files-in-a-directory
            if ($word == $last_word) {
                $last_word=$word;
                continue;
            }else{
                $last_word=$word;
            }

            $line_number = $input_file->getLineNumber();


            // run the crawler
            $results = $s3crawler->run($word);

            // process the results
            foreach ($results as $result) {
                $current_bucket = $result->bucketname;
   
                // update the process log table
                $s3process->update([
                    'current_line_number' => $line_number,
                    'current_word' => $word,
                    'current_bucket' => $current_bucket
                ]);

                // Record successful open buckets
                if ($result->status == 'success') {
                    $properties = get_object_vars($result);
                    unset($properties['response']);
                    S3openbucket::create($properties);
                    continue;
                }

                // Note: this records failed guzzle connections only
                // Recording all closed buckets would get into the hundreds of millions
                if ($result->guzzle_response_state != 'fulfilled') {
                    $properties = get_object_vars($result);
                    S3failbucket::create($properties);
                }
            }
        }
    }
}
