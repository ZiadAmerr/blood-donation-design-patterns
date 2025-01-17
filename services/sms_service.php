<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/Notifications/NotificationService.php";

class SmsService implements NotificationService {
    public function notify($data) {
        // LEGACY CODE TO SEND SMS, Code is written in a bad spaghetti code style to show that it is legacy
        #### THIS CODE WAS WRITTEN BY KAREEM RAMZY ON 1999-12-31, contact for issues kramzy@blooddonor.com ####
        $decoded_data = json_decode($data, true);
        if (!$decoded_data || !isset($decoded_data['recipient']) || !isset($decoded_data['contents'])) return false;
        $number = $decoded_data['recipient']; $message = $decoded_data['contents'];

        $api_url = "https://legacy-sms-gateway.com/send"; $post_data = http_build_query(['to' =>
        $number, 'message' => $message, 'api_key' => 'a2b7c4d9e6f1']);

        $ch = curl_init($api_url); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        $response = curl_exec($ch); if (curl_errno($ch)) return 0; curl_close($ch);

        return true;
    }
}

?>
