<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: /nel-portfolio/admin/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Nel Portfolio</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- HTMX -->
    <script src="https://unpkg.com/htmx.org@1.9.11"></script>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Google Fonts: Syncopate (Turnpike Header match), Montserrat (300 Light, 600 SemiBold) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300&family=Syncopate:wght@700&family=Orbitron:wght@700;800&display=swap" rel="stylesheet">
    <style>
        /* =========================================================
           TYPOGRAPHY SYSTEM (As per design specification)
           ========================================================= */
        /* 1. TURNPIKE: Use for headers in all caps and set with 100 pt kerning */
        .font-turnpike {
            font-family: 'Syncopate', 'Orbitron', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.25em; /* 100 pt equivalent extended kerning */
        }
        
        /* 2. Montserrat Semi Bold: Use for subheads in all caps or title caps (0 pt kerning) */
        .font-subhead {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            letter-spacing: 0;
        }

        /* 3. Montserrat Light: Use for body copy and long paragraphs (0 pt kerning) */
        .font-body {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            letter-spacing: 0;
        }

        /* =========================================================
           PORTFOLIO COLOR PALETTE & GLASSMORPHISM
           ========================================================= */
        body {
            background-color: #030712;
            color: #f3f4f6;
            font-family: 'Montserrat', sans-serif;
        }

        /* Ambient Background Mesh Grid */
        .bg-grid-pattern {
            background-size: 32px 32px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }

        /* Luxury Glass Card */
        .luxe-glass-card {
            background: rgba(13, 20, 36, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 245, 212, 0.15);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 
                        0 0 35px rgba(0, 245, 212, 0.06),
                        inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .luxe-input {
            background: rgba(7, 11, 22, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.25s ease-in-out;
        }

        .luxe-input:focus {
            background: rgba(10, 16, 32, 0.85);
            border-color: #00f5d4;
            box-shadow: 0 0 20px rgba(0, 245, 212, 0.25), inset 0 0 10px rgba(0, 245, 212, 0.05);
            outline: none;
        }

        /* Glowing Button Gradient */
        .btn-luxe-primary {
            background: linear-gradient(135deg, #00f5d4 0%, #06b6d4 50%, #3b82f6 100%);
            box-shadow: 0 4px 20px rgba(0, 245, 212, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-luxe-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 245, 212, 0.5);
            filter: brightness(1.1);
        }

        .btn-luxe-primary:active {
            transform: translateY(0);
        }

        /* HTMX Indicator */
        .htmx-indicator { display: none; }
        .htmx-request .htmx-indicator { display: inline-block; }
        .htmx-request.htmx-indicator { display: inline-block; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col items-center justify-center relative overflow-x-hidden overflow-y-auto bg-grid-pattern selection:bg-cyan-500/30 selection:text-cyan-200 py-10 px-4">

    <!-- Ambient Glowing Light Orbs -->
    <div class="fixed inset-0 overflow-hidden -z-10 pointer-events-none">
        <div class="absolute -top-[25%] -left-[15%] w-[60%] h-[60%] rounded-full bg-cyan-500/15 blur-[140px] animate-pulse"></div>
        <div class="absolute -bottom-[20%] -right-[15%] w-[55%] h-[55%] rounded-full bg-indigo-600/20 blur-[130px]"></div>
        <div class="absolute top-[40%] left-[50%] -translate-x-1/2 w-[35%] h-[35%] rounded-full bg-teal-400/10 blur-[120px]"></div>
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-md p-6 sm:p-10 my-auto rounded-3xl luxe-glass-card relative z-10 transition-all duration-300">
        
        <!-- Top Portfolio Branding Badge -->
        <div class="flex justify-center mb-6">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-cyan-500 to-indigo-600 p-[1px] shadow-lg shadow-cyan-500/20">
                <div class="w-full h-full bg-slate-950 rounded-[15px] flex items-center justify-center">
                    <i class="ph-bold ph-terminal-window text-2xl text-cyan-400"></i>
                </div>
            </div>
        </div>

        <!-- Header Section -->
        <div class="text-center mb-8">
            <!-- TURNPIKE Typography: Headers in all caps with 100 pt kerning -->
            <h1 class="font-turnpike text-xl sm:text-2xl font-bold text-white tracking-[0.25em] mb-3 bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-100 to-cyan-300">
                WELCOME BACK
            </h1>
            <!-- Montserrat Light Typography: Body copy -->
            <p class="font-body text-slate-400 text-xs sm:text-sm tracking-wide">
                Sign in to manage your portfolio
            </p>
        </div>

        <!-- Login Form -->
        <form hx-post="/nel-portfolio/api/auth_handler.php" hx-target="#auth-message" hx-indicator="#loading-spinner" class="space-y-5">
            
            <!-- Username Input -->
            <div>
                <!-- Montserrat Semi Bold Typography: Subhead for field label -->
                <label for="username" class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">
                    Username
                </label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-slate-400">
                        <i class="ph-bold ph-user text-lg"></i>
                    </span>
                    <input type="text" id="username" name="username" required placeholder="Enter your username"
                        class="w-full font-body luxe-input rounded-xl pl-11 pr-4 py-3 text-sm text-white placeholder-slate-500 transition-all">
                </div>
            </div>

            <!-- Password Input -->
            <div>
                <!-- Montserrat Semi Bold Typography: Subhead for field label -->
                <label for="password" class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">
                    Password
                </label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-slate-400">
                        <i class="ph-bold ph-lock-key text-lg"></i>
                    </span>
                    <input type="password" id="password" name="password" required placeholder="••••••••"
                        class="w-full font-body luxe-input rounded-xl pl-11 pr-11 py-3 text-sm text-white placeholder-slate-500 transition-all">
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute right-4 text-slate-400 hover:text-cyan-400 transition-colors focus:outline-none" title="Toggle password visibility">
                        <i id="password-toggle-icon" class="ph-bold ph-eye text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Auth Message Target for HTMX -->
            <div id="auth-message" class="font-body text-xs"></div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full btn-luxe-primary font-subhead font-semibold text-slate-950 uppercase tracking-widest py-3.5 px-6 rounded-xl transition-all flex items-center justify-center gap-2 group cursor-pointer mt-2">
                <span>Sign In</span>
                <i class="ph-bold ph-arrow-right text-lg group-hover:translate-x-1 transition-transform"></i>
                <svg id="loading-spinner" class="htmx-indicator animate-spin h-5 w-5 text-slate-950 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>

            <!-- Register Link -->
            <div class="pt-2 text-center">
                <p class="font-body text-xs text-slate-400">
                    Don't have an account? 
                    <a href="/nel-portfolio/admin/register.php" class="font-subhead font-semibold text-cyan-400 hover:text-cyan-300 transition-colors underline underline-offset-4 decoration-cyan-500/30 hover:decoration-cyan-400">
                        Register
                    </a>
                </p>
            </div>
        </form>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('ph-eye');
                toggleIcon.classList.add('ph-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('ph-eye-slash');
                toggleIcon.classList.add('ph-eye');
            }
        }
    </script>
</body>
</html>
