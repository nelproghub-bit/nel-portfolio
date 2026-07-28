<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';
check_auth_htmx();

// Define upload directory
$uploadDir = __DIR__ . '/../uploads/certificates/';
$webPath = '/nel-portfolio/uploads/certificates/';

// Create upload directory if it doesn't exist
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$method = $_SERVER['REQUEST_METHOD'];

// Handle DELETE
if ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        // Get certificate file path before deleting
        $stmt = $pdo->prepare("SELECT certificate_file FROM certifications WHERE id = ?");
        $stmt->execute([$id]);
        $cert = $stmt->fetch();
        
        // Delete file if exists
        if ($cert && $cert['certificate_file']) {
            $filePath = __DIR__ . '/..' . str_replace('/nel-portfolio', '', $cert['certificate_file']);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM certifications WHERE id = ?");
        $stmt->execute([$id]);
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Certification deleted successfully\"}}");
        echo ""; // Return empty to remove element
        exit;
    }
}

// Handle GET for edit form
if ($method === 'GET' && isset($_GET['action']) && $_GET['action'] === 'edit_form') {
    $id = $_GET['id'] ?? null;
    $stmt = $pdo->prepare("SELECT * FROM certifications WHERE id = ?");
    $stmt->execute([$id]);
    $cert = $stmt->fetch();
    
    if (!$cert) {
        echo "<p>Certification not found.</p>";
        exit;
    }
    ?>
    <div class="p-6 sm:p-8">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-800/80">
            <h3 class="font-turnpike text-lg font-bold text-white tracking-widest">EDIT CERTIFICATION</h3>
            <button onclick="closeCertModal()" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>

        <form hx-post="/nel-portfolio/api/certifications_handler.php" hx-encoding="multipart/form-data" hx-target="#main-content" hx-swap="innerHTML"
              onsubmit="closeCertModal()" class="space-y-5 font-body">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $cert['id'] ?>">

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Certification Title *</label>
                <input type="text" name="name" value="<?= htmlspecialchars($cert['name']) ?>" required
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Issuing Organization *</label>
                <input type="text" name="issuing_organization" value="<?= htmlspecialchars($cert['issuing_organization']) ?>" required
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Issue Date</label>
                <input type="date" name="issue_date" value="<?= htmlspecialchars($cert['issue_date']) ?>" style="color-scheme: dark;"
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all">
            </div>

            <div>
                <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Certificate File Upload</label>
                <input type="file" name="certificate_file" accept=".pdf,.jpg,.jpeg,.png" 
                    class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-cyan-500/10 file:text-cyan-400 hover:file:bg-cyan-500/20 file:cursor-pointer focus:outline-none focus:border-cyan-500 transition-all">
                <p class="font-body text-xs text-slate-500 mt-2">Upload a new certificate to replace the existing one (optional).</p>
                <?php if ($cert['certificate_file']): ?>
                <div class="mt-2 p-3 bg-slate-900/60 rounded-lg border border-slate-800 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Current: <?= basename($cert['certificate_file']) ?></span>
                    <a href="<?= htmlspecialchars($cert['certificate_file']) ?>" target="_blank" class="text-xs text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                        <i class="ph-bold ph-eye"></i> View
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-800/80">
                <button type="button" onclick="closeCertModal()"
                    class="px-5 py-3 rounded-xl font-subhead text-xs font-semibold uppercase tracking-wider text-slate-400 hover:text-white transition-colors">Cancel</button>
                <button type="submit"
                    class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer">
                    Update Certification
                </button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('cert-modal').classList.remove('hidden');
    </script>
    <?php
    exit;
}

// Handle POST (Create / Update)
if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    
    $name = $_POST['name'] ?? '';
    $issuing_organization = $_POST['issuing_organization'] ?? '';
    $issue_date = $_POST['issue_date'] ?: null; // Handle empty date as null
    $certificate_file = '';
    
    // Check if certificate_file column exists, if not add it
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM certifications LIKE 'certificate_file'");
        $columnExists = $stmt->rowCount() > 0;
        
        if (!$columnExists) {
            // Add the column
            $pdo->exec("ALTER TABLE certifications ADD COLUMN certificate_file VARCHAR(255) DEFAULT NULL AFTER issue_date");
            
            // Migrate data from credential_url if it exists
            $stmt = $pdo->query("SHOW COLUMNS FROM certifications LIKE 'credential_url'");
            if ($stmt->rowCount() > 0) {
                $pdo->exec("UPDATE certifications SET certificate_file = credential_url WHERE credential_url IS NOT NULL AND credential_url != ''");
                $pdo->exec("ALTER TABLE certifications DROP COLUMN credential_url");
            }
        }
    } catch (PDOException $e) {
        // Column might already exist, continue
    }
    
    // Handle file upload
    if (isset($_FILES['certificate_file']) && $_FILES['certificate_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['certificate_file'];
        
        // Validate file type
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Error: Only PDF and image files are allowed\", \"type\": \"error\"}}");
            include __DIR__ . '/../admin/views/certifications.php';
            exit;
        }
        
        // Validate file size (5MB max)
        $maxSize = 5 * 1024 * 1024; // 5MB in bytes
        if ($file['size'] > $maxSize) {
            header("HX-Trigger: {\"showMessage\": {\"value\": \"Error: File size exceeds 5MB limit\", \"type\": \"error\"}}");
            include __DIR__ . '/../admin/views/certifications.php';
            exit;
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'cert_' . time() . '_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $certificate_file = $webPath . $filename;
        }
    }
    
    if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO certifications (name, issuing_organization, issue_date, certificate_file) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $issuing_organization, $issue_date, $certificate_file]);
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Certification added successfully\"}}");
    } elseif ($action === 'update') {
        $id = $_POST['id'] ?? null;
        
        // If new file uploaded, delete old file
        if ($certificate_file) {
            $stmt = $pdo->prepare("SELECT certificate_file FROM certifications WHERE id = ?");
            $stmt->execute([$id]);
            $oldCert = $stmt->fetch();
            if ($oldCert && $oldCert['certificate_file']) {
                $oldFilePath = __DIR__ . '/..' . str_replace('/nel-portfolio', '', $oldCert['certificate_file']);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            $stmt = $pdo->prepare("UPDATE certifications SET name=?, issuing_organization=?, issue_date=?, certificate_file=? WHERE id=?");
            $stmt->execute([$name, $issuing_organization, $issue_date, $certificate_file, $id]);
        } else {
            // No new file, just update other fields
            $stmt = $pdo->prepare("UPDATE certifications SET name=?, issuing_organization=?, issue_date=? WHERE id=?");
            $stmt->execute([$name, $issuing_organization, $issue_date, $id]);
        }
        
        header("HX-Trigger: {\"showMessage\": {\"value\": \"Certification updated successfully\"}}");
    }
    
    // Redirect to reload via HTMX
    include __DIR__ . '/../admin/views/certifications.php';
    exit;
}
?>
