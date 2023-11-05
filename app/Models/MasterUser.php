<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Summary of MasterUser
 *
 * @property int $id
 *
 * @property int|null $role
 * @property string|null $access_token
 * @property string|null $lastname
 * @property string|null $firstname
 * @property string|null $lastname_kana
 * @property string|null $firstname_kana
 * @property int|null $gender
 * @property date|null $birthday
 * @property string|null $email
 * @property string|null $tel
 * @property string|null $zip_code
 * @property string|null $address
 *
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */

class MasterUser extends Model
{
    use HasFactory;

    /**
     * Summary of table
     * @var string
     */
    protected $table = 'master_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role',
        'access_token',
        'lastname',
        'firstname',
        'lastname_kana',
        'firstname_kana',
        'gender',
        'birthday',
        'email',
        'tel',
        'zip_code',
        'address',
        'disable_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'role' => 'int',
        'gender' => 'int',
        'birthday' => 'date',
        'disable_at' => 'datetime',

        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}
