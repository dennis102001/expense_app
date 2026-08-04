<?php

namespace models;

use core\Model;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer;

class MailModel extends Model{

    public static function sendResetLink($email, $resetLink){
        try {
            $mail = self::createMailer();
            $mail->addAddress($email);
            $mail->Subject = "Password Reset";
            $mail->Body = "This email contains link to reset your password from Expense Tracker App. Disregard if you do not know about this. Click to reset password: <a href='$resetLink'>$resetLink</a>";
            $mail->send();

            return true;
        } catch (PHPMailerException $e) {
            return false;
        }

    }

    public static function sendEmailVerification($email, $link){
        try {
            $mail = self::createMailer();
            $mail->addAddress($email);
            $mail->Subject = "Account Activation";
            $mail->Body = "This email contains link to activate your account from Expense Tracker App. Disregard if you do not know about this. Click to activate your account: <a href='$link'>$link</a>";
            $mail->send();

            return true;
        } catch (PHPMailerException $e) {
            return false;
        }

    }

    public static function createMailer(){
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = getenv('MAIL_HOST') ?: $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = getenv('MAIL_USERNAME') ?: $_ENV['MAIL_USERNAME'];
        $mail->Password = getenv('MAIL_PASSWORD') ?: $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = getenv('MAIL_ENCRYPTION') ?: $_ENV['MAIL_ENCRYPTION'];
        $mail->Port = (int) getenv('MAIL_PORT') ?: $_ENV['MAIL_PORT'];
        $mail->setFrom(getenv('MAIL_USERNAME') ?: $_ENV['MAIL_USERNAME'], getenv('MAIL_FROM_NAME') ?: $_ENV['MAIL_FROM_NAME']);
        $mail->isHTML(true);

        return $mail;
    }
}