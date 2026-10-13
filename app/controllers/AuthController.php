<?php
// app/controllers/AuthController.php
// Handles authentication: register, login, logout

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->userModel = new UserModel($db);
    }

    // -------------------------
    // REGISTER
    // -------------------------
    public function register() {
        $errors = [];
        $old = ['username' => '', 'full_name' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username  = trim($_POST['username'] ?? '');
            $full_name = trim($_POST['full_name'] ?? '');
            $password  = $_POST['password'] ?? '';
            $confirm   = $_POST['confirm_password'] ?? '';

            $old['username']  = $username;
            $old['full_name'] = $full_name;

            // Validation
            if ($username === '') {
                $errors[] = 'Username is required.';
            } elseif (strlen($username) < 3) {
                $errors[] = 'Username must be at least 3 characters.';
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $errors[] = 'Username can only contain letters, numbers, and underscores.';
            } elseif ($this->userModel->usernameExists($username)) {
                $errors[] = 'Username is already taken.';
            }

            if ($full_name === '') {
                $errors[] = 'Full name is required.';
            }

            if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters.';
            } elseif ($password !== $confirm) {
                $errors[] = 'Passwords do not match.';
            }

            // If no errors, create user
            if (empty($errors)) {
                if ($this->userModel->create($username, $password, $full_name)) {
                    // Auto-login after register
                    $user = $this->userModel->findByUsername($username);
                    $_SESSION['user_id']  = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'];

                    header('Location: index.php?page=feed');
                    exit;
                } else {
                    $errors[] = 'Something went wrong. Please try again.';
                }
            }
        }

        // Show the register view
        require __DIR__ . '/../views/auth/register.php';
    }

    // -------------------------
    // LOGIN
    // -------------------------
    public function login() {
        // If already logged in, go to feed
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?page=feed');
            exit;
        }

        $error = '';
        $old_username = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $old_username = $username;

            if ($username === '' || $password === '') {
                $error = 'Please enter both username and password.';
            } else {
                $user = $this->userModel->findByUsername($username);

                if ($user && password_verify($password, $user['password'])) {
                    // Success — set session
                    session_regenerate_id(true); // prevent session fixation
                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['username']  = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'];

                    header('Location: index.php?page=feed');
                    exit;
                } else {
                    $error = 'Invalid username or password.';
                }
            }
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    // -------------------------
    // LOGOUT
    // -------------------------
    public function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}