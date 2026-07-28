<?php
require_once __DIR__ . '/config/db.php';

// Test frontend integration - check if settings are loaded correctly
$settingsQuery = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'projects_%'");
$settings = [];
while ($row = $settingsQuery->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Project card button texts (same as index.php)
$projectsLiveBtnText = $settings['projects_live_btn_text'] ?? 'Live Demo';
$projectsCodeBtnText = $settings['projects_code_btn_text'] ?? 'Source Code';
$projectsStatusLiveText = $settings['projects_status_live_text'] ?? 'Live';
$projectsStatusDevText = $settings['projects_status_dev_text'] ?? 'In Development';
$projectsCompletedText = $settings['projects_completed_text'] ?? 'Completed';

// Test project for preview
$testProject = [
    'title' => 'Test Project',
    'description' => 'A test project to verify the dynamic text system is working.',
    'live_link' => 'https://example.com',
    'github_link' => 'https://github.com/user/repo'
];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Project Cards Test</title>
    <script src="https://unpkg.com/phosphor-icons@2.0.2"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass-card { 
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 41, 59, 0.7) 100%);
            backdrop-filter: blur(16px);
        }
    </style>
</head>
<body class="bg-slate-900 text-white p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-center">Project Card Text Customization Test</h1>
        
        <!-- Settings Display -->
        <div class="bg-slate-800 p-6 rounded-xl mb-8">
            <h2 class="text-xl font-semibold mb-4">Current Settings</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <strong>Live Button:</strong> <?= htmlspecialchars($projectsLiveBtnText) ?>
                </div>
                <div>
                    <strong>Code Button:</strong> <?= htmlspecialchars($projectsCodeBtnText) ?>
                </div>
                <div>
                    <strong>Live Status:</strong> <?= htmlspecialchars($projectsStatusLiveText) ?>
                </div>
                <div>
                    <strong>Dev Status:</strong> <?= htmlspecialchars($projectsStatusDevText) ?>
                </div>
                <div class="col-span-2">
                    <strong>Completed Text:</strong> <?= htmlspecialchars($projectsCompletedText) ?>
                </div>
            </div>
        </div>
        
        <!-- Test Project Card (matching index.php structure) -->
        <div class="max-w-sm mx-auto">
            <div class="relative glass-card rounded-2xl overflow-hidden border border-white/10 bg-gradient-to-br from-slate-900/90 to-slate-800/70 group">
                <!-- Project Image Area -->
                <div class="relative aspect-[4/3] bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                    <i class="ph-bold ph-monitor-play text-4xl text-slate-600"></i>
                    
                    <!-- Number Badge -->
                    <div class="absolute top-3 left-3">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-purple-500/30 to-pink-500/30 backdrop-blur-sm border border-purple-400/40 flex items-center justify-center">
                            <span class="text-purple-300 font-bold text-sm">01</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="absolute bottom-3 left-3 right-3 flex gap-2">
                        <?php if ($testProject['live_link']): ?>
                            <a href="<?= htmlspecialchars($testProject['live_link']) ?>" target="_blank" 
                               class="flex-1 bg-gradient-to-r from-purple-500/25 to-pink-500/25 hover:from-purple-500/40 hover:to-pink-500/40 backdrop-blur-md text-white px-3 py-2 rounded-xl font-medium transition-all duration-300 border border-purple-400/40 flex items-center justify-center gap-1.5 text-xs">
                                <i class="ph-bold ph-arrow-square-out text-sm"></i>
                                <span><?= htmlspecialchars($projectsLiveBtnText) ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($testProject['github_link']): ?>
                            <a href="<?= htmlspecialchars($testProject['github_link']) ?>" target="_blank" 
                               class="flex-1 bg-slate-700/40 hover:bg-slate-600/60 backdrop-blur-md text-white px-3 py-2 rounded-xl font-medium transition-all duration-300 border border-slate-600/50 flex items-center justify-center gap-1.5 text-xs">
                                <i class="ph-fill ph-github-logo text-sm"></i>
                                <span><?= htmlspecialchars($projectsCodeBtnText) ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Project Info -->
                <div class="relative z-10 p-5">
                    <h3 class="text-lg font-bold text-white mb-2"><?= htmlspecialchars($testProject['title']) ?></h3>
                    <p class="text-gray-400 text-sm mb-4"><?= htmlspecialchars($testProject['description']) ?></p>
                    
                    <!-- Tech Stack -->
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        <span class="px-2 py-1 text-xs font-medium bg-slate-800/60 text-slate-300 rounded-lg border border-slate-700/50">PHP</span>
                        <span class="px-2 py-1 text-xs font-medium bg-slate-800/60 text-slate-300 rounded-lg border border-slate-700/50">MySQL</span>
                    </div>
                    
                    <!-- Meta Info -->
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1.5 text-slate-400">
                            <i class="ph-bold ph-calendar text-purple-400"></i>
                            <span><?= htmlspecialchars($projectsCompletedText) ?></span>
                        </div>
                        <div class="flex items-center gap-1">
                            <?php if ($testProject['live_link']): ?>
                                <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></div>
                                <span class="text-emerald-400 font-medium"><?= htmlspecialchars($projectsStatusLiveText) ?></span>
                            <?php else: ?>
                                <div class="w-1.5 h-1.5 bg-amber-400 rounded-full"></div>
                                <span class="text-amber-400 font-medium"><?= htmlspecialchars($projectsStatusDevText) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <p class="text-slate-400 mb-4">The card above should display the current dynamic text settings.</p>
            <a href="admin/index.php" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-xl text-white font-semibold transition-colors">
                Go to Admin Panel to Test Customization
            </a>
        </div>
    </div>
</body>
</html>