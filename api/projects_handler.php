<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';
check_auth_htmx();

$method = $_SERVER['REQUEST_METHOD'];

// Handle DELETE
if ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Project deleted successfully\"}}");
        echo ""; // Return empty to remove element
        exit;
    }
}

// Handle GET for edit form
if ($method === 'GET' && isset($_GET['action']) && $_GET['action'] === 'edit_form') {
    $id = $_GET['id'] ?? null;
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();
    
    if (!$project) {
        echo "<p>Project not found.</p>";
        exit;
    }
    ?>
    <div class="p-6 sm:p-8">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-800/80">
            <h3 class="font-turnpike text-lg font-bold text-white tracking-widest">EDIT PROJECT</h3>
            <button onclick="closeProjectModal()" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>

        <form hx-post="/nel-portfolio/api/projects_handler.php" hx-target="#main-content" hx-swap="innerHTML"
              onsubmit="closeProjectModal()" class="space-y-5 font-body">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $project['id'] ?>">

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Project Title *</label>
                <input type="text" name="title" value="<?= htmlspecialchars($project['title']) ?>" required
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Description</label>
                <textarea name="description" rows="4"
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all"><?= htmlspecialchars($project['description']) ?></textarea>
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Image URL</label>
                <input type="url" name="image_url" value="<?= htmlspecialchars($project['image_url']) ?>"
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Live Demo URL</label>
                    <input type="url" name="live_link" value="<?= htmlspecialchars($project['live_link']) ?>"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                </div>
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">GitHub Repository URL</label>
                    <input type="url" name="github_link" value="<?= htmlspecialchars($project['github_link']) ?>"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                </div>
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Tech Stack</label>
                <input type="text" name="tech_stack" value="<?= htmlspecialchars($project['tech_stack'] ?? '') ?>" 
                    placeholder="React, Node.js, MongoDB, etc."
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                <p class="font-body text-xs text-slate-500 mt-2">Separate technologies with commas (e.g., React, Node.js, MongoDB)</p>
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="<?= htmlspecialchars($project['sort_order']) ?>"
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all">
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-800/80">
                <button type="button" onclick="closeProjectModal()"
                    class="px-5 py-3 rounded-xl font-subhead text-xs font-semibold uppercase tracking-wider text-slate-400 hover:text-white transition-colors">Cancel</button>
                <button type="submit"
                    class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer">
                    Update Project
                </button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('project-modal').classList.remove('hidden');
    </script>
    <?php
    exit;
}

