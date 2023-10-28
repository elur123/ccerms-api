<?php

namespace App\Services;

use App\Models\Notification;
class SendSubmissionNotification {

    public function execute($recipients = [], $options)
    {
        $data = $recipients->map(function($e) use($options) {
            return [
                'user_id' => $e['id'],
                'type' => $options['type'],
                'message' => $options['message'],
                'url' => $e['role'] == 'admin' ? $options['url'] : '/student/group'
            ];
        });

        Notification::upsert($data->toArray(), ['type', 'message', 'user_id']);
    }
}