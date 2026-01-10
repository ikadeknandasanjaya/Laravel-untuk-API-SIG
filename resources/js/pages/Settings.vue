<template>
    <AppLayout>
        <div class="settings-container">
            <div class="settings-header">
                <h1>Pengaturan</h1>
                <p>Kustomisasi preferensi aplikasi Anda</p>
            </div>

            <!-- Appearance Settings -->
            <div class="settings-section">
                <div class="section-header">
                    <h2>Tampilan</h2>
                    <p>Sesuaikan cara aplikasi terlihat dan terasa</p>
                </div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <h3>Mode Gelap</h3>
                        <p>Beralih antara tema terang dan gelap</p>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input 
                                type="checkbox" 
                                :checked="isDarkMode"
                                @change="toggleDarkMode"
                            >
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h3>Sidebar Terlipat</h3>
                        <p>Jaga sidebar tetap terlipat secara default</p>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input 
                                type="checkbox" 
                                :checked="sidebarCollapsed"
                                @change="toggleSidebarCollapsed"
                            >
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Map Settings -->
            <div class="settings-section">
                <div class="section-header">
                    <h2>Preferensi Peta</h2>
                    <p>Konfigurasi pengaturan peta default</p>
                </div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <h3>Gaya Peta Default</h3>
                        <p>Pilih layer tile peta pilihan Anda</p>
                    </div>
                    <div class="setting-control">
                        <select v-model="mapStyle" @change="saveMapStyle" class="select-input">
                            <option value="openstreetmap">OpenStreetMap</option>
                            <option value="satellite">Satellite</option>
                            <option value="terrain">Terrain</option>
                        </select>
                    </div>
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h3>Level Zoom Default</h3>
                        <p>Atur level zoom awal untuk peta</p>
                    </div>
                    <div class="setting-control">
                        <input 
                            type="range" 
                            min="1" 
                            max="18" 
                            v-model="defaultZoom"
                            @change="saveDefaultZoom"
                            class="range-input"
                        >
                        <span class="zoom-value">{{ defaultZoom }}</span>
                    </div>
                </div>
            </div>

            <!-- Data Settings -->
            <div class="settings-section">
                <div class="section-header">
                    <h2>Manajemen Data</h2>
                    <p>Konfigurasi opsi tampilan dan ekspor data</p>
                </div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <h3>Item Per Halaman</h3>
                        <p>Jumlah item yang ditampilkan di tabel data</p>
                    </div>
                    <div class="setting-control">
                        <select v-model="itemsPerPage" @change="saveItemsPerPage" class="select-input">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h3>Simpan Otomatis</h3>
                        <p>Simpan perubahan otomatis saat mengedit</p>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input 
                                type="checkbox" 
                                :checked="autoSave"
                                @change="toggleAutoSave"
                            >
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h3>Notifikasi</h3>
                        <p>Tampilkan notifikasi untuk setiap aksi</p>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input 
                                type="checkbox" 
                                :checked="showNotifications"
                                @change="toggleNotifications"
                            >
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Aksi -->
            <div class="settings-actions">
                <button @click="resetToDefaults" class="btn-secondary">
                    <i class="fas fa-undo"></i>
                    Reset ke Default
                </button>
                <button @click="exportSettings" class="btn-primary">
                    <i class="fas fa-download"></i>
                    Ekspor Pengaturan
                </button>
            </div>

            <!-- Success Message -->
            <div v-if="successMessage" class="success-message">
                <i class="fas fa-check-circle"></i>
                {{ successMessage }}
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../components/AppLayout.vue';
import toast from '../utils/toast.js';