// Handle POST (Create / Update)
if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_projects_cards') {
        $liveBtnText = $_POST['live_btn_text'] ?? 'Live Demo';
        $codeBtnText = $_POST['code_btn_text'] ?? 'Source Code';
        $statusLiveText = $_POST['status_live_text'] ?? 'Live';
        $statusDevText = $_POST['status_dev_text'] ?? 'In Development';
        $completedText = $_POST['completed_text'] ?? 'Completed';
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('projects_live_btn_text', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$liveBtnText, $liveBtnText]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('projects_code_btn_text', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$codeBtnText, $codeBtnText]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('projects_status_live_text', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$statusLiveText, $statusLiveText]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('projects_status_dev_text', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$statusDevText, $statusDevText]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('projects_completed_text', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$completedText, $completedText]);
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Project cards settings updated successfully\"}}");
        
        ?>
        <div id="projects-cards-container" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Live Demo Button Text</label>
                    <input type="text" name="live_btn_text" value="<?= htmlspecialchars($liveBtnText) ?>" 
                        placeholder="Live Demo"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    <p class="font-body text-xs text-slate-500 mt-2">Text for the button that links to live project.</p>
                </div>

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Source Code Button Text</label>
                    <input type="text" name="code_btn_text" value="<?= htmlspecialchars($codeBtnText) ?>" 
                        placeholder="Source Code"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    <p class="font-body text-xs text-slate-500 mt-2">Text for the button that links to GitHub repository.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Live Status Text</label>
                    <input type="text" name="status_live_text" value="<?= htmlspecialchars($statusLiveText) ?>" 
                        placeholder="Live"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    <p class="font-body text-xs text-slate-500 mt-2">Status text for live projects.</p>
                </div>

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Development Status Text</label>
                    <input type="text" name="status_dev_text" value="<?= htmlspecialchars($statusDevText) ?>" 
                        placeholder="In Development"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    <p class="font-body text-xs text-slate-500 mt-2">Status text for projects in development.</p>
                </div>

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Completed Status Text</label>
                    <input type="text" name="completed_text" value="<?= htmlspecialchars($completedText) ?>" 
                        placeholder="Completed"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    <p class="font-body text-xs text-slate-500 mt-2">Status text for completed projects.</p>
                </div>
            </div>

            <!-- Preview -->
            <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800">
                <p class="font-subhead text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Preview</p>
                <div class="bg-slate-800/50 rounded-xl p-6 border border-slate-700/50 hover:border-slate-600/50 transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            <span class="text-xs text-green-400 font-medium"><?= htmlspecialchars($statusLiveText) ?></span>
                        </div>
                        <span class="text-xs text-slate-500 font-medium"><?= htmlspecialchars($completedText) ?></span>
                    </div>
                    
                    <h3 class="font-heading text-lg font-bold text-white mb-2">Sample Project</h3>
                    <p class="text-slate-400 text-sm mb-6 leading-relaxed">A sample project description to show how the card will look.</p>
                    
                    <div class="flex gap-3">
                        <button class="flex-1 bg-gradient-to-r from-cyan-500 to-teal-500 text-slate-900 font-semibold text-xs py-2.5 rounded-lg transition-all hover:shadow-lg">
                            <?= htmlspecialchars($liveBtnText) ?>
                        </button>
                        <button class="flex-1 border border-slate-600 text-slate-300 font-semibold text-xs py-2.5 rounded-lg hover:bg-slate-700 transition-all">
                            <?= htmlspecialchars($codeBtnText) ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-800/80">
                <button type="submit" 
                    class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                    <i class="ph-bold ph-floppy-disk text-base"></i>
                    <span>Save Card Settings</span>
                </button>
            </div>
        </div>
        <?php
        exit;
    }
    
    if ($action === 'update_projects_header') {
        $badgeText = $_POST['badge_text'] ?? 'Portfolio Showcase';
        $title = $_POST['title'] ?? 'Selected Works';
        $subtitle = $_POST['subtitle'] ?? 'A curated collection of my most impactful projects, showcasing innovation, technical expertise, and creative problem-solving.';
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('projects_badge_text', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$badgeText, $badgeText]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('projects_title', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$title, $title]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('projects_subtitle', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$subtitle, $subtitle]);
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Projects header updated successfully\"}}");
        
        ?>
        <div id="projects-header-container" class="space-y-5">
            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Badge Text</label>
                <input type="text" name="badge_text" value="<?= htmlspecialchars($badgeText) ?>" 
                    placeholder="Portfolio Showcase"
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                <p class="font-body text-xs text-slate-500 mt-2">The small badge text above the main title.</p>
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Section Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" 
                    placeholder="Selected Works"
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                <p class="font-body text-xs text-slate-500 mt-2">The main title for the projects section. Use 'Works' for gradient effect.</p>
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Section Subtitle</label>
                <textarea name="subtitle" rows="3" 
                    placeholder="A curated collection of my most impactful projects..."
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all leading-relaxed"><?= htmlspecialchars($subtitle) ?></textarea>
                <p class="font-body text-xs text-slate-500 mt-2">The description paragraph below the main title.</p>
            </div>

            <!-- Preview -->
            <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800">
                <p class="font-subhead text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Preview</p>
                <div class="text-center space-y-4">
                    <div class="inline-flex items-center gap-3 bg-gradient-to-r from-white/5 to-white/0 backdrop-blur-sm border border-white/10 rounded-full px-6 py-2.5">
                        <i class="ph-bold ph-folder-open text-purple-400 text-sm"></i>
                        <span class="text-gray-400 font-medium tracking-wider uppercase text-xs"><?= htmlspecialchars($badgeText) ?></span>
                    </div>
                    <h2 class="font-heading text-3xl font-black text-white">
                        <?php 
                        $titleParts = explode(' ', $title);
                        if (count($titleParts) > 1 && end($titleParts) === 'Works') {
                            $lastWord = array_pop($titleParts);
                            echo htmlspecialchars(implode(' ', $titleParts)) . ' <span class="text-gradient bg-clip-text text-transparent bg-gradient-to-r from-purple-400 via-pink-400 to-brand-400">' . htmlspecialchars($lastWord) . '</span>';
                        } else {
                            echo '<span class="text-gradient bg-clip-text text-transparent bg-gradient-to-r from-purple-400 via-pink-400 to-brand-400">' . htmlspecialchars($title) . '</span>';
                        }
                        ?>
                    </h2>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-2xl mx-auto"><?= htmlspecialchars($subtitle) ?></p>
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
        <?php
        exit;
    }
    
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    $live_link = $_POST['live_link'] ?? '';
    $github_link = $_POST['github_link'] ?? '';
    $tech_stack = $_POST['tech_stack'] ?? '';
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    
    if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO projects (title, description, image_url, live_link, github_link, tech_stack, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $image_url, $live_link, $github_link, $tech_stack, $sort_order]);
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Project created successfully\"}}");
    } elseif ($action === 'update') {
        $id = $_POST['id'] ?? null;
        $stmt = $pdo->prepare("UPDATE projects SET title=?, description=?, image_url=?, live_link=?, github_link=?, tech_stack=?, sort_order=? WHERE id=?");
        $stmt->execute([$title, $description, $image_url, $live_link, $github_link, $tech_stack, $sort_order, $id]);
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Project updated successfully\"}}");
    }
    
    // Trigger a refresh of the projects view by redirecting the HTMX request
    header('HX-Redirect: /nel-portfolio/admin/index.php'); // Simplest way to refresh whole state, but ideally we'd just return the projects.php view.
    // Let's actually include projects.php to do it seamlessly without page reload:
    include __DIR__ . '/../admin/views/projects.php';
    exit;
}
?>
