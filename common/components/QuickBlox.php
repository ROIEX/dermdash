<?php

namespace common\components;

use yii\base\Component;

class QuickBlox extends Component
{
    const API_ENDPOINT = 'https://api.quickblox.com';
    const API_SESSION_PATH = 'session.json';
    const API_DIALOGS_PATH = 'chat/Dialog.json';
    const APP_ID = '42379';
    const API_AUTH_KEY = 'R8Ey7R6kWFpZ-rs';
    const API_AUTH_SECRET = 'a4bz3de9T2ryZVk';

    public function createSession($app_id, $auth_key, $auth_secret, $login, $password)
    {
        if (!$app_id || !$auth_key || !$auth_secret || !$login || !$password) {
            return false;
        }

        $nonce = rand();
        $timestamp = time(); // time() method must return current timestamp in UTC but seems like hi is return timestamp in current time zone
        $signature_string = "application_id=" . $app_id . "&auth_key=" . $auth_key . "&nonce=" . $nonce . "&timestamp=" . $timestamp . "&user[login]=" . $login . "&user[password]=" . $password;

        $signature = hash_hmac('sha1', $signature_string , $auth_secret);

        // Build post body
        $post_body = http_build_query( array(
            'application_id' => $app_id,
            'auth_key' => $auth_key,
            'timestamp' => $timestamp,
            'nonce' => $nonce,
            'signature' => $signature,
            'user[login]' => $login,
            'user[password]' => $password
        ));

        // Configure cURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::API_ENDPOINT . '/' . self::API_SESSION_PATH); // Full path is - https://api.quickblox.com/session.json
        curl_setopt($curl, CURLOPT_POST, true); // Use POST
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body); // Setup post body
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Receive server response

        // Execute request and read response
        $response = curl_exec($curl);
        $responseJSON = json_decode($response)->session;

        // Check errors
        if ($responseJSON) {
            return $responseJSON;
        } else {
            $error = curl_error($curl). '(' .curl_errno($curl). ')';
            return $error;
        }

        // Close connection
        curl_close($curl);

    }

    public function getDialogs($token)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::API_ENDPOINT . '/' . self::API_DIALOGS_PATH . '');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Receive server response
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'QuickBlox-REST-API-Version: 0.1.0',
            'QB-Token: ' . $token
        ));
        $response = curl_exec($curl);
        $responseJSON = json_decode($response);

        // Check errors
        if ($responseJSON) {
            return $responseJSON;
        } else {
            $error = curl_error($curl). '(' .curl_errno($curl). ')';
            return $error;
        }
        curl_close($curl);

    }

    public function getDialog()
    {

    }

    public function sendMessage()
    {
        $session = $this->createSession(self::APP_ID, self::API_AUTH_KEY, self::API_AUTH_SECRET, 'dermdash_admin_user', '11111111');
        $token = $session->token;
        $chat_dialog_id = '5513e91f535c12b98f0212f1';
//        $attachment = array( array(
//            'type' => 'image',
//            'url' => 'https://qbprod.s3.amazonaws.com/70a9a896466f44b2b70ee79386e86f3e00',
//            'id' => 580795
//        ));

        $data = array(
            'chat_dialog_id' => $chat_dialog_id,
            'message' => 'This is a message',
          //  'attachments' => (object) $attachment,
        );

        $request = json_encode($data);

        $ch = curl_init('https://api.quickblox.com/chat/Message.json');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'QuickBlox-REST-API-Version: 0.1.0',
            'QB-Token: ' . $token
        ));

        $resultJSON = curl_exec($ch);
        $pretty = json_encode(json_decode($resultJSON), JSON_PRETTY_PRINT);

        echo $pretty;
    }
}