<?php
/**
 * Auth Controller
 * Handles user authentication (login, register, logout)
 */

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Display login form
     */
    public function showLogin() {
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    /**
     * Handle login submission
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?route=login');
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $errors = [];
        
        if (empty($email)) {
            $errors[] = 'Email is required';
        }
        
        if (empty($password)) {
            $errors[] = 'Password is required';
        }
        
        if (!empty($errors)) {
            $_SESSION['login_errors'] = $errors;
            $_SESSION['login_email'] = $email;
            header('Location: ' . BASE_URL . '/index.php?route=login');
            exit;
        }
        
        $user = $this->userModel->login($email, $password);
        
        if ($user) {
            // Check if NGO is verified
            if ($user['role'] === 'ngo') {
                $ngoDetails = $this->userModel->getNGODetails($user['user_id']);
                if ($ngoDetails && $ngoDetails['verification_status'] === 'pending') {
                    $_SESSION['login_errors'] = ['Your NGO account is pending verification'];
                    header('Location: ' . BASE_URL . '/index.php?route=login');
                    exit;
                } elseif ($ngoDetails && $ngoDetails['verification_status'] === 'rejected') {
                    $_SESSION['login_errors'] = ['Your NGO account has been rejected'];
                    header('Location: ' . BASE_URL . '/index.php?route=login');
                    exit;
                }
            }
            
            // Set session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            
            // Redirect based on role
            switch ($user['role']) {
                case 'donor':
                    header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
                    break;
                case 'ngo':
                    header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
                    break;
                case 'admin':
                    header('Location: ' . BASE_URL . '/index.php?route=admin-dashboard');
                    break;
                default:
                    header('Location: ' . BASE_URL . '/index.php?route=home');
            }
            exit;
        } else {
            $_SESSION['login_errors'] = ['Invalid email or password'];
            $_SESSION['login_email'] = $email;
            header('Location: ' . BASE_URL . '/index.php?route=login');
            exit;
        }
    }
    
    /**
     * Display registration form
     */
    public function showRegister() {
        require_once __DIR__ . '/../views/auth/register.php';
    }
    
    /**
     * Handle registration submission
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?route=register');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = $_POST['role'] ?? '';
        $address = trim($_POST['address'] ?? '');
        $latitude = $_POST['latitude'] ?? null;
        $longitude = $_POST['longitude'] ?? null;
        
        // NGO-specific fields
        $organizationName = trim($_POST['organization_name'] ?? '');
        $registrationNumber = trim($_POST['registration_number'] ?? '');
        
        $errors = [];
        
        // Validation
        if (empty($name)) {
            $errors[] = 'Name is required';
        }
        
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
       if (empty($phone)) {
    $errors[] = 'Phone number is required';
} elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
    $errors[] = 'Phone number must contain exactly 10 digits';
}
        
        if (empty($password)) {
            $errors[] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }
        
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match';
        }
        
        if (empty($role) || !in_array($role, ['donor', 'ngo'])) {
            $errors[] = 'Invalid role selected';
        }
        
        if (empty($address)) {
            $errors[] = 'Address is required';
        }
        
        // NGO-specific validation
        if ($role === 'ngo') {
            if (empty($organizationName)) {
                $errors[] = 'Organization name is required for NGOs';
            }
            if (empty($registrationNumber)) {
                $errors[] = 'Registration number is required for NGOs';
            }
        }
        
        // Check if email already exists
        if ($this->userModel->emailExists($email)) {
            $errors[] = 'Email already registered';
        }
        
        if (!empty($errors)) {
            $_SESSION['register_errors'] = $errors;
            $_SESSION['register_data'] = $_POST;
            header('Location: ' . BASE_URL . '/index.php?route=register');
            exit;
        }
        
        // Register user
        $userData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'role' => $role,
            'address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
        
        $userId = $this->userModel->register($userData);
        
        if ($userId) {
            // Register NGO details if applicable
            if ($role === 'ngo') {
                $ngoData = [
                    'ngo_id' => $userId,
                    'organization_name' => $organizationName,
                    'registration_number' => $registrationNumber
                ];
                
                if (!$this->userModel->registerNGO($ngoData)) {
                    $_SESSION['register_errors'] = ['Failed to register NGO details'];
                    $_SESSION['register_data'] = $_POST;
                    header('Location: ' . BASE_URL . '/index.php?route=register');
                    exit;
                }
            }
            
            $_SESSION['register_success'] = 'Registration successful! Please login.';
            header('Location: ' . BASE_URL . '/index.php?route=login');
            exit;
        } else {
            $_SESSION['register_errors'] = ['Registration failed. Please try again.'];
            $_SESSION['register_data'] = $_POST;
            header('Location: ' . BASE_URL . '/index.php?route=register');
            exit;
        }
    }
    
    /**
     * Logout user
     */
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/index.php?route=login');
        exit;
    }
}
?>
