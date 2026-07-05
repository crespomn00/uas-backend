<?php
class FormValidatorLogin {
    private $data;
    private $errors = [];

    public function __construct(array $data) {
        $this->data = $data;
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

    public function validateAll() {
        $this->validateUsername();
        $this->validatePassword();
    }

    public function hasErrors() {
        return !empty($this->errors);
    }
    
    public function getErrors() {
        return $this->errors;
    }



}