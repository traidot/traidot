<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MasterUserLogin
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $platform
 * @property string|null $identifier
 * @property string|null $password
 * @property string|null $push_token
 *
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class MasterUserLogin extends Model
{
    use HasFactory;

    /**
     * Summary of table
     * @var string
     */
    protected $table = 'master_user_logins';

    /**
     * Summary of casts
     * @var array
     */
    protected $casts = [
        'user_id' => 'int',
        'platform' => 'int',

        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'user_id',
        'platform',
        'identifier',
        'push_token',
    ];
}
