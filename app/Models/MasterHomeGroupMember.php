<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MasterHomeGroupMember
 *
 * @property int $id
 * @property int|null $home_group_id
 * @property int|null $user_id
 *
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class MasterHomeGroupMember extends Model
{
    use HasFactory;
    protected $table = 'master_home_group_members';

    protected $casts = [
        'home_group_id' => 'int',
        'user_id' => 'int',

        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'home_group_id',
        'user_id'
    ];
}
