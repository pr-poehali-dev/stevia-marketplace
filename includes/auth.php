<?php
session_start();
require_once 'config/database.php';

class Auth {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function register($username, $email, $password) {
        try {
            // Проверяем существование пользователя
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Пользователь с таким именем или email уже существует'];
            }
            
            // Создаем нового пользователя
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $passwordHash]);
            
            return ['success' => true, 'message' => 'Регистрация успешна'];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Ошибка регистрации: ' . $e->getMessage()];
        }
    }
    
    public function login($login, $password) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$login, $login]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Обновляем время последнего входа
                $stmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);
                
                return ['success' => true, 'message' => 'Вход выполнен'];
            }
            
            return ['success' => false, 'message' => 'Неверный логин или пароль'];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Ошибка входа: ' . $e->getMessage()];
        }
    }
    
    public function logout() {
        session_destroy();
        return ['success' => true, 'message' => 'Выход выполнен'];
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
    
    public function isAdmin() {
        return $this->isLoggedIn() && $_SESSION['role'] === 'admin';
    }
    
    public function isSupport() {
        return $this->isLoggedIn() && $_SESSION['role'] === 'support';
    }
}
?>