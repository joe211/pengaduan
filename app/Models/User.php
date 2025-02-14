<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'email',
        'username',
        'password',
        'level_user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getData()
    {
        $data = $this->leftJoin('level_users','users.level_user_id','level_users.id')
            ->select(
                'users.nama as nama_user',
                'users.id as id',
                'users.username as username_user',
                'users.email as email_user',
                'users.updated_at as updated_at_user',
                'level_users.nama as level_user'
            )
            ->orderBy('users.created_at','asc')
            ->get();
        return $data;
    }
    
    public function getProfile($id_user)
    {
        $data = $this->leftJoin('level_users','users.level_user_id','level_users.id')
            ->leftJoin('jabatans','users.jabatan_id','jabatans.id')
            ->leftJoin('pangkats','users.pangkat_id','pangkats.id')
            ->leftJoin('opds','users.opd_id','opds.id')
            ->select(
                'users.nip as nip',
                'users.nope as nope',
                'users.nama as nama_user',
                'users.id as id',
                'users.username as username_user',
                'users.email as email_user',
                'users.updated_at as updated_at_user',
                'level_users.nama as level_user',
                'jabatans.nama as nama_jabatan',
                'pangkats.nama as nama_pangkat',
                'opds.nama as nama_opd'
            )
            ->where('users.id',$id_user)
            ->orderBy('users.created_at','asc')
            ->first();
        return $data;
    }
}
