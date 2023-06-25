<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Friend extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'master_id', 'friend_id'
    ];

    public function getFriends($masterId)
    {
	    $friends = Friend::select('users.id', 'users.name')
		    ->from('friends')
		    ->join('users', function($j) {
			    $j->on('users.id', '=', 'friends.friend_id');
		    })
		    ->where('friends.master_id', '=', $masterId)
		    ->get();

	    return $friends;
    }

}
