<?php

namespace models;

use core\Model;

class UserModel extends Model{

    public function signup($name, $email, $password, $token){
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, verification_token) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $name, 
            $email, 
            $hashed_password, 
            $token
        ]);
    }

    public function login($email, $password, $remember){
        $getUser = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $getUser->execute([$email]);

        $user = $getUser->fetch();

        if(!$user) return 'not_found';
        if($user['verified'] == 0) return 'not_verified';

        if(password_verify($password, $user['password'])){
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];

            if($remember){
                $token = bin2hex(random_bytes(32));

                $storeToken = $this->db->prepare("UPDATE users SET remember_token=? WHERE id=?");
                $storeToken->execute([$token, $user['id']]);

                setcookie("remember_token", $token, time() + (86400 * 30), "/");
            }

            return $user['id'];
        }

        return 'wrong_password';
    }

    public function existingEmail($email){
        $stmt = $this->db->prepare('SELECT email FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ? true : false;
    }

    public function saveResetToken($email, $token, $expiry) {
        $saveToken = $this->db->prepare('UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?');
        return $saveToken->execute([$token, $expiry, $email]);
    }

    public function getUserByResetToken($token){
        $getUser = $this->db->prepare("SELECT id, token_expiry FROM users WHERE reset_token = ?");
        $getUser->execute([$token]);
        return $getUser->fetch();
    }

    public function getUserByVerificationToken($token){
        $getUser = $this->db->prepare("SELECT * FROM users WHERE verification_token = ?");
        $getUser->execute([$token]);
        return $getUser->fetch();
    }

    public function resetPassword($userId, $password){
        $updateUser = $this->db->prepare('UPDATE users SET password = ?, token_expiry = NULL, reset_token = NULL WHERE id = ?');
        return $updateUser->execute([$password, $userId]);
    }

    public function hasRememberToken($rememberToken){
        $getUser = $this->db->prepare('SELECT * FROM users WHERE remember_token = ?');
        $getUser->execute([$rememberToken]);

        $user = $getUser->fetch();

        if($user){
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            return true;
        }

        return false;
    }

    public function deleteRememberToken($userId){
        setcookie("remember_token", "", time() - 3600, "/");
        $deleteRememberToken = $this->db->prepare("UPDATE users SET remember_token=NULL WHERE id=?");
        return $deleteRememberToken->execute([$userId]);
    }

    public function verifyAccount($token){
        $getUser = $this->db->prepare('SELECT * FROM users WHERE verification_token = ?');
        $getUser->execute([$token]);
        
        $user = $getUser->fetch();
        if($user){
            $verifyUser = $this->db->prepare('UPDATE users SET verified = 1, verification_token = NULL WHERE id = ?');
            return $verifyUser->execute([$user['id']]);
        }

        return false;
    }

    public function updateVerificationToken($token, $email){
        $updateVerification = $this->db->prepare('UPDATE users SET verification_token = ? WHERE email = ?');
        return $updateVerification->execute([$token, $email]);
    }

    public function verifiedAccount($email){
        $stmt = $this->db->prepare('SELECT email FROM users WHERE verified = 1 AND email = ?');
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ? true : false;
    }
}