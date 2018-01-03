<?php

namespace Mutant\S3Crawler\App\Console\Commands;

use Illuminate\Console\Command;

use Mutant\S3Crawler\App\Helpers\S3Crawler as S3C;
use Mutant\File\App\Helpers\File as MutantFile;
use Mutant\File\App\Helpers\FileHelper as MutantFileHelper;
use App\Models\S3openbucket;

class S3BucketCrawlerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mutant:s3-bucket-crawler {--inputfile=}';

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
        $input_file = $this->option('inputfile');

        if (!MutantFileHelper::fileExists($input_file)) {
            $this->error('No file exists by that name!');
        }


        $input_file = new MutantFile($input_file);
        $s3crawler = new S3C();

        while (!$input_file->eof()) {
            $next_name = $input_file->getLineDataAndAdvance();
            $results = $s3crawler->run($next_name);

            // process results
            foreach ($results as $result) {
                if ($result->status == 'success') {
                       $properties = get_object_vars($result);
                       S3openbucket::create($properties);
                       continue;
                }
            }
        }


    }
}
