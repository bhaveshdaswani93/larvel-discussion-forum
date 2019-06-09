<?php

namespace App;

use App\User;
use Illuminate\Support\Str;
use App\Notifications\ReplyMarkAsBestReplyNotification;

class Discussion extends Model
{
    //
    protected $fillable = ['user_id','channel_id','content','title','slug','reply_id'];

    protected static function boot() {
        parent::boot();

        static::creating(function ($discussion) {
            $discussion->slug = Str::slug($discussion->title);
        });
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_slug($this->title);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function bestReply()
    {
        return $this->belongsTo(Reply::class,'reply_id');
    }

    public function markAsBestReply(Reply $reply)
    {
        $this->update([
                'reply_id' => $reply->id
            ]);
            if($this->user->id !== $reply->user-id)
            {
                $reply->user->notify(new ReplyMarkAsBestReplyNotification($reply->discussion));
            }
    }
     
    public function scopeGetFilterByChannel($query)
    {
        $channelSlug = request()->query('channel');
        if($channelSlug)
        {
            $channel = Channel::where('slug',$channelSlug)->first();
            if($channel)
            {
                return $query->where('channel_id',$channel->id);
            }
            return $query;
        }
        return $query;
    }
}
