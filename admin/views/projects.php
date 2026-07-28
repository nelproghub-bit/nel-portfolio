<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';
check_auth();

// Fetch projects section settings
$settingsQuery = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'projects_%'");
$projectsSettings = [];
while ($row = $settingsQuery->fetch()) {
    $projectsSettings[$row['setting_key']] = $row['setting_value'];
}

$projectsBadgeText = $projectsSettings['projects_badge_text'] ?? 'Portfolio Showcase';
$projectsTitle = $projectsSettings['projects_title'] ?? 'Selected Works';
$projectsSubtitle = $projectsSettings['projects_subtitle'] ?? 'A curated collection of my most impactful projects, showcasing innovation, technical expertise, and creative problem-solving.';

// Project card button texts
$projectsLiveBtnText = $projectsSettings['projects_live_btn_text'] ?? 'Live Demo';
$projectsCodeBtnText = $projectsSettings['projects_code_btn_text'] ?? 'Source Code';
$projectsStatusLiveText = $projectsSettings['projects_status_live_text'] ?? 'Live';
$projectsStatusDevText = $projectsSettings['projects_status_dev_text'] ?? 'In Development';
$projectsCompletedText = $projectsSettings['projects_completed_text'] ?? 'Recently Completed';

$projects = $pdo->query("SELECT * FROM projects ORDER BY sort_order ASC, created_at DESC")->fetchAll();
?>

<!-- Projects Section Header Customization -->
<div class="glass-panel p-6 sm:p-8 rounded-3xl mb-8">
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/80">
        <div class="w-9 h-9 rounded-xl bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400">
            <i class="ph-bold ph-text-h text-lg"></i>
        </div>
        <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">PROJECTS SECTION HEADER</h3>
    </div>
    
    <form hx-post="/nel-portfolio/api/projects_handler.php" hx-target="#projects-header-container" hx-swap="outerHTML" class="font-body">
        <input type="hidden" name="action" value="update_projects_header">
        <div id="projects-header-container" class="space-y-5">
            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Badge Text</label>
                <input type="text" name="badge_text" value="<?= htmlspecialchars($projectsBadgeText) ?>" 
                    placeholder="Portfolio Showcase"
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                <p class="font-body text-xs text-slate-500 mt-2">The small badge text above the main title.</p>
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Section Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($projectsTitle) ?>" 
                    placeholder="Selected Works"
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                <p class="font-body text-xs text-slate-500 mt-2">The main title for the projects section. Use 'Works' for gradient effect.</p>
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Section Subtitle</label>
                <textarea name="subtitle" rows="3" 
                    placeholder="A curated collection of my most impactful projects..."
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all leading-relaxed"><?= htmlspecialchars($projectsSubtitle) ?></textarea>
                <p class="font-body text-xs text-slate-500 mt-2">The description paragraph below the main title.</p>
            </div>

            <!-- Preview -->
            <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800">
                <p class="font-subhead text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Preview</p>
                <div class="text-center space-y-4">
                    <div class="inline-flex items-center gap-3 bg-gradient-to-r from-white/5 to-white/0 backdrop-blur-sm border border-white/10 rounded-full px-6 py-2.5">
                        <i class="ph-bold ph-folder-open text-purple-400 text-sm"></i>
                        <span class="text-gray-400 font-medium tracking-wider uppercase text-xs"><?= htmlspecialchars($projectsBadgeText) ?></span>
                    </div>
                    <h2 class="font-heading text-3xl font-black text-white">
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
                    <p class="text-gray-400 text-sm leading-relaxed max-w-2xl mx-auto"><?= htmlspecialchars($projectsSubtitle) ?></p>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-800/80">
                <button type="submit" 
                    class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                    <i class="ph-bold ph-floppy-disk text-base"></i>
                    <span>Save Header</span>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Project Cards Customization -->
