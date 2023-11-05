<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MasterHospitalGroup
 *
 * @property int $id
 * @property int|null $hospital_id
 * @property int|null $hospital_department_id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $remark
 *
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class MasterHospitalGroup extends Model
{
    use HasFactory;
    protected $table = 'master_hospital_groups';

    protected $casts = [
        'hospital_id' => 'int',
        'hospital_department_id' => 'int',
        'user_id' => 'int',

        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'hospital_id',
        'hospital_department_id',
        'user_id',
        'name',
        'remark',
    ];
}
