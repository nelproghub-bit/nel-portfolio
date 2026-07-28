<?php
require_once 'config/db.php';

// Fetch Settings (Bio, Resume Link)
$settingsQuery = $pdo->query("SELECT setting_key, setting_value FROM settings");
$settings = [];
while ($row = $settingsQuery->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
$resumeSummary = $settings['resume_summary'] ?? 'Passionate developer creating modern, unique web experiences.';
$resumePdfUrl = $settings['resume_pdf_url'] ?? '#';

// About section settings
$aboutYearsExperience = $settings['about_years_experience'] ?? '5+';
$aboutTotalProjects = $settings['about_total_projects'] ?? '50+';
$aboutTotalClients = $settings['about_total_clients'] ?? '20+';
$aboutEmail = $settings['about_email'] ?? 'contact@nel.dev';
$aboutLocation = $settings['about_location'] ?? 'Remote / Worldwide';
$aboutCoreExpertise = $settings['about_core_expertise'] ?? 'Full Stack Development,UI/UX Design,Cloud Architecture,DevOps';
$aboutProfilePhoto = $settings['about_profile_photo'] ?? '';

// Hero section settings
$heroBadgeText = $settings['hero_badge_text'] ?? 'Available for Work';
$heroTitleLine1 = $settings['hero_title_line1'] ?? 'Creative';
$heroTitleLine2 = $settings['hero_title_line2'] ?? 'Developer';
$heroTitleFontFamily = $settings['hero_title_font_family'] ?? 'Outfit';
$heroTitleFontSize = $settings['hero_title_font_size'] ?? '72';
$heroTitleFontWeight = $settings['hero_title_font_weight'] ?? '900';
$heroSubtitle = $settings['hero_subtitle'] ?? 'Crafting <span class="text-white font-medium">digital experiences</span> that merge cutting-edge technology with <span class="text-white font-medium">premium design aesthetics</span>.';
$heroPrimaryBtnText = $settings['hero_primary_btn_text'] ?? 'Discover My Work';
$heroPrimaryBtnLink = $settings['hero_primary_btn_link'] ?? '#about';
$heroSecondaryBtnText = $settings['hero_secondary_btn_text'] ?? 'View Projects';
$heroSecondaryBtnLink = $settings['hero_secondary_btn_link'] ?? '#projects';

// Fetch Skills grouped by category
$skills = $pdo->query("SELECT * FROM skills ORDER BY category ASC, id DESC")->fetchAll(PDO::FETCH_GROUP);

// Skills section settings
$skillsBadgeText = $settings['skills_badge_text'] ?? 'Technical Proficiency';
$skillsTitle = $settings['skills_title'] ?? 'Technical Arsenal';
$skillsSubtitle = $settings['skills_subtitle'] ?? 'A comprehensive toolkit of cutting-edge technologies and frameworks I leverage to build exceptional digital experiences.';

// Projects section settings
$projectsBadgeText = $settings['projects_badge_text'] ?? 'Portfolio Showcase';
$projectsTitle = $settings['projects_title'] ?? 'Selected Works';
$projectsSubtitle = $settings['projects_subtitle'] ?? 'A curated collection of my most impactful projects, showcasing innovation, technical expertise, and creative problem-solving.';
$projectsLiveBtnText = $settings['projects_live_btn_text'] ?? 'Live Demo';
$projectsCodeBtnText = $settings['projects_code_btn_text'] ?? 'Source Code';
$projectsStatusLiveText = $settings['projects_status_live_text'] ?? 'Live';
$projectsStatusDevText = $settings['projects_status_dev_text'] ?? 'In Development';
$projectsCompletedText = $settings['projects_completed_text'] ?? 'Completed';

// Fetch Projects
$projects = $pdo->query("SELECT * FROM projects ORDER BY sort_order ASC, created_at DESC")->fetchAll();

// Fetch Certifications
$certs = $pdo->query("SELECT * FROM certifications ORDER BY issue_date DESC, id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio | Creative Developer</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        gray: tailwind.colors.slate,
                        brand: {
                            400: '#818cf8', // indigo-400
                            500: '#6366f1', // indigo-500
                            600: '#4f46e5', // indigo-600
                        }
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'fade-in-up': 'fadeInUp 1s ease-out forwards',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@200;300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800;900&family=Raleway:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #020617; color: #e2e8f0; overflow-x: hidden; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Outfit', sans-serif; }
        
        /* Animated Tech Background */
        .tech-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(0, 40, 120, 0.4) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(0, 60, 150, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(0, 80, 200, 0.2) 0%, transparent 50%),
                linear-gradient(135deg, #0a0f1c 0%, #0d1424 25%, #0f1729 50%, #0a0f1c 100%);
            z-index: -100;
            overflow: hidden;
        }

        /* Circuit Board Pattern */
        .circuit-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(90deg, rgba(0, 150, 255, 0.1) 1px, transparent 1px),
                linear-gradient(rgba(0, 150, 255, 0.1) 1px, transparent 1px);
            background-size: 100px 100px;
            animation: circuit-move 20s linear infinite;
        }

        @keyframes circuit-move {
            0% { transform: translate(0, 0); }
            100% { transform: translate(100px, 100px); }
        }

        /* Animated Circuit Lines */
        .circuit-line {
            position: absolute;
            background: linear-gradient(90deg, transparent, #00bfff, transparent);
            height: 2px;
            animation: line-flow 4s ease-in-out infinite;
        }

        .circuit-line.horizontal {
            width: 200px;
            left: -200px;
            animation: line-flow-horizontal 6s linear infinite;
        }

        .circuit-line.vertical {
            width: 2px;
            height: 200px;
            top: -200px;
            animation: line-flow-vertical 8s linear infinite;
        }

        @keyframes line-flow-horizontal {
            0% { left: -200px; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { left: 100%; opacity: 0; }
        }

        @keyframes line-flow-vertical {
            0% { top: -200px; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }

        /* Glowing Nodes */
        .circuit-node {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #00bfff;
            border-radius: 50%;
            box-shadow: 0 0 10px #00bfff, 0 0 20px #00bfff, 0 0 30px #00bfff;
            animation: node-pulse 2s ease-in-out infinite alternate;
        }

        @keyframes node-pulse {
            0% { 
                transform: scale(1);
                box-shadow: 0 0 10px #00bfff, 0 0 20px #00bfff, 0 0 30px #00bfff;
            }
            100% { 
                transform: scale(1.5);
                box-shadow: 0 0 15px #00bfff, 0 0 30px #00bfff, 0 0 45px #00bfff;
            }
        }

        /* Large Circuit Elements */
        .large-circuit {
            position: absolute;
            border: 2px solid rgba(0, 191, 255, 0.3);
            border-radius: 50%;
            animation: rotate 30s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Data Flow Particles */
        .data-particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(0, 191, 255, 0.8);
            border-radius: 50%;
            animation: particle-flow 3s linear infinite;
        }

        @keyframes particle-flow {
            0% {
                transform: translateX(0) translateY(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateX(300px) translateY(-300px);
                opacity: 0;
            }
        }

        /* Tech Arrows */
        .tech-arrow {
            position: absolute;
            width: 0;
            height: 0;
            border-style: solid;
            animation: arrow-glow 2s ease-in-out infinite;
        }

        .tech-arrow.right {
            border-left: 15px solid rgba(0, 191, 255, 0.6);
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
        }

        @keyframes arrow-glow {
            0%, 100% { 
                filter: drop-shadow(0 0 5px rgba(0, 191, 255, 0.6));
            }
            50% { 
                filter: drop-shadow(0 0 15px rgba(0, 191, 255, 1));
            }
        }

        /* Hexagonal Patterns */
        .hex-pattern {
            position: absolute;
            width: 60px;
            height: 60px;
            background: rgba(0, 191, 255, 0.1);
            clip-path: polygon(30% 0%, 70% 0%, 100% 50%, 70% 100%, 30% 100%, 0% 50%);
            animation: hex-float 6s ease-in-out infinite;
        }

        @keyframes hex-float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Enhanced Background Mesh */
        .mesh-bg {
            background-image: 
                radial-gradient(at 40% 20%, hsla(253,16%,7%,1) 0px, transparent 50%),
                radial-gradient(at 80% 0%, hsla(240,100%,70%,0.15) 0px, transparent 50%),
                radial-gradient(at 0% 50%, hsla(255,100%,76%,0.1) 0px, transparent 50%),
                radial-gradient(at 80% 100%, hsla(242,100%,70%,0.15) 0px, transparent 50%),
                radial-gradient(at 0% 0%, hsla(343,100%,76%,0.1) 0px, transparent 50%);
        }

        /* Enhanced Glass Navigation */
        .glass-nav {
            background: rgba(2, 6, 23, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Enhanced Glass Card */
        .glass-card {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        /* Enhanced Text Gradient */
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(135deg, #c7d2fe 0%, #818cf8 30%, #a78bfa 60%, #c084fc 100%);
            background-size: 200% 200%;
            animation: gradient-shift 8s ease infinite;
        }

        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Enhanced Typography Hero */
        .hero-title {
            font-size: clamp(3.5rem, 10vw, 8rem);
            line-height: 1;
            letter-spacing: -0.04em;
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #4f46e5, #6366f1);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #6366f1, #818cf8);
        }

        /* Additional Tech Effects */
        @keyframes glitch {
            0% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(-2px, -2px); }
            60% { transform: translate(2px, 2px); }
            80% { transform: translate(2px, -2px); }
            100% { transform: translate(0); }
        }

        .tech-glitch {
            animation: glitch 0.3s ease-in-out infinite;
        }

        /* Scanline Effect */
        .scanlines {
            position: relative;
        }

        .scanlines::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: repeating-linear-gradient(
                0deg,
                rgba(0, 191, 255, 0.03),
                rgba(0, 191, 255, 0.03) 1px,
                transparent 1px,
                transparent 2px
            );
            pointer-events: none;
            z-index: 1000;
        }

        /* Matrix Rain Effect */
        .matrix-rain {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -99;
        }

        .matrix-char {
            position: absolute;
            color: rgba(0, 255, 0, 0.8);
            font-family: 'Courier New', monospace;
            font-size: 14px;
            animation: matrix-fall 10s linear infinite;
        }

        @keyframes matrix-fall {
            0% {
                transform: translateY(-100vh);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(100vh);
                opacity: 0;
            }
        }

        /* Enhanced Circuit Connections */
        .circuit-connection {
            position: absolute;
            background: rgba(0, 191, 255, 0.5);
            height: 1px;
            transform-origin: left center;
            animation: connection-pulse 3s ease-in-out infinite;
        }

        @keyframes connection-pulse {
            0%, 100% {
                opacity: 0.3;
                box-shadow: 0 0 5px rgba(0, 191, 255, 0.3);
            }
            50% {
                opacity: 1;
                box-shadow: 0 0 15px rgba(0, 191, 255, 0.8);
            }
        }

        /* PDF Popup Modal Styles */
        .pdf-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .pdf-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .pdf-modal-content {
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 24px;
            padding: 0;
            max-width: 98vw;
            max-height: 98vh;
            width: min(1400px, 98vw);
            height: min(900px, 98vh);
            position: relative;
            overflow: hidden;
            transform: scale(0.8) translateY(50px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.8),
                0 0 0 1px rgba(99, 102, 241, 0.1);
        }

        .pdf-modal.active .pdf-modal-content {
            transform: scale(1) translateY(0);
        }

        .pdf-modal-header {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(30, 41, 59, 0.9));
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(99, 102, 241, 0.2);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 60px;
        }

        .pdf-modal-title {
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .pdf-modal-close {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ef4444;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .pdf-modal-close:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.5);
            transform: scale(1.05);
        }

        .pdf-modal-body {
            height: calc(100% - 70px);
            position: relative;
            background: #ffffff;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pdf-viewer {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 0 0 24px 24px;
            background: #ffffff;
            object-fit: contain;
        }

        .pdf-viewer-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border-radius: 0 0 24px 24px;
            position: relative;
            overflow: auto;
        }

        .pdf-viewer-wrapper {
            width: 100%;
            height: 100%;
            overflow: auto;
            position: relative;
            background: #ffffff;
            border-radius: 0 0 24px 24px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 10px;
            box-sizing: border-box;
        }

        .pdf-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #94a3b8;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .pdf-loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(99, 102, 241, 0.2);
            border-top: 3px solid #6366f1;
            border-radius: 50%;
            animation: pdf-spin 1s linear infinite;
        }

        @keyframes pdf-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .pdf-error {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #ef4444;
        }

        .pdf-actions {
            position: absolute;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 12px;
        }

        .pdf-action-btn {
            background: rgba(99, 102, 241, 0.9);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 12px;
            padding: 12px 16px;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
            backdrop-filter: blur(10px);
        }

        .pdf-action-btn:hover {
            background: rgba(99, 102, 241, 1);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .pdf-modal-content {
                width: 95vw;
                height: 90vh;
                border-radius: 16px;
                margin: 2.5vh auto;
            }
            
            .pdf-modal-header {
                padding: 16px 20px;
            }
            
            .pdf-modal-title {
                font-size: 16px;
            }
            
            .pdf-modal-body {
                height: calc(100% - 70px);
            }
            
            .pdf-actions {
                bottom: 16px;
                right: 16px;
                flex-direction: column;
                gap: 8px;
            }

            .pdf-action-btn {
                padding: 10px 14px;
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .pdf-modal-content {
                width: 98vw;
                height: 95vh;
                border-radius: 12px;
                margin: 2.5vh auto;
            }

            .pdf-modal-header {
                padding: 12px 16px;
            }

            .pdf-modal-title {
                font-size: 14px;
            }

            .pdf-actions {
                bottom: 12px;
                right: 12px;
            }
        }

        /* Improved PDF display */
        .pdf-viewer-wrapper {
            position: relative;
            overflow: auto;
        }

        .pdf-viewer-wrapper::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .pdf-viewer-wrapper::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .pdf-viewer-wrapper::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .pdf-viewer-wrapper::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Holographic UI Elements */
        .holographic-border {
            position: relative;
            border: 1px solid rgba(0, 191, 255, 0.3);
        }

        .holographic-border::before {
            content: "";
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(
                45deg,
                rgba(0, 191, 255, 0.5),
                rgba(0, 255, 127, 0.3),
                rgba(255, 0, 255, 0.3),
                rgba(0, 191, 255, 0.5)
            );
            background-size: 400% 400%;
            animation: holographic-glow 4s ease-in-out infinite;
            z-index: -1;
            border-radius: inherit;
        }

        @keyframes holographic-glow {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }
    </style>
</head>
<body class="min-h-screen antialiased selection:bg-brand-500/30 selection:text-brand-400 scanlines">
    <!-- Animated Tech Background -->
    <div class="tech-bg">
        <!-- Matrix Rain Effect -->
        <div class="matrix-rain" id="matrixRain"></div>
        
        <!-- Circuit Pattern Background -->
        <div class="circuit-pattern"></div>
        
        <!-- Dynamic Circuit Lines -->
        <div class="circuit-line horizontal" style="top: 15%; animation-delay: 0s;"></div>
        <div class="circuit-line horizontal" style="top: 35%; animation-delay: 2s;"></div>
        <div class="circuit-line horizontal" style="top: 65%; animation-delay: 4s;"></div>
        <div class="circuit-line horizontal" style="top: 85%; animation-delay: 1s;"></div>
        
        <div class="circuit-line vertical" style="left: 20%; animation-delay: 1s;"></div>
        <div class="circuit-line vertical" style="left: 45%; animation-delay: 3s;"></div>
        <div class="circuit-line vertical" style="left: 70%; animation-delay: 5s;"></div>
        <div class="circuit-line vertical" style="left: 90%; animation-delay: 2s;"></div>
        
        <!-- Circuit Nodes -->
        <div class="circuit-node" style="top: 15%; left: 20%; animation-delay: 0.5s;"></div>
        <div class="circuit-node" style="top: 35%; left: 45%; animation-delay: 1.5s;"></div>
        <div class="circuit-node" style="top: 65%; left: 70%; animation-delay: 2.5s;"></div>
        <div class="circuit-node" style="top: 85%; left: 90%; animation-delay: 3.5s;"></div>
        <div class="circuit-node" style="top: 25%; left: 80%; animation-delay: 1s;"></div>
        <div class="circuit-node" style="top: 55%; left: 25%; animation-delay: 2s;"></div>
        <div class="circuit-node" style="top: 75%; left: 60%; animation-delay: 3s;"></div>
        
        <!-- Large Circuit Elements -->
        <div class="large-circuit" style="top: 10%; right: 10%; width: 300px; height: 300px; animation-duration: 45s;"></div>
        <div class="large-circuit" style="bottom: 10%; left: 10%; width: 200px; height: 200px; animation-duration: 35s; animation-direction: reverse;"></div>
        
        <!-- Data Particles -->
        <div class="data-particle" style="top: 20%; left: 10%; animation-delay: 0s;"></div>
        <div class="data-particle" style="top: 40%; left: 20%; animation-delay: 1s;"></div>
        <div class="data-particle" style="top: 60%; left: 30%; animation-delay: 2s;"></div>
        <div class="data-particle" style="top: 80%; left: 40%; animation-delay: 3s;"></div>
        
        <!-- Tech Arrows -->
        <div class="tech-arrow right" style="top: 30%; left: 15%; animation-delay: 0s;"></div>
        <div class="tech-arrow right" style="top: 50%; left: 35%; animation-delay: 1s;"></div>
        <div class="tech-arrow right" style="top: 70%; left: 55%; animation-delay: 2s;"></div>
        
        <!-- Hexagonal Patterns -->
        <div class="hex-pattern" style="top: 20%; right: 20%; animation-delay: 0s;"></div>
        <div class="hex-pattern" style="top: 60%; right: 40%; animation-delay: 2s;"></div>
        <div class="hex-pattern" style="bottom: 30%; left: 30%; animation-delay: 4s;"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="#home" class="font-heading font-bold text-2xl tracking-tighter text-white group relative">
                    <span class="relative z-10">Nel<span class="text-brand-500 group-hover:text-brand-400 transition-colors">.</span></span>
                    <div class="absolute inset-0 bg-brand-500/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </a>
                <div class="hidden md:flex items-center space-x-1 bg-white/5 backdrop-blur-sm rounded-full px-2 py-2 border border-white/10">
                    <a href="#home" class="text-sm font-medium text-gray-400 hover:text-white hover:bg-white/10 px-4 py-2 rounded-full transition-all">Home</a>
                    <a href="#about" class="text-sm font-medium text-gray-400 hover:text-white hover:bg-white/10 px-4 py-2 rounded-full transition-all">About</a>
                    <a href="#skills" class="text-sm font-medium text-gray-400 hover:text-white hover:bg-white/10 px-4 py-2 rounded-full transition-all">Skills</a>
                    <?php if (!empty($projects)): ?>
                    <a href="#projects" class="text-sm font-medium text-gray-400 hover:text-white hover:bg-white/10 px-4 py-2 rounded-full transition-all">Projects</a>
                    <?php endif; ?>
                </div>
                <div class="flex items-center gap-3">
                    <?php if ($resumePdfUrl && $resumePdfUrl !== '#'): ?>
                    <a href="<?= htmlspecialchars($resumePdfUrl) ?>" target="_blank" class="hidden md:flex items-center gap-2 text-sm font-semibold bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 text-white px-6 py-2.5 rounded-full transition-all shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 hover:scale-105 transform duration-300">
                        <i class="ph-bold ph-download-simple text-base"></i>
                        <span>Resume</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section id="home" class="relative min-h-screen flex items-center pt-20 overflow-hidden">
            <!-- Enhanced Animated Background -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
                <!-- Main gradient blobs -->
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand-600/30 rounded-full mix-blend-screen filter blur-[120px] opacity-70 animate-blob"></div>
                <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-purple-600/30 rounded-full mix-blend-screen filter blur-[120px] opacity-70 animate-blob" style="animation-delay: 2s;"></div>
                <div class="absolute bottom-1/4 left-1/3 w-96 h-96 bg-pink-600/20 rounded-full mix-blend-screen filter blur-[120px] opacity-70 animate-blob" style="animation-delay: 4s;"></div>
                
                <!-- Grid overlay -->
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:4rem_4rem] opacity-20"></div>
            </div>

            <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Column: Hero Content -->
                    <div class="opacity-0 animate-fade-in-up">
                        <!-- Top Badge -->
                        <div class="flex items-center gap-4 mb-8">
                            <div class="flex items-center gap-3 bg-gradient-to-r from-white/10 to-white/5 backdrop-blur-sm border border-white/10 rounded-full px-5 py-2.5 group hover:border-brand-400/50 transition-all duration-300">
                                <div class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-brand-500"></span>
                                </div>
                                <span class="text-white font-medium tracking-wider uppercase text-xs"><?= htmlspecialchars($heroBadgeText) ?></span>
                            </div>
                        </div>

                        <!-- Main Heading with Enhanced Typography -->
                        <h1 class="hero-title mb-6 relative" style="font-family: '<?= htmlspecialchars($heroTitleFontFamily) ?>', sans-serif; font-size: <?= htmlspecialchars($heroTitleFontSize) ?>px; font-weight: <?= htmlspecialchars($heroTitleFontWeight) ?>; line-height: 1.1;">
                            <span class="block text-white"><?= htmlspecialchars($heroTitleLine1) ?></span>
                            <span class="block text-gradient relative">
                                <?= htmlspecialchars($heroTitleLine2) ?>
                                <span class="absolute -bottom-2 left-0 w-32 h-1 bg-gradient-to-r from-brand-400 via-purple-400 to-pink-400 rounded-full"></span>
                            </span>
                        </h1>

                        <!-- Subtitle with Better Formatting -->
                        <p class="text-xl md:text-2xl text-gray-300 font-light leading-relaxed mb-12">
                            <?= $heroSubtitle ?>
                        </p>

                        <!-- Enhanced CTA Section -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                            <!-- Primary CTA -->
                            <a href="<?= htmlspecialchars($heroPrimaryBtnLink) ?>" class="group relative inline-flex items-center gap-3 bg-gradient-to-r from-brand-600 via-brand-500 to-purple-600 text-white px-8 py-4 rounded-full font-semibold transition-all duration-300 shadow-[0_0_40px_rgba(99,102,241,0.4)] hover:shadow-[0_0_60px_rgba(99,102,241,0.6)] hover:scale-105 transform">
                                <span><?= htmlspecialchars($heroPrimaryBtnText) ?></span>
                                <i class="ph-bold ph-arrow-down-right text-lg group-hover:translate-x-1 group-hover:translate-y-1 transition-transform"></i>
                                <div class="absolute inset-0 bg-gradient-to-r from-brand-500 to-purple-500 rounded-full opacity-0 group-hover:opacity-100 blur-xl transition-opacity"></div>
                            </a>

                            <!-- Secondary CTA -->
                            <a href="<?= htmlspecialchars($heroSecondaryBtnLink) ?>" class="group inline-flex items-center gap-3 bg-white/5 backdrop-blur-sm hover:bg-white/10 text-white px-8 py-4 rounded-full font-semibold transition-all border border-white/10 hover:border-white/20">
                                <span><?= htmlspecialchars($heroSecondaryBtnText) ?></span>
                                <i class="ph-bold ph-arrow-right text-lg group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>

                        <!-- Social Links with Enhanced Design -->
                        <?php
                        // Fetch social links
                        try {
                            $checkTable = $pdo->query("SHOW TABLES LIKE 'hero_social_links'");
                            if ($checkTable->rowCount() > 0) {
                                $socialLinksQuery = $pdo->query("SELECT * FROM hero_social_links WHERE profile_url != '' ORDER BY sort_order ASC, id ASC");
                                $socialLinks = $socialLinksQuery->fetchAll();
                            } else {
                                $socialLinks = [];
                            }
                        } catch (Exception $e) {
                            $socialLinks = [];
                        }
                        ?>
                        
                        <?php if (!empty($socialLinks)): ?>
                            <div class="flex items-center gap-4 mt-12 pt-8 border-t border-white/10">
                                <span class="text-sm text-gray-500 font-medium uppercase tracking-wider">Connect</span>
                                <div class="flex items-center gap-3">
                                    <?php foreach ($socialLinks as $social): ?>
                                        <a href="<?= htmlspecialchars($social['profile_url']) ?>" target="_blank" rel="noopener noreferrer" class="group relative p-3 glass-card rounded-xl hover:text-white hover:border-brand-500/50 transition-all overflow-hidden">
                                            <div class="absolute inset-0 bg-gradient-to-br from-brand-500/0 to-brand-500/0 group-hover:from-brand-500/20 group-hover:to-purple-500/20 transition-all"></div>
                                            <i class="ph-fill <?= htmlspecialchars($social['platform_icon']) ?> text-xl group-hover:scale-110 transition-transform relative z-10"></i>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Right Column: Tech Stack -->
                    <?php
                    try {
                        // Check if table exists
                        $checkTable = $pdo->query("SHOW TABLES LIKE 'hero_tech_stack'");
                        if ($checkTable->rowCount() > 0) {
                            $techStackQuery = $pdo->query("SELECT * FROM hero_tech_stack ORDER BY sort_order ASC, id ASC");
                            $techStackItems = $techStackQuery->fetchAll();
                        } else {
                            $techStackItems = [];
                        }
                    } catch (Exception $e) {
                        $techStackItems = [];
                    }
                    ?>
                    
                    <?php if (!empty($techStackItems)): ?>
                        <div class="hidden lg:flex items-center justify-center opacity-0 animate-fade-in-up" style="animation-delay: 0.3s;">
                            <div class="relative">
                                <!-- Central Glow -->
                                <div class="absolute inset-0 bg-gradient-to-br from-brand-500/20 via-purple-500/20 to-pink-500/20 rounded-full blur-3xl"></div>
                                
                                <!-- Tech Stack Grid -->
                                <div class="relative glass-card rounded-3xl p-8 backdrop-blur-xl border-2 border-white/10">
                                    <h3 class="text-center font-heading font-bold text-xl text-white mb-6 tracking-wider">
                                        Tech <span class="text-gradient">Stack</span>
                                    </h3>
                                    
                                    <div class="grid grid-cols-3 gap-6">
                                        <?php foreach ($techStackItems as $tech): ?>
                                            <div class="group relative">
                                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-800/80 to-slate-900/80 border border-slate-700/50 flex items-center justify-center p-4 transition-all duration-300 hover:scale-110 hover:border-brand-400/50 hover:shadow-[0_0_30px_rgba(99,102,241,0.3)]">
                                                    <img src="/nel-portfolio/<?= htmlspecialchars($tech['icon_path']) ?>" 
                                                         alt="<?= htmlspecialchars($tech['tech_name']) ?>" 
                                                         class="w-full h-full object-contain filter drop-shadow-lg">
                                                </div>
                                                <!-- Tooltip -->
                                                <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                                    <span class="text-xs font-medium text-white bg-slate-900 px-3 py-1 rounded-full border border-slate-700">
                                                        <?= htmlspecialchars($tech['tech_name']) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-1/4 right-12 w-72 h-72 border border-white/5 rounded-full hidden xl:block"></div>
            <div class="absolute bottom-1/4 left-12 w-96 h-96 border border-white/5 rounded-full hidden xl:block"></div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-32 relative">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-20">
                    <div class="inline-flex items-center gap-3 bg-gradient-to-r from-white/10 to-white/5 backdrop-blur-sm border border-white/10 rounded-full px-5 py-2 mb-6">
                        <i class="ph-bold ph-user text-brand-400"></i>
                        <span class="text-gray-400 font-medium tracking-wider uppercase text-xs">Get to Know Me</span>
                    </div>
                    <h2 class="font-heading text-5xl md:text-6xl font-bold text-white mb-4">
                        About <span class="text-gradient">Me</span>
                    </h2>
                    <p class="text-gray-400 text-lg max-w-2xl mx-auto">Building digital solutions with passion and precision</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                    <!-- Left Column - Profile Card -->
                    <div class="lg:col-span-5">
                        <div class="glass-card rounded-3xl p-8 sticky top-24">
                            <!-- Profile Image/Avatar Area -->
                            <div class="aspect-[4/5] rounded-2xl overflow-hidden relative group mb-6">
                                <?php if ($aboutProfilePhoto): ?>
                                    <!-- Uploaded Photo with 3D Effects -->
                                    <div class="absolute inset-0">
                                        <!-- Actual Photo -->
                                        <img src="<?= htmlspecialchars($aboutProfilePhoto) ?>" alt="Profile" class="w-full h-full object-cover">
                                        
                                        <!-- Gradient Overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 via-cyan-500/5 to-pink-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                        
                                        <!-- Glassmorphic Overlay on Hover -->
                                        <div class="absolute inset-0 bg-slate-950/30 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                                    </div>
                                <?php else: ?>
                                    <!-- Default Identity Design -->
                                    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-slate-900 to-black flex items-center justify-center">
                                        <!-- Large Background Letter -->
                                        <div class="font-heading font-black text-[16rem] leading-none text-white/5 group-hover:text-white/10 transition-colors duration-500 select-none absolute -right-10 -bottom-10 rotate-12">
                                            N
                                        </div>
                                        
                                        <!-- Center Content -->
                                        <div class="relative z-10 text-center px-8">
                                            <div class="w-24 h-24 mx-auto rounded-full border-2 border-brand-500/30 flex items-center justify-center mb-6 bg-brand-500/10 backdrop-blur-sm group-hover:border-brand-400/50 transition-all duration-300">
                                                <i class="ph-bold ph-fingerprint text-5xl text-brand-400"></i>
                                            </div>
                                            <h3 class="font-heading text-3xl font-bold text-white mb-3">Identity</h3>
                                            <p class="text-gray-400 text-sm leading-relaxed">Bridging the gap between design and engineering</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Animated Border -->
                                <div class="absolute inset-0 border-2 border-white/10 rounded-2xl group-hover:border-brand-500/30 transition-all duration-500"></div>
                                
                                <!-- Corner Accents -->
                                <div class="absolute top-4 left-4 w-8 h-8 border-l-2 border-t-2 border-cyan-400/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="absolute bottom-4 right-4 w-8 h-8 border-r-2 border-b-2 border-violet-400/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                
                                <!-- 3D Shadow Effect -->
                                <div class="absolute -inset-0.5 bg-gradient-to-br from-cyan-500 via-violet-500 to-pink-500 rounded-2xl blur-xl opacity-20 group-hover:opacity-40 transition-opacity duration-500 -z-10"></div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="grid grid-cols-3 gap-4 pt-6 border-t border-white/10">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-white mb-1"><?= htmlspecialchars($aboutYearsExperience) ?></div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wider">Years</div>
                                </div>
                                <div class="text-center border-x border-white/10">
                                    <div class="text-2xl font-bold text-white mb-1"><?= htmlspecialchars($aboutTotalProjects) ?></div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wider">Projects</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-white mb-1"><?= htmlspecialchars($aboutTotalClients) ?></div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wider">Clients</div>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div class="mt-6 pt-6 border-t border-white/10 space-y-3">
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="w-10 h-10 rounded-lg bg-brand-500/10 flex items-center justify-center">
                                        <i class="ph-bold ph-envelope text-brand-400"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Email</div>
                                        <div class="text-white font-medium"><?= htmlspecialchars($aboutEmail) ?></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="w-10 h-10 rounded-lg bg-brand-500/10 flex items-center justify-center">
                                        <i class="ph-bold ph-map-pin text-brand-400"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Location</div>
                                        <div class="text-white font-medium"><?= htmlspecialchars($aboutLocation) ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Bio & Certifications -->
                    <div class="lg:col-span-7 space-y-8">
                        <!-- Bio Section -->
                        <div class="glass-card rounded-3xl p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-500 to-purple-500 flex items-center justify-center">
                                    <i class="ph-bold ph-text-aa text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-heading text-2xl font-bold text-white">My Story</h3>
                                    <p class="text-sm text-gray-500">Professional Background</p>
                                </div>
                            </div>
                            
                            <div class="prose prose-invert prose-lg max-w-none">
                                <div class="text-gray-300 leading-relaxed space-y-4">
                                    <?= nl2br(htmlspecialchars($resumeSummary)) ?>
                                </div>
                            </div>

                            <!-- Skills Tags -->
                            <div class="mt-8 pt-8 border-t border-white/10">
                                <h4 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Core Expertise</h4>
                                <div class="flex flex-wrap gap-2">
                                    <?php 
                                    $expertiseTags = array_filter(array_map('trim', explode(',', $aboutCoreExpertise)));
                                    $tagColors = [
                                        'bg-brand-500/10 border-brand-500/30 text-brand-300',
                                        'bg-purple-500/10 border-purple-500/30 text-purple-300',
                                        'bg-pink-500/10 border-pink-500/30 text-pink-300',
                                        'bg-cyan-500/10 border-cyan-500/30 text-cyan-300',
                                    ];
                                    foreach ($expertiseTags as $index => $tag): 
                                        $colorClass = $tagColors[$index % count($tagColors)];
                                    ?>
                                    <span class="px-4 py-2 <?= $colorClass ?> border rounded-full text-sm font-medium"><?= htmlspecialchars($tag) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Certifications Section -->
                        <?php if (!empty($certs)): ?>
                        <div class="glass-card rounded-3xl p-8">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-orange-500 flex items-center justify-center">
                                        <i class="ph-bold ph-certificate text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-heading text-2xl font-bold text-white">Certifications</h3>
                                        <p class="text-sm text-gray-500">Professional Credentials</p>
                                    </div>
                                </div>
                                <div class="text-sm text-brand-400 font-semibold"><?= count($certs) ?> Total</div>
                            </div>

                            <div class="space-y-4">
                                <?php foreach (array_slice($certs, 0, 4) as $index => $cert): ?>
                                <div class="group relative bg-white/5 hover:bg-white/10 border border-white/10 hover:border-brand-500/30 rounded-2xl p-5 transition-all duration-300 <?php echo $cert['certificate_file'] ? 'cursor-pointer' : ''; ?>" 
                                     <?php if ($cert['certificate_file']): ?>onclick="openPdfModal('<?= htmlspecialchars($cert['certificate_file']) ?>', '<?= htmlspecialchars($cert['name']) ?>')"<?php endif; ?>>
                                    <!-- Cert Number Badge -->
                                    <div class="absolute -left-3 top-6 w-8 h-8 rounded-full bg-gradient-to-br from-brand-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold shadow-lg">
                                        <?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>
                                    </div>

                                    <div class="flex items-start gap-4 ml-6">
                                        <!-- Icon -->
                                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-brand-500/20 to-purple-500/20 flex items-center justify-center flex-shrink-0 border border-brand-500/30">
                                            <i class="ph-fill ph-seal-check text-3xl text-brand-400"></i>
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-white text-lg mb-1 group-hover:text-brand-400 transition-colors">
                                                <?= htmlspecialchars($cert['name']) ?>
                                            </h4>
                                            <p class="text-sm text-gray-400 mb-2"><?= htmlspecialchars($cert['issuing_organization']) ?></p>
                                            <?php if ($cert['issue_date']): ?>
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <i class="ph-bold ph-calendar-blank"></i>
                                                <span><?= date('F Y', strtotime($cert['issue_date'])) ?></span>
                                            </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Action Button -->
                                        <?php if ($cert['certificate_file']): ?>
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg border border-white/10 hover:border-brand-500/50 hover:bg-brand-500/10 flex items-center justify-center text-gray-400 hover:text-brand-400 transition-all group-hover:scale-110 relative">
                                            <i class="ph-bold ph-file-pdf text-lg"></i>
                                            <!-- Click indicator -->
                                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full flex items-center justify-center">
                                                <i class="ph-bold ph-eye text-[8px] text-white"></i>
                                            </div>
                                        </div>
                                        <?php else: ?>
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg border border-white/10 flex items-center justify-center text-gray-600">
                                            <i class="ph-bold ph-file-x text-lg"></i>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Hover overlay for clickable certificates -->
                                    <?php if ($cert['certificate_file']): ?>
                                    <div class="absolute inset-0 bg-gradient-to-r from-brand-500/0 to-brand-500/0 group-hover:from-brand-500/5 group-hover:to-purple-500/5 rounded-2xl transition-all duration-300 pointer-events-none"></div>
                                    <!-- Click to view indicator -->
                                    <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="bg-brand-500/20 border border-brand-400/50 rounded-lg px-2 py-1 flex items-center gap-1">
                                            <i class="ph-bold ph-mouse-left-click text-xs text-brand-300"></i>
                                            <span class="text-xs text-brand-300 font-medium">Click to view</span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <?php if (count($certs) > 4): ?>
                            <div class="mt-6 text-center">
                                <button class="text-brand-400 hover:text-brand-300 font-semibold text-sm flex items-center gap-2 mx-auto group">
                                    <span>View All Certifications</span>
                                    <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Call to Action -->
                        <div class="glass-card rounded-3xl p-8 bg-gradient-to-br from-brand-500/10 to-purple-500/10 border-brand-500/30">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                                <div>
                                    <h3 class="font-heading text-2xl font-bold text-white mb-2">Let's Work Together</h3>
                                    <p class="text-gray-400">Ready to bring your project to life?</p>
                                </div>
                                <a href="#contact" class="group inline-flex items-center gap-3 bg-gradient-to-r from-brand-600 to-purple-600 text-white px-8 py-4 rounded-full font-semibold transition-all duration-300 shadow-[0_0_30px_rgba(99,102,241,0.3)] hover:shadow-[0_0_50px_rgba(99,102,241,0.5)] hover:scale-105 transform whitespace-nowrap">
                                    <span>Get In Touch</span>
                                    <i class="ph-bold ph-paper-plane-tilt text-lg group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decorative Background Elements -->
            <div class="absolute top-1/4 right-0 w-96 h-96 bg-brand-500/5 rounded-full blur-[150px] -z-10"></div>
            <div class="absolute bottom-1/4 left-0 w-96 h-96 bg-purple-500/5 rounded-full blur-[150px] -z-10"></div>
        </section>

        <!-- Skills Section -->
        <section id="skills" class="py-32 relative overflow-hidden">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-b from-gray-950 via-slate-900/50 to-gray-950"></div>
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand-500/10 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
            
            <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
                <!-- Section Header -->
                <div class="text-center mb-20">
                    <div class="inline-flex items-center gap-3 bg-gradient-to-r from-white/5 to-white/0 backdrop-blur-sm border border-white/10 rounded-full px-6 py-2.5 mb-6">
                        <i class="ph-bold ph-code text-brand-400"></i>
                        <span class="text-gray-400 font-medium tracking-wider uppercase text-xs"><?= htmlspecialchars($skillsBadgeText) ?></span>
                    </div>
                    <h2 class="font-heading text-5xl md:text-6xl font-black text-white mb-6">
                        <?php 
                        $titleParts = explode(' ', $skillsTitle);
                        if (count($titleParts) > 1 && end($titleParts) === 'Arsenal') {
                            $lastWord = array_pop($titleParts);
                            echo htmlspecialchars(implode(' ', $titleParts)) . ' <span class="text-gradient bg-clip-text text-transparent bg-gradient-to-r from-brand-400 via-purple-400 to-pink-400">' . htmlspecialchars($lastWord) . '</span>';
                        } else {
                            echo '<span class="text-gradient bg-clip-text text-transparent bg-gradient-to-r from-brand-400 via-purple-400 to-pink-400">' . htmlspecialchars($skillsTitle) . '</span>';
                        }
                        ?>
                    </h2>
                    <p class="text-xl text-gray-400 max-w-3xl mx-auto leading-relaxed">
                        <?= htmlspecialchars($skillsSubtitle) ?>
                    </p>
                </div>

                <?php if (empty($skills)): ?>
                    <div class="text-center py-20">
                        <div class="inline-flex flex-col items-center gap-4 glass-card p-12 rounded-3xl">
                            <i class="ph-bold ph-code text-6xl text-gray-700"></i>
                            <p class="text-gray-500 text-lg">No skills added yet.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Skills Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        <?php foreach ($skills as $category => $catSkills): ?>
                            <div class="group relative">
                                <!-- Card -->
                                <div class="relative glass-card rounded-3xl p-8 h-full border-2 border-white/5 hover:border-brand-400/30 transition-all duration-500 overflow-hidden">
                                    <!-- Animated Background Gradient -->
                                    <div class="absolute inset-0 bg-gradient-to-br from-brand-500/0 via-purple-500/0 to-pink-500/0 group-hover:from-brand-500/5 group-hover:via-purple-500/5 group-hover:to-pink-500/5 transition-all duration-700"></div>
                                    
                                    <!-- Glowing Orb Effect -->
                                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-gradient-to-br from-brand-400/20 to-purple-400/20 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                                    
                                    <!-- Content -->
                                    <div class="relative z-10">
                                        <!-- Category Header -->
                                        <div class="flex items-center justify-between mb-8">
                                            <div class="flex items-center gap-4">
                                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-500/20 to-purple-500/20 border border-brand-400/30 flex items-center justify-center backdrop-blur-sm group-hover:scale-110 transition-transform duration-300">
                                                    <?php 
                                                        $icon = 'ph-code';
                                                        $iconColor = 'text-brand-400';
                                                        if (stripos($category, 'front') !== false) { $icon = 'ph-layout'; $iconColor = 'text-cyan-400'; }
                                                        if (stripos($category, 'back') !== false) { $icon = 'ph-database'; $iconColor = 'text-green-400'; }
                                                        if (stripos($category, 'tool') !== false) { $icon = 'ph-wrench'; $iconColor = 'text-yellow-400'; }
                                                        if (stripos($category, 'design') !== false) { $icon = 'ph-palette'; $iconColor = 'text-pink-400'; }
                                                        if (stripos($category, 'devops') !== false) { $icon = 'ph-git-branch'; $iconColor = 'text-orange-400'; }
                                                    ?>
                                                    <i class="ph-bold <?= $icon ?> text-2xl <?= $iconColor ?>"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-heading text-xl font-bold text-white mb-1">
                                                        <?= htmlspecialchars($category ?: 'Other Skills') ?>
                                                    </h3>
                                                    <p class="text-xs text-gray-500 font-medium"><?= count($catSkills) ?> Skills</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Skills List -->
                                        <div class="space-y-4">
                                            <?php foreach ($catSkills as $skill): ?>
                                                <div class="group/skill relative">
                                                    <!-- Modern Skill Card -->
                                                    <div class="relative bg-gradient-to-r from-slate-900/80 to-slate-800/60 border border-slate-700/50 rounded-2xl p-5 backdrop-blur-sm hover:border-brand-400/40 hover:shadow-lg hover:shadow-brand-500/10 transition-all duration-300 overflow-hidden">
                                                        <!-- Micro Gradient Background -->
                                                        <div class="absolute inset-0 bg-gradient-to-br from-brand-500/0 via-purple-500/0 to-pink-500/0 group-hover/skill:from-brand-500/5 group-hover/skill:via-purple-500/5 group-hover/skill:to-pink-500/5 transition-all duration-500"></div>
                                                        
                                                        <!-- Left Side: Icon + Name -->
                                                        <div class="relative z-10 flex items-center justify-between">
                                                            <div class="flex items-center gap-4">
                                                                <!-- Technology Icon Container -->
                                                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-700/50 to-slate-800/50 border border-slate-600/50 flex items-center justify-center group-hover/skill:scale-110 group-hover/skill:border-brand-400/50 transition-all duration-300">
                                                                    <?php
                                                                        // Technology-specific icons
                                                                        $techIcon = 'ph-code';
                                                                        $techIconColor = 'text-brand-400';
                                                                        $skillLower = strtolower($skill['name']);
                                                                        
                                                                        // Frontend Technologies
                                                                        if (stripos($skillLower, 'react') !== false) { $techIcon = 'ph-atom'; $techIconColor = 'text-cyan-400'; }
                                                                        elseif (stripos($skillLower, 'vue') !== false) { $techIcon = 'ph-triangle'; $techIconColor = 'text-green-400'; }
                                                                        elseif (stripos($skillLower, 'angular') !== false) { $techIcon = 'ph-selection-all'; $techIconColor = 'text-red-400'; }
                                                                        elseif (stripos($skillLower, 'javascript') !== false || stripos($skillLower, 'js') !== false) { $techIcon = 'ph-function'; $techIconColor = 'text-yellow-400'; }
                                                                        elseif (stripos($skillLower, 'typescript') !== false || stripos($skillLower, 'ts') !== false) { $techIcon = 'ph-brackets-curly'; $techIconColor = 'text-blue-400'; }
                                                                        elseif (stripos($skillLower, 'html') !== false) { $techIcon = 'ph-file-html'; $techIconColor = 'text-orange-400'; }
                                                                        elseif (stripos($skillLower, 'css') !== false || stripos($skillLower, 'sass') !== false) { $techIcon = 'ph-paint-brush'; $techIconColor = 'text-pink-400'; }
                                                                        elseif (stripos($skillLower, 'tailwind') !== false) { $techIcon = 'ph-wind'; $techIconColor = 'text-teal-400'; }
                                                                        
                                                                        // Backend Technologies
                                                                        elseif (stripos($skillLower, 'node') !== false) { $techIcon = 'ph-tree-structure'; $techIconColor = 'text-green-400'; }
                                                                        elseif (stripos($skillLower, 'python') !== false) { $techIcon = 'ph-snake'; $techIconColor = 'text-yellow-400'; }
                                                                        elseif (stripos($skillLower, 'php') !== false) { $techIcon = 'ph-code-simple'; $techIconColor = 'text-purple-400'; }
                                                                        elseif (stripos($skillLower, 'java') !== false) { $techIcon = 'ph-coffee'; $techIconColor = 'text-amber-400'; }
                                                                        
                                                                        // Databases
                                                                        elseif (stripos($skillLower, 'mysql') !== false || stripos($skillLower, 'sql') !== false) { $techIcon = 'ph-database'; $techIconColor = 'text-blue-400'; }
                                                                        elseif (stripos($skillLower, 'mongodb') !== false) { $techIcon = 'ph-leaf'; $techIconColor = 'text-green-400'; }
                                                                        
                                                                        // Tools & DevOps
                                                                        elseif (stripos($skillLower, 'git') !== false) { $techIcon = 'ph-git-branch'; $techIconColor = 'text-orange-400'; }
                                                                        elseif (stripos($skillLower, 'docker') !== false) { $techIcon = 'ph-package'; $techIconColor = 'text-blue-400'; }
                                                                        elseif (stripos($skillLower, 'aws') !== false) { $techIcon = 'ph-cloud'; $techIconColor = 'text-orange-400'; }
                                                                        elseif (stripos($skillLower, 'figma') !== false) { $techIcon = 'ph-figma-logo'; $techIconColor = 'text-purple-400'; }
                                                                        elseif (stripos($skillLower, 'photoshop') !== false) { $techIcon = 'ph-image-square'; $techIconColor = 'text-blue-400'; }
                                                                    ?>
                                                                    <i class="ph-bold <?= $techIcon ?> text-xl <?= $techIconColor ?>"></i>
                                                                </div>
                                                                
                                                                <!-- Skill Info -->
                                                                <div class="flex-1">
                                                                    <h4 class="font-heading font-bold text-white text-base mb-0.5 group-hover/skill:text-brand-300 transition-colors">
                                                                        <?= htmlspecialchars($skill['name']) ?>
                                                                    </h4>
                                                                    <?php
                                                                        $percentage = 0;
                                                                        switch ($skill['proficiency_level']) {
                                                                            case 'Expert': $percentage = 95; break;
                                                                            case 'Advanced': $percentage = 75; break;
                                                                            case 'Intermediate': $percentage = 55; break;
                                                                            case 'Beginner': $percentage = 30; break;
                                                                        }
                                                                    ?>
                                                                    <p class="text-xs text-gray-500 font-medium"><?= $percentage ?>% Proficiency</p>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Right Side: Level Badge -->
                                                            <div class="flex items-center gap-3">
                                                                <span class="text-xs font-bold px-3 py-1.5 rounded-xl border backdrop-blur-sm transition-all duration-300 group-hover/skill:scale-105 <?php
                                                                    $levelStyles = [
                                                                        'Expert' => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/30 shadow-sm shadow-emerald-500/20',
                                                                        'Advanced' => 'bg-cyan-500/10 text-cyan-300 border-cyan-500/30 shadow-sm shadow-cyan-500/20',
                                                                        'Intermediate' => 'bg-amber-500/10 text-amber-300 border-amber-500/30 shadow-sm shadow-amber-500/20',
                                                                        'Beginner' => 'bg-slate-500/10 text-slate-300 border-slate-500/30'
                                                                    ];
                                                                    echo $levelStyles[$skill['proficiency_level']] ?? 'bg-slate-500/10 text-slate-300 border-slate-500/30';
                                                                ?>"><?= htmlspecialchars($skill['proficiency_level']) ?></span>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Bottom Progress Indicator -->
                                                        <div class="relative mt-4 h-1.5 bg-slate-800/80 rounded-full overflow-hidden border border-slate-700/30">
                                                            <div class="absolute inset-0 flex">
                                                                <div class="h-full rounded-full transition-all duration-1000 ease-out shadow-sm <?php
                                                                    $progressColors = [
                                                                        'Expert' => 'bg-gradient-to-r from-emerald-400 to-teal-400 shadow-emerald-400/50',
                                                                        'Advanced' => 'bg-gradient-to-r from-cyan-400 to-blue-400 shadow-cyan-400/50',
                                                                        'Intermediate' => 'bg-gradient-to-r from-amber-400 to-orange-400 shadow-amber-400/50',
                                                                        'Beginner' => 'bg-gradient-to-r from-slate-400 to-gray-400'
                                                                    ];
                                                                    echo $progressColors[$skill['proficiency_level']] ?? 'bg-gradient-to-r from-slate-400 to-gray-400';
                                                                ?>" style="width: <?= $percentage ?>%">
                                                                    <!-- Shimmer Effect -->
                                                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent animate-shimmer"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Bottom Accent Line -->
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-brand-400/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Stats Section -->
                    <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="glass-card rounded-2xl p-8 text-center border border-white/5 hover:border-brand-400/30 transition-all duration-300 group">
                            <div class="text-5xl font-black text-gradient bg-clip-text text-transparent bg-gradient-to-r from-brand-400 to-purple-400 mb-2">
                                <?= count($skills) ?>+
                            </div>
                            <p class="text-gray-400 font-medium">Technology Categories</p>
                        </div>
                        <div class="glass-card rounded-2xl p-8 text-center border border-white/5 hover:border-brand-400/30 transition-all duration-300 group">
                            <div class="text-5xl font-black text-gradient bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-pink-400 mb-2">
                                <?php
                                    $totalSkills = 0;
                                    foreach ($skills as $catSkills) {
                                        $totalSkills += count($catSkills);
                                    }
                                    echo $totalSkills;
                                ?>+
                            </div>
                            <p class="text-gray-400 font-medium">Technical Skills</p>
                        </div>
                        <div class="glass-card rounded-2xl p-8 text-center border border-white/5 hover:border-brand-400/30 transition-all duration-300 group">
                            <div class="text-5xl font-black text-gradient bg-clip-text text-transparent bg-gradient-to-r from-pink-400 to-brand-400 mb-2">
                                <?php
                                    $expertCount = 0;
                                    foreach ($skills as $catSkills) {
                                        foreach ($catSkills as $skill) {
                                            if ($skill['proficiency_level'] === 'Expert') $expertCount++;
                                        }
                                    }
                                    echo $expertCount;
                                ?>+
                            </div>
                            <p class="text-gray-400 font-medium">Expert Level Skills</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Custom Animations -->
            <style>
                @keyframes shimmer {
                    0% { transform: translateX(-100%); }
                    100% { transform: translateX(100%); }
                }
                .animate-shimmer {
                    animation: shimmer 2s infinite;
                }
            </style>
        </section>

        <?php if (!empty($projects)): ?>
        <!-- Projects Section (Brief) -->
        <section id="projects" class="py-32 relative overflow-hidden">
            <!-- Background Effects -->
            <div class="absolute inset-0 bg-gradient-to-b from-gray-950 via-slate-900/30 to-gray-950"></div>
            <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-purple-500/8 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-1/3 left-1/4 w-96 h-96 bg-brand-500/8 rounded-full blur-[120px] animate-pulse" style="animation-delay: 3s;"></div>
            
            <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
                <!-- Enhanced Section Header -->
                <div class="text-center mb-20">
                    <div class="inline-flex items-center gap-3 bg-gradient-to-r from-white/5 to-white/0 backdrop-blur-sm border border-white/10 rounded-full px-6 py-2.5 mb-6">
                        <i class="ph-bold ph-folder-open text-purple-400"></i>
                        <span class="text-gray-400 font-medium tracking-wider uppercase text-xs"><?= htmlspecialchars($projectsBadgeText) ?></span>
                    </div>
                    <h2 class="font-heading text-5xl md:text-6xl font-black text-white mb-6">
                        <?php 
                        $titleParts = explode(' ', $projectsTitle);
                        if (count($titleParts) > 1 && end($titleParts) === 'Works') {
                            $lastWord = array_pop($titleParts);
                            echo htmlspecialchars(implode(' ', $titleParts)) . ' <span class="text-gradient bg-clip-text text-transparent bg-gradient-to-r from-purple-400 via-pink-400 to-brand-400">' . htmlspecialchars($lastWord) . '</span>';
                        } else {
                            echo '<span class="text-gradient bg-clip-text text-transparent bg-gradient-to-r from-purple-400 via-pink-400 to-brand-400">' . htmlspecialchars($projectsTitle) . '</span>';
                        }
                        ?>
                    </h2>
                    <p class="text-xl text-gray-400 max-w-3xl mx-auto leading-relaxed">
                        <?= htmlspecialchars($projectsSubtitle) ?>
                    </p>
                </div>

                <!-- Projects Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-16">
                    <?php foreach ($projects as $index => $project): ?>
                        <div class="group relative">
                            <!-- Compact Project Card -->
                            <div class="relative glass-card rounded-2xl overflow-hidden border border-white/10 hover:border-purple-400/40 transition-all duration-300 bg-gradient-to-br from-slate-900/90 to-slate-800/70 hover:scale-[1.02]">
                                <!-- Animated Background -->
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 via-pink-500/0 to-brand-500/0 group-hover:from-purple-500/5 group-hover:via-pink-500/5 group-hover:to-brand-500/5 transition-all duration-500"></div>
                                
                                <!-- Compact Image Container -->
                                <div class="relative aspect-[4/3] overflow-hidden">
                                    <?php if ($project['image_url']): ?>
                                        <img src="<?= htmlspecialchars($project['image_url']) ?>" 
                                             alt="<?= htmlspecialchars($project['title']) ?>" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <?php else: ?>
                                        <!-- Compact Placeholder -->
                                        <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                                            <i class="ph-bold ph-monitor-play text-4xl text-slate-600"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Subtle Gradient Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                                    
                                    <!-- Compact Number Badge -->
                                    <div class="absolute top-3 left-3">
                                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-purple-500/30 to-pink-500/30 backdrop-blur-sm border border-purple-400/40 flex items-center justify-center">
                                            <span class="text-purple-300 font-bold text-sm"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Compact Action Buttons -->
                                    <div class="absolute bottom-3 left-3 right-3 flex gap-2">
                                        <?php if ($project['live_link']): ?>
                                            <a href="<?= htmlspecialchars($project['live_link']) ?>" target="_blank" rel="noopener noreferrer" 
                                               class="group/btn flex-1 bg-gradient-to-r from-purple-500/25 to-pink-500/25 hover:from-purple-500/40 hover:to-pink-500/40 backdrop-blur-md text-white px-3 py-2 rounded-xl font-medium transition-all duration-300 border border-purple-400/40 flex items-center justify-center gap-1.5 text-xs">
                                                <i class="ph-bold ph-arrow-square-out text-sm"></i>
                                                <span><?= htmlspecialchars($projectsLiveBtnText) ?></span>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($project['github_link']): ?>
                                            <a href="<?= htmlspecialchars($project['github_link']) ?>" target="_blank" rel="noopener noreferrer" 
                                               class="group/btn flex-1 bg-slate-700/40 hover:bg-slate-600/60 backdrop-blur-md text-white px-3 py-2 rounded-xl font-medium transition-all duration-300 border border-slate-600/50 flex items-center justify-center gap-1.5 text-xs">
                                                <i class="ph-fill ph-github-logo text-sm"></i>
                                                <span><?= htmlspecialchars($projectsCodeBtnText) ?></span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Compact Info Section -->
                                <div class="relative z-10 p-5">
                                    <!-- Project Title -->
                                    <h3 class="font-heading text-lg font-bold text-white mb-2 group-hover:text-purple-300 transition-colors line-clamp-1">
                                        <?= htmlspecialchars($project['title']) ?>
                                    </h3>
                                    
                                    <!-- Compact Description -->
                                    <p class="text-gray-400 text-sm leading-relaxed mb-4 line-clamp-2">
                                        <?= htmlspecialchars($project['description']) ?>
                                    </p>
                                    
                                    <!-- Compact Tech Stack -->
                                    <div class="flex flex-wrap gap-1.5 mb-3">
                                        <?php 
                                        // Example tech stack - you can make this dynamic
                                        $techStacks = [
                                            ['React', 'Node.js', 'MongoDB'],
                                            ['Vue.js', 'Express', 'PostgreSQL'],
                                            ['Angular', 'NestJS', 'MySQL'],
                                            ['Next.js', 'Prisma', 'SQLite']
                                        ];
                                        $currentStack = $techStacks[$index % count($techStacks)];
                                        $displayStack = array_slice($currentStack, 0, 3); // Show max 3 tags
                                        
                                        foreach ($displayStack as $tech): 
                                        ?>
                                            <span class="px-2 py-1 text-xs font-medium bg-slate-800/60 text-slate-300 rounded-lg border border-slate-700/50">
                                                <?= htmlspecialchars($tech) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- Compact Meta -->
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-1.5 text-slate-400">
                                            <i class="ph-bold ph-calendar text-purple-400"></i>
                                            <span><?= htmlspecialchars($projectsCompletedText) ?></span>
                                        </div>
                                        
                                        <div class="flex items-center gap-1">
                                            <?php if ($project['live_link']): ?>
                                                <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></div>
                                                <span class="text-emerald-400 font-medium"><?= htmlspecialchars($projectsStatusLiveText) ?></span>
                                            <?php else: ?>
                                                <div class="w-1.5 h-1.5 bg-amber-400 rounded-full"></div>
                                                <span class="text-amber-400 font-medium"><?= htmlspecialchars($projectsStatusDevText) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Subtle Hover Glow -->
                                <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500/0 via-pink-500/0 to-brand-500/0 group-hover:from-purple-500/20 group-hover:via-pink-500/20 group-hover:to-brand-500/20 rounded-2xl blur-sm opacity-0 group-hover:opacity-100 transition-all duration-500 -z-10"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Compact CTA -->
                <div class="text-center">
                    <div class="inline-flex items-center gap-4 glass-card p-6 rounded-2xl border border-purple-400/20 hover:border-purple-400/40 transition-all group">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 border border-purple-400/30 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="ph-bold ph-arrow-right text-xl text-purple-400"></i>
                        </div>
                        <div class="text-left">
                            <h3 class="font-heading text-lg font-bold text-white mb-1">Explore More Projects</h3>
                            <p class="text-gray-400 text-sm">Discover additional work and case studies</p>
                        </div>
                        <a href="/nel-portfolio/admin/projects.php" class="bg-gradient-to-r from-purple-500 via-pink-500 to-brand-500 text-white px-4 py-2 rounded-xl font-semibold text-sm hover:shadow-lg hover:shadow-purple-500/30 transition-all hover:scale-105 flex items-center gap-2">
                            <span>View Portfolio</span>
                            <i class="ph-bold ph-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

    </main>

    <!-- Footer -->
    <footer class="py-12 border-t border-white/5 bg-gray-950">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-gray-500 text-sm">© <?= date('Y') ?> Nel Portfolio. All rights reserved.</p>
            <div class="flex gap-4">
                <a href="/nel-portfolio/admin/login.php" class="text-gray-600 hover:text-brand-400 text-sm transition-colors">Admin Login</a>
            </div>
        </div>
    </footer>

    <!-- PDF Popup Modal -->
    <div id="pdfModal" class="pdf-modal">
        <div class="pdf-modal-content">
            <div class="pdf-modal-header">
                <h3 class="pdf-modal-title">
                    <i class="ph-bold ph-certificate text-yellow-400"></i>
                    <span id="pdfModalTitle">Certificate Preview</span>
                </h3>
                <button class="pdf-modal-close" onclick="closePdfModal()">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>
            <div class="pdf-modal-body">
                <div class="pdf-loading" id="pdfLoading">
                    <div class="pdf-loading-spinner"></div>
                    <span>Loading certificate...</span>
                </div>
                <div class="pdf-viewer-wrapper">
                    <iframe id="pdfViewer" class="pdf-viewer" style="display: none;" 
                            sandbox="allow-same-origin allow-scripts" 
                            loading="lazy"></iframe>
                </div>
                <div class="pdf-error" id="pdfError" style="display: none;">
                    <i class="ph-bold ph-warning text-4xl mb-4"></i>
                    <h4 class="text-lg font-semibold mb-2">Unable to load certificate</h4>
                    <p class="text-sm text-gray-400">Please try downloading the file instead.</p>
                </div>
                <div class="pdf-actions">
                    <a id="pdfDownloadBtn" href="#" target="_blank" class="pdf-action-btn">
                        <i class="ph-bold ph-download-simple"></i>
                        Download
                    </a>
                    <a id="pdfOpenBtn" href="#" target="_blank" class="pdf-action-btn">
                        <i class="ph-bold ph-arrow-square-out"></i>
                        Open in New Tab
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Intersection Observer for Fade-in effects -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Add scroll blur to nav
            const nav = document.querySelector('nav');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    nav.classList.add('shadow-lg', 'shadow-black/20');
                } else {
                    nav.classList.remove('shadow-lg', 'shadow-black/20');
                }
            });

            // Dynamic Tech Background Animation
            const techBg = document.querySelector('.tech-bg');
            
            // Matrix Rain Effect
            function createMatrixRain() {
                const matrixContainer = document.getElementById('matrixRain');
                const chars = '01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン';
                
                function createMatrixChar() {
                    const char = document.createElement('div');
                    char.className = 'matrix-char';
                    char.textContent = chars[Math.floor(Math.random() * chars.length)];
                    char.style.left = Math.random() * 100 + '%';
                    char.style.animationDuration = (Math.random() * 8 + 5) + 's';
                    char.style.animationDelay = Math.random() * 2 + 's';
                    char.style.opacity = Math.random() * 0.5 + 0.3;
                    matrixContainer.appendChild(char);
                    
                    setTimeout(() => {
                        if (char.parentNode) {
                            char.parentNode.removeChild(char);
                        }
                    }, 13000);
                }
                
                // Create initial matrix characters
                for (let i = 0; i < 15; i++) {
                    setTimeout(() => createMatrixChar(), i * 200);
                }
                
                // Continue creating characters
                setInterval(createMatrixChar, 800);
            }
            
            // Initialize matrix rain
            createMatrixRain();
            
            // Create additional random circuit nodes
            function createRandomNode() {
                const node = document.createElement('div');
                node.className = 'circuit-node';
                node.style.top = Math.random() * 100 + '%';
                node.style.left = Math.random() * 100 + '%';
                node.style.animationDelay = Math.random() * 3 + 's';
                node.style.animationDuration = (Math.random() * 2 + 1) + 's';
                techBg.appendChild(node);
                
                // Remove node after animation
                setTimeout(() => {
                    if (node.parentNode) {
                        node.parentNode.removeChild(node);
                    }
                }, 8000);
            }
            
            // Create random data particles
            function createRandomParticle() {
                const particle = document.createElement('div');
                particle.className = 'data-particle';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 2 + 's';
                particle.style.animationDuration = (Math.random() * 4 + 2) + 's';
                techBg.appendChild(particle);
                
                // Remove particle after animation
                setTimeout(() => {
                    if (particle.parentNode) {
                        particle.parentNode.removeChild(particle);
                    }
                }, 6000);
            }

            // Create flowing circuit lines
            function createFlowingLine() {
                const line = document.createElement('div');
                const isHorizontal = Math.random() > 0.5;
                
                line.className = `circuit-line ${isHorizontal ? 'horizontal' : 'vertical'}`;
                
                if (isHorizontal) {
                    line.style.top = Math.random() * 100 + '%';
                    line.style.left = '-200px';
                } else {
                    line.style.left = Math.random() * 100 + '%';
                    line.style.top = '-200px';
                }
                
                line.style.animationDuration = (Math.random() * 4 + 4) + 's';
                techBg.appendChild(line);
                
                // Remove line after animation
                setTimeout(() => {
                    if (line.parentNode) {
                        line.parentNode.removeChild(line);
                    }
                }, 8000);
            }

            // Mouse interaction effects
            document.addEventListener('mousemove', (e) => {
                const mouseX = e.clientX / window.innerWidth;
                const mouseY = e.clientY / window.innerHeight;
                
                // Move circuit pattern slightly based on mouse position
                const circuitPattern = document.querySelector('.circuit-pattern');
                if (circuitPattern) {
                    circuitPattern.style.transform = `translate(${mouseX * 20}px, ${mouseY * 20}px)`;
                }
                
                // Occasionally spawn particles near mouse
                if (Math.random() < 0.02) {
                    const particle = document.createElement('div');
                    particle.className = 'data-particle';
                    particle.style.top = e.clientY + 'px';
                    particle.style.left = e.clientX + 'px';
                    particle.style.position = 'fixed';
                    particle.style.pointerEvents = 'none';
                    particle.style.zIndex = '1';
                    document.body.appendChild(particle);
                    
                    setTimeout(() => {
                        if (particle.parentNode) {
                            particle.parentNode.removeChild(particle);
                        }
                    }, 3000);
                }
            });
            
            // Periodically create new elements
            setInterval(createRandomNode, 3000);
            setInterval(createRandomParticle, 2000);
            setInterval(createFlowingLine, 4000);
            
            // Create initial burst of elements
            for (let i = 0; i < 5; i++) {
                setTimeout(createRandomNode, i * 500);
                setTimeout(createRandomParticle, i * 300);
                setTimeout(createFlowingLine, i * 700);
            }

            // Add pulsing effect to existing circuit nodes on scroll
            window.addEventListener('scroll', () => {
                const scrollPercent = window.scrollY / (document.documentElement.scrollHeight - window.innerHeight);
                const nodes = document.querySelectorAll('.circuit-node');
                
                nodes.forEach((node, index) => {
                    const delay = index * 0.1;
                    const intensity = Math.sin(scrollPercent * Math.PI * 4 + delay) * 0.5 + 0.5;
                    node.style.opacity = 0.3 + intensity * 0.7;
                });
            });
        });

        // PDF Modal Functions with Better Scaling
        function openPdfModal(pdfUrl, certName) {
            const modal = document.getElementById('pdfModal');
            const modalTitle = document.getElementById('pdfModalTitle');
            const pdfViewer = document.getElementById('pdfViewer');
            const pdfLoading = document.getElementById('pdfLoading');
            const pdfError = document.getElementById('pdfError');
            const pdfDownloadBtn = document.getElementById('pdfDownloadBtn');
            const pdfOpenBtn = document.getElementById('pdfOpenBtn');

            // Set modal title
            modalTitle.textContent = certName || 'Certificate Preview';

            // Set download and open links
            pdfDownloadBtn.href = pdfUrl;
            pdfOpenBtn.href = pdfUrl;

            // Show modal
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Reset states
            pdfLoading.style.display = 'flex';
            pdfViewer.style.display = 'none';
            pdfError.style.display = 'none';

            // Load PDF with optimal scaling parameters
            setTimeout(() => {
                try {
                    let pdfViewerUrl = pdfUrl;
                    
                    // Check if it's a PDF file
                    if (pdfUrl.toLowerCase().endsWith('.pdf')) {
                        // Use Google Docs Viewer for better scaling and compatibility
                        pdfViewerUrl = `https://docs.google.com/viewer?url=${encodeURIComponent(window.location.origin + pdfUrl)}&embedded=true`;
                        
                        // Alternative: Use PDF.js with scaling parameters
                        // const pdfParams = [
                        //     'toolbar=0',
                        //     'navpanes=0',
                        //     'scrollbar=1',
                        //     'view=FitV',  // Fit to width
                        //     'zoom=page-width'
                        // ].join('&');
                        // pdfViewerUrl = `${pdfUrl}#${pdfParams}`;
                    }
                    
                    // Set iframe source
                    pdfViewer.src = pdfViewerUrl;
                    
                    // Handle load events
                    let loadTimeout;
                    let hasLoaded = false;
                    
                    const handleLoad = () => {
                        if (!hasLoaded) {
                            hasLoaded = true;
                            clearTimeout(loadTimeout);
                            setTimeout(() => {
                                pdfLoading.style.display = 'none';
                                pdfViewer.style.display = 'block';
                            }, 1000);
                        }
                    };
                    
                    const handleError = () => {
                        if (!hasLoaded) {
                            hasLoaded = true;
                            clearTimeout(loadTimeout);
                            // Try fallback method
                            tryDirectPDFEmbed();
                        }
                    };
                    
                    // Fallback: Direct PDF embedding with object/embed
                    const tryDirectPDFEmbed = () => {
                        console.log('Trying direct PDF embedding...');
                        
                        const pdfWrapper = document.querySelector('.pdf-viewer-wrapper');
                        pdfViewer.style.display = 'none';
                        
                        // Remove existing fallback
                        const existingEmbed = pdfWrapper.querySelector('.pdf-embed-fallback');
                        if (existingEmbed) existingEmbed.remove();
                        
                        // Create object element
                        const pdfObject = document.createElement('object');
                        pdfObject.className = 'pdf-embed-fallback';
                        pdfObject.data = pdfUrl;
                        pdfObject.type = 'application/pdf';
                        pdfObject.style.cssText = `
                            width: 100%;
                            height: 100%;
                            border: none;
                            background: #fff;
                        `;
                        
                        // Add fallback embed
                        pdfObject.innerHTML = `
                            <embed src="${pdfUrl}" type="application/pdf" width="100%" height="100%">
                            <div style="
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                                height: 100%;
                                background: #f8fafc;
                                color: #64748b;
                                font-family: Inter, sans-serif;
                                padding: 2rem;
                                text-align: center;
                            ">
                                <i class="ph-bold ph-file-pdf" style="font-size: 4rem; color: #ef4444; margin-bottom: 1rem;"></i>
                                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.2rem; color: #334155;">PDF Preview Not Available</h3>
                                <p style="margin: 0 0 1.5rem 0; font-size: 0.9rem;">This PDF cannot be displayed in the browser.</p>
                                <div style="display: flex; gap: 1rem;">
                                    <a href="${pdfUrl}" target="_blank" style="
                                        display: inline-flex;
                                        align-items: center;
                                        gap: 0.5rem;
                                        background: #6366f1;
                                        color: white;
                                        padding: 0.75rem 1.5rem;
                                        border-radius: 0.5rem;
                                        text-decoration: none;
                                        font-weight: 600;
                                        font-size: 0.875rem;
                                    ">
                                        <i class="ph-bold ph-arrow-square-out"></i>
                                        Open PDF
                                    </a>
                                    <a href="${pdfUrl}" download style="
                                        display: inline-flex;
                                        align-items: center;
                                        gap: 0.5rem;
                                        background: #10b981;
                                        color: white;
                                        padding: 0.75rem 1.5rem;
                                        border-radius: 0.5rem;
                                        text-decoration: none;
                                        font-weight: 600;
                                        font-size: 0.875rem;
                                    ">
                                        <i class="ph-bold ph-download-simple"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        `;
                        
                        pdfWrapper.appendChild(pdfObject);
                        
                        setTimeout(() => {
                            pdfLoading.style.display = 'none';
                        }, 1000);
                    };
                    
                    // Set up event listeners
                    pdfViewer.onload = handleLoad;
                    pdfViewer.onerror = handleError;
                    
                    // Fallback timeout
                    loadTimeout = setTimeout(() => {
                        if (!hasLoaded) {
                            console.log('PDF load timeout, trying fallback...');
                            handleError();
                        }
                    }, 5000);

                } catch (error) {
                    console.error('Error loading PDF:', error);
                    pdfLoading.style.display = 'none';
                    pdfError.style.display = 'block';
                }
            }, 500);
        }

        function closePdfModal() {
            const modal = document.getElementById('pdfModal');
            const pdfViewer = document.getElementById('pdfViewer');
            const pdfWrapper = document.querySelector('.pdf-viewer-wrapper');
            
            modal.classList.remove('active');
            document.body.style.overflow = '';
            
            // Clean up all PDF content
            setTimeout(() => {
                pdfViewer.src = 'about:blank';
                
                // Remove any fallback embeds
                const fallbackEmbed = pdfWrapper.querySelector('.pdf-embed-fallback');
                if (fallbackEmbed) {
                    fallbackEmbed.remove();
                }
            }, 300);
        }

        // Close modal when clicking outside
        document.getElementById('pdfModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePdfModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('pdfModal');
                if (modal.classList.contains('active')) {
                    closePdfModal();
                }
            }
        });
    </script>
</body>
</html>