export default {
    name: 'Settings',
    components: {
        AppLayout
    },
    data() {
        return {
            isDarkMode: false,
            sidebarCollapsed: false,
            mapStyle: 'openstreetmap',
            defaultZoom: 10,
            itemsPerPage: 25,
            autoSave: true,
            showNotifications: true,
            successMessage: ''
        }
    },
    mounted() {
        console.log('⚙️ Settings mounted');
        this.loadSettings();
        this.applyDarkMode();
        
        // Listen to dark mode changes from AppLayout
        window.addEventListener('storage', (e) => {
            if (e.key === 'appSettings') {
                console.log('Storage event received for appSettings');
                const newSettings = JSON.parse(e.newValue || '{}');
                if (newSettings.isDarkMode !== undefined && newSettings.isDarkMode !== this.isDarkMode) {
                    console.log('🔄 Dark mode sync from storage:', newSettings.isDarkMode);
                    this.isDarkMode = newSettings.isDarkMode;
                    this.applyDarkMode();
                }
            }
        });
    },
    watch: {
        isDarkMode(newVal) {
            console.log('👁️ isDarkMode watcher triggered, new value:', newVal);
            this.applyDarkMode();
        }
    },
    methods: {
        loadSettings() {
            // Load settings from localStorage
            const settings = JSON.parse(localStorage.getItem('appSettings') || '{}');
            
            console.log('📖 Loading settings from localStorage:', settings);
            
            this.isDarkMode = settings.isDarkMode ?? false;
            this.sidebarCollapsed = settings.sidebarCollapsed ?? false;
            this.mapStyle = settings.mapStyle ?? 'openstreetmap';
            this.defaultZoom = settings.defaultZoom ?? 10;
            this.itemsPerPage = settings.itemsPerPage ?? 25;
            this.autoSave = settings.autoSave ?? true;
            this.showNotifications = settings.showNotifications ?? true;
            
            console.log('Settings loaded');
        },
        saveSettings() {
            const settings = {
                isDarkMode: this.isDarkMode,
                sidebarCollapsed: this.sidebarCollapsed,
                mapStyle: this.mapStyle,
                defaultZoom: this.defaultZoom,
                itemsPerPage: this.itemsPerPage,
                autoSave: this.autoSave,
                showNotifications: this.showNotifications
            };
            
            console.log('Saving settings:', settings);
            localStorage.setItem('appSettings', JSON.stringify(settings));
            localStorage.setItem('lastSettingsUpdate', new Date().toISOString());
            
            // Emit event to parent components if needed
            this.$emit('settings-changed', settings);
            
            // Show success message
            this.successMessage = 'Pengaturan berhasil disimpan!';
            console.log('Settings saved successfully');
            setTimeout(() => {
                this.successMessage = '';
            }, 3000);
        },
        toggleDarkMode() {
            console.log('🌙 toggleDarkMode clicked, current:', this.isDarkMode);
            this.isDarkMode = !this.isDarkMode;
            console.log('🌙 Dark mode changed to:', this.isDarkMode);
            this.applyDarkMode();
            this.saveSettings();
            if (this.showNotifications) {
                const msg = this.isDarkMode ? 'Mode Gelap Diaktifkan' : 'Mode Terang Diaktifkan';
                console.log('📢 Toast:', msg);
                toast.info(msg, 'Info');
            }
        },
        applyDarkMode() {
            console.log('🎨 applyDarkMode called, isDarkMode:', this.isDarkMode);
            if (this.isDarkMode) {
                console.log('Adding dark class');
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            } else {
                console.log('Removing dark class');
                document.documentElement.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            }
        },
        toggleSidebarCollapsed() {
            console.log('📂 toggleSidebarCollapsed, current:', this.sidebarCollapsed);
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed ? 'true' : 'false');
            this.saveSettings();
            if (this.showNotifications) {
                const msg = this.sidebarCollapsed ? 'Sidebar Dilipat' : 'Sidebar Diperluas';
                console.log('📢 Toast:', msg);
                toast.info(msg, 'Info');
            }
            // Broadcast to sidebar
            window.dispatchEvent(new CustomEvent('sidebar-toggle', { detail: { collapsed: this.sidebarCollapsed } }));
        },
        saveMapStyle() {
            localStorage.setItem('mapStyle', this.mapStyle);
            this.saveSettings();
            if (this.showNotifications) {
                toast.info(`Gaya Peta Diubah ke ${this.mapStyle}`, 'Info');
            }
        },
        saveDefaultZoom() {
            localStorage.setItem('defaultZoom', this.defaultZoom);
            this.saveSettings();
        },
        saveItemsPerPage() {
            localStorage.setItem('itemsPerPage', this.itemsPerPage);
            this.saveSettings();
            if (this.showNotifications) {
                toast.info(`Items per halaman diubah ke ${this.itemsPerPage}`, 'Info');
            }
        },
        toggleAutoSave() {
            this.autoSave = !this.autoSave;
            localStorage.setItem('autoSave', this.autoSave ? 'true' : 'false');
            this.saveSettings();
            if (this.showNotifications) {
                toast.info(this.autoSave ? 'Simpan Otomatis Diaktifkan' : 'Simpan Otomatis Dinonaktifkan', 'Info');
            }
        },
        toggleNotifications() {
            this.showNotifications = !this.showNotifications;
            localStorage.setItem('showNotifications', this.showNotifications ? 'true' : 'false');
            this.saveSettings();
        },
        resetToDefaults() {
            if (confirm('Apakah Anda yakin ingin mereset semua pengaturan ke default?')) {
                this.isDarkMode = false;
                this.sidebarCollapsed = false;
                this.mapStyle = 'openstreetmap';
                this.defaultZoom = 10;
                this.itemsPerPage = 25;
                this.autoSave = true;
                this.showNotifications = true;
                
                this.applyDarkMode();
                this.saveSettings();
                
                toast.success('Pengaturan berhasil direset ke default!', 'Sukses');
            }
        },
        exportSettings() {
            const settings = {
                isDarkMode: this.isDarkMode,
                sidebarCollapsed: this.sidebarCollapsed,
                mapStyle: this.mapStyle,
                defaultZoom: this.defaultZoom,
                itemsPerPage: this.itemsPerPage,
                autoSave: this.autoSave,
                showNotifications: this.showNotifications,
                exportDate: new Date().toISOString()
            };
            
            const dataStr = JSON.stringify(settings, null, 2);
            const dataBlob = new Blob([dataStr], { type: 'application/json' });
            
            const link = document.createElement('a');
            link.href = URL.createObjectURL(dataBlob);
            link.download = `gis-app-settings-${new Date().getTime()}.json`;
            link.click();
            
            if (this.showNotifications) {
                toast.success('Pengaturan berhasil diekspor!', 'Sukses');
            }
        }
    }
}
</script>

