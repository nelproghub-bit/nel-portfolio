<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';
check_auth_htmx();

// Define upload directory for profile photos
$uploadDir = __DIR__ . '/../uploads/profile/';
$webPath = '/nel-portfolio/uploads/profile/';

// Create upload directory if it doesn't exist
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'upload_photo') {
        // Check if file was uploaded
        if (!isset($_FILES['profile_photo']) || $_FILES['profile_photo']['error'] !== UPLOAD_ERR_OK) {
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Error: No file uploaded or upload failed\", \"type\": \"error\"}}");
            exit;
        }

        $file = $_FILES['profile_photo'];
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Error: Only JPG, PNG, and WebP images are allowed\", \"type\": \"error\"}}");
            exit;
        }
        
        // Validate file size (5MB max)
        $maxSize = 5 * 1024 * 1024; // 5MB in bytes
        if ($file['size'] > $maxSize) {
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Error: File size exceeds 5MB limit\", \"type\": \"error\"}}");
            exit;
        }
        
        // Delete old profile photo if exists
        $oldPhotoQuery = $pdo->query("SELECT setting_value FROM settings WHERE setting_key = 'about_profile_photo'");
        $oldPhoto = $oldPhotoQuery->fetchColumn();
        if ($oldPhoto && file_exists(__DIR__ . '/..' . str_replace('/nel-portfolio', '', $oldPhoto))) {
            unlink(__DIR__ . '/..' . str_replace('/nel-portfolio', '', $oldPhoto));
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . time() . '.' . $extension;
        $destination = $uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $fileUrl = $webPath . $filename;
            
            // Save to database
            $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('about_profile_photo', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
            $stmt->execute([$fileUrl, $fileUrl]);
            
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Profile photo uploaded successfully\"}}");
            
            // Return updated container
            ?>
            <div id="photo-container">
                <!-- Current Photo Preview -->
                <div class="mb-6">
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-3">Current Photo</label>
                    <div class="relative group">
                        <!-- Photo Card with 3D Effect -->
                        <div class="aspect-square max-w-xs mx-auto rounded-3xl overflow-hidden relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 border border-slate-700 shadow-2xl transform transition-all duration-500 hover:scale-105 hover:rotate-1">
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 via-cyan-500/5 to-pink-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            
                            <!-- Actual Photo -->
                            <img src="<?= htmlspecialchars($fileUrl) ?>" alt="Profile" class="w-full h-full object-cover relative z-10">
                            
                            <!-- Glassmorphic Overlay on Hover -->
                            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center z-20">
                                <div class="text-center">
                                    <i class="ph-bold ph-camera text-4xl text-white mb-2"></i>
                                    <p class="text-white font-semibold text-sm">Change Photo</p>
                                </div>
                            </div>
                            
                            <!-- Corner Accents -->
                            <div class="absolute top-3 left-3 w-8 h-8 border-l-2 border-t-2 border-cyan-400/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute bottom-3 right-3 w-8 h-8 border-r-2 border-b-2 border-violet-400/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- 3D Shadow -->
                            <div class="absolute -inset-0.5 bg-gradient-to-br from-cyan-500 via-violet-500 to-pink-500 rounded-3xl blur-xl opacity-20 group-hover:opacity-40 transition-opacity duration-500 -z-10"></div>
                        </div>
                        
                        <!-- Delete Button -->
                        <form hx-post="/nel-portfolio/api/about_handler.php" hx-target="#photo-container" hx-swap="outerHTML" hx-confirm="Are you sure you want to delete your profile photo?" class="mt-4">
                            <input type="hidden" name="action" value="delete_photo">
                            <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500 hover:text-white font-subhead text-xs font-semibold uppercase transition-all flex items-center justify-center gap-2">
                                <i class="ph-bold ph-trash text-sm"></i>
                                <span>Delete Photo</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Upload Input -->
                <div class="mb-6">
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Change Photo</label>
                    <input type="file" name="profile_photo" accept=".jpg,.jpeg,.png,.webp" required 
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-violet-500/10 file:text-violet-400 hover:file:bg-violet-500/20 file:cursor-pointer focus:outline-none focus:border-cyan-500 transition-all">
                    <p class="font-body text-xs text-slate-500 mt-2">Recommended: Square image, at least 500x500px. JPG, PNG, or WebP. Max 5MB.</p>
                </div>

                <!-- Photo Effects Options -->
                <div class="mb-6 p-4 rounded-2xl bg-slate-900/60 border border-slate-800">
                    <p class="font-subhead text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Display Effects</p>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="flex items-center gap-2 text-xs text-slate-300">
                            <i class="ph-bold ph-sparkle text-violet-400"></i>
                            <span>3D Depth Shadow</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-300">
                            <i class="ph-bold ph-gradient text-cyan-400"></i>
                            <span>Gradient Border</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-300">
                            <i class="ph-bold ph-corners-out text-pink-400"></i>
                            <span>Corner Accents</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-300">
                            <i class="ph-bold ph-cube text-indigo-400"></i>
                            <span>Hover Lift Effect</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-800/80">
                    <button type="submit" 
                        class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                        <i class="ph-bold ph-upload-simple text-base"></i>
                        <span>Upload Photo</span>
                    </button>
                </div>
            </div>
            <?php
        } else {
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Error: Failed to save file\", \"type\": \"error\"}}");
        }
        exit;
    }

    if ($action === 'delete_photo') {
        // Get current photo URL
        $photoQuery = $pdo->query("SELECT setting_value FROM settings WHERE setting_key = 'about_profile_photo'");
        $photoUrl = $photoQuery->fetchColumn();
        
        if ($photoUrl) {
            // Delete file from server
            $filePath = __DIR__ . '/..' . str_replace('/nel-portfolio', '', $photoUrl);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Delete from database
            $stmt = $pdo->prepare("DELETE FROM settings WHERE setting_key = 'about_profile_photo'");
            $stmt->execute();
            
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Profile photo deleted successfully\"}}");
        }
        
        // Return empty container
        ?>
        <div id="photo-container">
            <!-- Empty State -->
            <div class="mb-6">
                <div class="aspect-square max-w-xs mx-auto rounded-3xl overflow-hidden relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 border border-slate-700 flex items-center justify-center">
                    <div class="text-center p-8">
                        <div class="w-20 h-20 mx-auto rounded-full bg-violet-500/10 border-2 border-violet-500/30 flex items-center justify-center mb-4">
                            <i class="ph-bold ph-user text-4xl text-violet-400"></i>
                        </div>
                        <h4 class="font-subhead text-sm font-semibold text-white mb-2">No Photo Uploaded</h4>
                        <p class="text-xs text-slate-400">Upload a professional photo</p>
                    </div>
                </div>
            </div>

            <!-- Upload Input -->
            <div class="mb-6">
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Upload Photo</label>
                <input type="file" name="profile_photo" accept=".jpg,.jpeg,.png,.webp" required 
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-violet-500/10 file:text-violet-400 hover:file:bg-violet-500/20 file:cursor-pointer focus:outline-none focus:border-cyan-500 transition-all">
                <p class="font-body text-xs text-slate-500 mt-2">Recommended: Square image, at least 500x500px. JPG, PNG, or WebP. Max 5MB.</p>
            </div>

            <!-- Photo Effects Options -->
            <div class="mb-6 p-4 rounded-2xl bg-slate-900/60 border border-slate-800">
                <p class="font-subhead text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Display Effects</p>
                <div class="grid grid-cols-2 gap-2">
                    <div class="flex items-center gap-2 text-xs text-slate-300">
                        <i class="ph-bold ph-sparkle text-violet-400"></i>
                        <span>3D Depth Shadow</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-300">
                        <i class="ph-bold ph-gradient text-cyan-400"></i>
                        <span>Gradient Border</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-300">
                        <i class="ph-bold ph-corners-out text-pink-400"></i>
                        <span>Corner Accents</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-300">
                        <i class="ph-bold ph-cube text-indigo-400"></i>
                        <span>Hover Lift Effect</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-800/80">
                <button type="submit" 
                    class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                    <i class="ph-bold ph-upload-simple text-base"></i>
                    <span>Upload Photo</span>
                </button>
            </div>
        </div>
        <?php
        exit;
    }

    if ($action === 'update_bio') {
        $resumeSummary = $_POST['resume_summary'] ?? '';
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('resume_summary', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$resumeSummary, $resumeSummary]);
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Professional biography updated successfully\"}}");
        
        ?>
        <div id="bio-container">
            <div class="mb-6">
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Professional Biography</label>
                <textarea name="resume_summary" rows="8" 
                    placeholder="Write about your professional background, experience, and journey as a developer..."
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all leading-relaxed"><?= htmlspecialchars($resumeSummary) ?></textarea>
                <p class="font-body text-xs text-slate-500 mt-2">This text appears in the "My Story" card in the About section of your portfolio.</p>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-800/80">
                <button type="submit" 
                    class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                    <i class="ph-bold ph-floppy-disk text-base"></i>
                    <span>Save Biography</span>
                </button>
            </div>
        </div>
        <?php
        exit;
    }

    if ($action === 'update_stats') {
        $yearsExperience = $_POST['years_experience'] ?? '5+';
        $totalProjects = $_POST['total_projects'] ?? '50+';
        $totalClients = $_POST['total_clients'] ?? '20+';
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('about_years_experience', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$yearsExperience, $yearsExperience]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('about_total_projects', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$totalProjects, $totalProjects]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('about_total_clients', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$totalClients, $totalClients]);
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Statistics updated successfully\"}}");
        
        ?>
        <div id="stats-container">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Years Experience</label>
                    <input type="text" name="years_experience" value="<?= htmlspecialchars($yearsExperience) ?>" 
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all text-center font-bold text-lg">
                </div>
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Total Projects</label>
                    <input type="text" name="total_projects" value="<?= htmlspecialchars($totalProjects) ?>" 
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all text-center font-bold text-lg">
                </div>
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Total Clients</label>
                    <input type="text" name="total_clients" value="<?= htmlspecialchars($totalClients) ?>" 
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all text-center font-bold text-lg">
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-800/80">
                <button type="submit" 
                    class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                    <i class="ph-bold ph-floppy-disk text-base"></i>
                    <span>Save Statistics</span>
                </button>
            </div>
        </div>
        <?php
        exit;
    }

    if ($action === 'update_contact') {
        $email = $_POST['email'] ?? '';
        $location = $_POST['location'] ?? '';
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('about_email', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$email, $email]);
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('about_location', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$location, $location]);
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Contact information updated successfully\"}}");
        
        ?>
        <div id="contact-container">
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Email Address</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-slate-500">
                            <i class="ph-bold ph-envelope text-base"></i>
                        </span>
                        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" 
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl pl-11 pr-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Location</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-slate-500">
                            <i class="ph-bold ph-map-pin text-base"></i>
                        </span>
                        <input type="text" name="location" value="<?= htmlspecialchars($location) ?>" 
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl pl-11 pr-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-800/80">
                <button type="submit" 
                    class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                    <i class="ph-bold ph-floppy-disk text-base"></i>
                    <span>Save Contact Info</span>
                </button>
            </div>
        </div>
        <?php
        exit;
    }

    if ($action === 'update_expertise') {
        $coreExpertise = $_POST['core_expertise'] ?? '';
        
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('about_core_expertise', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$coreExpertise, $coreExpertise]);
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Expertise tags updated successfully\"}}");
        
        ?>
        <div id="expertise-container">
            <div class="mb-6">
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Expertise Tags (Comma Separated)</label>
                <textarea name="core_expertise" rows="3" 
                    placeholder="Full Stack Development, UI/UX Design, Cloud Architecture, DevOps"
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all leading-relaxed"><?= htmlspecialchars($coreExpertise) ?></textarea>
                <p class="font-body text-xs text-slate-500 mt-2">Enter your core expertise areas separated by commas. These will appear as colored tags in the About section.</p>
            </div>

            <!-- Preview Tags -->
            <div class="mb-6 p-4 rounded-2xl bg-slate-900/60 border border-slate-800">
                <p class="font-subhead text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Preview</p>
                <div class="flex flex-wrap gap-2">
                    <?php 
                    $tags = array_filter(array_map('trim', explode(',', $coreExpertise)));
                    $colors = [
                        'bg-brand-500/10 border-brand-500/30 text-brand-300',
                        'bg-purple-500/10 border-purple-500/30 text-purple-300',
                        'bg-pink-500/10 border-pink-500/30 text-pink-300',
                        'bg-cyan-500/10 border-cyan-500/30 text-cyan-300',
                        'bg-yellow-500/10 border-yellow-500/30 text-yellow-300',
                        'bg-green-500/10 border-green-500/30 text-green-300',
                    ];
                    foreach ($tags as $index => $tag): 
                        $colorClass = $colors[$index % count($colors)];
                    ?>
                    <span class="px-4 py-2 <?= $colorClass ?> border rounded-full text-sm font-medium">
                        <?= htmlspecialchars($tag) ?>
                    </span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-800/80">
                <button type="submit" 
                    class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                    <i class="ph-bold ph-floppy-disk text-base"></i>
                    <span>Save Expertise Tags</span>
                </button>
            </div>
        </div>
        <?php
        exit;
    }
}
?>
