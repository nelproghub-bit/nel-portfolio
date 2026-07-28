<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';
check_auth_htmx();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_titles') {
        $badgeText = $_POST['badge_text'] ?? 'Available for Work';
        $titleLine1 = $_POST['title_line1'] ?? 'Creative';
        $titleLine2 = $_POST['title_line2'] ?? 'Developer';
        $fontFamily = $_POST['font_family'] ?? 'Outfit';
        $fontSize = $_POST['font_size'] ?? '72';
        $fontWeight = $_POST['font_weight'] ?? '900';
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('hero_badge_text', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$badgeText, $badgeText]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('hero_title_line1', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$titleLine1, $titleLine1]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('hero_title_line2', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$titleLine2, $titleLine2]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('hero_title_font_family', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$fontFamily, $fontFamily]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('hero_title_font_size', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$fontSize, $fontSize]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('hero_title_font_weight', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$fontWeight, $fontWeight]);
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Hero titles and font styling updated successfully\"}}");
        
        ?>
        <div id="titles-container" class="space-y-5">
            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Status Badge Text</label>
                <input type="text" name="badge_text" value="<?= htmlspecialchars($badgeText) ?>" 
                    placeholder="Available for Work"
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                <p class="font-body text-xs text-slate-500 mt-2">The animated badge text at the top of the hero section.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Title Line 1</label>
                    <input type="text" name="title_line1" value="<?= htmlspecialchars($titleLine1) ?>" 
                        placeholder="Creative"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                </div>
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Title Line 2 (Gradient)</label>
                    <input type="text" name="title_line2" value="<?= htmlspecialchars($titleLine2) ?>" 
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
                            <option value="Outfit" <?= $fontFamily === 'Outfit' ? 'selected' : '' ?>>Outfit (Default)</option>
                            <option value="Inter" <?= $fontFamily === 'Inter' ? 'selected' : '' ?>>Inter</option>
                            <option value="Poppins" <?= $fontFamily === 'Poppins' ? 'selected' : '' ?>>Poppins</option>
                            <option value="Montserrat" <?= $fontFamily === 'Montserrat' ? 'selected' : '' ?>>Montserrat</option>
                            <option value="Raleway" <?= $fontFamily === 'Raleway' ? 'selected' : '' ?>>Raleway</option>
                            <option value="Space Grotesk" <?= $fontFamily === 'Space Grotesk' ? 'selected' : '' ?>>Space Grotesk</option>
                            <option value="Playfair Display" <?= $fontFamily === 'Playfair Display' ? 'selected' : '' ?>>Playfair Display</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Font Size (px)</label>
                        <input type="number" name="font_size" value="<?= htmlspecialchars($fontSize) ?>" 
                            min="24" max="120" step="2"
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                        <p class="font-body text-xs text-slate-500 mt-1">24-120px recommended</p>
                    </div>

                    <div>
                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Font Weight</label>
                        <select name="font_weight" 
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all">
                            <option value="300" <?= $fontWeight === '300' ? 'selected' : '' ?>>Light (300)</option>
                            <option value="400" <?= $fontWeight === '400' ? 'selected' : '' ?>>Regular (400)</option>
                            <option value="500" <?= $fontWeight === '500' ? 'selected' : '' ?>>Medium (500)</option>
                            <option value="600" <?= $fontWeight === '600' ? 'selected' : '' ?>>Semi Bold (600)</option>
                            <option value="700" <?= $fontWeight === '700' ? 'selected' : '' ?>>Bold (700)</option>
                            <option value="800" <?= $fontWeight === '800' ? 'selected' : '' ?>>Extra Bold (800)</option>
                            <option value="900" <?= $fontWeight === '900' ? 'selected' : '' ?>>Black (900)</option>
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
                        <span class="text-white font-medium tracking-wider uppercase text-xs"><?= htmlspecialchars($badgeText) ?></span>
                    </div>
                    <div>
                        <h1 style="font-family: '<?= htmlspecialchars($fontFamily) ?>', sans-serif; font-size: <?= htmlspecialchars($fontSize) ?>px; font-weight: <?= htmlspecialchars($fontWeight) ?>;" class="text-white mb-1 leading-tight"><?= htmlspecialchars($titleLine1) ?></h1>
                        <h1 style="font-family: '<?= htmlspecialchars($fontFamily) ?>', sans-serif; font-size: <?= htmlspecialchars($fontSize) ?>px; font-weight: <?= htmlspecialchars($fontWeight) ?>;" class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 leading-tight"><?= htmlspecialchars($titleLine2) ?></h1>
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
        <?php
        exit;
    }

    if ($action === 'update_subtitle') {
        $subtitle = $_POST['subtitle'] ?? '';
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('hero_subtitle', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$subtitle, $subtitle]);
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Hero subtitle updated successfully\"}}");
        
        ?>
        <div id="subtitle-container" class="space-y-5">
            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Subtitle / Tagline</label>
                <textarea name="subtitle" rows="4" 
                    placeholder="Crafting digital experiences..."
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all leading-relaxed"><?= htmlspecialchars($subtitle) ?></textarea>
                <p class="font-body text-xs text-slate-500 mt-2">Use HTML tags like &lt;span class="text-white font-medium"&gt;text&lt;/span&gt; to highlight keywords.</p>
            </div>

            <!-- Preview -->
            <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800">
                <p class="font-subhead text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Preview</p>
                <p class="text-gray-300 text-sm leading-relaxed">
                    <?= $subtitle ?>
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
        <?php
        exit;
    }

    if ($action === 'update_buttons') {
        $primaryBtnText = $_POST['primary_btn_text'] ?? 'Discover My Work';
        $primaryBtnLink = $_POST['primary_btn_link'] ?? '#about';
        $secondaryBtnText = $_POST['secondary_btn_text'] ?? 'View Projects';
        $secondaryBtnLink = $_POST['secondary_btn_link'] ?? '#projects';
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('hero_primary_btn_text', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$primaryBtnText, $primaryBtnText]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('hero_primary_btn_link', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$primaryBtnLink, $primaryBtnLink]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('hero_secondary_btn_text', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$secondaryBtnText, $secondaryBtnText]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('hero_secondary_btn_link', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$secondaryBtnLink, $secondaryBtnLink]);
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Hero buttons updated successfully\"}}");
        
        ?>
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
                        <input type="text" name="primary_btn_text" value="<?= htmlspecialchars($primaryBtnText) ?>" 
                            placeholder="Discover My Work"
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    </div>
                    <div>
                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Link / Anchor</label>
                        <input type="text" name="primary_btn_link" value="<?= htmlspecialchars($primaryBtnLink) ?>" 
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
                        <input type="text" name="secondary_btn_text" value="<?= htmlspecialchars($secondaryBtnText) ?>" 
                            placeholder="View Projects"
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    </div>
                    <div>
                        <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Link / Anchor</label>
                        <input type="text" name="secondary_btn_link" value="<?= htmlspecialchars($secondaryBtnLink) ?>" 
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
                        <span><?= htmlspecialchars($primaryBtnText) ?></span>
                        <i class="ph-bold ph-arrow-down-right"></i>
                    </button>
                    <button class="inline-flex items-center gap-3 bg-white/5 backdrop-blur-sm text-white px-6 py-3 rounded-full font-semibold text-sm border border-white/10">
                        <span><?= htmlspecialchars($secondaryBtnText) ?></span>
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
        <?php
        exit;
    }

    // ===== SOCIAL LINKS CRUD =====
    
    if ($action === 'add_social_link') {
        $platformName = $_POST['platform_name'] ?? '';
        $platformIcon = $_POST['platform_icon'] ?? '';
        $profileUrl = $_POST['profile_url'] ?? '';
        
        // Get next sort order
        $maxOrderStmt = $pdo->query("SELECT COALESCE(MAX(sort_order), 0) as max_order FROM hero_social_links");
        $maxOrder = $maxOrderStmt->fetch()['max_order'];
        
        $stmt = $pdo->prepare("INSERT INTO hero_social_links (platform_name, platform_icon, profile_url, sort_order) VALUES (?, ?, ?, ?)");
        $stmt->execute([$platformName, $platformIcon, $profileUrl, $maxOrder + 1]);
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Social link added successfully\"}}");
        
        // Return updated list
        $socialLinksQuery = $pdo->query("SELECT * FROM hero_social_links ORDER BY sort_order ASC, id ASC");
        $socialLinks = $socialLinksQuery->fetchAll();
        
        include __DIR__ . '/../admin/views/partials/social_links_list.php';
        exit;
    }
    
    if ($action === 'update_social_link') {
        $id = $_POST['id'] ?? 0;
        $platformName = $_POST['platform_name'] ?? '';
        $platformIcon = $_POST['platform_icon'] ?? '';
        $profileUrl = $_POST['profile_url'] ?? '';
        
        $stmt = $pdo->prepare("UPDATE hero_social_links SET platform_name = ?, platform_icon = ?, profile_url = ? WHERE id = ?");
        $stmt->execute([$platformName, $platformIcon, $profileUrl, $id]);
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Social link updated successfully\"}}");
        
        // Return updated list
        $socialLinksQuery = $pdo->query("SELECT * FROM hero_social_links ORDER BY sort_order ASC, id ASC");
        $socialLinks = $socialLinksQuery->fetchAll();
        
        include __DIR__ . '/../admin/views/partials/social_links_list.php';
        exit;
    }
    
    if ($action === 'delete_social_link') {
        $id = $_POST['id'] ?? 0;
        
        $stmt = $pdo->prepare("DELETE FROM hero_social_links WHERE id = ?");
        $stmt->execute([$id]);
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Social link deleted\"}}");
        
        // Return updated list
        $socialLinksQuery = $pdo->query("SELECT * FROM hero_social_links ORDER BY sort_order ASC, id ASC");
        $socialLinks = $socialLinksQuery->fetchAll();
        
        include __DIR__ . '/../admin/views/partials/social_links_list.php';
        exit;
    }
    
    if ($action === 'reorder_social_link') {
        $id = $_POST['id'] ?? 0;
        $direction = $_POST['direction'] ?? 'up';
        
        // Get current item
        $stmt = $pdo->prepare("SELECT * FROM hero_social_links WHERE id = ?");
        $stmt->execute([$id]);
        $currentItem = $stmt->fetch();
        
        if ($currentItem) {
            if ($direction === 'up') {
                // Swap with previous item
                $stmt = $pdo->prepare("SELECT * FROM hero_social_links WHERE sort_order < ? ORDER BY sort_order DESC LIMIT 1");
                $stmt->execute([$currentItem['sort_order']]);
                $swapItem = $stmt->fetch();
                
                if ($swapItem) {
                    $tempOrder = $currentItem['sort_order'];
                    
                    $stmt = $pdo->prepare("UPDATE hero_social_links SET sort_order = ? WHERE id = ?");
                    $stmt->execute([$swapItem['sort_order'], $currentItem['id']]);
                    
                    $stmt = $pdo->prepare("UPDATE hero_social_links SET sort_order = ? WHERE id = ?");
                    $stmt->execute([$tempOrder, $swapItem['id']]);
                }
            } else {
                // Swap with next item
                $stmt = $pdo->prepare("SELECT * FROM hero_social_links WHERE sort_order > ? ORDER BY sort_order ASC LIMIT 1");
                $stmt->execute([$currentItem['sort_order']]);
                $swapItem = $stmt->fetch();
                
                if ($swapItem) {
                    $tempOrder = $currentItem['sort_order'];
                    
                    $stmt = $pdo->prepare("UPDATE hero_social_links SET sort_order = ? WHERE id = ?");
                    $stmt->execute([$swapItem['sort_order'], $currentItem['id']]);
                    
                    $stmt = $pdo->prepare("UPDATE hero_social_links SET sort_order = ? WHERE id = ?");
                    $stmt->execute([$tempOrder, $swapItem['id']]);
                }
            }
        }
        
        // Return updated list
        $socialLinksQuery = $pdo->query("SELECT * FROM hero_social_links ORDER BY sort_order ASC, id ASC");
        $socialLinks = $socialLinksQuery->fetchAll();
        
        include __DIR__ . '/../admin/views/partials/social_links_list.php';
        exit;
    }

    // ===== TECH STACK MANAGEMENT =====
    
    if ($action === 'add_tech_stack') {
        $techName = $_POST['tech_name'] ?? '';
        $uploadDir = __DIR__ . '/../uploads/tech-stack/';
        
        // Handle file upload
        if (isset($_FILES['tech_icon']) && $_FILES['tech_icon']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['tech_icon'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $allowedExts = ['png', 'jpg', 'jpeg', 'svg', 'webp'];
            
            if (in_array($fileExt, $allowedExts) && $fileSize <= 2097152) { // 2MB max
                $newFileName = 'tech_' . time() . '_' . uniqid() . '.' . $fileExt;
                $destination = $uploadDir . $newFileName;
                
                if (move_uploaded_file($fileTmpName, $destination)) {
                    // Get next sort order
                    $maxOrderStmt = $pdo->query("SELECT COALESCE(MAX(sort_order), 0) as max_order FROM hero_tech_stack");
                    $maxOrder = $maxOrderStmt->fetch()['max_order'];
                    
                    $stmt = $pdo->prepare("INSERT INTO hero_tech_stack (tech_name, icon_path, sort_order) VALUES (?, ?, ?)");
                    $stmt->execute([$techName, 'uploads/tech-stack/' . $newFileName, $maxOrder + 1]);
                    
                    header("HX-Trigger: {\"showMessage\": {\"value\": \"Tech stack item added successfully\"}}");
                }
            }
        }
        
        // Return updated list
        $techStackQuery = $pdo->query("SELECT * FROM hero_tech_stack ORDER BY sort_order ASC, id ASC");
        $techStackItems = $techStackQuery->fetchAll();
        
        include __DIR__ . '/../admin/views/partials/tech_stack_list.php';
        exit;
    }
    
    if ($action === 'delete_tech_stack') {
        $id = $_POST['id'] ?? 0;
        
        // Get file path before deleting
        $stmt = $pdo->prepare("SELECT icon_path FROM hero_tech_stack WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        
        if ($item) {
            // Delete file
            $filePath = __DIR__ . '/../' . $item['icon_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Delete from database
            $stmt = $pdo->prepare("DELETE FROM hero_tech_stack WHERE id = ?");
            $stmt->execute([$id]);
            
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Tech stack item deleted\"}}");
        }
        
        // Return updated list
        $techStackQuery = $pdo->query("SELECT * FROM hero_tech_stack ORDER BY sort_order ASC, id ASC");
        $techStackItems = $techStackQuery->fetchAll();
        
        include __DIR__ . '/../admin/views/partials/tech_stack_list.php';
        exit;
    }
    
    if ($action === 'reorder_tech_stack') {
        $id = $_POST['id'] ?? 0;
        $direction = $_POST['direction'] ?? 'up';
        
        // Get current item
        $stmt = $pdo->prepare("SELECT * FROM hero_tech_stack WHERE id = ?");
        $stmt->execute([$id]);
        $currentItem = $stmt->fetch();
        
        if ($currentItem) {
            if ($direction === 'up') {
                // Swap with previous item
                $stmt = $pdo->prepare("SELECT * FROM hero_tech_stack WHERE sort_order < ? ORDER BY sort_order DESC LIMIT 1");
                $stmt->execute([$currentItem['sort_order']]);
                $swapItem = $stmt->fetch();
                
                if ($swapItem) {
                    $tempOrder = $currentItem['sort_order'];
                    
                    $stmt = $pdo->prepare("UPDATE hero_tech_stack SET sort_order = ? WHERE id = ?");
                    $stmt->execute([$swapItem['sort_order'], $currentItem['id']]);
                    
                    $stmt = $pdo->prepare("UPDATE hero_tech_stack SET sort_order = ? WHERE id = ?");
                    $stmt->execute([$tempOrder, $swapItem['id']]);
                }
            } else {
                // Swap with next item
                $stmt = $pdo->prepare("SELECT * FROM hero_tech_stack WHERE sort_order > ? ORDER BY sort_order ASC LIMIT 1");
                $stmt->execute([$currentItem['sort_order']]);
                $swapItem = $stmt->fetch();
                
                if ($swapItem) {
                    $tempOrder = $currentItem['sort_order'];
                    
                    $stmt = $pdo->prepare("UPDATE hero_tech_stack SET sort_order = ? WHERE id = ?");
                    $stmt->execute([$swapItem['sort_order'], $currentItem['id']]);
                    
                    $stmt = $pdo->prepare("UPDATE hero_tech_stack SET sort_order = ? WHERE id = ?");
                    $stmt->execute([$tempOrder, $swapItem['id']]);
                }
            }
        }
        
        // Return updated list
        $techStackQuery = $pdo->query("SELECT * FROM hero_tech_stack ORDER BY sort_order ASC, id ASC");
        $techStackItems = $techStackQuery->fetchAll();
        
        include __DIR__ . '/../admin/views/partials/tech_stack_list.php';
        exit;
    }
}
?>
