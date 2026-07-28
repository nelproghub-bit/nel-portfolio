<?php
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/db.php';
check_auth();

// Check if certificate_file column exists, if not use credential_url
$stmt = $pdo->query("SHOW COLUMNS FROM certifications LIKE 'certificate_file'");
$useCertificateFile = $stmt->rowCount() > 0;

$stmt = $pdo->query("SHOW COLUMNS FROM certifications LIKE 'credential_url'");
$hasCredentialUrl = $stmt->rowCount() > 0;

$certs = $pdo->query("SELECT * FROM certifications ORDER BY issue_date DESC, id DESC")->fetchAll();
?>
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-slate-800/80 pb-6">
    <div>
        <h2 class="text-2xl font-bold font-turnpike text-white tracking-[0.2em] bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-100 to-cyan-300">
            CERTIFICATIONS &amp; BADGES
        </h2>
        <p class="font-body text-slate-400 text-xs sm:text-sm mt-1">Manage your verified certifications, diplomas, and technical awards.</p>
    </div>
    <button onclick="openAddCertModal()"
        class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 px-5 py-3 rounded-xl font-subhead font-semibold text-xs uppercase tracking-widest hover:shadow-lg hover:shadow-cyan-500/30 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 cursor-pointer">
        <i class="ph-bold ph-plus-circle text-lg"></i> Add Certification
    </button>
</div>

<div id="certs-list" class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <?php if (empty($certs)): ?>
        <div class="col-span-full glass-panel p-12 text-center rounded-3xl">
            <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center mx-auto mb-4">
                <i class="ph-bold ph-certificate text-3xl"></i>
            </div>
            <h3 class="font-subhead text-base font-semibold text-white mb-1">No certifications added yet</h3>
            <p class="font-body text-xs text-slate-400 max-w-sm mx-auto mb-6">Catalog your verified achievements and credentials.</p>
            <button onclick="openAddCertModal()"
                class="px-5 py-2.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 font-subhead font-semibold text-xs uppercase tracking-wider hover:bg-amber-500 hover:text-slate-950 transition-all inline-flex items-center gap-2">
                <i class="ph-bold ph-plus text-sm"></i> Add Certification
            </button>
        </div>
    <?php else: ?>
        <?php foreach ($certs as $cert): ?>
            <div class="glass-panel p-6 rounded-3xl flex gap-5 group relative hover:border-amber-500/30 transition-all" id="cert-<?= $cert['id'] ?>">
                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center flex-shrink-0 border border-amber-500/30 text-amber-400 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-slate-950 transition-all">
                    <i class="ph-bold ph-certificate text-2xl"></i>
                </div>
                <div class="flex-1 pr-16">
                    <h3 class="font-subhead text-base font-semibold text-white mb-1 group-hover:text-amber-400 transition-colors"><?= htmlspecialchars($cert['name']) ?></h3>
                    <p class="font-body text-xs text-slate-400"><?= htmlspecialchars($cert['issuing_organization']) ?></p>
                    <div class="flex items-center gap-4 mt-3 text-xs font-body text-slate-500">
                        <?php if ($cert['issue_date']): ?>
                            <span class="flex items-center gap-1.5"><i class="ph-bold ph-calendar-blank text-amber-400"></i> <?= date('F Y', strtotime($cert['issue_date'])) ?></span>
                        <?php endif; ?>
                        <?php 
                        $certFile = '';
                        if ($useCertificateFile && isset($cert['certificate_file'])) {
                            $certFile = $cert['certificate_file'];
                        } elseif ($hasCredentialUrl && isset($cert['credential_url'])) {
                            $certFile = $cert['credential_url'];
                        }
                        if ($certFile): 
                        ?>
                            <a href="<?= htmlspecialchars($certFile) ?>" target="_blank" class="flex items-center gap-1 text-cyan-400 hover:text-cyan-300 transition-colors">
                                <i class="ph-bold ph-file-pdf"></i> View Certificate
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="absolute top-6 right-6 flex gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                    <button
                        hx-get="/nel-portfolio/api/certifications_handler.php?action=edit_form&id=<?= $cert['id'] ?>"
                        hx-target="#modal-content"
                        hx-swap="innerHTML"
                        class="w-8 h-8 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                        <i class="ph-bold ph-pencil text-sm"></i>
                    </button>
                    <button
                        hx-delete="/nel-portfolio/api/certifications_handler.php?id=<?= $cert['id'] ?>"
                        hx-confirm="Delete this certification?"
                        hx-target="#cert-<?= $cert['id'] ?>"
                        hx-swap="outerHTML swap:200ms"
                        class="w-8 h-8 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20 flex items-center justify-center transition-colors">
                        <i class="ph-bold ph-trash text-sm"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Container -->
