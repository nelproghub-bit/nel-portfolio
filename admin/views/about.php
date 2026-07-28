<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';
check_auth();

// Fetch about settings
$settingsQuery = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'about_%' OR setting_key = 'resume_summary'");
$aboutSettings = [];
while ($row = $settingsQuery->fetch()) {
    $aboutSettings[$row['setting_key']] = $row['setting_value'];
}

$yearsExperience = $aboutSettings['about_years_experience'] ?? '5+';
$totalProjects = $aboutSettings['about_total_projects'] ?? '50+';
$totalClients = $aboutSettings['about_total_clients'] ?? '20+';
$email = $aboutSettings['about_email'] ?? 'contact@nel.dev';
$location = $aboutSettings['about_location'] ?? 'Remote / Worldwide';
$coreExpertise = $aboutSettings['about_core_expertise'] ?? 'Full Stack Development,UI/UX Design,Cloud Architecture,DevOps';
$resumeSummary = $aboutSettings['resume_summary'] ?? 'I am a passionate developer...';
$profilePhoto = $aboutSettings['about_profile_photo'] ?? '';
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-slate-800/80 pb-6">
    <div>
        <h2 class="text-2xl font-bold font-turnpike text-white tracking-[0.2em] bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-100 to-cyan-300">
            ABOUT SECTION
        </h2>
        <p class="font-body text-slate-400 text-xs sm:text-sm mt-1">Customize your About section statistics, contact info, and expertise tags.</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
    <!-- Profile Photo Upload Card -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/80">
            <div class="w-9 h-9 rounded-xl bg-violet-500/10 border border-violet-500/30 flex items-center justify-center text-violet-400">
                <i class="ph-bold ph-image text-lg"></i>
            </div>
            <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">PROFILE PHOTO</h3>
        </div>
        
        <form hx-post="/nel-portfolio/api/about_handler.php" hx-encoding="multipart/form-data" hx-target="#photo-container" hx-swap="outerHTML" class="font-body">
            <input type="hidden" name="action" value="upload_photo">
            <div id="photo-container">
                <!-- Current Photo Preview -->
                <?php if ($profilePhoto): ?>
                <div class="mb-6">
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-3">Current Photo</label>
                    <div class="relative group">
                        <!-- Photo Card with 3D Effect -->
                        <div class="aspect-square max-w-xs mx-auto rounded-3xl overflow-hidden relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 border border-slate-700 shadow-2xl transform transition-all duration-500 hover:scale-105 hover:rotate-1">
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 via-cyan-500/5 to-pink-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            
                            <!-- Actual Photo -->
                            <img src="<?= htmlspecialchars($profilePhoto) ?>" alt="Profile" class="w-full h-full object-cover relative z-10">
                            
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
                <?php else: ?>
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
                <?php endif; ?>

                <!-- Upload Input -->
                <div class="mb-6">
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">
                        <?= $profilePhoto ? 'Change Photo' : 'Upload Photo' ?>
                    </label>
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
        </form>
    </div>

    <!-- Professional Bio Card -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/80">
            <div class="w-9 h-9 rounded-xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                <i class="ph-bold ph-text-aa text-lg"></i>
            </div>
            <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">MY STORY - PROFESSIONAL BACKGROUND</h3>
        </div>
        
        <form hx-post="/nel-portfolio/api/about_handler.php" hx-target="#bio-container" hx-swap="outerHTML" class="font-body">
            <input type="hidden" name="action" value="update_bio">
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
        </form>
    </div>

    <!-- Statistics Card -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/80">
            <div class="w-9 h-9 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                <i class="ph-bold ph-chart-line text-lg"></i>
            </div>
            <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">QUICK STATISTICS</h3>
        </div>
        
        <form hx-post="/nel-portfolio/api/about_handler.php" hx-target="#stats-container" hx-swap="outerHTML" class="font-body">
            <input type="hidden" name="action" value="update_stats">
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
        </form>
    </div>

    <!-- Contact Information Card -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/80">
            <div class="w-9 h-9 rounded-xl bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-purple-400">
                <i class="ph-bold ph-address-book text-lg"></i>
            </div>
            <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">CONTACT INFORMATION</h3>
        </div>
        
        <form hx-post="/nel-portfolio/api/about_handler.php" hx-target="#contact-container" hx-swap="outerHTML" class="font-body">
            <input type="hidden" name="action" value="update_contact">
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
        </form>
    </div>

    <!-- Core Expertise Tags Card -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl xl:col-span-2">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/80">
            <div class="w-9 h-9 rounded-xl bg-pink-500/10 border border-pink-500/30 flex items-center justify-center text-pink-400">
                <i class="ph-bold ph-tag text-lg"></i>
            </div>
            <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">CORE EXPERTISE TAGS</h3>
        </div>
        
        <form hx-post="/nel-portfolio/api/about_handler.php" hx-target="#expertise-container" hx-swap="outerHTML" class="font-body">
            <input type="hidden" name="action" value="update_expertise">
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
        </form>
    </div>
</div>

<!-- Info Card -->
<div class="mt-8 glass-panel p-6 rounded-3xl border-l-4 border-cyan-500/50">
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-full bg-cyan-500/10 flex items-center justify-center text-cyan-400 flex-shrink-0">
            <i class="ph-bold ph-info text-xl"></i>
        </div>
        <div>
            <h4 class="font-subhead text-sm font-semibold text-white mb-2">About Section Customization</h4>
            <p class="font-body text-xs text-slate-400 leading-relaxed">
                Customize all aspects of your About section including your professional biography ("My Story"), profile statistics, 
                contact information, and expertise tags. These settings control how your profile appears to visitors.
            </p>
        </div>
    </div>
</div>
