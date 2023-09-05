<?php

use Pusher\Pusher;
use App\Models\Group;
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

function generateGroupKey(): string
{
  $alphabet = 'BCDFGHJLMNPRSTVWXYZ2456789';

  do {
    $code = substr(str_shuffle(str_repeat($alphabet, 6)), 0, 6);
  } while(Group::where('key', $code)->exists());

  return $code;
}