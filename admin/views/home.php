<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';
check_auth();

// Fetch home/hero settings
$settingsQuery = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'hero_%'");
$heroSettings = [];
while ($row = $settingsQuery->fetch()) {
    $heroSettings[$row['setting_key']] = $row['setting_value'];
}

$heroBadgeText = $heroSettings['hero_badge_text'] ?? 'Available for Work';
$heroTitleLine1 = $heroSettings['hero_title_line1'] ?? 'Creative';
$heroTitleLine2 = $heroSettings['hero_title_line2'] ?? 'Developer';
$heroTitleFontFamily = $heroSettings['hero_title_font_family'] ?? 'Outfit';
$heroTitleFontSize = $heroSettings['hero_title_font_size'] ?? '72';
$heroTitleFontWeight = $heroSettings['hero_title_font_weight'] ?? '900';
$heroSubtitle = $heroSettings['hero_subtitle'] ?? 'Crafting <span class="text-white font-medium">digital experiences</span> that merge cutting-edge technology with <span class="text-white font-medium">premium design aesthetics</span>.';
$heroPrimaryBtnText = $heroSettings['hero_primary_btn_text'] ?? 'Discover My Work';
$heroPrimaryBtnLink = $heroSettings['hero_primary_btn_link'] ?? '#about';
$heroSecondaryBtnText = $heroSettings['hero_secondary_btn_text'] ?? 'View Projects';
$heroSecondaryBtnLink = $heroSettings['hero_secondary_btn_link'] ?? '#projects';
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-slate-800/80 pb-6">
    <div>
        <h2 class="text-2xl font-bold font-turnpike text-white tracking-[0.2em] bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-100 to-cyan-300">
            HOME / HERO SECTION
        </h2>
        <p class="font-body text-slate-400 text-xs sm:text-sm mt-1">Customize your homepage hero section text, titles, and call-to-action buttons.</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
    <!-- Badge & Titles Card -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/80">
            <div class="w-9 h-9 rounded-xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                <i class="ph-bold ph-text-h text-lg"></i>
            </div>
            <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">HERO TITLES & BADGE</h3>
        </div>
        
        <form hx-post="/nel-portfolio/api/home_handler.php" hx-target="#titles-container" hx-swap="outerHTML" class="font-body">
            <input type="hidden" name="action" value="update_titles">
            <div id="titles-container" class="space-y-5">
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Status Badge Text</label>
                    <input type="text" name="badge_text" value="<?= htmlspecialchars($heroBadgeText) ?>" 
                        placeholder="Available for Work"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    <p class="font-body text-xs text-slate-500 mt-2">The animated badge text at the top of the hero section.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Title Line 1</label>
                        <input type="text" name="title_line1" value="<?= htmlspecialchars($heroTitleLine1) ?>" 
                            placeholder="Creative"
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    </div>
                    <div>
                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Title Line 2 (Gradient)</label>
                        <input type="text" name="title_line2" value="<?= htmlspecialchars($heroTitleLine2) ?>" 
                            placeholder="Developer"
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    </div>
                </div>

                <!-- Font Styling Controls -->
                <div class="p-5 rounded-2xl bg-slate-900/40 border border-slate-800 space-y-4">
                    <h4 class="font-subhead text-xs font-semibold uppercase text-cyan-300 tracking-wider flex items-center gap-2">
                        <i class="ph-bold ph-text-aa text-cyan-400"></i>
                        Title Font Styling
                    </h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Font Family</label>
                            <select name="font_family" 
                                class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all">
                                <option value="Outfit" <?= $heroTitleFontFamily === 'Outfit' ? 'selected' : '' ?>>Outfit (Default)</option>
                                <option value="Inter" <?= $heroTitleFontFamily === 'Inter' ? 'selected' : '' ?>>Inter</option>
                                <option value="Poppins" <?= $heroTitleFontFamily === 'Poppins' ? 'selected' : '' ?>>Poppins</option>
                                <option value="Montserrat" <?= $heroTitleFontFamily === 'Montserrat' ? 'selected' : '' ?>>Montserrat</option>
                                <option value="Raleway" <?= $heroTitleFontFamily === 'Raleway' ? 'selected' : '' ?>>Raleway</option>
                                <option value="Space Grotesk" <?= $heroTitleFontFamily === 'Space Grotesk' ? 'selected' : '' ?>>Space Grotesk</option>
                                <option value="Playfair Display" <?= $heroTitleFontFamily === 'Playfair Display' ? 'selected' : '' ?>>Playfair Display</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Font Size (px)</label>
                            <input type="number" name="font_size" value="<?= htmlspecialchars($heroTitleFontSize) ?>" 
                                min="24" max="120" step="2"
                                class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                            <p class="font-body text-xs text-slate-500 mt-1">24-120px recommended</p>
                        </div>

                        <div>
                            <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Font Weight</label>
                            <select name="font_weight" 
                                class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all">
                                <option value="300" <?= $heroTitleFontWeight === '300' ? 'selected' : '' ?>>Light (300)</option>
                                <option value="400" <?= $heroTitleFontWeight === '400' ? 'selected' : '' ?>>Regular (400)</option>
                                <option value="500" <?= $heroTitleFontWeight === '500' ? 'selected' : '' ?>>Medium (500)</option>
                                <option value="600" <?= $heroTitleFontWeight === '600' ? 'selected' : '' ?>>Semi Bold (600)</option>
                                <option value="700" <?= $heroTitleFontWeight === '700' ? 'selected' : '' ?>>Bold (700)</option>
                                <option value="800" <?= $heroTitleFontWeight === '800' ? 'selected' : '' ?>>Extra Bold (800)</option>
                                <option value="900" <?= $heroTitleFontWeight === '900' ? 'selected' : '' ?>>Black (900)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800">
                    <p class="font-subhead text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Preview</p>
                    <div class="space-y-3">
                        <div class="inline-flex items-center gap-2 bg-gradient-to-r from-white/10 to-white/5 backdrop-blur-sm border border-white/10 rounded-full px-4 py-2">
                            <div class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                            </div>
                            <span class="text-white font-medium tracking-wider uppercase text-xs"><?= htmlspecialchars($heroBadgeText) ?></span>
                        </div>
                        <div>
                            <h1 style="font-family: '<?= htmlspecialchars($heroTitleFontFamily) ?>', sans-serif; font-size: <?= htmlspecialchars($heroTitleFontSize) ?>px; font-weight: <?= htmlspecialchars($heroTitleFontWeight) ?>;" class="text-white mb-1 leading-tight"><?= htmlspecialchars($heroTitleLine1) ?></h1>
                            <h1 style="font-family: '<?= htmlspecialchars($heroTitleFontFamily) ?>', sans-serif; font-size: <?= htmlspecialchars($heroTitleFontSize) ?>px; font-weight: <?= htmlspecialchars($heroTitleFontWeight) ?>;" class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 leading-tight"><?= htmlspecialchars($heroTitleLine2) ?></h1>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-800/80">
                    <button type="submit" 
                        class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                        <i class="ph-bold ph-floppy-disk text-base"></i>
                        <span>Save Titles</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Subtitle Card -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/80">
            <div class="w-9 h-9 rounded-xl bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400">
                <i class="ph-bold ph-quotes text-lg"></i>
            </div>
            <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">HERO SUBTITLE</h3>
        </div>
        
        <form hx-post="/nel-portfolio/api/home_handler.php" hx-target="#subtitle-container" hx-swap="outerHTML" class="font-body">
            <input type="hidden" name="action" value="update_subtitle">
            <div id="subtitle-container" class="space-y-5">
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Subtitle / Tagline</label>
                    <textarea name="subtitle" rows="4" 
                        placeholder="Crafting digital experiences..."
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all leading-relaxed"><?= htmlspecialchars($heroSubtitle) ?></textarea>
                    <p class="font-body text-xs text-slate-500 mt-2">Use HTML tags like &lt;span class="text-white font-medium"&gt;text&lt;/span&gt; to highlight keywords.</p>
                </div>

                <!-- Preview -->
                <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800">
                    <p class="font-subhead text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Preview</p>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        <?= $heroSubtitle ?>
                    </p>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-800/80">
                    <button type="submit" 
                        class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                        <i class="ph-bold ph-floppy-disk text-base"></i>
                        <span>Save Subtitle</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Call-to-Action Buttons Card -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl xl:col-span-2">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/80">
            <div class="w-9 h-9 rounded-xl bg-pink-500/10 border border-pink-500/30 flex items-center justify-center text-pink-400">
                <i class="ph-bold ph-cursor-click text-lg"></i>
            </div>
            <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">CALL-TO-ACTION BUTTONS</h3>
        </div>
        
        <form hx-post="/nel-portfolio/api/home_handler.php" hx-target="#buttons-container" hx-swap="outerHTML" class="font-body">
            <input type="hidden" name="action" value="update_buttons">
            <div id="buttons-container">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Primary Button -->
                    <div class="space-y-4 p-5 rounded-2xl bg-slate-900/40 border border-slate-800">
                        <h4 class="font-subhead text-xs font-semibold uppercase text-indigo-300 tracking-wider flex items-center gap-2">
                            <i class="ph-bold ph-star text-indigo-400"></i>
                            Primary Button (Gradient)
                        </h4>
                        <div>
                            <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Button Text</label>
                            <input type="text" name="primary_btn_text" value="<?= htmlspecialchars($heroPrimaryBtnText) ?>" 
                                placeholder="Discover My Work"
                                class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                        </div>
                        <div>
                            <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Link / Anchor</label>
                            <input type="text" name="primary_btn_link" value="<?= htmlspecialchars($heroPrimaryBtnLink) ?>" 
                                placeholder="#about"
                                class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                            <p class="font-body text-xs text-slate-500 mt-2">Use #about, #skills, or full URL</p>
                        </div>
                    </div>

                    <!-- Secondary Button -->
                    <div class="space-y-4 p-5 rounded-2xl bg-slate-900/40 border border-slate-800">
                        <h4 class="font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider flex items-center gap-2">
                            <i class="ph-bold ph-circle text-slate-400"></i>
                            Secondary Button (Ghost)
                        </h4>
                        <div>
                            <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Button Text</label>
                            <input type="text" name="secondary_btn_text" value="<?= htmlspecialchars($heroSecondaryBtnText) ?>" 
                                placeholder="View Projects"
                                class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                        </div>
                        <div>
                            <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Link / Anchor</label>
                            <input type="text" name="secondary_btn_link" value="<?= htmlspecialchars($heroSecondaryBtnLink) ?>" 
                                placeholder="#projects"
                                class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                            <p class="font-body text-xs text-slate-500 mt-2">Use #projects, #contact, or full URL</p>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 mb-6">
                    <p class="font-subhead text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Preview</p>
                    <div class="flex flex-wrap gap-4">
                        <button class="inline-flex items-center gap-3 bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 text-white px-6 py-3 rounded-full font-semibold text-sm shadow-lg shadow-indigo-500/30">
                            <span><?= htmlspecialchars($heroPrimaryBtnText) ?></span>
                            <i class="ph-bold ph-arrow-down-right"></i>
                        </button>
                        <button class="inline-flex items-center gap-3 bg-white/5 backdrop-blur-sm text-white px-6 py-3 rounded-full font-semibold text-sm border border-white/10">
                            <span><?= htmlspecialchars($heroSecondaryBtnText) ?></span>
                            <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-800/80">
                    <button type="submit" 
                        class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                        <i class="ph-bold ph-floppy-disk text-base"></i>
                        <span>Save Buttons</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Social Links Card -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl xl:col-span-2">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800/80">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-violet-500/10 border border-violet-500/30 flex items-center justify-center text-violet-400">
                    <i class="ph-bold ph-share-network text-lg"></i>
                </div>
                <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">SOCIAL MEDIA LINKS</h3>
            </div>
            <button onclick="document.getElementById('add-social-form').classList.toggle('hidden')" 
                class="bg-gradient-to-r from-green-500 via-emerald-500 to-teal-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-4 py-2 rounded-lg transition-all hover:shadow-lg hover:shadow-green-500/30 flex items-center gap-2">
                <i class="ph-bold ph-plus-circle text-base"></i>
                <span>Add Social</span>
            </button>
        </div>

        <?php
        // Check if hero_social_links table exists, if not, create it
        try {
            $checkTable = $pdo->query("SHOW TABLES LIKE 'hero_social_links'");
            if ($checkTable->rowCount() === 0) {
                $pdo->exec("CREATE TABLE IF NOT EXISTS hero_social_links (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    platform_name VARCHAR(50) NOT NULL,
                    platform_icon VARCHAR(100) NOT NULL,
                    profile_url VARCHAR(255) NOT NULL,
                    sort_order INT DEFAULT 0,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )");
            }
            
            $socialLinksQuery = $pdo->query("SELECT * FROM hero_social_links ORDER BY sort_order ASC, id ASC");
            $socialLinks = $socialLinksQuery->fetchAll();
        } catch (Exception $e) {
            $socialLinks = [];
        }
        ?>

        <!-- Add New Social Link Form (Hidden by default) -->
        <div id="add-social-form" class="hidden mb-6 p-5 rounded-2xl bg-slate-900/40 border border-slate-800">
            <h4 class="font-subhead text-xs font-semibold uppercase text-green-300 tracking-wider mb-4 flex items-center gap-2">
                <i class="ph-bold ph-plus-circle text-green-400"></i>
                Add New Social Platform
            </h4>
            
            <form hx-post="/nel-portfolio/api/home_handler.php" 
                  hx-target="#social-links-list" 
                  hx-swap="innerHTML"
                  class="font-body space-y-4">
                <input type="hidden" name="action" value="add_social_link">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Platform Name</label>
                        <input type="text" name="platform_name" required
                            placeholder="Instagram, Facebook, YouTube..."
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    </div>
                    
                    <div>
                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Icon Name (Phosphor)</label>
                        <input type="text" name="platform_icon" required
                            placeholder="ph-instagram-logo, ph-facebook-logo..."
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                        <p class="font-body text-xs text-slate-500 mt-1">Find icons at <a href="https://phosphoricons.com/" target="_blank" class="text-cyan-400 hover:underline">phosphoricons.com</a></p>
                    </div>
                    
                    <div>
                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Profile URL</label>
                        <input type="url" name="profile_url" required
                            placeholder="https://instagram.com/username"
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('add-social-form').classList.add('hidden')"
                        class="bg-slate-800 text-slate-300 font-subhead font-semibold text-xs uppercase tracking-widest px-4 py-2 rounded-lg transition-all hover:bg-slate-700">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="bg-gradient-to-r from-green-500 via-emerald-500 to-teal-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-4 py-2 rounded-lg transition-all hover:shadow-lg hover:shadow-green-500/30">
                        Add Platform
                    </button>
                </div>
            </form>
        </div>

        <!-- Existing Social Links List -->
        <div id="social-links-list" class="space-y-3 mb-6">
            <?php if (empty($socialLinks)): ?>
                <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 text-center">
                    <i class="ph-bold ph-share-network text-4xl text-slate-600 mb-2"></i>
                    <p class="font-body text-sm text-slate-500">No social platforms added yet. Click "Add Social" to create your first one!</p>
                </div>
            <?php else: ?>
                <?php foreach ($socialLinks as $social): ?>
                    <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 hover:border-slate-700 transition-all group">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-12 h-12 rounded-lg bg-slate-800/50 border border-slate-700 flex items-center justify-center group-hover:scale-105 transition-transform">
                                    <i class="ph-fill <?= htmlspecialchars($social['platform_icon']) ?> text-2xl text-slate-300"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-subhead text-sm font-semibold text-white"><?= htmlspecialchars($social['platform_name']) ?></p>
                                    <p class="font-body text-xs text-slate-500 truncate"><?= htmlspecialchars($social['profile_url']) ?></p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <!-- Edit Button -->
                                <button onclick="editSocial<?= $social['id'] ?>()"
                                    class="p-2 rounded-lg bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-cyan-400 hover:border-cyan-500/50 transition-all">
                                    <i class="ph-bold ph-pencil-simple text-sm"></i>
                                </button>
                                
                                <!-- Move Up -->
                                <button hx-post="/nel-portfolio/api/home_handler.php"
                                        hx-vals='{"action": "reorder_social_link", "id": "<?= $social['id'] ?>", "direction": "up"}'
                                        hx-target="#social-links-list"
                                        hx-swap="innerHTML"
                                        class="p-2 rounded-lg bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-indigo-400 hover:border-indigo-500/50 transition-all">
                                    <i class="ph-bold ph-arrow-up text-sm"></i>
                                </button>
                                
                                <!-- Move Down -->
                                <button hx-post="/nel-portfolio/api/home_handler.php"
                                        hx-vals='{"action": "reorder_social_link", "id": "<?= $social['id'] ?>", "direction": "down"}'
                                        hx-target="#social-links-list"
                                        hx-swap="innerHTML"
                                        class="p-2 rounded-lg bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-indigo-400 hover:border-indigo-500/50 transition-all">
                                    <i class="ph-bold ph-arrow-down text-sm"></i>
                                </button>
                                
                                <!-- Delete -->
                                <button hx-post="/nel-portfolio/api/home_handler.php"
                                        hx-vals='{"action": "delete_social_link", "id": "<?= $social['id'] ?>"}'
                                        hx-target="#social-links-list"
                                        hx-swap="innerHTML"
                                        hx-confirm="Delete <?= htmlspecialchars($social['platform_name']) ?>?"
                                        class="p-2 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 hover:border-red-500/50 transition-all">
                                    <i class="ph-bold ph-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Edit Form (Hidden by default) -->
                        <div id="edit-social-<?= $social['id'] ?>" class="hidden mt-4 pt-4 border-t border-slate-700">
                            <form hx-post="/nel-portfolio/api/home_handler.php" 
                                  hx-target="#social-links-list" 
                                  hx-swap="innerHTML"
                                  class="font-body space-y-3">
                                <input type="hidden" name="action" value="update_social_link">
                                <input type="hidden" name="id" value="<?= $social['id'] ?>">
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Platform Name</label>
                                        <input type="text" name="platform_name" value="<?= htmlspecialchars($social['platform_name']) ?>" required
                                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all">
                                    </div>
                                    
                                    <div>
                                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Icon Name</label>
                                        <input type="text" name="platform_icon" value="<?= htmlspecialchars($social['platform_icon']) ?>" required
                                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all">
                                    </div>
                                    
                                    <div>
                                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Profile URL</label>
                                        <input type="url" name="profile_url" value="<?= htmlspecialchars($social['profile_url']) ?>" required
                                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all">
                                    </div>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="document.getElementById('edit-social-<?= $social['id'] ?>').classList.add('hidden')"
                                        class="bg-slate-800 text-slate-300 font-subhead font-semibold text-xs uppercase tracking-widest px-3 py-2 rounded-lg transition-all hover:bg-slate-700">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                        class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-3 py-2 rounded-lg transition-all hover:shadow-lg hover:shadow-cyan-500/30">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <script>
                            function editSocial<?= $social['id'] ?>() {
                                document.getElementById('edit-social-<?= $social['id'] ?>').classList.toggle('hidden');
                            }
                        </script>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Preview -->
        <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800">
            <p class="font-subhead text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Preview</p>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500 font-medium uppercase tracking-wider">Connect</span>
                <div class="flex items-center gap-3 flex-wrap">
                    <?php if (empty($socialLinks)): ?>
                        <p class="text-sm text-slate-500 italic">No social links added yet</p>
                    <?php else: ?>
                        <?php foreach ($socialLinks as $social): ?>
                            <?php if (!empty($social['profile_url'])): ?>
                                <a href="<?= htmlspecialchars($social['profile_url']) ?>" target="_blank" class="p-3 bg-slate-800/50 border border-slate-700 rounded-xl hover:border-brand-500/50 transition-all group">
                                    <i class="ph-fill <?= htmlspecialchars($social['platform_icon']) ?> text-xl text-slate-300 group-hover:scale-110 transition-transform"></i>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tech Stack Section -->
