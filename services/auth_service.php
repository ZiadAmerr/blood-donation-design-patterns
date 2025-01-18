<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/models/people/Donor.php';

class AuthService {
    public static function login(string $username, string $password): array {
        $response = ['success' => false, 'message' => '', 'user' => null];

        try {
            // Validate required fields
            if (empty($username) || empty($password)) {
                throw new Exception("Missing required field: username or password");
            }

            // Check if user exists
            $donor_id = Donor::findByUsername($username);
            if ($donor_id == -1) {
                throw new Exception("User not found");
            }
            $donor = new Donor($donor_id);

            // Check if password is correct
            if ($donor->getPassword() !== md5($password)) {
                throw new Exception("Incorrect password");
            }

            // Start session
            session_start();

            $_SESSION['user'] = $donor->getAsJson();

            $response['success'] = true;
            $response['message'] = "Login successful! Redirecting...";
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return $response;
    }
}