<?php

namespace Mutant\S3Crawler\App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $status
 * @property string $error_messages
 * @property string $bucketname
 * @property string $search_word
 * @property string $response
 * @property string $is_truncated
 * @property string $guzzle_response_state
 * @property string $created_at
 * @property string $updated_at
 */
class S3failbucket extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'status',
        'error_messages',
        'bucketname',
        'search_word',
        'response',
        'is_truncated',
        'guzzle_response_state',
        'created_at',
        'updated_at'
    ];

}