<div class="mt-8 glass-panel p-6 sm:p-8 rounded-3xl">
    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/80">
        <div class="w-9 h-9 rounded-xl bg-green-500/10 border border-green-500/30 flex items-center justify-center text-green-400">
            <i class="ph-bold ph-stack text-lg"></i>
        </div>
        <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">HERO TECH STACK</h3>
    </div>

    <?php
    // Check if hero_tech_stack table exists, if not, create it
    try {
        $checkTable = $pdo->query("SHOW TABLES LIKE 'hero_tech_stack'");
        if ($checkTable->rowCount() === 0) {
            // Create the table
            $pdo->exec("CREATE TABLE IF NOT EXISTS hero_tech_stack (
                id INT AUTO_INCREMENT PRIMARY KEY,
                tech_name VARCHAR(50) NOT NULL,
                icon_path VARCHAR(255) NOT NULL,
                sort_order INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
        }
        
        // Fetch existing tech stack items
        $techStackQuery = $pdo->query("SELECT * FROM hero_tech_stack ORDER BY sort_order ASC, id ASC");
        $techStackItems = $techStackQuery->fetchAll();
    } catch (Exception $e) {
        $techStackItems = [];
    }
    ?>

    <div class="space-y-6">
        <!-- Upload New Tech Stack Item -->
        <div class="p-5 rounded-2xl bg-slate-900/40 border border-slate-800">
            <h4 class="font-subhead text-xs font-semibold uppercase text-green-300 tracking-wider mb-4 flex items-center gap-2">
                <i class="ph-bold ph-upload-simple text-green-400"></i>
                Add New Tech Stack Item
            </h4>
            
            <form hx-post="/nel-portfolio/api/home_handler.php" 
                  hx-encoding="multipart/form-data"
                  hx-target="#tech-stack-list" 
                  hx-swap="innerHTML"
                  class="font-body space-y-4">
                <input type="hidden" name="action" value="add_tech_stack">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Tech Name</label>
                        <input type="text" name="tech_name" required
                            placeholder="React, Node.js, Python..."
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    </div>
                    
                    <div>
                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Tech Icon/Logo</label>
                        <input type="file" name="tech_icon" accept="image/png,image/jpg,image/jpeg,image/svg+xml,image/webp" required
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-cyan-500 file:text-slate-950 hover:file:bg-cyan-400 focus:outline-none focus:border-cyan-500 transition-all">
                        <p class="font-body text-xs text-slate-500 mt-1">PNG, JPG, SVG, WebP (max 2MB)</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                        class="bg-gradient-to-r from-green-500 via-emerald-500 to-teal-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-green-500/30 cursor-pointer flex items-center gap-2">
                        <i class="ph-bold ph-plus-circle text-base"></i>
                        <span>Add Tech Stack</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Existing Tech Stack Items -->
        <div id="tech-stack-list" class="space-y-3">
            <?php if (empty($techStackItems)): ?>
                <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 text-center">
                    <i class="ph-bold ph-stack text-4xl text-slate-600 mb-2"></i>
                    <p class="font-body text-sm text-slate-500">No tech stack items yet. Add your first one above!</p>
                </div>
            <?php else: ?>
                <?php foreach ($techStackItems as $item): ?>
                    <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 flex items-center justify-between gap-4 hover:border-slate-700 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-slate-800/50 border border-slate-700 flex items-center justify-center p-2 group-hover:scale-105 transition-transform">
                                <img src="/nel-portfolio/<?= htmlspecialchars($item['icon_path']) ?>" 
                                     alt="<?= htmlspecialchars($item['tech_name']) ?>" 
                                     class="w-full h-full object-contain">
                            </div>
                            <div>
                                <p class="font-subhead text-sm font-semibold text-white"><?= htmlspecialchars($item['tech_name']) ?></p>
                                <p class="font-body text-xs text-slate-500">Order: <?= htmlspecialchars($item['sort_order']) ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <!-- Move Up -->
                            <button hx-post="/nel-portfolio/api/home_handler.php"
                                    hx-vals='{"action": "reorder_tech_stack", "id": "<?= $item['id'] ?>", "direction": "up"}'
                                    hx-target="#tech-stack-list"
                                    hx-swap="innerHTML"
                                    class="p-2 rounded-lg bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-cyan-400 hover:border-cyan-500/50 transition-all">
                                <i class="ph-bold ph-arrow-up text-sm"></i>
                            </button>
                            
                            <!-- Move Down -->
                            <button hx-post="/nel-portfolio/api/home_handler.php"
                                    hx-vals='{"action": "reorder_tech_stack", "id": "<?= $item['id'] ?>", "direction": "down"}'
                                    hx-target="#tech-stack-list"
                                    hx-swap="innerHTML"
                                    class="p-2 rounded-lg bg-slate-800/50 border border-slate-700 text-slate-400 hover:text-indigo-400 hover:border-indigo-500/50 transition-all">
                                <i class="ph-bold ph-arrow-down text-sm"></i>
                            </button>
                            
                            <!-- Delete -->
                            <button hx-post="/nel-portfolio/api/home_handler.php"
                                    hx-vals='{"action": "delete_tech_stack", "id": "<?= $item['id'] ?>"}'
                                    hx-target="#tech-stack-list"
                                    hx-swap="innerHTML"
                                    hx-confirm="Delete '<?= htmlspecialchars($item['tech_name']) ?>'?"
                                    class="p-2 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 hover:border-red-500/50 transition-all">
                                <i class="ph-bold ph-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Info -->
        <div class="p-4 rounded-xl bg-cyan-500/5 border border-cyan-500/20">
            <div class="flex items-start gap-3">
                <i class="ph-bold ph-info text-cyan-400 text-lg mt-0.5"></i>
                <div>
                    <p class="font-body text-xs text-slate-300 leading-relaxed">
                        Tech stack icons will appear on the right side of your hero section. Upload icons/logos for technologies you use. 
                        Recommended: Square icons (128x128px or higher), transparent PNG or SVG format.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Info Card -->
<div class="mt-8 glass-panel p-6 rounded-3xl border-l-4 border-cyan-500/50">
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-full bg-cyan-500/10 flex items-center justify-center text-cyan-400 flex-shrink-0">
            <i class="ph-bold ph-info text-xl"></i>
        </div>
        <div>
            <h4 class="font-subhead text-sm font-semibold text-white mb-2">Hero Section Customization</h4>
            <p class="font-body text-xs text-slate-400 leading-relaxed">
                Customize all text elements in your homepage hero section including the status badge, main titles, subtitle/tagline, 
                call-to-action buttons, and tech stack icons. Changes appear instantly on your portfolio homepage.
            </p>
        </div>
    </div>
</div>