<div id="cert-modal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="glass-panel border border-cyan-500/20 rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto" id="modal-content">
        <!-- Default Add Form -->
        <div id="add-cert-form-template" class="p-6 sm:p-8">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-800/80">
                <h3 class="font-turnpike text-lg font-bold text-white tracking-widest">ADD CERTIFICATION</h3>
                <button onclick="closeCertModal()" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <form hx-post="/nel-portfolio/api/certifications_handler.php" hx-encoding="multipart/form-data" hx-target="#main-content" hx-swap="innerHTML"
                  onsubmit="closeCertModal()" class="space-y-5 font-body">
                <input type="hidden" name="action" value="create">

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Certification Title *</label>
                    <input type="text" name="name" required placeholder="AWS Certified Solutions Architect"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                </div>

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Issuing Organization *</label>
                    <input type="text" name="issuing_organization" required placeholder="Amazon Web Services, Coursera..."
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 transition-all">
                </div>

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Issue Date</label>
                    <input type="date" name="issue_date" style="color-scheme: dark;"
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all">
                </div>

                <div>
                    <label class="block font-subhead text-xs font-semibold uppercase text-slate-300 tracking-wider mb-2">Certificate File Upload</label>
                    <input type="file" name="certificate_file" accept=".pdf,.jpg,.jpeg,.png" 
                        class="w-full bg-slate-900/80 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-cyan-500/10 file:text-cyan-400 hover:file:bg-cyan-500/20 file:cursor-pointer focus:outline-none focus:border-cyan-500 transition-all">
                    <p class="font-body text-xs text-slate-500 mt-2">Upload your certificate (PDF, JPG, PNG). Maximum file size: 5MB.</p>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-800/80">
                    <button type="button" onclick="closeCertModal()"
                        class="px-5 py-3 rounded-xl font-subhead text-xs font-semibold uppercase tracking-wider text-slate-400 hover:text-white transition-colors">Cancel</button>
                    <button type="submit"
                        class="bg-gradient-to-r from-cyan-500 via-teal-500 to-indigo-600 text-slate-950 font-subhead font-semibold text-xs uppercase tracking-widest px-6 py-3 rounded-xl transition-all hover:shadow-lg hover:shadow-cyan-500/30 cursor-pointer">
                        Save Certification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Capture the add form HTML so we can restore it after an edit form loads
    (function () {
        var t = document.getElementById('add-cert-form-template');
        if (t) window._addCertFormHTML = t.outerHTML;
    })();

    function openAddCertModal() {
        var modalContent = document.getElementById('modal-content');
        // If the add form template is gone (edit form replaced it), restore it
        if (!document.getElementById('add-cert-form-template')) {
            modalContent.innerHTML = window._addCertFormHTML;
            htmx.process(modalContent);
        }
        document.getElementById('cert-modal').classList.remove('hidden');
    }

    function closeCertModal() {
        document.getElementById('cert-modal').classList.add('hidden');
    }

    // Close modal when clicking the backdrop
    document.getElementById('cert-modal').addEventListener('click', function (e) {
        if (e.target === this) closeCertModal();
    });
</script>
