<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PinnedItem extends Model
{
    protected $fillable = ['user_id', 'pinnable_id', 'pinnable_type'];

    public function pinnable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
