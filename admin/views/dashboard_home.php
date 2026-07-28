<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';
check_auth();

// Fetch counts for the overview
$projectsCount = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$skillsCount = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
$certsCount = $pdo->query("SELECT COUNT(*) FROM certifications")->fetchColumn();

// Fetch recent projects for dashboard preview
$recentProjects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC LIMIT 3")->fetchAll();
?>

<!-- Header Banner -->
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800/80 pb-6">
    <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-xs font-subhead uppercase tracking-wider mb-3">
            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
            System Status: Operational
        </div>
        <!-- TURNPIKE Typography Header -->
        <h2 class="text-2xl md:text-3xl font-bold font-turnpike text-white tracking-[0.2em] bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-100 to-cyan-300">
            DASHBOARD OVERVIEW
        </h2>
        <!-- Montserrat Light Body -->
        <p class="font-body text-slate-400 text-xs sm:text-sm mt-1">
            Welcome back to your portfolio management control center.
        </p>
    </div>
    
    <div class="flex items-center gap-3">
        <a href="/" target="_blank" 
            class="px-5 py-3 rounded-xl bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest hover:shadow-lg hover:shadow-cyan-500/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <i class="ph-bold ph-globe text-base"></i>
            <span>View Live Portfolio</span>
            <i class="ph-bold ph-arrow-square-out text-base"></i>
        </a>
    </div>
</div>

<!-- Key Metrics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    
    <!-- Total Projects Card (Clickable to Projects Module) -->
    <div class="glass-panel p-6 rounded-3xl relative overflow-hidden group hover:border-cyan-500/40 transition-all duration-300 hover:-translate-y-1 cursor-pointer"
         hx-get="/nel-portfolio/admin/views/projects.php"
         hx-target="#main-content"
         hx-push-url="/nel-portfolio/admin/projects.php"
         hx-swap="innerHTML transition:true"
         onclick="navigateToModule('projects.php')">
        <div class="flex items-center justify-between mb-4">
            <span class="font-subhead text-xs font-semibold uppercase tracking-wider text-slate-400">Total Projects</span>
            <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400 group-hover:scale-110 group-hover:bg-cyan-500 group-hover:text-slate-950 transition-all duration-300">
                <i class="ph-bold ph-briefcase text-2xl"></i>
            </div>
        </div>
        
        <div class="flex items-baseline gap-3 mb-3">
            <div class="text-4xl font-bold font-turnpike text-white tracking-tight"><?= sprintf("%02d", $projectsCount) ?></div>
            <span class="inline-flex items-center text-[11px] font-subhead text-cyan-400 bg-cyan-500/10 px-2.5 py-0.5 rounded-full border border-cyan-500/20">
                + Showcase Ready
            </span>
        </div>

        <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
            <span class="font-body text-xs text-slate-500">Live portfolio items</span>
            <span class="font-subhead text-xs font-semibold text-cyan-400 group-hover:text-cyan-300 flex items-center gap-1 group-hover:gap-2 transition-all">
                <span>Manage</span>
                <i class="ph-bold ph-arrow-right"></i>
            </span>
        </div>
    </div>

    <!-- Skills Listed Card (Clickable to Skills Module) -->
    <div class="glass-panel p-6 rounded-3xl relative overflow-hidden group hover:border-emerald-500/40 transition-all duration-300 hover:-translate-y-1 cursor-pointer"
         hx-get="/nel-portfolio/admin/views/skills.php"
         hx-target="#main-content"
         hx-push-url="/nel-portfolio/admin/skills.php"
         hx-swap="innerHTML transition:true"
         onclick="navigateToModule('skills.php')">
        <div class="flex items-center justify-between mb-4">
            <span class="font-subhead text-xs font-semibold uppercase tracking-wider text-slate-400">Skills Listed</span>
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-slate-950 transition-all duration-300">
                <i class="ph-bold ph-lightning text-2xl"></i>
            </div>
        </div>
        
        <div class="flex items-baseline gap-3 mb-3">
            <div class="text-4xl font-bold font-turnpike text-white tracking-tight"><?= sprintf("%02d", $skillsCount) ?></div>
            <span class="inline-flex items-center text-[11px] font-subhead text-emerald-400 bg-emerald-500/10 px-2.5 py-0.5 rounded-full border border-emerald-500/20">
                + Technical Stack
            </span>
        </div>

        <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
            <span class="font-body text-xs text-slate-500">Categorized abilities</span>
            <span class="font-subhead text-xs font-semibold text-emerald-400 group-hover:text-emerald-300 flex items-center gap-1 group-hover:gap-2 transition-all">
                <span>Manage</span>
                <i class="ph-bold ph-arrow-right"></i>
            </span>
        </div>
    </div>

    <!-- Certifications Card (Clickable to Certifications Module) -->
    <div class="glass-panel p-6 rounded-3xl relative overflow-hidden group hover:border-amber-500/40 transition-all duration-300 hover:-translate-y-1 sm:col-span-2 lg:col-span-1 cursor-pointer"
         hx-get="/nel-portfolio/admin/views/certifications.php"
         hx-target="#main-content"
         hx-push-url="/nel-portfolio/admin/certifications.php"
         hx-swap="innerHTML transition:true"
         onclick="navigateToModule('certifications.php')">
        <div class="flex items-center justify-between mb-4">
            <span class="font-subhead text-xs font-semibold uppercase tracking-wider text-slate-400">Certifications</span>
            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-slate-950 transition-all duration-300">
                <i class="ph-bold ph-certificate text-2xl"></i>
            </div>
        </div>
        
        <div class="flex items-baseline gap-3 mb-3">
            <div class="text-4xl font-bold font-turnpike text-white tracking-tight"><?= sprintf("%02d", $certsCount) ?></div>
            <span class="inline-flex items-center text-[11px] font-subhead text-amber-400 bg-amber-500/10 px-2.5 py-0.5 rounded-full border border-amber-500/20">
                + Credentials
            </span>
        </div>

        <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
            <span class="font-body text-xs text-slate-500">Verified achievements</span>
            <span class="font-subhead text-xs font-semibold text-amber-400 group-hover:text-amber-300 flex items-center gap-1 group-hover:gap-2 transition-all">
                <span>Manage</span>
                <i class="ph-bold ph-arrow-right"></i>
            </span>
        </div>
    </div>