<div class="glass-panel p-6 sm:p-8 rounded-3xl mb-8">
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/80">
        <div class="w-9 h-9 rounded-xl bg-pink-500/10 border border-pink-500/30 flex items-center justify-center text-pink-400">
            <i class="ph-bold ph-cards text-lg"></i>
        </div>
        <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">PROJECT CARDS TEXT</h3>
    </div>
    
    <form hx-post="/nel-portfolio/api/projects_handler.php" hx-target="#projects-cards-container" hx-swap="outerHTML" class="font-body">
        <input type="hidden" name="action" value="update_projects_cards">
        <div id="projects-cards-container" class="space-y-5">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Live Demo Button</label>
                    <input type="text" name="live_btn_text" value="<?= htmlspecialchars($projectsLiveBtnText) ?>" 
                        placeholder="Live Demo"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    <p class="font-body text-xs text-slate-500 mt-2">Text for the primary project button.</p>
                </div>

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Source Code Button</label>
                    <input type="text" name="code_btn_text" value="<?= htmlspecialchars($projectsCodeBtnText) ?>" 
                        placeholder="Source Code"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    <p class="font-body text-xs text-slate-500 mt-2">Text for the secondary project button.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Live Status Text</label>
                    <input type="text" name="status_live_text" value="<?= htmlspecialchars($projectsStatusLiveText) ?>" 
                        placeholder="Live"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    <p class="font-body text-xs text-slate-500 mt-2">Status for deployed projects.</p>
                </div>

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Development Status</label>
                    <input type="text" name="status_dev_text" value="<?= htmlspecialchars($projectsStatusDevText) ?>" 
                        placeholder="In Development"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    <p class="font-body text-xs text-slate-500 mt-2">Status for projects in development.</p>
                </div>

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Completed Text</label>
                    <input type="text" name="completed_text" value="<?= htmlspecialchars($projectsCompletedText) ?>" 
                        placeholder="Recently Completed"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    <p class="font-body text-xs text-slate-500 mt-2">Text for project completion status.</p>
                </div>
            </div>

            <!-- Card Preview -->
            <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800">
                <p class="font-subhead text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Card Preview</p>
                <div class="max-w-sm mx-auto">
                    <!-- Mock Project Card -->
                    <div class="relative glass-card rounded-2xl overflow-hidden border border-white/10 bg-gradient-to-br from-slate-900/90 to-slate-800/70">
                        <!-- Mock Image -->
                        <div class="relative aspect-[4/3] bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                            <i class="ph-bold ph-monitor-play text-4xl text-slate-600"></i>
                            
                            <!-- Project Number -->
                            <div class="absolute top-3 left-3">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-purple-500/30 to-pink-500/30 backdrop-blur-sm border border-purple-400/40 flex items-center justify-center">
                                    <span class="text-purple-300 font-bold text-sm">01</span>
                                </div>
                            </div>
                            
                            <!-- Mock Action Buttons -->
                            <div class="absolute bottom-3 left-3 right-3 flex gap-2">
                                <button class="flex-1 bg-gradient-to-r from-purple-500/25 to-pink-500/25 backdrop-blur-md text-white px-3 py-2 rounded-xl font-medium border border-purple-400/40 flex items-center justify-center gap-1.5 text-xs">
                                    <i class="ph-bold ph-arrow-square-out text-sm"></i>
                                    <span><?= htmlspecialchars($projectsLiveBtnText) ?></span>
                                </button>
                                <button class="flex-1 bg-slate-700/40 backdrop-blur-md text-white px-3 py-2 rounded-xl font-medium border border-slate-600/50 flex items-center justify-center gap-1.5 text-xs">
                                    <i class="ph-fill ph-github-logo text-sm"></i>
                                    <span><?= htmlspecialchars($projectsCodeBtnText) ?></span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Mock Info -->
                        <div class="p-5">
                            <h3 class="font-heading text-lg font-bold text-white mb-2">Sample Project</h3>
                            <p class="text-gray-400 text-sm mb-4">A brief description of the project goes here...</p>
                            
                            <!-- Mock Tech Stack -->
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                <span class="px-2 py-1 text-xs font-medium bg-slate-800/60 text-slate-300 rounded-lg border border-slate-700/50">React</span>
                                <span class="px-2 py-1 text-xs font-medium bg-slate-800/60 text-slate-300 rounded-lg border border-slate-700/50">Node.js</span>
                            </div>
                            
                            <!-- Mock Meta -->
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-1.5 text-slate-400">
                                    <i class="ph-bold ph-calendar text-purple-400"></i>
                                    <span><?= htmlspecialchars($projectsCompletedText) ?></span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></div>
                                    <span class="text-emerald-400 font-medium"><?= htmlspecialchars($projectsStatusLiveText) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-800/80">
                <button type="submit" 
                    class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                    <i class="ph-bold ph-floppy-disk text-base"></i>
                    <span>Save Card Text</span>
                </button>
            </div>
        </div>
    </form>
