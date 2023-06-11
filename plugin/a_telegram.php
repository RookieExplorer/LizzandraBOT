<?php

/**
 * Telegram BOT API
 * @version	2022-03-26
 */

function telegram($method, $content, $token = '')
{
    try {
        $url = 'https://api.telegram.org/bot' . $token . '/' . $method;
        $options = [
            CURLOPT_HEADER => 0,
            CURLOPT_HTTPHEADER => [
                'HTTP/1.1 200 Ok',
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($content),
            CURLOPT_RETURNTRANSFER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSLVERSION => 0,
            CURLOPT_URL => $url
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        curl_close($ch);
    } catch (Exception $e) {
        error_log('Error while sending message: ' . $e->getMessage());
    }
}

$input = file_get_contents('php://input');
$telegram = json_decode($input, true);
