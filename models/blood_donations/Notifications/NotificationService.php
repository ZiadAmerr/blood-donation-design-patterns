<?php

interface NotificationService {
    // notify_user should take a user JSON object and a message string
    public function notify(string $number, string $message);
}

?>