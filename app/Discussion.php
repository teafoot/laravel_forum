<?php

namespace LaravelForum;

use LaravelForum\Channel;
use LaravelForum\Notifications\ReplyMarkedAsBestReply;

class Discussion extends Model
{
    public function author() {
        return $this->belongsTo(User::class, "user_id");
    }
    
    public function replies() {
        return $this->hasMany(Reply::class);
    }
    
    public function getRouteKeyName() {
        return "slug";
    }
    
    public function markAsBestReply(Reply $reply) {
        $this->update([
           "reply_id" => $reply->id
        ]);

        if ($reply->owner->id != $this->author->id) {
            $reply->owner->notify(new ReplyMarkedAsBestReply($reply->discussion));
        }
    }
    
    public function bestReply() {
//        return Reply::find($this->reply_id);
        return $this->belongsTo(Reply::class, "reply_id");
    }

    public function scopeFilterByChannels($builder) {
        if (request()->query("channel")) {
            $channel = Channel::where("slug", request()->query("channel"))->first();

            if ($channel) {
                return $builder->where("channel_id", $channel->id);
            }
        }

        return $builder;
    }
}
