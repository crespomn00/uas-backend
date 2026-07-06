<?php
class FormValidatorUser {
    private $data;
    private $errors = [];

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function validateEmail() {
        $email = trim($this->data['email'] ?? '');
        if (empty($email)) {
            $this->errors['email'] = 'Email wajib diisi.';
        } elseif (strlen($email) < 4) {
            $this->errors['email'] = 'Email Harus Valid!.';
        }
    }


    public function validateUsername() {
        $username = trim($this->data['username'] ?? '');
        if (empty($username)) {
            $this->errors['username'] = 'Username wajib diisi.';
        } elseif (strlen($username) < 4) {
            $this->errors['username'] = 'Username minimal 4 karakter.';
        }
    }

    public function validatePassword() {
        $password = $this->data['password'] ?? '';
        if (empty($password)) {
            $this->errors['password'] = 'Password wajib diisi.';
        } elseif (strlen($password) < 6) {
            $this->errors['password'] = 'Password minimal 6 karakter.';
        }
    }

    public function validateConfirmPassword() {
        $password = $this->data['password'] ?? '';
        $confirm_password = $this->data['confirm_password'] ?? '';
        if (empty($confirm_password)) {
            $this->errors['confirm_password'] = 'Konfirmasi Password wajib diisi.';
        } elseif ($password !== $confirm_password) {
            $this->errors['confirm_password'] = 'Password tidak cocok.';
        }
    }

    public function validateAll() {
        $this->validateEmail();
        $this->validateUsername();
        $this->validatePassword();
        $this->validateConfirmPassword();
    }

    public function hasErrors() {
        return !empty($this->errors);
    }
    
    public function getErrors() {
        return $this->errors;
    }



}