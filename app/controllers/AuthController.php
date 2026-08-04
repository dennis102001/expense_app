<?php

namespace controllers;

use core\Controller;
use models\MailModel;
use models\UserModel;
use PHPMailer\PHPMailer\PHPMailer;

class AuthController extends Controller {

    public function showForgotPassword(){
        $this->view('ForgotPassword', ['openMessageModal' => false]);
    }

    public function forgotPassword(){
        $email = trim($_POST['email']);

        $userModel = new UserModel();

        if($userModel->existingEmail($email) && $userModel->verifiedAccount($email)){
            $token = bin2hex(random_bytes(32));
            $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

            if($userModel->saveResetToken($email, $token, $expiry)){
                $appUrl = getenv('APP_URL') ?: ($_ENV['APP_URL'] ?? '');
                $resetLink = "$appUrl/reset_password?token=$token";
                
                MailModel::sendResetLink($email, $resetLink);
            }
        }
        
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'If the email exists, a reset link was sent.'
        ];

        header('Location: forgot_password');
        exit;
    }

    public function showResetPassword(){
        $token = trim($_GET['token'] ?? '');

        $userModel = new UserModel();

        $user = $userModel->getUserByResetToken($token);

        if($user && (strtotime($user['token_expiry']) >= time())){
            $this->view('ResetPassword', [
                'token' => $token,
            ]);
        }
        else{
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'The reset link is invalid or expired'
            ];

            header('Location: forgot_password');
            exit;
        }        
    }

    public function resetPassword(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $token = trim($_POST['token'] ?? '');
        $password = trim($_POST['password']);
        $password_confirmation = trim($_POST['password_confirmation']);

        $userModel = new UserModel();

        $user = $userModel->getUserByResetToken($token);

        if(!$user || (strtotime($user['token_expiry']) < time() )){
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Reset link is expired.'
            ];

            header('Location: forgot_password');
            exit;
        }

        
        if(!$this->validateResetPassword($password, $password_confirmation, $token)){
            return;
        }

        $newPassword = password_hash($password, PASSWORD_DEFAULT);

        if($userModel->resetPassword($user['id'], $newPassword)){
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Password change successfully. You can now login.'
            ];
        }
        else{
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Failed to update password. Please try again.'
            ];

            header('Location: reset_password?token=' . urlencode($token));
            exit;
        }

        header('Location: login');
        exit;
    }

    public function showLogin(){
        $this->view('Login', [
            'login_error' => [
                'error' => false,
            ],
        ]);
    }

    public function showSignup(){
        $this->view('Signup', [
            'registration_error' => [
                'error' => false
            ]
        ]);
    }

    public function signup(){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirmation = $_POST['password_confirmation'];

        if(!$this->validateSignup($name, $email, $password, $password_confirmation)){
            return;
        }
        if(!$this->checkExistingEmail($name, $email)){
            return;
        }
        
        $userModel = new UserModel();

        $verificationToken = bin2hex(random_bytes(32));

        if($userModel->signup($name, $email, $password, $verificationToken)){
            $appUrl = getenv('APP_URL') ?: ($_ENV['APP_URL'] ?? '');
            $link = "$appUrl/verify_email?token=$verificationToken";

            MailModel::sendEmailVerification($email, $link);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'An activation link was sent to your email.'
            ];

            header('Location: verify_email');
            exit;

        }
        else{
            $this->view('Signup', [
                'registration_error' => [
                    'error' => true,
                    'message' => 'Failed to register'
                ],
                'form_data' => [
                    'name' => $name,
                    'email' => $email,
                ]
            ]);
        }
    }

    public function login(){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $remember = isset($_POST['remember']) ? true : false;

        $validated = $this->validateLogin($email, $password);

        if($validated){            
            $userModel = new UserModel();
            $userId = $userModel->login($email, $password, $remember);
            
            if(is_numeric($userId)){
                header('Location: dashboard');
                exit;
            }

            if($userId === 'not_found'){
                $message = 'Account not found';
            }elseif($userId === 'not_verified'){
                $message = 'Account not verified';
            }else{
                $message = 'Incorrect Credentials';
            }
            
            $this->view('Login', [
                'login_error' => [
                    'error' => true,
                    'message' => $message,
                ],
                
                'form_data' => [
                    'email' => $email,
                ]
            ]);
            
        }
    }

    public function logout(){
        $userModel = new UserModel();
        if(isset($_SESSION['user_id'])){
            $userModel->deleteRememberToken($_SESSION['user_id']);
        }
        session_unset();
        session_destroy();
        header('Location: login');
        exit;
    }

    private function validateLogin($email, $password){
        $errors = [];

        if(!$email){
            $errors['email'] = 'Email field is required';
        }

        if(!$password){
            $errors['password'] = 'Password field is required';
        }

        if(empty($errors)){
            return true;

        }else{
            $this->view('Login', [
                'login_error' => [
                    'error' => true,
                    'message' => 'Please provide your credentials'
                ],
                'validation_errors' => $errors,
                'form_data' => [
                    'email' => $email
                ]
            ]);

            return false;
        }
    }

    private function validateSignup($name, $email, $password, $password_confirmation){
        $errors = [];

        if(!$name){
            $errors['name'] = 'Name field is required';
        }

        if(!$email){
            $errors['email'] = 'Email field is required';
        }

        if(!$password){
            $errors['password'] = 'Password field is required';
        }

        if($password && strlen($password) < 6){
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if($password && !$password_confirmation){
            $errors['password_confirmation'] = 'Password not confirmed';
        }
        elseif($password && ($password != $password_confirmation)){
            $errors['password_confirmation'] = 'Password did not match';
            
        }

        if(empty($errors)){
            return true;

        }else{
            $this->view('Signup', [
                'validation_errors' => $errors, 
                'registration_error' => [
                    'error' => true,
                    'message' => 'Please provide all required fields'
                ],
                'form_data' => [
                    'name' => $name,
                    'email' => $email,
                ]
            ]);
            
            return false;    
        }
    }

    private function validateResetPassword($password, $password_confirmation, $token){
        $errors = [];

        if(!$password){
            $errors['password'] = 'Password field is required';
        }

        if($password && strlen($password) < 6){
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if($password && !$password_confirmation){
            $errors['password_confirmation'] = 'Password not confirmed';
        }
        elseif($password && ($password != $password_confirmation)){
            $errors['password_confirmation'] = 'Password did not match';
            
        }

        if(empty($errors)){
            return true;
        }else{
            $this->view('ResetPassword', [
                'token' => $token,
                'validation_errors' => $errors,
            ]);
            
            return false;    
        }
    }

    private function checkExistingEmail($name, $email){
        $userModel = new UserModel();

        if($userModel->existingEmail($email)){
            $this->view('Signup', [
                'validation_errors' => ['email' => 'Email is already existing'],
                'registration_error' => [
                    'error' => true,
                    'message' => 'Failed to register'
                ],
                'form_data' => [
                    'name' => $name,
                    'email' => $email,
                ]
            ]);

            return false;
        }

        return true;
    }

    public function verifyEmail(){
        $token = $_GET['token'] ?? null;
        
        if($token){
            $userModel = new UserModel();
            
            $user = $userModel->getUserByVerificationToken($token);
            
            if(!$user){
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Link already used or invalid'
                ];
            }
            else{
                if($userModel->verifyAccount($token)){
                    $_SESSION['flash'] = [
                        'type' => 'success',
                        'message' => 'Account activated. You can now login with your account.'
                    ];                    
                }            
                else{
                    $_SESSION['flash'] = [
                        'type' => 'error',
                        'message' => 'Failed to activate account. Please try again.'
                    ];
                }
            }

            header('Location: login');
            exit;
        }

        $this->view('VerifyEmail');
    }

    public function resendVerification(){
        $email = $_POST['email'];

        $userModel = new UserModel();

        if($userModel->existingEmail($email) && $userModel->verifiedAccount($email)){
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Account already activated. You can now login using your account.'
            ];

            header('Location: login');
            exit;
        }

        if($userModel->existingEmail($email) && !$userModel->verifiedAccount($email)){
            $verificationToken = bin2hex(random_bytes(32));

            if($userModel->updateVerificationToken($verificationToken, $email)){
                $appUrl = getenv('APP_URL') ?: ($_ENV['APP_URL'] ?? '');
                $link = "$appUrl/verify_email?token=$verificationToken";

                MailModel::sendEmailVerification($email, $link);

                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'If the account exists, an activation link was sent'
                ];

                header('Location: verify_email');
                exit;
            }
        }

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'If the account exists, an activation link was sent.'
        ];

        header('Location: verify_email');
        exit;

    }
}