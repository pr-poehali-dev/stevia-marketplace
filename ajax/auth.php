<?php
require_once '../includes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
    exit;
}

$action = $_POST['action'] ?? '';
$auth = new Auth();

switch ($action) {
    case 'register':
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($username) || empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
            exit;
        }
        
        if ($password !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'Пароли не совпадают']);
            exit;
        }
        
        if (strlen($password) < 6) {
            echo json_encode(['success' => false, 'message' => 'Пароль должен содержать минимум 6 символов']);
            exit;
        }
        
        $result = $auth->register($username, $email, $password);
        echo json_encode($result);
        break;
        
    case 'login':
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($login) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
            exit;
        }
        
        $result = $auth->login($login, $password);
        echo json_encode($result);
        break;
        
    case 'logout':
        $result = $auth->logout();
        echo json_encode($result);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
}
?>