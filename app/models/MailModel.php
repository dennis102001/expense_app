<?php

namespace models;

use core\Model;

class MailModel extends Model{

    public static function sendResetLink($email, $resetLink){
        try {
            return self::sendViaBrevoAPI($email, "Password Reset", "This email contains link to reset your password from Expense Tracker App. Disregard if you do not know about this. Click to reset password: <a href='$resetLink'>$resetLink</a>");
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function sendEmailVerification($email, $link){
        try {
            return self::sendViaBrevoAPI($email, "Account Activation", "This email contains link to activate your account from Expense Tracker App. Disregard if you do not know about this. Click to activate your account: <a href='$link'>$link</a>");
        } catch (\Exception $e) {
            return false;
        }
    }

    private static function sendViaBrevoAPI($toEmail, $subject, $htmlBody){
        $apiKey = getenv('BREVO_API_KEY') ?: $_ENV['BREVO_API_KEY'];
        $fromEmail = getenv('MAIL_USERNAME') ?: $_ENV['MAIL_USERNAME'];
        $fromName = getenv('MAIL_FROM_NAME') ?: $_ENV['MAIL_FROM_NAME'];

        $postData = [
            'sender' => [
                'name' => $fromName,
                'email' => $fromEmail
            ],
            'to' => [
                ['email' => $toEmail]
            ],
            'subject' => $subject,
            'htmlContent' => $htmlBody
        ];

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'api-key: ' . $apiKey
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode >= 200 && $httpCode < 300;
    }

}