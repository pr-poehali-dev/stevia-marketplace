<?php
/**
 * Скрипт установки базы данных для Stevia Marketplace
 * Запустите этот файл один раз для создания всех необходимых таблиц
 */

// Настройки подключения к базе данных
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'stevia_marketplace';

try {
    // Подключаемся к MySQL (без указания базы данных)
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🚀 Установка Stevia Marketplace</h2>";
    
    // Создаем базу данных
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p>✅ База данных '$database' создана</p>";
    
    // Подключаемся к созданной базе данных
    $pdo->exec("USE $database");
    
    // Создаем таблицы
    $tables = [
        'users' => "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                role ENUM('user', 'admin', 'support') DEFAULT 'user',
                balance DECIMAL(10, 2) DEFAULT 0.00,
                avatar VARCHAR(255) DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                last_login TIMESTAMP NULL DEFAULT NULL,
                is_active BOOLEAN DEFAULT TRUE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'games' => "
            CREATE TABLE IF NOT EXISTS games (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                slug VARCHAR(100) UNIQUE NOT NULL,
                description TEXT,
                icon VARCHAR(255),
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'items' => "
            CREATE TABLE IF NOT EXISTS items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                game_id INT NOT NULL,
                title VARCHAR(200) NOT NULL,
                description TEXT,
                price DECIMAL(10, 2) NOT NULL,
                category ENUM('accounts', 'currency', 'items', 'boost', 'other') NOT NULL,
                status ENUM('active', 'sold', 'reserved', 'inactive') DEFAULT 'active',
                images JSON,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'reviews' => "
            CREATE TABLE IF NOT EXISTS reviews (
                id INT AUTO_INCREMENT PRIMARY KEY,
                from_user_id INT NOT NULL,
                to_user_id INT NOT NULL,
                item_id INT,
                rating INT CHECK (rating >= 1 AND rating <= 5),
                comment TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (from_user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (to_user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'promo_codes' => "
            CREATE TABLE IF NOT EXISTS promo_codes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                code VARCHAR(50) UNIQUE NOT NULL,
                discount_amount DECIMAL(10, 2) NOT NULL,
                discount_percent INT DEFAULT 0,
                max_uses INT DEFAULT 1,
                used_count INT DEFAULT 0,
                expires_at TIMESTAMP NULL,
                is_active BOOLEAN DEFAULT TRUE,
                created_by INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'transactions' => "
            CREATE TABLE IF NOT EXISTS transactions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                buyer_id INT NOT NULL,
                seller_id INT NOT NULL,
                item_id INT NOT NULL,
                amount DECIMAL(10, 2) NOT NULL,
                commission DECIMAL(10, 2) DEFAULT 0,
                status ENUM('pending', 'completed', 'cancelled', 'disputed') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                completed_at TIMESTAMP NULL,
                FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        "
    ];
    
    foreach ($tables as $tableName => $sql) {
        $pdo->exec($sql);
        echo "<p>✅ Таблица '$tableName' создана</p>";
    }
    
    // Вставляем начальные данные
    echo "<h3>Добавление начальных данных...</h3>";
    
    // Создаем аккаунт поддержки
    $supportPasswordHash = password_hash('frooz10', PASSWORD_DEFAULT);
    $pdo->exec("INSERT IGNORE INTO users (username, email, password_hash, role) VALUES 
                ('Вэил', 'support@stevia.dev', '$supportPasswordHash', 'support')");
    echo "<p>✅ Аккаунт поддержки создан (Логин: Вэил, Пароль: frooz10)</p>";
    
    // Добавляем игры
    $pdo->exec("INSERT IGNORE INTO games (name, slug, description, icon) VALUES 
                ('Minecraft', 'minecraft', 'Самая популярная песочница в мире', '/img/4dcc32db-5185-4e31-84bd-abc2b7d236eb.jpg')");
    echo "<p>✅ Игра Minecraft добавлена</p>";
    
    // Добавляем промокод
    $pdo->exec("INSERT IGNORE INTO promo_codes (code, discount_amount, max_uses, created_by) VALUES 
                ('NEWUSER50', 50.00, 100, 1)");
    echo "<p>✅ Промокод NEWUSER50 создан</p>";
    
    // Добавляем демо отзывы
    $pdo->exec("INSERT IGNORE INTO users (username, email, password_hash) VALUES 
                ('GamerPro2024', 'gamer@demo.com', '" . password_hash('demo123', PASSWORD_DEFAULT) . "'),
                ('MinecraftFan', 'fan@demo.com', '" . password_hash('demo123', PASSWORD_DEFAULT) . "')");
    
    $pdo->exec("INSERT IGNORE INTO reviews (from_user_id, to_user_id, rating, comment) VALUES 
                (2, 1, 5, 'Отличная площадка! Быстрые и безопасные транзакции.'),
                (3, 1, 5, 'Надёжно и удобно. Рекомендую всем геймерам!')");
    echo "<p>✅ Демо отзывы добавлены</p>";
    
    echo "<h3 style='color: green;'>🎉 Установка завершена успешно!</h3>";
    echo "<p><strong>Важно:</strong> Удалите файл install.php после установки для безопасности.</p>";
    echo "<p><a href='index.php' style='color: #10b981; font-weight: bold;'>➜ Перейти на сайт</a></p>";
    
    echo "<div style='background: #f0fdf4; padding: 1rem; border-radius: 0.5rem; margin-top: 1rem;'>";
    echo "<h4>Данные для входа в систему:</h4>";
    echo "<p><strong>Поддержка:</strong> Логин: Вэил, Пароль: frooz10</p>";
    echo "<p><strong>Демо пользователи:</strong> GamerPro2024 / MinecraftFan, Пароль: demo123</p>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>❌ Ошибка установки:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    echo "<p>Проверьте настройки подключения к базе данных в начале файла.</p>";
}
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    background: #f8fafc;
    color: #1f2937;
}

h2, h3 {
    color: #065f46;
}

p {
    margin: 0.5rem 0;
}

a {
    color: #10b981;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style>