<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Dmmessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id', 'reciever_id', 'src_message', 'trns_message', 'created_at', 'updated_at'
    ];

    public function getReadAll($senderId, $friendId)
    {
        $snd = intVal($senderId);
        $fnd = intVal($friendId);
	    $message = Dmmessage::select('d.sender_id', 'd.reciever_id', 'd.src_message', 'd.trns_message', 'su.name as sender_name', 'fu.name as rec_name')
		    ->from('dmmessages as d')
            ->join('users as su', 'su.id', '=', 'd.sender_id')
            ->join('users as fu', 'fu.id', '=', 'd.reciever_id')
            ->where(function($q) use(&$snd, &$fnd){
                $q->where('sender_id', '=', $snd)->where('reciever_id', '=', $fnd);
            })->orWhere(function($qq) use(&$snd, &$fnd){
                $qq->where('reciever_id', '=', $snd)->where('sender_id', '=', $fnd);
            })
            ->orderBy('d.id', 'asc')
		    ->get();

	    return $message;
    }


    public function addMessage($senderId, $friendId, $message, $trnsmessage)
    {
        $snd = intVal($senderId);
        $fnd = intVal($friendId);
        $dmm = new Dmmessage();

        $dmm->sender_id = $snd;
        $dmm->reciever_id = $fnd;
        $dmm->src_message = $message;
        $dmm->trns_message = $trnsmessage;

        $dmm->save();

    }
}