</div>

<!-- Content Grid: Quick Actions & Recent Projects Preview -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Quick Management Hub (1 Column) -->
    <div class="glass-panel p-6 rounded-3xl flex flex-col">
        <div class="flex items-center gap-3 border-b border-slate-800/80 pb-4 mb-6">
            <div class="w-8 h-8 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                <i class="ph-bold ph-lightning-a text-lg"></i>
            </div>
            <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">QUICK ACTIONS</h3>
        </div>

        <div class="space-y-3.5 flex-1">
            <!-- Action 1: Manage Projects -->
            <button hx-get="/nel-portfolio/admin/views/projects.php" hx-target="#main-content" hx-push-url="/nel-portfolio/admin/projects.php" hx-swap="innerHTML transition:true" onclick="navigateToModule('projects.php')"
                class="w-full p-3.5 rounded-2xl bg-slate-900/60 border border-slate-800/80 hover:border-cyan-500/40 hover:bg-slate-900 transition-all flex items-center justify-between group text-left cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-folder-plus text-lg"></i>
                    </div>
                    <div>
                        <p class="font-subhead text-xs font-semibold text-slate-200">Manage Projects</p>
                        <p class="font-body text-[11px] text-slate-400">Add or edit showcase items</p>
                    </div>
                </div>
                <i class="ph-bold ph-caret-right text-slate-500 group-hover:text-cyan-400 group-hover:translate-x-1 transition-all"></i>
            </button>

            <!-- Action 2: Manage Skills -->
            <button hx-get="/nel-portfolio/admin/views/skills.php" hx-target="#main-content" hx-push-url="/nel-portfolio/admin/skills.php" hx-swap="innerHTML transition:true" onclick="navigateToModule('skills.php')"
                class="w-full p-3.5 rounded-2xl bg-slate-900/60 border border-slate-800/80 hover:border-emerald-500/40 hover:bg-slate-900 transition-all flex items-center justify-between group text-left cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-code text-lg"></i>
                    </div>
                    <div>
                        <p class="font-subhead text-xs font-semibold text-slate-200">Update Tech Stack</p>
                        <p class="font-body text-[11px] text-slate-400">Manage skill categories</p>
                    </div>
                </div>
                <i class="ph-bold ph-caret-right text-slate-500 group-hover:text-emerald-400 group-hover:translate-x-1 transition-all"></i>
            </button>

            <!-- Action 3: Edit Resume & Settings -->
            <button hx-get="/nel-portfolio/admin/views/resume.php" hx-target="#main-content" hx-push-url="/nel-portfolio/admin/resume.php" hx-swap="innerHTML transition:true" onclick="navigateToModule('resume.php')"
                class="w-full p-3.5 rounded-2xl bg-slate-900/60 border border-slate-800/80 hover:border-amber-500/40 hover:bg-slate-900 transition-all flex items-center justify-between group text-left cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-file-text text-lg"></i>
                    </div>
                    <div>
                        <p class="font-subhead text-xs font-semibold text-slate-200">Edit Resume Bio</p>
                        <p class="font-body text-[11px] text-slate-400">Update bio & PDF link</p>
                    </div>
                </div>
                <i class="ph-bold ph-caret-right text-slate-500 group-hover:text-amber-400 group-hover:translate-x-1 transition-all"></i>
            </button>

            <!-- Action 4: View Portfolio -->
            <a href="/" target="_blank"
                class="w-full p-3.5 rounded-2xl bg-slate-900/60 border border-slate-800/80 hover:border-cyan-500/40 hover:bg-slate-900 transition-all flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-arrow-square-out text-lg"></i>
                    </div>
                    <div>
                        <p class="font-subhead text-xs font-semibold text-slate-200">Preview Frontend</p>
                        <p class="font-body text-[11px] text-slate-400">Open live portfolio site</p>
                    </div>
                </div>
                <i class="ph-bold ph-caret-right text-slate-500 group-hover:text-cyan-400 group-hover:translate-x-1 transition-all"></i>
            </a>
        </div>
    </div>

    <!-- Recent Projects Overview (2 Columns) -->
    <div class="glass-panel p-6 rounded-3xl lg:col-span-2 flex flex-col">
        <div class="flex items-center justify-between border-b border-slate-800/80 pb-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                    <i class="ph-bold ph-rows text-lg"></i>
                </div>
                <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">PROJECT CATALOG SNAPSHOT</h3>
            </div>
            <button hx-get="/nel-portfolio/admin/views/projects.php" hx-target="#main-content" hx-push-url="/nel-portfolio/admin/projects.php" hx-swap="innerHTML transition:true" onclick="navigateToModule('projects.php')"
                class="font-subhead text-xs font-semibold text-cyan-400 hover:text-cyan-300 flex items-center gap-1 transition-colors cursor-pointer">
                <span>View All (<?= $projectsCount ?>)</span>
                <i class="ph-bold ph-arrow-right"></i>
            </button>
        </div>

        <div class="space-y-4 flex-1">
            <?php if (empty($recentProjects)): ?>
                <div class="py-12 px-4 text-center rounded-2xl bg-slate-900/40 border border-dashed border-slate-800 flex flex-col items-center justify-center">
                    <i class="ph-bold ph-folder-open text-4xl text-slate-600 mb-3"></i>
                    <h4 class="font-subhead text-sm font-semibold text-slate-300 mb-1">No Projects Added Yet</h4>
                    <p class="font-body text-xs text-slate-500 mb-4 max-w-xs">Start populating your portfolio by adding your first project.</p>
                    <button hx-get="/nel-portfolio/admin/views/projects.php" hx-target="#main-content" hx-push-url="/nel-portfolio/admin/projects.php" hx-swap="innerHTML transition:true" onclick="navigateToModule('projects.php')"
                        class="px-4 py-2 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 font-subhead font-semibold text-xs uppercase tracking-wider hover:bg-cyan-500 hover:text-slate-950 transition-all flex items-center gap-2 cursor-pointer">
                        <i class="ph-bold ph-plus text-sm"></i> Add First Project
                    </button>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <?php foreach ($recentProjects as $project): ?>
                        <div hx-get="/nel-portfolio/admin/views/projects.php" hx-target="#main-content" hx-push-url="/nel-portfolio/admin/projects.php" hx-swap="innerHTML transition:true" onclick="navigateToModule('projects.php')"
                            class="rounded-2xl bg-slate-900/60 border border-slate-800/80 p-4 hover:border-cyan-500/30 transition-all flex flex-col group cursor-pointer">
                            <div class="h-28 rounded-xl bg-slate-950 overflow-hidden relative mb-3 flex items-center justify-center border border-slate-800/60">
                                <?php if (!empty($project['image_url'])): ?>
                                    <img src="<?= htmlspecialchars($project['image_url']) ?>" alt="Project Thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <?php else: ?>
                                    <i class="ph-bold ph-image text-3xl text-slate-700"></i>
                                <?php endif; ?>
                                <span class="absolute top-2 right-2 px-2 py-0.5 rounded-full bg-slate-950/80 backdrop-blur-sm text-[10px] font-subhead text-cyan-400 border border-cyan-500/20">
                                    Project
                                </span>
                            </div>
                            
                            <h4 class="font-subhead text-xs font-semibold text-white mb-1 truncate group-hover:text-cyan-400 transition-colors">
                                <?= htmlspecialchars($project['title']) ?>
                            </h4>
                            <p class="font-body text-[11px] text-slate-400 line-clamp-2 mb-3">
                                <?= htmlspecialchars($project['description'] ?? 'No description provided.') ?>
                            </p>
                            
                            <div class="mt-auto pt-2 border-t border-slate-800/60 flex justify-between items-center text-[10px] font-body text-slate-500">
                                <span><?= date('M d, Y', strtotime($project['created_at'] ?? 'now')) ?></span>
                                <i class="ph-bold ph-arrow-right text-slate-400 group-hover:text-cyan-400 group-hover:translate-x-1 transition-all"></i>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
