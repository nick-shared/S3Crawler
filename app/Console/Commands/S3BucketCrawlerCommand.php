<?php

namespace Mutant\S3Crawler\App\Console\Commands;

use Illuminate\Console\Command;

use Mutant\S3Crawler\App\Helpers\S3Crawler as S3C;
use Mutant\File\App\Helpers\File as MutantFile;
use Mutant\File\App\Helpers\FileHelper as MutantFileHelper;
use Mutant\S3Crawler\App\Models\S3openbucket;
use Mutant\S3Crawler\App\Models\S3failbucket;

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
        $input_file_name = $this->option('inputfile');

        if (!isset($input_file) || !MutantFileHelper::fileExists($input_file)) {
            $this->error('Please enter a valid filename!');
        }

        $input_file = new MutantFile($input_file_name);

        $s3crawler = new S3C();
        $s3crawlerprocess = new S3Cprocess();

        while (!$input_file->eof()) {
            $word = $input_file->getLineDataAndAdvance();
            $line_number = $input_file->getLineNumber();
            $results = $s3crawler->run($word);

            foreach ($results as $result) {
                // Record successful open buckets
                if ($result->status == 'success') {
                    $properties = get_object_vars($result);
                    unset($properties['response']);


                    S3openbucket::create($properties);
                    continue;
                }
                // Note: this records failed connections
                if ($result->guzzle_response_state != 'fulfilled') {
                    $properties = get_object_vars($result);
                    S3failbucket::create($properties);
                }
            }
        }
    }
}