</div>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-slate-800/80 pb-6">
    <div>
        <h2 class="text-2xl font-bold font-turnpike text-white tracking-[0.2em] bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-100 to-cyan-300">
            PROJECTS MANAGEMENT
        </h2>
        <p class="font-body text-slate-400 text-xs sm:text-sm mt-1">Manage, add, and organize your showcase portfolio projects.</p>
    </div>
    <button onclick="openAddProjectModal()"
        class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 px-5 py-3 rounded-xl font-subhead font-semibold text-xs uppercase tracking-widest hover:shadow-lg hover:shadow-cyan-500/30 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 cursor-pointer">
        <i class="ph-bold ph-plus-circle text-lg"></i> Add Project
    </button>
</div>

<!-- Project List -->
<div id="projects-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (empty($projects)): ?>
        <div class="col-span-full glass-panel p-12 text-center rounded-3xl">
            <div class="w-16 h-16 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center mx-auto mb-4">
                <i class="ph-bold ph-folder-open text-3xl"></i>
            </div>
            <h3 class="font-subhead text-base font-semibold text-white mb-1">No projects listed yet</h3>
            <p class="font-body text-xs text-slate-400 max-w-sm mx-auto mb-6">Click the button above to add your first portfolio project entry.</p>
            <button onclick="openAddProjectModal()"
                class="px-5 py-2.5 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 font-subhead font-semibold text-xs uppercase tracking-wider hover:bg-cyan-500 hover:text-slate-950 transition-all inline-flex items-center gap-2">
                <i class="ph-bold ph-plus text-sm"></i> Create Project Entry
            </button>
        </div>
    <?php else: ?>
        <?php foreach ($projects as $project): ?>
            <div class="glass-panel rounded-3xl overflow-hidden flex flex-col group hover:border-cyan-500/40 transition-all duration-300 hover:-translate-y-1" id="project-<?= $project['id'] ?>">
                <?php if ($project['image_url']): ?>
                    <div class="h-48 overflow-hidden relative">
                        <img src="<?= htmlspecialchars($project['image_url']) ?>" alt="Project image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                    </div>
                <?php else: ?>
                    <div class="h-48 bg-slate-900/80 flex items-center justify-center relative border-b border-slate-800/80">
                        <i class="ph-bold ph-image text-5xl text-slate-700"></i>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                    </div>
                <?php endif; ?>

                <div class="p-6 flex-1 flex flex-col relative z-10 -mt-8">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-subhead text-lg font-semibold text-white group-hover:text-cyan-400 transition-colors"><?= htmlspecialchars($project['title']) ?></h3>
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-900 border border-slate-700 text-[10px] font-subhead text-slate-400">Order: <?= (int)$project['sort_order'] ?></span>
                    </div>

                    <p class="font-body text-slate-400 text-xs line-clamp-3 mb-6 flex-1"><?= htmlspecialchars($project['description'] ?? '') ?></p>

                    <div class="flex gap-2.5 mt-auto pt-4 border-t border-slate-800/80">
                        <button class="flex-1 bg-slate-900 hover:bg-slate-800 text-slate-200 hover:text-white px-3 py-2.5 rounded-xl font-subhead text-xs font-semibold uppercase tracking-wider border border-slate-800 transition-all flex items-center justify-center gap-1.5"
                            hx-get="/nel-portfolio/api/projects_handler.php?action=edit_form&id=<?= $project['id'] ?>"
                            hx-target="#modal-content"
                            hx-swap="innerHTML">
                            <i class="ph-bold ph-pencil text-sm"></i> Edit
                        </button>
                        <button class="flex-1 bg-red-500/10 hover:bg-red-500/20 text-red-400 px-3 py-2.5 rounded-xl font-subhead text-xs font-semibold uppercase tracking-wider border border-red-500/20 transition-all flex items-center justify-center gap-1.5"
                            hx-delete="/nel-portfolio/api/projects_handler.php?id=<?= $project['id'] ?>"
                            hx-confirm="Are you sure you want to delete this project?"
                            hx-target="#project-<?= $project['id'] ?>"
                            hx-swap="outerHTML swap:200ms">
                            <i class="ph-bold ph-trash text-sm"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Container -->
