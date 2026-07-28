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
