<?php

namespace Mutant\S3Crawler\App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $filename
 * @property string $process_type
 * @property string $current_line_number
 * @property string $current_word
 * @property string $current_bucket
 * @property string $created_at
 * @property string $updated_at
 */
class S3crawlerprocess extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'filename',
        'process_type',
        'current_line_number',
        'current_word',
        'current_bucket',
        'created_at',
        'updated_at'
    ];

}