<div id="project-modal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="glass-panel border border-cyan-500/20 rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" id="modal-content">
        <!-- Default Add Form -->
        <div id="add-project-form-template" class="p-6 sm:p-8">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-800/80">
                <h3 class="font-turnpike text-lg font-bold text-white tracking-widest">ADD NEW PROJECT</h3>
                <button onclick="closeProjectModal()" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <form hx-post="/nel-portfolio/api/projects_handler.php" hx-target="#main-content" hx-swap="innerHTML"
                  onsubmit="closeProjectModal()" class="space-y-5 font-body">
                <input type="hidden" name="action" value="create">

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Project Title *</label>
                    <input type="text" name="title" required placeholder="My Awesome Web App"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                </div>

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Description</label>
                    <textarea name="description" rows="4" placeholder="Short summary of technologies used and features..."
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all"></textarea>
                </div>

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Image URL</label>
                    <input type="url" name="image_url" placeholder="https://example.com/screenshot.jpg"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                </div>

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Tech Stack</label>
                    <input type="text" name="tech_stack" placeholder="React, Node.js, MongoDB, TailwindCSS"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    <p class="font-body text-xs text-slate-500 mt-2">Separate technologies with commas (e.g., React, Node.js, MongoDB)</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Live Demo URL</label>
                        <input type="url" name="live_link" placeholder="https://myproject.com"
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    </div>
                    <div>
                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">GitHub Repository URL</label>
                        <input type="url" name="github_link" placeholder="https://github.com/user/repo"
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="0"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all">
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-800/80">
                    <button type="button" onclick="closeProjectModal()"
                        class="px-5 py-3 rounded-xl font-subhead text-xs font-semibold uppercase tracking-wider text-slate-400 hover:text-white transition-colors">Cancel</button>
                    <button type="submit"
                        class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer">
                        Save Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Capture the add form HTML so we can restore it after an edit form loads
    (function () {
        var t = document.getElementById('add-project-form-template');
        if (t) window._addProjectFormHTML = t.outerHTML;
    })();

    function openAddProjectModal() {
        var modalContent = document.getElementById('modal-content');
        // If the add form template is gone (edit form replaced it), restore it
        if (!document.getElementById('add-project-form-template')) {
            modalContent.innerHTML = window._addProjectFormHTML;
            htmx.process(modalContent);
        }
        document.getElementById('project-modal').classList.remove('hidden');
    }

    function closeProjectModal() {
        document.getElementById('project-modal').classList.add('hidden');
    }

    // Close modal when clicking the backdrop
    document.getElementById('project-modal').addEventListener('click', function (e) {
        if (e.target === this) closeProjectModal();
    });
</script>
