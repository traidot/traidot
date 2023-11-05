<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MasterHomeGroup
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 *
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class MasterHomeGroup extends Model
{
    use HasFactory;
    protected $table = 'master_home_groups';

    protected $casts = [
        'user_id' => 'int',

        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'name'
    ];
}
