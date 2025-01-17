<?php

require_once 'NotificationService.php';


class WhatsAppService implements NotificationService {
    public $api_url = "https://api.fakewhatsapp.com/sendMessage";
    public $api_token  = "supersecrettoken";

    public function notify($data) {
        #### THIS CODE WAS WRITTEN BY KAREEM RAMZY ON 1999-12-31, contact for issues kramzy@blooddonor.com ####
        ####                                    THIS IS A BAD PRACTICE                                     ####
        global $GLOBAL_API_URL, $GLOBAL_API_TOKEN;

        $decoded_data = json_decode($whatsappdata, true);
        if (!$decoded_data || !isset($decoded_data['target']) || !isset($decoded_data['contents']) || !isset($decoded_data['token']) || !isset($decoded_data['source']))
            return 0;

        $number = $decoded_data['target']; $message = $decoded_data['contents'];
        $source = $decoded_data['source']; $token = $decoded_data['token'];

        # SEND REQUEST HERE! #
        $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $this->api_url); curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['phone' => $number,'message' => $message,'token' => $this->api_token]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            goto curlFail;
        }

        curl_close($ch);

        if (strpos($response, "error") !== false) {
            if (strpos($response, "quota exceeded") !== false) goto quotaExceeded;
            goto unknownError;
        } else {
            if (strpos($response, "success") !== false) goto success;
            goto unknownError;
        }

        # Error handling & returns
        quotaExceeded:
        return false;
        unknownError:
        return false;
        curlFail:
        return "Error!";
        success:
        return "OK";
    }
}

?>