<style scoped>
.settings-container {
    max-width: 800px;
    margin: 0 auto;
}

.settings-header {
    margin-bottom: 2rem;
}

.settings-header h1 {
    font-size: 2rem;
    font-weight: bold;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.settings-header p {
    color: #6b7280;
    margin: 0;
}

.settings-section {
    background: #ffffff;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.section-header {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.section-header h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.25rem 0;
}

.section-header p {
    color: #6b7280;
    font-size: 0.875rem;
    margin: 0;
}

.setting-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.setting-item:last-child {
    border-bottom: none;
}

.setting-info h3 {
    font-size: 1rem;
    font-weight: 500;
    color: #111827;
    margin: 0 0 0.25rem 0;
}

.setting-info p {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}

.setting-control {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: 0.3s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #3b82f6;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

/* Select Input */
.select-input {
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: #ffffff;
    color: #111827;
    font-size: 0.875rem;
    min-width: 120px;
}

.select-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Range Input */
.range-input {
    width: 120px;
    margin-right: 0.5rem;
}

.zoom-value {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    min-width: 20px;
    text-align: center;
}

/* Action Buttons */
.settings-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

.btn-primary,
.btn-secondary {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.btn-primary {
    background: #3b82f6;
    color: #ffffff;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

/* Dark mode styles */
:global(.dark) .settings-header h1,
:global(.dark) .section-header h2,
:global(.dark) .setting-info h3 {
    color: #f9fafb;
}

:global(.dark) .settings-header p,
:global(.dark) .section-header p,
:global(.dark) .setting-info p {
    color: #d1d5db;
}

:global(.dark) .settings-section {
    background: #1f2937;
    border-color: #374151;
}

:global(.dark) .section-header {
    border-bottom-color: #374151;
}

:global(.dark) .setting-item {
    border-bottom-color: #374151;
}

:global(.dark) .select-input {
    background: #374151;
    border-color: #4b5563;
    color: #f9fafb;
}

:global(.dark) .select-input:focus {
    border-color: #60a5fa;
    box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
}

:global(.dark) .zoom-value {
    color: #d1d5db;
}

:global(.dark) .btn-secondary {
    background: #374151;
    color: #d1d5db;
    border-color: #4b5563;
}

:global(.dark) .btn-secondary:hover {
    background: #4b5563;
}

/* Success Message */
.success-message {
    margin-top: 1.5rem;
    padding: 1rem;
    background: #d1fae5;
    border: 1px solid #6ee7b7;
    border-radius: 8px;
    color: #065f46;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 500;
    animation: slideIn 0.3s ease-out;
}

.success-message i {
    color: #10b981;
}

:global(.dark) .success-message {
    background: #064e3b;
    border-color: #047857;
    color: #a7f3d0;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
