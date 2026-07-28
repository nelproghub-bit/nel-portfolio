<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';
check_auth_htmx();

// Define upload directory
$uploadDir = __DIR__ . '/../uploads/resume/';
$webPath = '/nel-portfolio/uploads/resume/';

// Create upload directory if it doesn't exist
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_summary') {
        $summary = $_POST['resume_summary'] ?? '';
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('resume_summary', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$summary, $summary]);
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Resume summary updated\"}}");
        
        // Return just the updated block
        ?>
        <div id="resume-summary-container" class="flex-1 flex flex-col">
            <div class="mb-5 flex-1">
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">About Me / Bio</label>
                <textarea name="resume_summary" rows="9" placeholder="Write a short summary about your background, experience, and development stack..." 
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all leading-relaxed"><?= htmlspecialchars($summary) ?></textarea>
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
        <?php
        exit;
    }
    
    if ($action === 'upload_pdf') {
        // Check if file was uploaded
        if (!isset($_FILES['resume_file']) || $_FILES['resume_file']['error'] !== UPLOAD_ERR_OK) {
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Error: No file uploaded or upload failed\", \"type\": \"error\"}}");
            exit;
        }

        $file = $_FILES['resume_file'];
        
        // Validate file type
        $allowedTypes = ['application/pdf'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Error: Only PDF files are allowed\", \"type\": \"error\"}}");
            exit;
        }
        
        // Validate file size (5MB max)
        $maxSize = 5 * 1024 * 1024; // 5MB in bytes
        if ($file['size'] > $maxSize) {
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Error: File size exceeds 5MB limit\", \"type\": \"error\"}}");
            exit;
        }
        
        // Delete old resume file if exists
        $oldResumeQuery = $pdo->query("SELECT setting_value FROM settings WHERE setting_key = 'resume_pdf_url'");
        $oldResume = $oldResumeQuery->fetchColumn();
        if ($oldResume && file_exists(__DIR__ . '/..' . str_replace('/nel-portfolio', '', $oldResume))) {
            unlink(__DIR__ . '/..' . str_replace('/nel-portfolio', '', $oldResume));
        }
        
        // Generate unique filename
        $filename = 'resume_' . time() . '.pdf';
        $destination = $uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $fileUrl = $webPath . $filename;
            
            // Save to database
            $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('resume_pdf_url', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
            $stmt->execute([$fileUrl, $fileUrl]);
            
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Resume uploaded successfully\"}}");
            
            // Return updated container
            ?>
            <div id="resume-pdf-container">
                <div class="mb-6">
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Upload Resume PDF</label>
                    <div class="relative">
                        <input type="file" name="resume_file" accept=".pdf" required
                            class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-cyan-500/10 file:text-cyan-400 hover:file:bg-cyan-500/20 file:cursor-pointer focus:outline-none focus:border-cyan-500 transition-all">
                    </div>
                    <p class="font-body text-xs text-slate-500 mt-2">Select a PDF file from your computer. Maximum file size: 5MB.</p>
                </div>
                
                <div class="mb-6 p-4 rounded-2xl bg-slate-900/60 border border-slate-800">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <i class="ph-bold ph-file-pdf text-2xl text-red-400"></i>
                            <div>
                                <p class="font-subhead text-xs font-semibold text-slate-200">Current Resume PDF</p>
                                <p class="font-body text-[11px] text-slate-400 truncate max-w-[200px]"><?= basename($fileUrl) ?></p>
                            </div>
                        </div>
                        <a href="<?= htmlspecialchars($fileUrl) ?>" target="_blank" class="px-3 py-1.5 rounded-lg bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 hover:bg-cyan-500 hover:text-slate-950 font-subhead text-[11px] font-semibold uppercase transition-all">
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

                <div class="flex justify-end pt-4 border-t border-slate-800/80">
                    <button type="submit" 
                        class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                        <i class="ph-bold ph-upload-simple text-base"></i>
                        <span>Upload Resume</span>
                    </button>
                </div>
            </div>
            <?php
        } else {
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Error: Failed to save file\", \"type\": \"error\"}}");
        }
        exit;
    }
    
    if ($action === 'delete_pdf') {
        // Get current resume URL
        $resumeQuery = $pdo->query("SELECT setting_value FROM settings WHERE setting_key = 'resume_pdf_url'");
        $resumeUrl = $resumeQuery->fetchColumn();
        
        if ($resumeUrl) {
            // Delete file from server
            $filePath = __DIR__ . '/..' . str_replace('/nel-portfolio', '', $resumeUrl);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Delete from database
            $stmt = $pdo->prepare("DELETE FROM settings WHERE setting_key = 'resume_pdf_url'");
            $stmt->execute();
            
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Resume deleted successfully\"}}");
        }
        
        // Return empty container
        ?>
        <div id="resume-pdf-container">
            <div class="mb-6">
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Upload Resume PDF</label>
                <div class="relative">
                    <input type="file" name="resume_file" accept=".pdf" required
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-cyan-500/10 file:text-cyan-400 hover:file:bg-cyan-500/20 file:cursor-pointer focus:outline-none focus:border-cyan-500 transition-all">
                </div>
                <p class="font-body text-xs text-slate-500 mt-2">Select a PDF file from your computer. Maximum file size: 5MB.</p>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-800/80">
                <button type="submit" 
                    class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer flex items-center gap-2">
                    <i class="ph-bold ph-upload-simple text-base"></i>
                    <span>Upload Resume</span>
                </button>
            </div>
        </div>
        <?php
        exit;
    }
}
?>
