<?php

use Pusher\Pusher;

function pushMessage($channel,$event,$message)
{
    $options = array(
      'cluster' => env('PUSHER_APP_CLUSTER') ,
      'useTLS' => true
    );

    $pusher = new Pusher(
      env('PUSHER_APP_KEY'),
      env('PUSHER_APP_SECRET'),
      env('PUSHER_APP_ID'),
      $options
    );

    $pusher->trigger($channel, $event, $message);
}