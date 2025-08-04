<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth = new Auth();
$currentUser = $auth->getCurrentUser();
$games = getGames();
$reviews = getReviews(6);
$stats = getStats();

// Получаем данные поддержки только для админов/поддержки
$supportData = $currentUser ? getSupportContact($currentUser['id']) : ['visible' => false];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stevia - Игровая торговая площадка</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #34d399;
            --secondary: #f0fdf4;
            --accent: #065f46;
            --text: #1f2937;
            --text-light: #6b7280;
            --white: #ffffff;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --border: #d1d5db;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Open Sans', sans-serif;
            line-height: 1.6;
            color: var(--text);
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            min-height: 100vh;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: var(--accent);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        /* Хедер */
        .header {
            background: var(--white);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .logo-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.25rem;
        }
        
        .logo h1 {
            font-size: 1.75rem;
            color: var(--primary);
            margin: 0;
        }
        
        .nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .nav a {
            text-decoration: none;
            color: var(--text-light);
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav a:hover {
            color: var(--primary);
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            box-shadow: var(--shadow);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }
        
        .btn-outline:hover {
            background: var(--primary);
            color: var(--white);
        }
        
        /* Герой секция */
        .hero {
            text-align: center;
            padding: 4rem 0;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
        }
        
        .hero h2 {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero p {
            font-size: 1.25rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .features {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        
        .feature {
            background: var(--white);
            padding: 1rem 1.5rem;
            border-radius: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: var(--shadow);
            color: var(--primary);
            font-weight: 500;
        }
        
        .search-box {
            max-width: 400px;
            margin: 0 auto;
            position: relative;
        }
        
        .search-box input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid var(--primary);
            border-radius: 2rem;
            font-size: 1.1rem;
            background: var(--white);
            box-shadow: var(--shadow);
        }
        
        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }
        
        /* Игры */
        .games {
            padding: 4rem 0;
        }
        
        .games h3 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
        }
        
        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }
        
        .game-card {
            background: var(--white);
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: all 0.3s;
            cursor: pointer;
            border: 2px solid transparent;
        }
        
        .game-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }
        
        .game-icon {
            width: 80px;
            height: 80px;
            border-radius: 1rem;
            margin: 0 auto 1.5rem;
            display: block;
            box-shadow: var(--shadow);
        }
        
        .game-card h4 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .game-card p {
            text-align: center;
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }
        
        .categories {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .category {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--secondary);
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            transition: background 0.3s;
        }
        
        .category:hover {
            background: #dcfce7;
        }
        
        .badge {
            background: var(--primary);
            color: var(--white);
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .empty-state {
            text-align: center;
            padding: 1.5rem;
            color: var(--text-light);
            border: 2px dashed var(--border);
            border-radius: 1rem;
            margin-top: 1rem;
        }
        
        /* Админ панель */
        .admin {
            background: var(--white);
            padding: 4rem 0;
        }
        
        .tabs {
            display: flex;
            border-bottom: 2px solid var(--gray-200);
            margin-bottom: 2rem;
        }
        
        .tab {
            padding: 1rem 2rem;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 500;
            color: var(--text-light);
            transition: all 0.3s;
        }
        
        .tab.active {
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text);
        }
        
        .form-group input,
        .form-group textarea {
            padding: 0.75rem;
            border: 2px solid var(--border);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        .promo-item {
            background: var(--secondary);
            padding: 1.5rem;
            border-radius: 1rem;
            border: 2px solid var(--primary);
            margin-bottom: 1rem;
        }
        
        .promo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .promo-code {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: var(--primary);
            font-size: 1.25rem;
        }
        
        /* Поддержка */
        .support {
            background: var(--secondary);
            padding: 4rem 0;
        }
        
        .support-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
        }
        
        .support-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 1.5rem;
            box-shadow: var(--shadow);
        }
        
        .support-status {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .status-dot {
            width: 12px;
            height: 12px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .support-info {
            background: var(--secondary);
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            font-family: 'Courier New', monospace;
            color: var(--primary);
            font-weight: 600;
        }
        
        /* Отзывы */
        .reviews {
            padding: 4rem 0;
        }
        
        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .review-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
        }
        
        .review-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .avatar {
            width: 50px;
            height: 50px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 600;
        }
        
        .review-meta {
            flex: 1;
        }
        
        .review-author {
            font-weight: 600;
            color: var(--primary);
        }
        
        .stars {
            color: #fbbf24;
            margin: 0.25rem 0;
        }
        
        .review-date {
            font-size: 0.875rem;
            color: var(--text-light);
        }
        
        /* Футер */
        .footer {
            background: var(--accent);
            color: var(--white);
            padding: 3rem 0 1rem;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .footer h5 {
            color: var(--white);
            margin-bottom: 1rem;
        }
        
        .footer a {
            color: #86efac;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.3s;
        }
        
        .footer a:hover {
            color: var(--white);
        }
        
        .footer-bottom {
            border-top: 1px solid #047857;
            padding-top: 2rem;
            text-align: center;
            color: #86efac;
        }
        
        /* Модальные окна */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        
        .modal-content {
            background: var(--white);
            margin: 10% auto;
            padding: 2rem;
            border-radius: 1rem;
            max-width: 500px;
            position: relative;
        }
        
        .close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-light);
        }
        
        .close:hover {
            color: var(--text);
        }
        
        /* Уведомления */
        .notification {
            position: fixed;
            top: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            color: var(--white);
            font-weight: 500;
            z-index: 1001;
            transform: translateX(400px);
            transition: transform 0.3s;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification.success {
            background: var(--primary);
        }
        
        .notification.error {
            background: #ef4444;
        }
        
        /* Адаптивность */
        @media (max-width: 768px) {
            .hero h2 {
                font-size: 2rem;
            }
            
            .features {
                flex-direction: column;
                align-items: center;
            }
            
            .nav {
                display: none;
            }
            
            .games-grid,
            .support-grid,
            .reviews-grid {
                grid-template-columns: 1fr;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Хедер -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <h1>Stevia</h1>
                    <span class="badge">v2.0</span>
                </div>
                
                <nav class="nav">
                    <a href="#games">Игры</a>
                    <a href="#support">Поддержка</a>
                    <a href="#reviews">Отзывы</a>
                    
                    <?php if ($currentUser): ?>
                        <div class="user-menu">
                            Привет, <?= htmlspecialchars($currentUser['username']) ?>!
                            <button class="btn btn-outline" onclick="logout()">
                                <i class="fas fa-sign-out-alt"></i>
                                Выход
                            </button>
                        </div>
                    <?php else: ?>
                        <button class="btn btn-primary" onclick="openModal('loginModal')">
                            <i class="fas fa-user"></i>
                            Вход
                        </button>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <!-- Герой секция -->
    <section class="hero">
        <div class="container">
            <h2>Игровая торговая площадка нового поколения</h2>
            <p>Покупайте и продавайте игровые товары в безопасной среде с полной защитой сделок</p>
            
            <div class="features">
                <div class="feature">
                    <i class="fas fa-shield-alt"></i>
                    Гарантия безопасности
                </div>
                <div class="feature">
                    <i class="fas fa-clock"></i>
                    Поддержка 24/7
                </div>
                <div class="feature">
                    <i class="fas fa-users"></i>
                    <?= $stats['users'] ?> пользователей
                </div>
            </div>
            
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Поиск игр и товаров..." id="searchInput">
            </div>
        </div>
    </section>

    <!-- Игры -->
    <section id="games" class="games">
        <div class="container">
            <h3>Популярные игры</h3>
            
            <div class="games-grid">
                <?php foreach ($games as $game): 
                    $gameItems = getItemsByGame($game['id'], 10);
                    $itemsCount = count($gameItems);
                ?>
                    <div class="game-card">
                        <img src="<?= htmlspecialchars($game['icon']) ?>" alt="<?= htmlspecialchars($game['name']) ?>" class="game-icon">
                        <h4><?= htmlspecialchars($game['name']) ?></h4>
                        <p><?= htmlspecialchars($game['description']) ?></p>
                        
                        <div class="categories">
                            <div class="category">
                                <span>Аккаунты</span>
                                <span class="badge"><?= $itemsCount ?> шт.</span>
                            </div>
                            <div class="category">
                                <span>Игровая валюта</span>
                                <span class="badge">0 шт.</span>
                            </div>
                            <div class="category">
                                <span>Предметы</span>
                                <span class="badge">0 шт.</span>
                            </div>
                            <div class="category">
                                <span>Буст услуги</span>
                                <span class="badge">0 шт.</span>
                            </div>
                        </div>
                        
                        <div class="empty-state">
                            <p>🎮 Товары скоро появятся!</p>
                            <p style="font-size: 0.875rem; margin-top: 0.5rem;">Станьте первым продавцом</p>
                        </div>
                        
                        <button class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                            <i class="fas fa-plus"></i>
                            Выставить товар
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Админ панель (только для админов) -->
    <?php if ($currentUser && ($currentUser['role'] === 'admin' || $currentUser['role'] === 'support')): ?>
    <section class="admin">
        <div class="container">
            <h3 style="text-align: center; margin-bottom: 2rem;">Админ панель</h3>
            
            <div class="tabs">
                <button class="tab active" onclick="switchTab('promo')">Промокоды</button>
                <button class="tab" onclick="switchTab('users')">Пользователи</button>
                <button class="tab" onclick="switchTab('stats')">Статистика</button>
            </div>
            
            <div id="promo" class="tab-content active">
                <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow); margin-bottom: 2rem;">
                    <h4 style="margin-bottom: 1rem;">Создать промокод</h4>
                    <form id="promoForm">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Код промокода</label>
                                <input type="text" name="code" placeholder="WELCOME2024" required>
                            </div>
                            <div class="form-group">
                                <label>Размер скидки (₽)</label>
                                <input type="number" name="discount" placeholder="100" required>
                            </div>
                            <div class="form-group">
                                <label>Максимум активаций</label>
                                <input type="number" name="max_uses" placeholder="50" required>
                            </div>
                            <div class="form-group">
                                <label>Действует до</label>
                                <input type="date" name="expires_at">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Создать промокод
                        </button>
                    </form>
                </div>
                
                <?php $promoCodes = getPromoCodes(); ?>
                <?php foreach ($promoCodes as $promo): ?>
                    <div class="promo-item">
                        <div class="promo-header">
                            <div>
                                <div class="promo-code"><?= htmlspecialchars($promo['code']) ?></div>
                                <p style="color: var(--text-light); margin: 0;">
                                    Скидка <?= formatPrice($promo['discount_amount']) ?> • 
                                    <?= $promo['used_count'] ?>/<?= $promo['max_uses'] ?> использований
                                </p>
                            </div>
                            <span class="badge">Активен</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div id="users" class="tab-content">
                <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow); text-align: center;">
                    <i class="fas fa-users" style="font-size: 4rem; color: var(--primary); margin-bottom: 1rem;"></i>
                    <h4>Пользователи: <?= $stats['users'] ?></h4>
                    <p style="color: var(--text-light);">Статистика пользователей будет доступна после регистраций</p>
                </div>
            </div>
            
            <div id="stats" class="tab-content">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                    <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow); text-align: center;">
                        <i class="fas fa-shopping-cart" style="font-size: 2rem; color: var(--primary); margin-bottom: 1rem;"></i>
                        <div style="font-size: 2rem; font-weight: 600; color: var(--primary);"><?= $stats['sales'] ?></div>
                        <div style="color: var(--text-light);">Продаж</div>
                    </div>
                    <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow); text-align: center;">
                        <i class="fas fa-ruble-sign" style="font-size: 2rem; color: var(--primary); margin-bottom: 1rem;"></i>
                        <div style="font-size: 2rem; font-weight: 600; color: var(--primary);"><?= formatPrice($stats['revenue']) ?></div>
                        <div style="color: var(--text-light);">Оборот</div>
                    </div>
                    <div style="background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow); text-align: center;">
                        <i class="fas fa-box" style="font-size: 2rem; color: var(--primary); margin-bottom: 1rem;"></i>
                        <div style="font-size: 2rem; font-weight: 600; color: var(--primary);"><?= $stats['items'] ?></div>
                        <div style="color: var(--text-light);">Товаров</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Поддержка -->
    <section id="support" class="support">
        <div class="container">
            <h3 style="text-align: center; margin-bottom: 2rem;">Поддержка</h3>
            
            <div class="support-grid">
                <div class="support-card">
                    <h4 style="margin-bottom: 1rem;">
                        <i class="fas fa-headset" style="margin-right: 0.5rem;"></i>
                        Техническая поддержка
                    </h4>
                    
                    <div class="support-status">
                        <div class="status-dot"></div>
                        <span style="font-weight: 500; color: var(--primary);">Онлайн</span>
                    </div>
                    
                    <?php if ($supportData['visible']): ?>
                        <div>
                            <p style="margin-bottom: 0.5rem; font-weight: 500;">Логин:</p>
                            <div class="support-info"><?= htmlspecialchars($supportData['login']) ?></div>
                        </div>
                        <div>
                            <p style="margin-bottom: 0.5rem; font-weight: 500;">Пароль:</p>
                            <div class="support-info"><?= htmlspecialchars($supportData['password']) ?></div>
                        </div>
                    <?php else: ?>
                        <p style="color: var(--text-light); margin-bottom: 1.5rem;">
                            Для получения контактов поддержки обратитесь к администратору
                        </p>
                    <?php endif; ?>
                    
                    <button class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-comment"></i>
                        Написать в поддержку
                    </button>
                </div>
                
                <div class="support-card">
                    <h4 style="margin-bottom: 1rem;">Задать вопрос</h4>
                    <form id="supportForm">
                        <div class="form-group">
                            <label>Тема</label>
                            <input type="text" name="subject" placeholder="Опишите проблему кратко" required>
                        </div>
                        <div class="form-group">
                            <label>Сообщение</label>
                            <textarea name="message" rows="4" placeholder="Подробно опишите проблему..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-paper-plane"></i>
                            Отправить
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Отзывы -->
    <section id="reviews" class="reviews">
        <div class="container">
            <h3 style="text-align: center; margin-bottom: 2rem;">Отзывы пользователей</h3>
            
            <div class="reviews-grid">
                <?php if (empty($reviews)): ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                        <i class="fas fa-comments" style="font-size: 4rem; color: var(--primary); margin-bottom: 1rem;"></i>
                        <h4>Первые отзывы скоро появятся!</h4>
                        <p style="color: var(--text-light);">Станьте первым, кто оставит отзыв о нашей площадке</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-card">
                            <div class="review-header">
                                <div class="avatar">
                                    <?= strtoupper(substr($review['from_user'], 0, 2)) ?>
                                </div>
                                <div class="review-meta">
                                    <div class="review-author"><?= htmlspecialchars($review['from_user']) ?></div>
                                    <div class="stars">
                                        <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                            <i class="fas fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="review-date"><?= timeAgo($review['created_at']) ?></div>
                                </div>
                            </div>
                            <p><?= htmlspecialchars($review['comment']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <?php if ($currentUser): ?>
            <div style="max-width: 600px; margin: 0 auto; background: var(--white); padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow);">
                <h4 style="text-align: center; margin-bottom: 1rem;">Оставить отзыв</h4>
                <form id="reviewForm">
                    <div style="text-align: center; margin-bottom: 1rem;">
                        <div class="stars" style="font-size: 1.5rem; cursor: pointer;">
                            <i class="far fa-star" data-rating="1"></i>
                            <i class="far fa-star" data-rating="2"></i>
                            <i class="far fa-star" data-rating="3"></i>
                            <i class="far fa-star" data-rating="4"></i>
                            <i class="far fa-star" data-rating="5"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea name="comment" rows="4" placeholder="Расскажите о своём опыте..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-comment"></i>
                        Опубликовать отзыв
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Футер -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="logo" style="margin-bottom: 1rem;">
                        <div class="logo-icon">
                            <i class="fas fa-gamepad"></i>
                        </div>
                        <h4 style="color: var(--white); margin: 0;">Stevia</h4>
                    </div>
                    <p style="color: #86efac;">Безопасная игровая торговая площадка нового поколения</p>
                </div>
                
                <div>
                    <h5>Игры</h5>
                    <?php foreach ($games as $game): ?>
                        <a href="#game-<?= $game['slug'] ?>"><?= htmlspecialchars($game['name']) ?></a>
                    <?php endforeach; ?>
                </div>
                
                <div>
                    <h5>Поддержка</h5>
                    <a href="#support">Связаться с нами</a>
                    <a href="#">Часто задаваемые вопросы</a>
                    <a href="#">Правила площадки</a>
                </div>
                
                <div>
                    <h5>Безопасность</h5>
                    <a href="#">Политика конфиденциальности</a>
                    <a href="#">Условия использования</a>
                    <a href="#">Гарантии</a>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 Stevia. Все права защищены.</p>
                <p style="margin-top: 0.5rem;">
                    <?= $stats['items'] ?> товаров • <?= $stats['users'] ?> пользователей • <?= $stats['sales'] ?> продаж
                </p>
            </div>
        </div>
    </footer>

    <!-- Модальные окна -->
    
    <!-- Вход -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('loginModal')">&times;</span>
            <h3 style="margin-bottom: 1.5rem; text-align: center;">Вход в аккаунт</h3>
            
            <form id="loginForm">
                <div class="form-group">
                    <label>Логин или Email</label>
                    <input type="text" name="login" required>
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                    Войти
                </button>
            </form>
            
            <div style="text-align: center;">
                <a href="#" onclick="closeModal('loginModal'); openModal('registerModal');" style="color: var(--primary);">
                    Нет аккаунта? Зарегистрироваться
                </a>
            </div>
        </div>
    </div>
    
    <!-- Регистрация -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('registerModal')">&times;</span>
            <h3 style="margin-bottom: 1.5rem; text-align: center;">Регистрация</h3>
            
            <form id="registerForm">
                <div class="form-group">
                    <label>Имя пользователя</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Подтвердите пароль</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                    Зарегистрироваться
                </button>
            </form>
            
            <div style="text-align: center;">
                <a href="#" onclick="closeModal('registerModal'); openModal('loginModal');" style="color: var(--primary);">
                    Уже есть аккаунт? Войти
                </a>
            </div>
        </div>
    </div>

    <script>
        // Модальные окна
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Закрытие модального окна при клике вне его
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
        
        // Уведомления
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => notification.classList.add('show'), 100);
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => document.body.removeChild(notification), 300);
            }, 3000);
        }
        
        // Вкладки админ панели
        function switchTab(tabName) {
            // Скрываем все вкладки
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Показываем выбранную вкладку
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }
        
        // Авторизация
        document.getElementById('loginForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'login');
            
            try {
                const response = await fetch('ajax/auth.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(result.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(result.message, 'error');
                }
            } catch (error) {
                showNotification('Ошибка соединения', 'error');
            }
        });
        
        // Регистрация
        document.getElementById('registerForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'register');
            
            try {
                const response = await fetch('ajax/auth.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(result.message);
                    setTimeout(() => {
                        closeModal('registerModal');
                        openModal('loginModal');
                    }, 1000);
                } else {
                    showNotification(result.message, 'error');
                }
            } catch (error) {
                showNotification('Ошибка соединения', 'error');
            }
        });
        
        // Выход
        async function logout() {
            try {
                const formData = new FormData();
                formData.append('action', 'logout');
                
                const response = await fetch('ajax/auth.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(result.message);
                    setTimeout(() => location.reload(), 1000);
                }
            } catch (error) {
                showNotification('Ошибка соединения', 'error');
            }
        }
        
        // Рейтинг звезд
        document.querySelectorAll('.stars i[data-rating]').forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                const stars = this.parentNode.querySelectorAll('i');
                
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.className = 'fas fa-star';
                    } else {
                        s.className = 'far fa-star';
                    }
                });
            });
        });
        
        // Поиск
        document.getElementById('searchInput')?.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            // Здесь можно добавить логику поиска
        });
    </script>
</body>
</html>