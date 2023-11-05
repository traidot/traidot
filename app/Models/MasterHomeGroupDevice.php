<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MasterHomeGroupDevice
 *
 * @property int $id
 * @property int|null $home_group_id
 * @property int|null $device_id
 * @property bool|null $is_disabled
 *
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class MasterHomeGroupDevice extends Model
{
    use SoftDeletes;
    protected $table = 'master_home_group_devices';

    protected $casts = [
        'home_group_id' => 'int',
        'device_id' => 'int',
        'is_disabled' => 'bool',

        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'home_group_id',
        'device_id',
        'is_disabled',
    ];
}
