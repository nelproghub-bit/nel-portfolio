<?php
require_once __DIR__ . '/../config/auth.php';
check_auth();

// Active page routing
$activePage = $page ?? ($_GET['page'] ?? 'overview');

// Determine HTMX initial view endpoint based on active page
$viewEndpoint = match($activePage) {
    'home' => '/nel-portfolio/admin/views/home.php',
    'projects' => '/nel-portfolio/admin/views/projects.php',
    'skills' => '/nel-portfolio/admin/views/skills.php',
    'certifications' => '/nel-portfolio/admin/views/certifications.php',
    'resume' => '/nel-portfolio/admin/views/resume.php',
    'about' => '/nel-portfolio/admin/views/about.php',
    default => '/nel-portfolio/admin/views/dashboard_home.php',
};
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ucfirst($activePage) ?> - Admin Dashboard - Nel Portfolio</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        slate: tailwind.colors.slate,
                        cyan: tailwind.colors.cyan,
                    }
                }
            }
        }
    </script>
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
           TYPOGRAPHY SYSTEM (Exact attached spec match)
           ========================================================= */
        /* 1. TURNPIKE: Use for headers in all caps and set with 100 pt kerning */
        .font-turnpike {
            font-family: 'Syncopate', 'Orbitron', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.25em;
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

        body { 
            font-family: 'Montserrat', sans-serif; 
            background-color: #030712;
            color: #f3f4f6;
        }
        
        h1, h2, h3, h4, h5, h6, .font-heading { 
            font-family: 'Syncopate', 'Orbitron', sans-serif; 
            letter-spacing: 0.15em; 
            text-transform: uppercase; 
        }
        
        /* Grid background pattern */
        .bg-grid-pattern {
            background-size: 32px 32px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }

        /* Smooth transitions for HTMX content swaps */
        .fade-me-out.htmx-swapping {
            opacity: 0;
            transition: opacity 200ms ease-out;
        }
        .fade-me-in.htmx-added {
            opacity: 0;
        }
        .fade-me-in {
            opacity: 1;
            transition: opacity 250ms ease-in;
        }
        
        /* Glassmorphism Panel */
        .glass-panel {
            background: rgba(13, 20, 36, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 245, 212, 0.15);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.7), 
                        0 0 30px rgba(0, 245, 212, 0.04),
                        inset 0 1px 0 rgba(255, 255, 255, 0.08);
        }

        .nav-item-active {
            background: linear-gradient(90deg, rgba(0, 245, 212, 0.18) 0%, rgba(6, 182, 212, 0.05) 100%);
            border-left: 3px solid #00f5d4;
            color: #ffffff !important;
        }

        .nav-item-active i {
            color: #00f5d4 !important;
        }

        /* =========================================================
           MODAL ANIMATION SYSTEM
           ========================================================= */
        /* Overlay fade-in */
        @keyframes overlayFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        /* Modal panel slide-up + scale entry */
        @keyframes modalEnter {
            from { opacity: 0; transform: scale(0.94) translateY(16px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        /* Modal panel exit */
        @keyframes modalExit {
            from { opacity: 1; transform: scale(1) translateY(0); }
            to   { opacity: 0; transform: scale(0.94) translateY(12px); }
        }

        /* Any div with class admin-modal when visible plays fade */
        .admin-modal:not(.hidden) {
            animation: overlayFadeIn 200ms ease forwards;
        }
        .admin-modal:not(.hidden) > .modal-panel {
            animation: modalEnter 280ms cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* HTMX request indicator inside modal */
        .htmx-request #modal-content {
            opacity: 0.6;
            transition: opacity 150ms;
            pointer-events: none;
        }

        /* Styled scrollbar inside modals */
        .modal-panel::-webkit-scrollbar { width: 5px; }
        .modal-panel::-webkit-scrollbar-track { background: transparent; }
        .modal-panel::-webkit-scrollbar-thumb { 
            background: rgba(0, 245, 212, 0.2); 
            border-radius: 99px; 
        }
        .modal-panel::-webkit-scrollbar-thumb:hover { 
            background: rgba(0, 245, 212, 0.4); 
        }

        /* Input focus glow */
        input:focus, textarea:focus, select:focus {
            box-shadow: 0 0 0 3px rgba(0, 245, 212, 0.12);
        }

        /* Select dark arrow */
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%2364748b'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            appearance: none;
            -webkit-appearance: none;
        }

        /* Confirm dialog override (browser native) styling hint */
        ::backdrop { background: rgba(3, 7, 18, 0.85); backdrop-filter: blur(4px); }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex selection:bg-cyan-500/30 selection:text-cyan-200 font-body relative overflow-x-hidden bg-grid-pattern">

    <!-- Ambient Glowing Light Orbs -->
    <div class="fixed inset-0 overflow-hidden -z-10 pointer-events-none">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-cyan-500/10 blur-[150px]"></div>
        <div class="absolute top-[40%] -right-[15%] w-[45%] h-[45%] rounded-full bg-indigo-600/15 blur-[140px]"></div>
        <div class="absolute -bottom-[20%] left-[30%] w-[40%] h-[40%] rounded-full bg-teal-400/10 blur-[130px]"></div>
    </div>

    <!-- Sidebar -->
    <aside class="w-72 border-r border-slate-800/80 bg-slate-950/90 backdrop-blur-xl flex flex-col hidden md:flex sticky top-0 h-screen z-20 shadow-2xl">
        <!-- Logo / Brand Header -->
        <div class="p-6 border-b border-slate-800/80 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-500 to-indigo-600 p-[1px] shadow-lg shadow-cyan-500/20 shrink-0">
                <div class="w-full h-full bg-slate-950 rounded-[11px] flex items-center justify-center">
                    <i class="ph-bold ph-terminal-window text-xl text-cyan-400"></i>
                </div>
            </div>
            <div>
                <h1 class="text-xs font-bold font-turnpike text-white tracking-[0.2em] bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-100 to-cyan-300">
                    PORTFOLIO
                </h1>
                <span class="font-subhead text-[10px] uppercase font-semibold text-cyan-400 tracking-wider">ADMIN CONTROL</span>
            </div>
        </div>

        <!-- User Profile Pill -->
        <div class="px-6 py-4 border-b border-slate-800/60 bg-slate-900/40 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-8 h-8 rounded-full bg-slate-800 border border-cyan-500/30 flex items-center justify-center text-cyan-400 font-bold text-xs">
                        <i class="ph-bold ph-user-gear"></i>
                    </div>
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-400 border-2 border-slate-950 rounded-full animate-pulse"></span>
                </div>
                <div>
                    <p class="font-subhead text-xs font-semibold text-slate-200">Admin User</p>
                    <p class="font-body text-[10px] text-emerald-400 flex items-center gap-1">
                        Online
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-6 space-y-1.5 overflow-y-auto">
            <p class="px-4 text-[10px] font-subhead uppercase tracking-widest text-slate-500 mb-2 font-semibold">Navigation</p>

            <!-- Overview Page Link -->
            <a href="/nel-portfolio/admin/index.php" hx-get="/nel-portfolio/admin/views/dashboard_home.php" hx-target="#main-content" hx-push-url="/nel-portfolio/admin/index.php" hx-swap="innerHTML transition:true" onclick="setActiveNav(this)"
               class="nav-link <?= $activePage === 'overview' ? 'nav-item-active' : '' ?> flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-slate-900/60 transition-all group">
                <i class="ph-bold ph-squares-four text-lg text-slate-400 group-hover:text-cyan-400 transition-colors"></i>
                <span class="font-subhead text-xs font-semibold">Overview</span>
            </a>
            
            <!-- Home/Hero Page Link -->
            <a href="/nel-portfolio/admin/home.php" hx-get="/nel-portfolio/admin/views/home.php" hx-target="#main-content" hx-push-url="/nel-portfolio/admin/home.php" hx-swap="innerHTML transition:true" onclick="setActiveNav(this)"
               class="nav-link <?= $activePage === 'home' ? 'nav-item-active' : '' ?> flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-slate-900/60 transition-all group">
                <i class="ph-bold ph-house text-lg text-slate-400 group-hover:text-cyan-400 transition-colors"></i>
                <span class="font-subhead text-xs font-semibold">Home / Hero</span>
            </a>
            
            <!-- Projects Page Link -->
            <a href="/nel-portfolio/admin/projects.php" hx-get="/nel-portfolio/admin/views/projects.php" hx-target="#main-content" hx-push-url="/nel-portfolio/admin/projects.php" hx-swap="innerHTML transition:true" onclick="setActiveNav(this)"
               class="nav-link <?= $activePage === 'projects' ? 'nav-item-active' : '' ?> flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-slate-900/60 transition-all group">
                <i class="ph-bold ph-briefcase text-lg text-slate-400 group-hover:text-cyan-400 transition-colors"></i>
                <span class="font-subhead text-xs font-semibold">Projects</span>
            </a>
            
            <!-- Skills Page Link -->
            <a href="/nel-portfolio/admin/skills.php" hx-get="/nel-portfolio/admin/views/skills.php" hx-target="#main-content" hx-push-url="/nel-portfolio/admin/skills.php" hx-swap="innerHTML transition:true" onclick="setActiveNav(this)"
               class="nav-link <?= $activePage === 'skills' ? 'nav-item-active' : '' ?> flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-slate-900/60 transition-all group">
                <i class="ph-bold ph-lightning text-lg text-slate-400 group-hover:text-cyan-400 transition-colors"></i>
                <span class="font-subhead text-xs font-semibold">Skills</span>
            </a>
            
            <!-- Certifications Page Link -->
            <a href="/nel-portfolio/admin/certifications.php" hx-get="/nel-portfolio/admin/views/certifications.php" hx-target="#main-content" hx-push-url="/nel-portfolio/admin/certifications.php" hx-swap="innerHTML transition:true" onclick="setActiveNav(this)"
               class="nav-link <?= $activePage === 'certifications' ? 'nav-item-active' : '' ?> flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-slate-900/60 transition-all group">
                <i class="ph-bold ph-certificate text-lg text-slate-400 group-hover:text-cyan-400 transition-colors"></i>
                <span class="font-subhead text-xs font-semibold">Certifications</span>
            </a>
            
            <!-- About Page Link -->
            <a href="/nel-portfolio/admin/about.php" hx-get="/nel-portfolio/admin/views/about.php" hx-target="#main-content" hx-push-url="/nel-portfolio/admin/about.php" hx-swap="innerHTML transition:true" onclick="setActiveNav(this)"
               class="nav-link <?= $activePage === 'about' ? 'nav-item-active' : '' ?> flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-slate-900/60 transition-all group">
                <i class="ph-bold ph-user-circle text-lg text-slate-400 group-hover:text-cyan-400 transition-colors"></i>
                <span class="font-subhead text-xs font-semibold">About Section</span>
            </a>
            
            <!-- Resume Page Link -->
            <a href="/nel-portfolio/admin/resume.php" hx-get="/nel-portfolio/admin/views/resume.php" hx-target="#main-content" hx-push-url="/nel-portfolio/admin/resume.php" hx-swap="innerHTML transition:true" onclick="setActiveNav(this)"
               class="nav-link <?= $activePage === 'resume' ? 'nav-item-active' : '' ?> flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-slate-900/60 transition-all group">
                <i class="ph-bold ph-file-text text-lg text-slate-400 group-hover:text-cyan-400 transition-colors"></i>
                <span class="font-subhead text-xs font-semibold">Resume & Settings</span>
            </a>
        </nav>
        
        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-800/80 bg-slate-950/60">
            <a href="/nel-portfolio/admin/logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-red-400 hover:bg-red-500/10 border border-transparent hover:border-red-500/20 transition-all w-full group">
                <i class="ph-bold ph-sign-out text-lg group-hover:rotate-12 transition-transform"></i>
                <span class="font-subhead text-xs font-semibold">Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 relative overflow-y-auto h-screen">
        <!-- Top header for mobile -->
        <header class="md:hidden p-4 border-b border-slate-800/80 flex justify-between items-center bg-slate-950/90 backdrop-blur-md sticky top-0 z-20">
            <div class="flex items-center gap-2">
                <i class="ph-bold ph-terminal-window text-xl text-cyan-400"></i>
                <h1 class="text-sm font-bold font-turnpike text-white tracking-widest">PORTFOLIO ADMIN</h1>
            </div>
            <a href="/nel-portfolio/admin/logout.php" class="text-slate-400 hover:text-red-400 p-2">
                <i class="ph-bold ph-sign-out text-xl"></i>
            </a>
        </header>

        <!-- Dynamic Content Container -->
        <div id="main-content" class="p-6 md:p-10 max-w-7xl mx-auto fade-me-in" hx-get="<?= $viewEndpoint ?>" hx-trigger="load">
            <!-- Content loaded via HTMX -->
            <div class="flex items-center justify-center h-64">
                <div class="animate-pulse flex flex-col items-center gap-4">
                    <div class="w-12 h-12 rounded-full border-4 border-cyan-500/20 border-t-cyan-400 animate-spin"></div>
                    <p class="font-body text-slate-400 text-sm">Loading <?= ucfirst($activePage) ?>...</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-3"></div>

    <script>
        function setActiveNav(element) {
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('nav-item-active');
            });
            if (element) {
                element.classList.add('nav-item-active');
            }
        }

        function navigateToModule(pageName) {
            const navLink = document.querySelector(`a[href*="${pageName}"]`);
            if (navLink) {
                setActiveNav(navLink);
            }
        }

        // Listen for HTMX events to show notifications
        document.body.addEventListener('showMessage', function(evt){
            const msg = evt.detail.value;
            const type = evt.detail.type || 'success';
            
            const toast = document.createElement('div');
            const isSuccess = type === 'success';
            toast.className = [
                'px-5 py-3.5 rounded-2xl shadow-2xl font-subhead text-xs uppercase tracking-wider font-semibold',
                'flex items-center gap-3 transform transition-all duration-300 translate-y-4 opacity-0',
                'border backdrop-blur-md',
                isSuccess
                    ? 'bg-slate-900/95 border-emerald-500/30 text-emerald-300 shadow-slate-950'
                    : 'bg-slate-900/95 border-red-500/30 text-red-300 shadow-slate-950'
            ].join(' ');

            const iconColor = isSuccess ? 'text-emerald-400' : 'text-red-400';
            const iconName = isSuccess ? 'ph-check-circle' : 'ph-warning-circle';
            toast.innerHTML = `<i class="ph-bold ${iconName} text-xl ${iconColor}"></i><span>${msg}</span>`;
            
            document.getElementById('toast-container').appendChild(toast);
            
            // Animate in
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    toast.classList.remove('translate-y-4', 'opacity-0');
                });
            });
            
            // Remove after 3.5 seconds
            setTimeout(() => {
                toast.classList.add('translate-y-4', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        });

        // Global: close any admin-modal when clicking the backdrop overlay
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('admin-modal')) {
                e.target.classList.add('hidden');
            }
        });

        // Global: press Escape to close any open admin-modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.admin-modal:not(.hidden)').forEach(m => m.classList.add('hidden'));
            }
        });
    </script>
</body>
</html>
