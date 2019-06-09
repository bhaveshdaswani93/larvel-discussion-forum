<?php

namespace App;


class Reply extends Model
{
    protected $fillable = ['discussion_id','user_id','content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }
    
}
