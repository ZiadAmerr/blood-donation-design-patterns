<?php

require_once 'NotificationService.php';


class WhatsAppService implements NotificationService {
    public $api_url = "https://api.fakewhatsapp.com/sendMessage";
    public $api_token  = "supersecrettoken";

    public function notify(string $number, string $message) {
        #### THIS CODE WAS WRITTEN BY KAREEM RAMZY ON 1999-12-31, contact for issues kramzy@blooddonor.com ####
        ####                                    THIS IS A BAD PRACTICE                                     ####
        global $GLOBAL_API_URL, $GLOBAL_API_TOKEN;

        # SEND REQUEST HERE! #
        $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $api_url); curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['phone' => $number,'message' => $message,'token' => $api_token]));
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
