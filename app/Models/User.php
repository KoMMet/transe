<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','recv_lang_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getUser($masterId)
    {
        $user = User::select('*')
            ->from('users')
            ->where('id', '=', $masterId)
            ->get();
 
        return $user;
    }

//    public function getUserLang($masterId)
//    {
//        $user = User::select('users.id, users.name, mes_languages.language')
//            ->from('users')
//            ->join('mes_languages', 'users.recv_lang_id', '=', 'mes_languages.id')
//            ->where('users.id', '=', $masterId)
//            ->get();
// 
//        return $user;
//    }

    public function getUserLang($masterId)
    {
        $user = DB::select(
            "select users.id, users.name, mes_languages.language from users join mes_languages on mes_languages.id = users.recv_lang_id where users.id = ? ;", [$masterId,]
        );
        return $user;
    }
}
