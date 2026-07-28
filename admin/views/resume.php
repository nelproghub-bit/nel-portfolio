<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';
check_auth();

// Fetch settings
$settingsQuery = $pdo->query("SELECT setting_key, setting_value FROM settings");
$settings = [];
while ($row = $settingsQuery->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$resumeSummary = $settings['resume_summary'] ?? '';
$resumePdfUrl = $settings['resume_pdf_url'] ?? '';
?>
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-slate-800/80 pb-6">
    <div>
        <h2 class="text-2xl font-bold font-turnpike text-white tracking-[0.2em] bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-100 to-cyan-300">
            RESUME & SETTINGS
        </h2>
        <p class="font-body text-slate-400 text-xs sm:text-sm mt-1">Update your personal biography summary and downloadable resume document link.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Resume Summary Form -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl flex flex-col">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/80">
            <div class="w-9 h-9 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
                <i class="ph-bold ph-text-align-left text-lg"></i>
            </div>
            <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">PROFESSIONAL BIO SUMMARY</h3>
        </div>
        
        <form hx-post="/nel-portfolio/api/resume_handler.php" hx-target="#resume-summary-container" hx-swap="outerHTML" class="flex-1 flex flex-col font-body">
            <input type="hidden" name="action" value="update_summary">
            <div id="resume-summary-container" class="flex-1 flex flex-col">
                <div class="mb-5 flex-1">
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">About Me / Bio</label>
                    <textarea name="resume_summary" rows="9" placeholder="Write a short summary about your background, experience, and development stack..." 
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all leading-relaxed"><?= htmlspecialchars($resumeSummary) ?></textarea>
                    <p class="font-body text-xs text-slate-500 mt-2">This summary text is rendered in the 'About Me' and 'Resume' sections of your main website.</p>
                </div>
                
                <div class="flex justify-end pt-4 border-t border-slate-800/80 mt-auto">
                    <button type="submit" 
                        class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                        <i class="ph-bold ph-floppy-disk text-base"></i>
                        <span>Save Bio Summary</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Resume PDF Form -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl h-fit">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/80">
            <div class="w-9 h-9 rounded-xl bg-red-500/10 border border-red-500/30 flex items-center justify-center text-red-400">
                <i class="ph-bold ph-file-pdf text-lg"></i>
            </div>
            <h3 class="font-turnpike text-sm font-bold text-white tracking-wider">RESUME FILE UPLOAD</h3>
        </div>
        
        <form hx-post="/nel-portfolio/api/resume_handler.php" hx-encoding="multipart/form-data" hx-target="#resume-pdf-container" hx-swap="outerHTML" class="font-body">
            <input type="hidden" name="action" value="upload_pdf">
            <div id="resume-pdf-container">
                <div class="mb-6">
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Upload Resume PDF</label>
                    <div class="relative">
                        <input type="file" name="resume_file" accept=".pdf" required
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-cyan-500/10 file:text-cyan-400 hover:file:bg-cyan-500/20 file:cursor-pointer focus:outline-none focus:border-cyan-500 transition-all">
                    </div>
                    <p class="font-body text-xs text-slate-500 mt-2">Select a PDF file from your computer. Maximum file size: 5MB.</p>
                </div>
                
                <?php if ($resumePdfUrl): ?>
                    <div class="mb-6 p-4 rounded-2xl bg-slate-900/60 border border-slate-800">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <i class="ph-bold ph-file-pdf text-2xl text-red-400"></i>
                                <div>
                                    <p class="font-subhead text-xs font-semibold text-slate-200">Current Resume PDF</p>
                                    <p class="font-body text-[11px] text-slate-400 truncate max-w-[200px]"><?= basename($resumePdfUrl) ?></p>
                                </div>
                            </div>
                            <a href="<?= htmlspecialchars($resumePdfUrl) ?>" target="_blank" class="px-3 py-1.5 rounded-lg bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 hover:bg-cyan-500 hover:text-slate-950 font-subhead text-[11px] font-semibold uppercase transition-all">
                                View PDF
                            </a>
                        </div>
                        <form hx-post="/nel-portfolio/api/resume_handler.php" hx-target="#resume-pdf-container" hx-swap="outerHTML" hx-confirm="Are you sure you want to delete this resume?">
                            <input type="hidden" name="action" value="delete_pdf">
                            <button type="submit" class="w-full px-3 py-2 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500 hover:text-white font-subhead text-[11px] font-semibold uppercase transition-all flex items-center justify-center gap-2">
                                <i class="ph-bold ph-trash text-sm"></i>
                                <span>Delete Current Resume</span>
                            </button>
                        </form>
                    </div>
                <?php endif; ?>

                <div class="flex justify-end pt-4 border-t border-slate-800/80">
                    <button type="submit" 
                        class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                        <i class="ph-bold ph-upload-simple text-base"></i>
                        <span>Upload Resume</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
