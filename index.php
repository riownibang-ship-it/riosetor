<?php
// index.php - PRODUCTION LANDING PAGE
require_once 'config.php';

// Check maintenance mode
if (getSetting('maintenance_mode', '0') === '1') {
    include 'maintenance.php';
    exit();
}

// Get statistics
$pdo = getDB();

// Total users
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'user'");
$total_users = $stmt->fetch()['count'];

// Total orders completed today
$stmt = $pdo->query("SELECT COUNT(*) as count FROM activations 
                     WHERE DATE(created_at) = CURDATE() AND status = 6");
$today_orders = $stmt->fetch()['count'];

// Total orders all time
$stmt = $pdo->query("SELECT COUNT(*) as count FROM activations WHERE status = 6");
$total_orders = $stmt->fetch()['count'];

// Success rate
$stmt = $pdo->query("SELECT COUNT(*) as total, 
                     SUM(CASE WHEN status = 6 THEN 1 ELSE 0 END) as completed 
                     FROM activations");
$stats = $stmt->fetch();
$success_rate = $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 98;

// Available services
$services = [
    ['code' => 'tg', 'name' => 'Telegram', 'icon' => 'fab fa-telegram', 'color' => '#26A5E4'],
    ['code' => 'wa', 'name' => 'WhatsApp', 'icon' => 'fab fa-whatsapp', 'color' => '#25D366'],
    ['code' => 'ig', 'name' => 'Instagram', 'icon' => 'fab fa-instagram', 'color' => '#E4405F'],
    ['code' => 'fb', 'name' => 'Facebook', 'icon' => 'fab fa-facebook', 'color' => '#1877F2'],
    ['code' => 'tw', 'name' => 'Twitter', 'icon' => 'fab fa-twitter', 'color' => '#1DA1F2'],
    ['code' => 'tt', 'name' => 'TikTok', 'icon' => 'fab fa-tiktok', 'color' => '#000000'],
    ['code' => 'dc', 'name' => 'Discord', 'icon' => 'fab fa-discord', 'color' => '#5865F2'],
    ['code' => 'sp', 'name' => 'Spotify', 'icon' => 'fab fa-spotify', 'color' => '#1DB954'],
    ['code' => 'nf', 'name' => 'Netflix', 'icon' => 'fas fa-film', 'color' => '#E50914'],
    ['code' => 'gp', 'name' => 'Google', 'icon' => 'fab fa-google', 'color' => '#4285F4']
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo SITE_NAME; ?> - Jasa Nomor Virtual untuk Verifikasi SMS</title>
    <meta name="description" content="Dapatkan nomor virtual untuk verifikasi WhatsApp, Telegram, Instagram, dan berbagai layanan lainnya. Cepat, aman, dan harga terjangkau.">
    <meta name="keywords" content="nomor virtual, sms online, verifikasi whatsapp, nomor telegram, otp online">
    <meta name="author" content="ZUROSMS">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo SITE_NAME; ?> - Nomor Virtual untuk Verifikasi SMS">
    <meta property="og:description" content="Dapatkan nomor virtual instant untuk verifikasi WhatsApp, Telegram, Instagram. Cepat, aman, dan harga terjangkau.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:image" content="/assets/og-image.jpg">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo SITE_NAME; ?> - Nomor Virtual untuk Verifikasi SMS">
    <meta name="twitter:description" content="Dapatkan nomor virtual instant untuk verifikasi WhatsApp, Telegram, Instagram.">
    <meta name="twitter:image" content="/assets/og-image.jpg">
    
    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Swiper CSS for Carousel -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #0a0a0a;
            font-family: 'Inter', sans-serif;
            color: #ffffff;
        }
        
        /* Animated Gradient Background */
        .gradient-bg {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a0033 50%, #0a0a0a 100%);
            position: relative;
        }
        
        .gradient-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(0, 51, 204, 0.15), transparent 50%);
            pointer-events: none;
        }
        
        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 51, 204, 0.2);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 51, 204, 0.3);
            border-radius: 24px;
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            border-color: #0033cc;
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 51, 204, 0.2);
        }
        
        /* Neon Button */
        .btn-neon {
            background: linear-gradient(135deg, #0033cc, #002299);
            border: none;
            color: white;
            font-weight: 700;
            padding: 14px 32px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: inline-block;
            text-decoration: none;
        }
        
        .btn-neon:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(0, 51, 204, 0.6);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid #0033cc;
            color: white;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
        }
        
        .btn-outline:hover {
            background: #0033cc;
            border-color: #0033cc;
        }
        
        /* Input Styles */
        .zuro-input {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(0, 51, 204, 0.3);
            border-radius: 12px;
            padding: 14px 18px;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .zuro-input:focus {
            outline: none;
            border-color: #0033cc;
            box-shadow: 0 0 15px rgba(0, 51, 204, 0.3);
        }
        
        .zuro-input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        
        /* Stats Counter */
        .stat-number {
            font-size: 36px;
            font-weight: 800;
            background: linear-gradient(135deg, #0033cc, #00ccff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        @media (min-width: 768px) {
            .stat-number {
                font-size: 48px;
            }
        }
        
        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Scroll Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }
        
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #0033cc;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #00ccff;
        }
        
        /* Mobile Menu */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(10, 10, 10, 0.98);
            backdrop-filter: blur(20px);
            z-index: 100;
            padding: 80px 20px 20px;
        }
        
        .mobile-menu.active {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }
        
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            z-index: 101;
        }
        
        .hamburger span {
            width: 25px;
            height: 3px;
            background: white;
            margin: 3px 0;
            transition: 0.3s;
            border-radius: 3px;
        }
        
        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }
            
            .desktop-menu {
                display: none;
            }
        }
        
        /* Text gradient fix */
        .text-gradient-blue {
            background: linear-gradient(135deg, #0033cc, #00ccff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .text-blue-light {
            color: #00ccff;
        }
        
        .bg-blue-primary {
            background-color: #0033cc;
        }
        
        .border-blue-primary {
            border-color: #0033cc;
        }
        
        .bg-blue-primary-20 {
            background-color: rgba(0, 51, 204, 0.2);
        }
        
        .border-blue-primary-30 {
            border-color: rgba(0, 51, 204, 0.3);
        }
        
        /* Animated Counter */
        .counter {
            display: inline-block;
        }
        
        /* Payment Support Scroll */
        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        .scroll-container {
            overflow: hidden;
            white-space: nowrap;
        }
        
        .scroll-content {
            display: inline-flex;
            animation: scroll 30s linear infinite;
        }
        
        .scroll-content:hover {
            animation-play-state: paused;
        }
        
        /* Testimonial Cards */
        .testimonial-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 51, 204, 0.3);
            border-radius: 24px;
            padding: 24px;
            height: 100%;
        }
        
        /* FAQ Accordion */
        .faq-item {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 51, 204, 0.3);
            border-radius: 16px;
            margin-bottom: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .faq-item:hover {
            border-color: #0033cc;
        }
        
        .faq-question {
            padding: 20px 24px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
        }
        
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            padding: 0 24px;
        }
        
        .faq-item.active .faq-answer {
            max-height: 500px;
            padding: 0 24px 20px 24px;
        }
        
        .faq-icon {
            transition: transform 0.3s ease;
        }
        
        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }
        
        /* Sticky CTA */
        .sticky-cta {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 50;
            display: none;
        }
        
        .sticky-cta.show {
            display: block;
            animation: slideInUp 0.5s ease;
        }
        
        @keyframes slideInUp {
            from {
                transform: translateY(100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* Live Chat Button */
        .live-chat-btn {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 50;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #25D366, #128C7E);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .live-chat-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(37, 211, 102, 0.6);
        }
        
        /* Flag emoji size */
        .flag-emoji {
            font-size: 20px;
            margin-right: 8px;
        }
        
        /* Trust Badge */
        .trust-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 14px;
        }
        
        /* Swiper custom styles */
        .swiper-pagination-bullet {
            background: #0033cc !important;
        }
        
        .swiper-pagination-bullet-active {
            background: #00ccff !important;
        }
    </style>
</head>
<body class="gradient-bg">

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass py-4 px-4 md:px-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <!-- Logo SVG -->
            <div class="flex items-center gap-2 md:gap-3">
                <svg class="w-8 h-8 md:w-10 md:h-10 flex-shrink-0" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#0033cc;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#00ccff;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <!-- Rounded square background -->
                    <rect x="10" y="10" width="80" height="80" rx="20" fill="url(#logoGradient)"/>
                    <!-- Lightning bolt -->
                    <path d="M 55 25 L 35 50 L 45 50 L 40 75 L 65 45 L 52 45 Z" fill="white"/>
                    <!-- Small circle accent -->
                    <circle cx="70" cy="30" r="8" fill="#00ccff" opacity="0.8"/>
                </svg>
                <span class="text-lg md:text-2xl font-extrabold bg-gradient-to-r from-white to-cyan-400 bg-clip-text text-transparent">ZUROSMS</span>
            </div>
            
            <!-- Desktop Menu -->
            <div class="desktop-menu hidden md:flex gap-8">
                <a href="#home" class="text-gray-300 hover:text-white transition">Beranda</a>
                <a href="#layanan" class="text-gray-300 hover:text-white transition">Layanan</a>
                <a href="#harga" class="text-gray-300 hover:text-white transition">Harga</a>
                <a href="#cara-kerja" class="text-gray-300 hover:text-white transition">Cara Kerja</a>
            </div>
            
            <!-- Auth Buttons Desktop -->
            <div class="hidden md:flex gap-3">
                <?php if (isLoggedIn()): ?>
                    <a href="dashboard.php" class="btn-neon px-6 py-2 text-sm">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                <?php else: ?>
                    <a href="login.php" class="text-white hover:text-cyan-400 transition px-4 py-2">Masuk</a>
                    <a href="register.php" class="btn-neon px-6 py-2 text-sm">Daftar</a>
                <?php endif; ?>
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="hamburger" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="#home" class="text-white text-xl py-4 hover:text-cyan-400 transition" onclick="toggleMobileMenu()">Beranda</a>
        <a href="#layanan" class="text-white text-xl py-4 hover:text-cyan-400 transition" onclick="toggleMobileMenu()">Layanan</a>
        <a href="#harga" class="text-white text-xl py-4 hover:text-cyan-400 transition" onclick="toggleMobileMenu()">Harga</a>
        <a href="#cara-kerja" class="text-white text-xl py-4 hover:text-cyan-400 transition" onclick="toggleMobileMenu()">Cara Kerja</a>
        
        <div class="flex flex-col gap-3 mt-8 w-full max-w-xs">
            <?php if (isLoggedIn()): ?>
                <a href="dashboard.php" class="btn-neon text-center">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
            <?php else: ?>
                <a href="login.php" class="btn-outline text-center">Masuk</a>
                <a href="register.php" class="btn-neon text-center">Daftar</a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center pt-20 px-4">
        <div class="max-w-7xl mx-auto w-full py-12 md:py-20">
            <div class="grid lg:grid-cols-2 gap-8 md:gap-12 items-center">
                <!-- Left Content -->
                <div class="reveal">
                    <div class="inline-flex items-center gap-2 bg-blue-primary-20 rounded-full px-3 md:px-4 py-2 mb-4 md:mb-6 border border-blue-primary-30">
                        <i class="fas fa-shield-alt text-blue-light text-xs md:text-sm"></i>
                        <span class="text-blue-light text-xs md:text-sm font-semibold">Aman & Terpercaya</span>
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl lg:text-7xl font-extrabold leading-tight mb-4 md:mb-6">
                        Nomor Virtual untuk
                        <span class="text-gradient-blue">
                            Verifikasi OTP
                        </span>
                    </h1>
                    
                    <p class="text-gray-400 text-base md:text-lg mb-6 md:mb-8 leading-relaxed">
                        Dapatkan nomor virtual instant untuk verifikasi WhatsApp, Telegram, Instagram, 
                        dan berbagai platform lainnya. Cepat, aman, dan harga terbaik.
                    </p>
                    
                    <div class="flex gap-3 md:gap-4 flex-wrap">
                        <a href="<?php echo isLoggedIn() ? 'dashboard.php' : 'register.php'; ?>" class="btn-neon inline-flex items-center gap-2 text-sm md:text-base px-6 md:px-8 py-3 md:py-4">
                            <i class="fas fa-rocket"></i>
                            Mulai Sekarang
                        </a>
                        <a href="#cara-kerja" class="btn-outline inline-flex items-center gap-2 text-sm md:text-base px-6 md:px-8 py-3 md:py-4">
                            <i class="fas fa-play"></i>
                            Lihat Demo
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-3 md:gap-6 mt-8 md:mt-12 pt-6 md:pt-8 border-t border-gray-800">
                        <div>
                            <div class="stat-number"><?php echo number_format($total_users); ?>+</div>
                            <div class="text-gray-500 text-xs md:text-sm">Pengguna Aktif</div>
                        </div>
                        <div>
                            <div class="stat-number"><?php echo number_format($total_orders); ?>+</div>
                            <div class="text-gray-500 text-xs md:text-sm">Nomor Terjual</div>
                        </div>
                        <div>
                            <div class="stat-number"><?php echo $success_rate; ?>%</div>
                            <div class="text-gray-500 text-xs md:text-sm">Sukses Verifikasi</div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Content - Demo Box -->
                <div class="reveal">
                    <div class="glass-card p-6 md:p-8 relative">
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-blue-primary px-4 md:px-6 py-2 rounded-full text-xs md:text-sm font-semibold">
                            <i class="fas fa-mobile-alt mr-2"></i>Coba Sekarang
                        </div>
                        
                        <div class="mt-6 space-y-4 md:space-y-5">
                            <div>
                                <label class="block text-xs md:text-sm text-gray-400 mb-2">Pilih Layanan</label>
                                <select id="demo-service" class="zuro-input text-sm md:text-base">
                                    <option value="tg">Telegram</option>
                                    <option value="wa">WhatsApp</option>
                                    <option value="ig">Instagram</option>
                                    <option value="fb">Facebook</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs md:text-sm text-gray-400 mb-2">Pilih Negara</label>
                                <select id="demo-country" class="zuro-input text-sm md:text-base">
                                    <option value="2">🇰🇿 Kazakhstan</option>
                                    <option value="0">🇷🇺 Russia</option>
                                    <option value="187">🇺🇸 United States</option>
                                    <option value="188">🇬🇧 United Kingdom</option>
                                    <option value="6">🇮🇩 Indonesia</option>
                                    <option value="36">🇨🇦 Canada</option>
                                    <option value="16">🇪🇸 Spain</option>
                                    <option value="22">🇦🇺 Australia</option>
                                    <option value="45">🇫🇷 France</option>
                                    <option value="43">🇩🇪 Germany</option>
                                </select>
                            </div>
                            
                            <button onclick="demoOrderNumber()" class="btn-neon w-full text-sm md:text-base" id="demo-btn">
                                <i class="fas fa-shopping-cart mr-2"></i>Pesan Nomor
                            </button>
                            
                            <div id="demo-result" style="display: none;" class="mt-4 p-4 bg-blue-primary-20 rounded-xl border border-blue-primary-30">
                                <div class="text-xs md:text-sm text-gray-400 mb-1">Nomor Anda:</div>
                                <div class="font-mono text-blue-light text-lg md:text-xl text-center" id="demo-number"></div>
                                <div class="text-xs text-gray-500 text-center mt-2" id="demo-price"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Payment Support Section -->
    <section class="py-8 md:py-12 bg-black/20">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="text-center mb-6">
                <p class="text-gray-400 text-sm md:text-base mb-4">Metode Pembayaran yang Didukung</p>
            </div>
            <div class="scroll-container">
                <div class="scroll-content">
                    <!-- First set -->
                    <div class="flex items-center gap-8 px-4">
                        <div class="payment-logo px-6 py-3 glass-card">
                            <i class="fab fa-cc-visa text-3xl md:text-4xl" style="color: #1A1F71"></i>
                        </div>
                        <div class="payment-logo px-6 py-3 glass-card">
                            <i class="fab fa-cc-mastercard text-3xl md:text-4xl" style="color: #EB001B"></i>
                        </div>
                        <div class="payment-logo px-6 py-3 glass-card">
                            <i class="fab fa-paypal text-3xl md:text-4xl" style="color: #00457C"></i>
                        </div>
                        <div class="payment-logo px-6 py-3 glass-card">
                            <i class="fab fa-bitcoin text-3xl md:text-4xl" style="color: #F7931A"></i>
                        </div>
                        <div class="payment-logo px-6 py-3 glass-card">
                            <span class="text-xl md:text-2xl font-bold text-white">GOPAY</span>
                        </div>
                        <div class="payment-logo px-6 py-3 glass-card">
                            <span class="text-xl md:text-2xl font-bold text-blue-500">OVO</span>
                        </div>
                        <div class="payment-logo px-6 py-3 glass-card">
                            <span class="text-xl md:text-2xl font-bold text-red-500">DANA</span>
                        </div>
                        <div class="payment-logo px-6 py-3 glass-card">
                            <i class="fab fa-google-pay text-3xl md:text-4xl text-white"></i>
                        </div>
                    </div>
                    <!-- Duplicate for seamless loop -->
                    <div class="flex items-center gap-8 px-4">
                        <div class="payment-logo px-6 py-3 glass-card">
                            <i class="fab fa-cc-visa text-3xl md:text-4xl" style="color: #1A1F71"></i>
                        </div>
                        <div class="payment-logo px-6 py-3 glass-card">
                            <i class="fab fa-cc-mastercard text-3xl md:text-4xl" style="color: #EB001B"></i>
                        </div>
                        <div class="payment-logo px-6 py-3 glass-card">
                            <i class="fab fa-paypal text-3xl md:text-4xl" style="color: #00457C"></i>
                        </div>
                        <div class="payment-logo px-6 py-3 glass-card">
                            <i class="fab fa-bitcoin text-3xl md:text-4xl" style="color: #F7931A"></i>
                        </div>
                        <div class="payment-logo px-6 py-3 glass-card">
                            <span class="text-xl md:text-2xl font-bold text-white">GOPAY</span>
                        </div>
                        <div class="payment-logo px-6 py-3 glass-card">
                            <span class="text-xl md:text-2xl font-bold text-blue-500">OVO</span>
                        </div>
                        <div class="payment-logo px-6 py-3 glass-card">
                            <span class="text-xl md:text-2xl font-bold text-red-500">DANA</span>
                        </div>
                        <div class="payment-logo px-6 py-3 glass-card">
                            <i class="fab fa-google-pay text-3xl md:text-4xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Trust Badges Section -->
    <section class="py-8 md:py-12">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="flex flex-wrap justify-center gap-4 md:gap-6">
                <div class="trust-badge">
                    <i class="fas fa-shield-alt text-green-500"></i>
                    <span class="text-sm md:text-base">SSL Secured</span>
                </div>
                <div class="trust-badge">
                    <i class="fas fa-lock text-green-500"></i>
                    <span class="text-sm md:text-base">100% Aman</span>
                </div>
                <div class="trust-badge">
                    <i class="fas fa-clock text-green-500"></i>
                    <span class="text-sm md:text-base">Support 24/7</span>
                </div>
                <div class="trust-badge">
                    <i class="fas fa-bolt text-green-500"></i>
                    <span class="text-sm md:text-base">Instant Delivery</span>
                </div>
                <div class="trust-badge">
                    <i class="fas fa-users text-green-500"></i>
                    <span class="text-sm md:text-base"><?php echo number_format($total_users); ?>+ Users</span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Services Section -->
    <section id="layanan" class="py-12 md:py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-8 md:mb-12 reveal">
                <h2 class="text-2xl md:text-4xl font-bold mb-3 md:mb-4">Layanan Yang <span class="text-gradient-blue">Didukung</span></h2>
                <p class="text-gray-400 text-sm md:text-base max-w-2xl mx-auto">Dapatkan nomor virtual untuk berbagai platform populer</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4">
                <?php foreach ($services as $service): ?>
                <div class="glass-card p-4 md:p-5 text-center hover:scale-105 transition cursor-pointer group">
                    <i class="<?php echo $service['icon']; ?> text-2xl md:text-4xl mb-2 md:mb-3" style="color: <?php echo $service['color']; ?>"></i>
                    <p class="font-semibold text-sm md:text-base"><?php echo $service['name']; ?></p>
                    <p class="text-xs text-gray-500 mt-1">Mulai $0.25</p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- Pricing Section -->
    <section id="harga" class="py-12 md:py-20 bg-black/30 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-8 md:mb-12 reveal">
                <h2 class="text-2xl md:text-4xl font-bold mb-3 md:mb-4">Harga <span class="text-gradient-blue">Terjangkau</span></h2>
                <p class="text-gray-400 text-sm md:text-base max-w-2xl mx-auto">Bayar sesuai pemakaian, tanpa biaya langganan</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6 md:gap-8 max-w-5xl mx-auto">
                <!-- Basic -->
                <div class="glass-card p-6 md:p-8 text-center reveal">
                    <h3 class="text-xl md:text-2xl font-bold mb-2">Basic</h3>
                    <div class="text-3xl md:text-4xl font-bold text-blue-light mb-3 md:mb-4">$0.30</div>
                    <p class="text-gray-400 text-xs md:text-sm mb-4 md:mb-6">Per aktivasi</p>
                    <ul class="text-left space-y-2 md:space-y-3 mb-6 md:mb-8 text-sm md:text-base">
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 flex-shrink-0"></i> <span>50+ Negara</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 flex-shrink-0"></i> <span>Support 24/7</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 flex-shrink-0"></i> <span>API Access</span></li>
                    </ul>
                </div>
                
                <!-- Professional -->
                <div class="glass-card p-6 md:p-8 text-center border-2 border-blue-primary relative reveal">
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-blue-primary px-3 md:px-4 py-1 rounded-full text-xs font-semibold">POPULER</div>
                    <h3 class="text-xl md:text-2xl font-bold mb-2">Professional</h3>
                    <div class="text-3xl md:text-4xl font-bold text-blue-light mb-3 md:mb-4">$0.25</div>
                    <p class="text-gray-400 text-xs md:text-sm mb-4 md:mb-6">Volume discount</p>
                    <ul class="text-left space-y-2 md:space-y-3 mb-6 md:mb-8 text-sm md:text-base">
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 flex-shrink-0"></i> <span>Semua fitur Basic</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 flex-shrink-0"></i> <span>Priority Support</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 flex-shrink-0"></i> <span>Bulk Ordering</span></li>
                    </ul>
                </div>
                
                <!-- Enterprise -->
                <div class="glass-card p-6 md:p-8 text-center reveal">
                    <h3 class="text-xl md:text-2xl font-bold mb-2">Enterprise</h3>
                    <div class="text-3xl md:text-4xl font-bold text-blue-light mb-3 md:mb-4">Custom</div>
                    <p class="text-gray-400 text-xs md:text-sm mb-4 md:mb-6">Volume besar</p>
                    <ul class="text-left space-y-2 md:space-y-3 mb-6 md:mb-8 text-sm md:text-base">
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 flex-shrink-0"></i> <span>Dedicated Support</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 flex-shrink-0"></i> <span>Custom Integration</span></li>
                        <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500 flex-shrink-0"></i> <span>Harga Terbaik</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="py-12 md:py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-8 md:mb-12 reveal">
                <h2 class="text-2xl md:text-4xl font-bold mb-3 md:mb-4">Apa Kata <span class="text-gradient-blue">Mereka?</span></h2>
                <p class="text-gray-400 text-sm md:text-base max-w-2xl mx-auto">Testimoni dari pengguna setia ZUROSMS</p>
            </div>
            
            <div class="swiper testimonial-swiper">
                <div class="swiper-wrapper pb-12">
                    <!-- Testimonial 1 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-gradient-to-br from-blue-600 to-cyan-400 flex items-center justify-center text-white font-bold text-lg md:text-xl">
                                    AH
                                </div>
                                <div>
                                    <h4 class="font-bold text-base md:text-lg">Ahmad Hidayat</h4>
                                    <p class="text-xs md:text-sm text-gray-400">Digital Marketer</p>
                                </div>
                            </div>
                            <div class="flex gap-1 mb-3">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                            <p class="text-gray-300 text-sm md:text-base leading-relaxed">
                                "Sangat membantu untuk verifikasi multiple WhatsApp accounts. Harga terjangkau dan nomor langsung aktif. Recommended banget!"
                            </p>
                        </div>
                    </div>
                    
                    <!-- Testimonial 2 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-gradient-to-br from-purple-600 to-pink-400 flex items-center justify-center text-white font-bold text-lg md:text-xl">
                                    SR
                                </div>
                                <div>
                                    <h4 class="font-bold text-base md:text-lg">Siti Rahayu</h4>
                                    <p class="text-xs md:text-sm text-gray-400">Online Seller</p>
                                </div>
                            </div>
                            <div class="flex gap-1 mb-3">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                            <p class="text-gray-300 text-sm md:text-base leading-relaxed">
                                "Pelayanan cepat, support responsif. Sudah pakai 3 bulan untuk jualan online, sangat membantu manage banyak akun."
                            </p>
                        </div>
                    </div>
                    
                    <!-- Testimonial 3 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-gradient-to-br from-green-600 to-teal-400 flex items-center justify-center text-white font-bold text-lg md:text-xl">
                                    BP
                                </div>
                                <div>
                                    <h4 class="font-bold text-base md:text-lg">Budi Prasetyo</h4>
                                    <p class="text-xs md:text-sm text-gray-400">App Developer</p>
                                </div>
                            </div>
                            <div class="flex gap-1 mb-3">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                            <p class="text-gray-300 text-sm md:text-base leading-relaxed">
                                "API nya mudah diintegrasikan. Perfect untuk testing aplikasi yang butuh verifikasi SMS. Worth it!"
                            </p>
                        </div>
                    </div>
                    
                    <!-- Testimonial 4 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-gradient-to-br from-orange-600 to-red-400 flex items-center justify-center text-white font-bold text-lg md:text-xl">
                                    DF
                                </div>
                                <div>
                                    <h4 class="font-bold text-base md:text-lg">Dewi Fortuna</h4>
                                    <p class="text-xs md:text-sm text-gray-400">Content Creator</p>
                                </div>
                            </div>
                            <div class="flex gap-1 mb-3">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                            <p class="text-gray-300 text-sm md:text-base leading-relaxed">
                                "Butuh nomor buat verifikasi akun Telegram dan Instagram. Prosesnya cepat, dalam hitungan detik langsung dapat kode OTP!"
                            </p>
                        </div>
                    </div>
                    
                    <!-- Testimonial 5 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-gradient-to-br from-indigo-600 to-blue-400 flex items-center justify-center text-white font-bold text-lg md:text-xl">
                                    RK
                                </div>
                                <div>
                                    <h4 class="font-bold text-base md:text-lg">Rian Kurniawan</h4>
                                    <p class="text-xs md:text-sm text-gray-400">Freelancer</p>
                                </div>
                            </div>
                            <div class="flex gap-1 mb-3">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                            <p class="text-gray-300 text-sm md:text-base leading-relaxed">
                                "Harga paling murah dibanding kompetitor. Kualitas nomor juga bagus, jarang gagal. Sudah langganan 6 bulan lebih."
                            </p>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    
    <!-- How It Works -->
    <section id="cara-kerja" class="py-12 md:py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-8 md:mb-12 reveal">
                <h2 class="text-2xl md:text-4xl font-bold mb-3 md:mb-4">Cara <span class="text-gradient-blue">Kerja</span></h2>
                <p class="text-gray-400 text-sm md:text-base max-w-2xl mx-auto">Mudah, cepat, dan aman</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6 md:gap-8">
                <div class="text-center reveal">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-blue-primary-20 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4 border-2 border-blue-primary">
                        <span class="text-2xl md:text-3xl font-bold text-blue-light">1</span>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold mb-2">Pilih Layanan</h3>
                    <p class="text-gray-400 text-sm md:text-base">Pilih platform yang ingin diverifikasi dan negara tujuan</p>
                </div>
                
                <div class="text-center reveal">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-blue-primary-20 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4 border-2 border-blue-primary">
                        <span class="text-2xl md:text-3xl font-bold text-blue-light">2</span>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold mb-2">Dapatkan Nomor</h3>
                    <p class="text-gray-400 text-sm md:text-base">Sistem akan memberikan nomor virtual secara instant</p>
                </div>
                
                <div class="text-center reveal">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-blue-primary-20 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4 border-2 border-blue-primary">
                        <span class="text-2xl md:text-3xl font-bold text-blue-light">3</span>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold mb-2">Terima Kode OTP</h3>
                    <p class="text-gray-400 text-sm md:text-base">Kode verifikasi akan muncul secara otomatis di dashboard</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="py-12 md:py-20 px-4 bg-black/20">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-8 md:mb-12 reveal">
                <h2 class="text-2xl md:text-4xl font-bold mb-3 md:mb-4">Pertanyaan <span class="text-gradient-blue">Umum</span></h2>
                <p class="text-gray-400 text-sm md:text-base">Jawaban untuk pertanyaan yang sering ditanyakan</p>
            </div>
            
            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="faq-item" onclick="toggleFAQ(this)">
                    <div class="faq-question">
                        <span class="text-sm md:text-base">Berapa lama nomor virtual aktif?</span>
                        <i class="fas fa-chevron-down faq-icon text-blue-light"></i>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-400 text-sm md:text-base">
                            Nomor virtual aktif selama 20 menit sejak Anda melakukan pemesanan. Waktu ini cukup untuk menerima kode OTP dari berbagai platform. Jika kode tidak masuk dalam waktu tersebut, saldo akan otomatis dikembalikan.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 2 -->
                <div class="faq-item" onclick="toggleFAQ(this)">
                    <div class="faq-question">
                        <span class="text-sm md:text-base">Apakah bisa digunakan untuk semua layanan?</span>
                        <i class="fas fa-chevron-down faq-icon text-blue-light"></i>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-400 text-sm md:text-base">
                            Ya, kami support 100+ layanan populer termasuk WhatsApp, Telegram, Instagram, Facebook, Twitter, TikTok, Discord, dan masih banyak lagi. Anda bisa cek daftar lengkap di dashboard setelah login.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 3 -->
                <div class="faq-item" onclick="toggleFAQ(this)">
                    <div class="faq-question">
                        <span class="text-sm md:text-base">Bagaimana cara melakukan top up saldo?</span>
                        <i class="fas fa-chevron-down faq-icon text-blue-light"></i>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-400 text-sm md:text-base">
                            Kami menerima berbagai metode pembayaran seperti Transfer Bank, GoPay, OVO, DANA, PayPal, dan Cryptocurrency. Proses top up otomatis dan saldo langsung masuk ke akun Anda dalam hitungan menit.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 4 -->
                <div class="faq-item" onclick="toggleFAQ(this)">
                    <div class="faq-question">
                        <span class="text-sm md:text-base">Apakah ada garansi jika nomor tidak berfungsi?</span>
                        <i class="fas fa-chevron-down faq-icon text-blue-light"></i>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-400 text-sm md:text-base">
                            Ya, jika nomor tidak menerima SMS dalam waktu 20 menit atau terjadi error, saldo akan otomatis dikembalikan ke akun Anda. Kami juga memiliki fitur "Cancel" untuk membatalkan pemesanan jika belum menerima kode.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 5 -->
                <div class="faq-item" onclick="toggleFAQ(this)">
                    <div class="faq-question">
                        <span class="text-sm md:text-base">Apakah nomor bisa digunakan kembali?</span>
                        <i class="fas fa-chevron-down faq-icon text-blue-light"></i>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-400 text-sm md:text-base">
                            Tidak, setiap nomor hanya untuk satu kali penggunaan (disposable). Ini memastikan privasi dan keamanan Anda. Setelah masa aktif berakhir, nomor akan di-recycle oleh sistem untuk pengguna lain di waktu mendatang.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 6 -->
                <div class="faq-item" onclick="toggleFAQ(this)">
                    <div class="faq-question">
                        <span class="text-sm md:text-base">Bagaimana cara menggunakan API?</span>
                        <i class="fas fa-chevron-down faq-icon text-blue-light"></i>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-400 text-sm md:text-base">
                            Setelah login, Anda bisa generate API key di menu Settings. Dokumentasi lengkap tersedia di dashboard dengan contoh code dalam berbagai bahasa pemrograman (PHP, Python, Node.js, dll). API kami RESTful dan mudah diintegrasikan.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 7 -->
                <div class="faq-item" onclick="toggleFAQ(this)">
                    <div class="faq-question">
                        <span class="text-sm md:text-base">Apakah data saya aman?</span>
                        <i class="fas fa-chevron-down faq-icon text-blue-light"></i>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-400 text-sm md:text-base">
                            Keamanan data adalah prioritas kami. Semua transaksi menggunakan SSL encryption, password di-hash dengan algoritma modern, dan kami tidak menyimpan informasi sensitif seperti kode OTP yang Anda terima. Kami juga tidak menjual data pengguna ke pihak ketiga.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ 8 -->
                <div class="faq-item" onclick="toggleFAQ(this)">
                    <div class="faq-question">
                        <span class="text-sm md:text-base">Minimum top up berapa?</span>
                        <i class="fas fa-chevron-down faq-icon text-blue-light"></i>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-400 text-sm md:text-base">
                            Minimum top up adalah $5 USD. Tidak ada maksimum top up. Semakin besar jumlah top up, semakin besar discount yang Anda dapatkan. Cek halaman pricing untuk detail discount tier.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-12 md:py-20 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <div class="glass-card p-8 md:p-12 reveal">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-3 md:mb-4">Siap Memulai?</h2>
                <p class="text-gray-400 text-sm md:text-base mb-6 md:mb-8">Daftar sekarang dan dapatkan bonus saldo $5 untuk percobaan pertama</p>
                <a href="<?php echo isLoggedIn() ? 'dashboard.php' : 'register.php'; ?>" class="btn-neon inline-flex items-center gap-2 text-base md:text-lg px-8 md:px-10 py-3 md:py-4">
                    <i class="fas fa-user-plus"></i>
                    Daftar Gratis
                </a>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="py-8 md:py-12 border-t border-gray-800 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2 mb-3 md:mb-4">
                        <svg class="w-8 h-8 flex-shrink-0" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="logoGradientFooter" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#0033cc;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#00ccff;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <rect x="10" y="10" width="80" height="80" rx="20" fill="url(#logoGradientFooter)"/>
                            <path d="M 55 25 L 35 50 L 45 50 L 40 75 L 65 45 L 52 45 Z" fill="white"/>
                            <circle cx="70" cy="30" r="8" fill="#00ccff" opacity="0.8"/>
                        </svg>
                        <span class="text-lg md:text-xl font-bold">ZUROSMS</span>
                    </div>
                    <p class="text-gray-500 text-xs md:text-sm mb-4">Solusi nomor virtual terpercaya untuk verifikasi SMS</p>
                    <div class="flex gap-3">
                        <a href="#" class="w-8 h-8 rounded-full bg-blue-primary flex items-center justify-center hover:bg-cyan-400 transition">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-blue-primary flex items-center justify-center hover:bg-cyan-400 transition">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-blue-primary flex items-center justify-center hover:bg-cyan-400 transition">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-blue-primary flex items-center justify-center hover:bg-cyan-400 transition">
                            <i class="fab fa-telegram text-sm"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-bold mb-3 md:mb-4 text-sm md:text-base">Layanan</h4>
                    <ul class="space-y-2 text-gray-500 text-xs md:text-sm">
                        <li><a href="#layanan" class="hover:text-white transition">WhatsApp</a></li>
                        <li><a href="#layanan" class="hover:text-white transition">Telegram</a></li>
                        <li><a href="#layanan" class="hover:text-white transition">Instagram</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-3 md:mb-4 text-sm md:text-base">Perusahaan</h4>
                    <ul class="space-y-2 text-gray-500 text-xs md:text-sm">
                        <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition">Kontak</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-3 md:mb-4 text-sm md:text-base">Legal</h4>
                    <ul class="space-y-2 text-gray-500 text-xs md:text-sm">
                        <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Kebijakan Refund</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="text-center text-gray-500 text-xs md:text-sm pt-6 md:pt-8 mt-6 md:mt-8 border-t border-gray-800">
                &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.
            </div>
        </div>
    </footer>
    
    <!-- Sticky CTA Button -->
    <div class="sticky-cta" id="stickyCTA">
        <a href="<?php echo isLoggedIn() ? 'dashboard.php' : 'register.php'; ?>" class="btn-neon px-6 py-3 shadow-2xl flex items-center gap-2">
            <i class="fas fa-rocket"></i>
            <span class="hidden md:inline">Mulai Sekarang</span>
            <span class="md:hidden">Daftar</span>
        </a>
    </div>
    
    <!-- Live Chat Button (WhatsApp) -->
    <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20bertanya%20tentang%20ZUROSMS" target="_blank" class="live-chat-btn" title="Chat dengan kami">
        <i class="fab fa-whatsapp text-white text-2xl"></i>
    </a>
    
    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('active');
            document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
        }
        
        // FAQ Toggle
        function toggleFAQ(element) {
            element.classList.toggle('active');
        }
        
        // Sticky CTA Show/Hide
        window.addEventListener('scroll', function() {
            const stickyCTA = document.getElementById('stickyCTA');
            if (window.scrollY > 800) {
                stickyCTA.classList.add('show');
            } else {
                stickyCTA.classList.remove('show');
            }
        });
        
        // Animated Counter
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current).toLocaleString();
                }
            }, 20);
        }
        
        // Initialize counters when in viewport
        const counterElements = document.querySelectorAll('.stat-number');
        let countersAnimated = false;
        
        function checkCounters() {
            if (countersAnimated) return;
            
            counterElements.forEach(el => {
                if (isElementInViewport(el)) {
                    const target = parseInt(el.textContent.replace(/[^0-9]/g, ''));
                    animateCounter(el, target);
                    countersAnimated = true;
                }
            });
        }
        
        // Scroll reveal
        function isElementInViewport(el) {
            const rect = el.getBoundingClientRect();
            return (
                rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.8 &&
                rect.bottom >= 0
            );
        }
        
        function handleScrollReveal() {
            const reveals = document.querySelectorAll('.reveal');
            reveals.forEach(el => {
                if (isElementInViewport(el)) {
                    el.classList.add('active');
                }
            });
            checkCounters();
        }
        
        window.addEventListener('scroll', handleScrollReveal);
        window.addEventListener('load', handleScrollReveal);
        
        // Initialize Swiper for Testimonials
        const testimonialSwiper = new Swiper('.testimonial-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
        });
        
        // Demo order function with country flags
        function demoOrderNumber() {
            const service = $('#demo-service').val();
            const country = $('#demo-country').val();
            
            $('#demo-btn').html('<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...').prop('disabled', true);
            
            $.ajax({
                url: 'ajax/get_demo_number.php',
                method: 'POST',
                data: { service: service, country: country },
                success: function(response) {
                    if (response.success) {
                        $('#demo-result').show();
                        $('#demo-number').text(response.phoneNumber);
                        $('#demo-price').text('Harga: $' + response.price);
                        $('#demo-result').addClass('animate-pulse');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Nomor Didapatkan!',
                            html: `<div class="text-2xl font-mono" style="color: #00ccff">${response.phoneNumber}</div>
                                   <div class="text-sm mt-2">Harga: $${response.price}</div>`,
                            confirmButtonColor: '#0033cc',
                            background: '#1a1a1a',
                            color: '#ffffff'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Gagal mendapatkan nomor',
                            confirmButtonColor: '#0033cc',
                            background: '#1a1a1a',
                            color: '#ffffff'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan sistem',
                        confirmButtonColor: '#0033cc',
                        background: '#1a1a1a',
                        color: '#ffffff'
                    });
                },
                complete: function() {
                    $('#demo-btn').html('<i class="fas fa-shopping-cart mr-2"></i>Pesan Nomor').prop('disabled', false);
                }
            });
        }
        
        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            const target = $(this.hash);
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 80
                }, 800);
            }
        });
    </script>
</body>
</html>
