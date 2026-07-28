<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';
check_auth_htmx();

$method = $_SERVER['REQUEST_METHOD'];

// Handle DELETE
if ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
        $stmt->execute([$id]);
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Skill deleted successfully\"}}");
        echo ""; // Return empty to remove element
        exit;
    }
}

// Handle GET for edit form
if ($method === 'GET' && isset($_GET['action']) && $_GET['action'] === 'edit_form') {
    $id = $_GET['id'] ?? null;
    $stmt = $pdo->prepare("SELECT * FROM skills WHERE id = ?");
    $stmt->execute([$id]);
    $skill = $stmt->fetch();
    
    if (!$skill) {
        echo "<p class='text-red-400 p-4'>Skill not found.</p>";
        exit;
    }
    ?>
    <div class="p-6 sm:p-8">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-800/80">
            <h3 class="font-turnpike text-lg font-bold text-white tracking-widest">EDIT SKILL</h3>
            <button onclick="closeSkillModal()" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        
        <form hx-post="/nel-portfolio/api/skills_handler.php" hx-target="#main-content" hx-swap="innerHTML" 
              onsubmit="closeSkillModal()" class="space-y-5 font-body">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $skill['id'] ?>">
            
            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Skill Name *</label>
                <input type="text" name="name" value="<?= htmlspecialchars($skill['name']) ?>" required 
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all">
            </div>
            
            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Category</label>
                <input type="text" name="category" value="<?= htmlspecialchars($skill['category']) ?>" 
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all">
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Proficiency Level *</label>
                <select name="proficiency_level" required 
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all">
                    <option value="Beginner" <?= $skill['proficiency_level'] === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
                    <option value="Intermediate" <?= $skill['proficiency_level'] === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
                    <option value="Advanced" <?= $skill['proficiency_level'] === 'Advanced' ? 'selected' : '' ?>>Advanced</option>
                    <option value="Expert" <?= $skill['proficiency_level'] === 'Expert' ? 'selected' : '' ?>>Expert</option>
                </select>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-800/80">
                <button type="button" onclick="closeSkillModal()" 
                    class="px-5 py-3 rounded-xl font-subhead text-xs font-semibold uppercase tracking-wider text-slate-400 hover:text-white transition-colors">Cancel</button>
                <button type="submit" 
                    class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer">
                    Update Skill
                </button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('skill-modal').classList.remove('hidden');
    </script>
    <?php
    exit;
}

// Handle POST (Create / Update)
if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_skills_header') {
        $badgeText = $_POST['badge_text'] ?? 'Technical Proficiency';
        $title = $_POST['title'] ?? 'Technical Arsenal';
        $subtitle = $_POST['subtitle'] ?? 'A comprehensive toolkit of cutting-edge technologies and frameworks I leverage to build exceptional digital experiences.';
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('skills_badge_text', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$badgeText, $badgeText]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('skills_title', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$title, $title]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('skills_subtitle', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$subtitle, $subtitle]);
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Skills header updated successfully\"}}");
        
        ?>
        <div id="skills-header-container" class="space-y-5">
            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Badge Text</label>
                <input type="text" name="badge_text" value="<?= htmlspecialchars($badgeText) ?>" 
                    placeholder="Technical Proficiency"
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                <p class="font-body text-xs text-slate-500 mt-2">The small badge text above the main title.</p>
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Section Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" 
                    placeholder="Technical Arsenal"
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                <p class="font-body text-xs text-slate-500 mt-2">The main title for the skills section. Use 'Arsenal' for gradient effect.</p>
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Section Subtitle</label>
                <textarea name="subtitle" rows="3" 
                    placeholder="A comprehensive toolkit of cutting-edge technologies and frameworks..."
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all leading-relaxed"><?= htmlspecialchars($subtitle) ?></textarea>
                <p class="font-body text-xs text-slate-500 mt-2">The description paragraph below the main title.</p>
            </div>

            <!-- Preview -->
            <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800">
                <p class="font-subhead text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Preview</p>
                <div class="text-center space-y-4">
                    <div class="inline-flex items-center gap-3 bg-gradient-to-r from-white/5 to-white/0 backdrop-blur-sm border border-white/10 rounded-full px-6 py-2.5">
                        <i class="ph-bold ph-code text-brand-400 text-sm"></i>
                        <span class="text-gray-400 font-medium tracking-wider uppercase text-xs"><?= htmlspecialchars($badgeText) ?></span>
                    </div>
                    <h2 class="font-heading text-3xl font-black text-white">
                        <?php 
                        $titleParts = explode(' ', $title);
                        if (count($titleParts) > 1 && end($titleParts) === 'Arsenal') {
                            $lastWord = array_pop($titleParts);
                            echo htmlspecialchars(implode(' ', $titleParts)) . ' <span class="text-gradient bg-clip-text text-transparent bg-gradient-to-r from-brand-400 via-purple-400 to-pink-400">' . htmlspecialchars($lastWord) . '</span>';
                        } else {
                            echo htmlspecialchars($title);
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
    
    $name = $_POST['name'] ?? '';
    $proficiency_level = $_POST['proficiency_level'] ?? '';
    $category = $_POST['category'] ?? '';
    
    if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO skills (name, proficiency_level, category) VALUES (?, ?, ?)");
        $stmt->execute([$name, $proficiency_level, $category]);
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Skill added successfully\"}}");
    } elseif ($action === 'update') {
        $id = $_POST['id'] ?? null;
        $stmt = $pdo->prepare("UPDATE skills SET name=?, proficiency_level=?, category=? WHERE id=?");
        $stmt->execute([$name, $proficiency_level, $category, $id]);
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Skill updated successfully\"}}");
    }
    
    // Redirect to reload via HTMX
    include __DIR__ . '/../admin/views/skills.php';
    exit;
}
?>
