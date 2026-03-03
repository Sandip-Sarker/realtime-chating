<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'is_group',
        'status',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function groupUsers()
    {
        return $this->hasMany(GroupUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_users');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function isPrivate()
    {
        return !$this->is_group;
    }

    public function getRecipientAttribute()
    {
        if ($this->is_group) {
            return null;
        }

        return $this->users()->where('users.id', '!=', auth()->id())->first();
    }

    public function getDisplayNameAttribute()
    {
        if ($this->is_group) {
            return $this->name ?? 'Group';
        }

        $recipient = $this->recipient;
        return $recipient ? $recipient->name : 'Unknown User';
    }
}
