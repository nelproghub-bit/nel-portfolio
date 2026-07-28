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
