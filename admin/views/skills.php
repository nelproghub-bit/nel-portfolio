<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';
check_auth();

// Fetch skills section settings
$settingsQuery = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'skills_%'");
$skillsSettings = [];
while ($row = $settingsQuery->fetch()) {
    $skillsSettings[$row['setting_key']] = $row['setting_value'];
}

$skillsBadgeText = $skillsSettings['skills_badge_text'] ?? 'Technical Proficiency';
$skillsTitle = $skillsSettings['skills_title'] ?? 'Technical Arsenal';
$skillsSubtitle = $skillsSettings['skills_subtitle'] ?? 'A comprehensive toolkit of cutting-edge technologies and frameworks I leverage to build exceptional digital experiences.';

$skillRows = $pdo->query("SELECT * FROM skills ORDER BY category ASC, name ASC")->fetchAll();
$skills = [];
foreach ($skillRows as $row) {
    $cat = $row['category'] ?: 'Uncategorized';
    $skills[$cat][] = $row;
}
?>

<!-- Skills Section Header Customization -->
<div class="glass-panel p-6 sm:p-8 rounded-3xl mb-8">
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/80">
        <div class="w-9 h-9 rounded-xl bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400">
            <i class="ph-bold ph-text-h text-lg"></i>
        </div>
        <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">SKILLS SECTION HEADER</h3>
    </div>
    
    <form hx-post="/nel-portfolio/api/skills_handler.php" hx-target="#skills-header-container" hx-swap="outerHTML" class="font-body">
        <input type="hidden" name="action" value="update_skills_header">
        <div id="skills-header-container" class="space-y-5">
            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Badge Text</label>
                <input type="text" name="badge_text" value="<?= htmlspecialchars($skillsBadgeText) ?>" 
                    placeholder="Technical Proficiency"
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                <p class="font-body text-xs text-slate-500 mt-2">The small badge text above the main title.</p>
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Section Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($skillsTitle) ?>" 
                    placeholder="Technical Arsenal"
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                <p class="font-body text-xs text-slate-500 mt-2">The main title for the skills section. Use 'Arsenal' for gradient effect.</p>
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Section Subtitle</label>
                <textarea name="subtitle" rows="3" 
                    placeholder="A comprehensive toolkit of cutting-edge technologies and frameworks..."
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all leading-relaxed"><?= htmlspecialchars($skillsSubtitle) ?></textarea>
                <p class="font-body text-xs text-slate-500 mt-2">The description paragraph below the main title.</p>
            </div>

            <!-- Preview -->
            <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800">
                <p class="font-subhead text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Preview</p>
                <div class="text-center space-y-4">
                    <div class="inline-flex items-center gap-3 bg-gradient-to-r from-white/5 to-white/0 backdrop-blur-sm border border-white/10 rounded-full px-6 py-2.5">
                        <i class="ph-bold ph-code text-brand-400 text-sm"></i>
                        <span class="text-gray-400 font-medium tracking-wider uppercase text-xs"><?= htmlspecialchars($skillsBadgeText) ?></span>
                    </div>
                    <h2 class="font-heading text-3xl font-black text-white">
                        <?php 
                        $titleParts = explode(' ', $skillsTitle);
                        if (count($titleParts) > 1 && end($titleParts) === 'Arsenal') {
                            $lastWord = array_pop($titleParts);
                            echo htmlspecialchars(implode(' ', $titleParts)) . ' <span class="text-gradient bg-clip-text text-transparent bg-gradient-to-r from-brand-400 via-purple-400 to-pink-400">' . htmlspecialchars($lastWord) . '</span>';
                        } else {
                            echo htmlspecialchars($skillsTitle);
                        }
                        ?>
                    </h2>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-2xl mx-auto"><?= htmlspecialchars($skillsSubtitle) ?></p>
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

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-slate-800/80 pb-6">
    <div>
        <h2 class="text-2xl font-bold font-turnpike text-white tracking-[0.2em] bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-100 to-cyan-300">
            SKILLS & PROFICIENCIES
        </h2>
        <p class="font-body text-slate-400 text-xs sm:text-sm mt-1">Organize your technical capabilities by category and proficiency level.</p>
    </div>
    <button onclick="openAddSkillModal()" 
        class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 px-5 py-3 rounded-xl font-subhead font-semibold text-xs uppercase tracking-widest hover:shadow-lg hover:shadow-cyan-500/30 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 cursor-pointer">
        <i class="ph-bold ph-plus-circle text-lg"></i> Add Skill
    </button>
</div>

