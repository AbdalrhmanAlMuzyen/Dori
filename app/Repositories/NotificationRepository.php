<?php

namespace App\Repositories;

class NotificationRepository{

    public function createNotification($user,$title,$body,$type,$target_id)
    {
        return $user->notifications()->create([
            "title"=>$title,
            "body"=>$body,
            "target_id"=>$target_id,
            "type"=>$type
        ]);
    }
}