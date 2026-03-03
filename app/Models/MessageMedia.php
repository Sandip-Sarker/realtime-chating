<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageMedia extends Model
{
    protected $fillable = [
        'message_id',
        'file_path',
        'file_type',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