<div id="skills-list" class="space-y-8">
    <?php if (empty($skills)): ?>
        <div class="glass-panel p-12 text-center rounded-3xl">
            <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto mb-4">
                <i class="ph-bold ph-lightning text-3xl"></i>
            </div>
            <h3 class="font-subhead text-base font-semibold text-white mb-1">No skills cataloged yet</h3>
            <p class="font-body text-xs text-slate-400 max-w-sm mx-auto mb-6">Add your tech stack skills to display on your portfolio frontend.</p>
            <button onclick="openAddSkillModal()" 
                class="px-5 py-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 font-subhead font-semibold text-xs uppercase tracking-wider hover:bg-emerald-500 hover:text-slate-950 transition-all inline-flex items-center gap-2">
                <i class="ph-bold ph-plus text-sm"></i> Add First Skill
            </button>
        </div>
    <?php else: ?>
        <?php foreach ($skills as $category => $categorySkills): ?>
            <div class="glass-panel p-6 sm:p-8 rounded-3xl">
                <div class="flex items-center gap-3 mb-6 pb-3 border-b border-slate-800/80">
                    <div class="w-8 h-8 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                        <i class="ph-bold ph-tag text-base"></i>
                    </div>
                    <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">
                        <?= htmlspecialchars(strtoupper($category ?: 'UNCATEGORIZED')) ?>
                    </h3>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($categorySkills as $skill): ?>
                        <div class="bg-slate-900/60 border border-slate-800 p-4 rounded-2xl flex justify-between items-center group hover:border-cyan-500/30 transition-all" id="skill-<?= $skill['id'] ?>">
                            <div>
                                <div class="font-subhead text-xs font-semibold text-white group-hover:text-cyan-400 transition-colors"><?= htmlspecialchars($skill['name']) ?></div>
                                <div class="font-body text-[11px] text-slate-400 mt-1 flex items-center gap-1.5">
                                    <span class="inline-block w-2 h-2 rounded-full 
                                        <?= $skill['proficiency_level'] === 'Expert' ? 'bg-cyan-400 shadow-sm shadow-cyan-400' : 
                                           ($skill['proficiency_level'] === 'Advanced' ? 'bg-emerald-400 shadow-sm shadow-emerald-400' : 
                                           ($skill['proficiency_level'] === 'Intermediate' ? 'bg-amber-400' : 'bg-slate-500')) ?>">
                                    </span>
                                    <?= htmlspecialchars($skill['proficiency_level']) ?>
                                </div>
                            </div>
                            <div class="flex gap-1.5 opacity-80 group-hover:opacity-100 transition-opacity">
                                <button hx-get="/nel-portfolio/api/skills_handler.php?action=edit_form&id=<?= $skill['id'] ?>" hx-target="#modal-content" hx-swap="innerHTML" class="w-8 h-8 rounded-lg bg-slate-800/80 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                                    <i class="ph-bold ph-pencil text-sm"></i>
                                </button>
                                <button hx-delete="/nel-portfolio/api/skills_handler.php?id=<?= $skill['id'] ?>" 
                                        hx-confirm="Delete this skill?"
                                        hx-target="#skill-<?= $skill['id'] ?>" hx-swap="outerHTML swap:200ms"
                                        class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 flex items-center justify-center transition-colors">
                                    <i class="ph-bold ph-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Container -->
<div id="skill-modal" class="admin-modal fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="modal-panel glass-panel border border-cyan-500/20 rounded-3xl shadow-2xl max-w-lg w-full" id="modal-content">
        <!-- Default: Add Skill Form -->
        <div id="add-skill-form-template" class="p-6 sm:p-8">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-800/80">
                <h3 class="font-turnpike text-lg font-bold text-white tracking-widest">ADD NEW SKILL</h3>
                <button onclick="closeSkillModal()" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>
            
            <form hx-post="/nel-portfolio/api/skills_handler.php" hx-target="#main-content" hx-swap="innerHTML" 
                  onsubmit="closeSkillModal()" class="space-y-5 font-body">
                <input type="hidden" name="action" value="create">
                
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Skill Name *</label>
                    <input type="text" name="name" required placeholder="e.g. React, PHP, Tailwind CSS" 
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                </div>
                
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Category</label>
                    <input type="text" name="category" placeholder="Frontend, Backend, Tools..." 
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                </div>
                
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Proficiency Level</label>
                    <select name="proficiency_level" 
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all">
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate" selected>Intermediate</option>
                        <option value="Advanced">Advanced</option>
                        <option value="Expert">Expert</option>
                    </select>
                </div>
                
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-800/80">
                    <button type="button" onclick="closeSkillModal()" 
                        class="px-5 py-3 rounded-xl font-subhead text-xs font-semibold uppercase tracking-wider text-slate-400 hover:text-white transition-colors">Cancel</button>
                    <button type="submit" 
                        class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer">
                        Save Skill
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Capture the add-form HTML immediately (works for both full page load and HTMX fragment)
    (function() {
        var template = document.getElementById('add-skill-form-template');
        if (template) {
            window._addSkillFormHTML = template.outerHTML;
        }
    })();

    function openAddSkillModal() {
        // Restore Add form if it was replaced by Edit form
        var modalContent = document.getElementById('modal-content');
        if (!document.getElementById('add-skill-form-template')) {
            if (_addSkillFormHTML) {
                modalContent.innerHTML = _addSkillFormHTML;
            }
            htmx.process(modalContent); // re-init HTMX bindings
        }
        document.getElementById('skill-modal').classList.remove('hidden');
    }

    function closeSkillModal() {
        document.getElementById('skill-modal').classList.add('hidden');
    }
</script>
