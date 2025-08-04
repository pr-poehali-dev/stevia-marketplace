<?php
require_once 'config/database.php';

function getGames() {
    $database = new Database();
    $db = $database->getConnection();
    
    $stmt = $db->prepare("SELECT * FROM games WHERE is_active = 1 ORDER BY name");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getItemsByGame($gameId, $limit = 20) {
    $database = new Database();
    $db = $database->getConnection();
    
    $stmt = $db->prepare("
        SELECT i.*, u.username as seller_name, g.name as game_name 
        FROM items i 
        JOIN users u ON i.user_id = u.id 
        JOIN games g ON i.game_id = g.id 
        WHERE i.game_id = ? AND i.status = 'active' 
        ORDER BY i.created_at DESC 
        LIMIT ?
    ");
    $stmt->execute([$gameId, $limit]);
    return $stmt->fetchAll();
}

function getReviews($limit = 10) {
    $database = new Database();
    $db = $database->getConnection();
    
    $stmt = $db->prepare("
        SELECT r.*, 
               u1.username as from_user, 
               u2.username as to_user 
        FROM reviews r 
        JOIN users u1 ON r.from_user_id = u1.id 
        JOIN users u2 ON r.to_user_id = u2.id 
        ORDER BY r.created_at DESC 
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function getStats() {
    $database = new Database();
    $db = $database->getConnection();
    
    $stats = [];
    
    // Количество пользователей
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'user'");
    $stmt->execute();
    $stats['users'] = $stmt->fetch()['count'];
    
    // Количество товаров
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM items WHERE status = 'active'");
    $stmt->execute();
    $stats['items'] = $stmt->fetch()['count'];
    
    // Количество продаж
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM transactions WHERE status = 'completed'");
    $stmt->execute();
    $stats['sales'] = $stmt->fetch()['count'];
    
    // Общий оборот
    $stmt = $db->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM transactions WHERE status = 'completed'");
    $stmt->execute();
    $stats['revenue'] = $stmt->fetch()['total'];
    
    return $stats;
}

function getPromoCodes() {
    $database = new Database();
    $db = $database->getConnection();
    
    $stmt = $db->prepare("
        SELECT p.*, u.username as created_by_name 
        FROM promo_codes p 
        JOIN users u ON p.created_by = u.id 
        WHERE p.is_active = 1 
        ORDER BY p.created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll();
}

function formatPrice($price) {
    return number_format($price, 0, ',', ' ') . ' ₽';
}

function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'только что';
    if ($time < 3600) return floor($time/60) . ' мин назад';
    if ($time < 86400) return floor($time/3600) . ' ч назад';
    if ($time < 2592000) return floor($time/86400) . ' дн назад';
    
    return date('d.m.Y', strtotime($datetime));
}

function getSupportContact($userId) {
    // Показываем контакт поддержки только админам и самой поддержке
    $database = new Database();
    $db = $database->getConnection();
    
    $stmt = $db->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if ($user && in_array($user['role'], ['admin', 'support'])) {
        return [
            'login' => 'Вэил',
            'password' => 'frooz10',
            'visible' => true
        ];
    }
    
    return ['visible' => false];
}
?>