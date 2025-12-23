<template>
    <AppLayout>
        <div class="settings-container">
            <div class="settings-header">
                <h1>Settings</h1>
                <p>Customize your application preferences</p>
            </div>

            <!-- Appearance Settings -->
            <div class="settings-section">
                <div class="section-header">
                    <h2>Appearance</h2>
                    <p>Customize how the application looks and feels</p>
                </div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <h3>Dark Mode</h3>
                        <p>Switch between light and dark themes</p>
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
                        <h3>Sidebar Collapsed</h3>
                        <p>Keep sidebar collapsed by default</p>
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
                    <h2>Map Preferences</h2>
                    <p>Configure default map settings</p>
                </div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <h3>Default Map Style</h3>
                        <p>Choose your preferred map tile layer</p>
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
                        <h3>Default Zoom Level</h3>
                        <p>Set the initial zoom level for maps</p>
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
                    <h2>Data Management</h2>
                    <p>Configure data display and export options</p>
                </div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <h3>Items Per Page</h3>
                        <p>Number of items to display in data tables</p>
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
                        <h3>Auto-save</h3>
                        <p>Automatically save changes while editing</p>
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
            </div>

            <!-- Actions -->
            <div class="settings-actions">
                <button @click="resetToDefaults" class="btn-secondary">
                    <i class="fas fa-undo"></i>
                    Reset to Defaults
                </button>
                <button @click="exportSettings" class="btn-primary">
                    <i class="fas fa-download"></i>
                    Export Settings
                </button>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../components/AppLayout.vue';

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
            autoSave: true
        }
    },
    mounted() {
        this.loadSettings();
    },
    methods: {
        loadSettings() {
            // Load settings from localStorage
            const settings = JSON.parse(localStorage.getItem('appSettings') || '{}');
            
            this.isDarkMode = settings.isDarkMode ?? false;
            this.sidebarCollapsed = settings.sidebarCollapsed ?? false;
            this.mapStyle = settings.mapStyle ?? 'openstreetmap';
            this.defaultZoom = settings.defaultZoom ?? 10;
            this.itemsPerPage = settings.itemsPerPage ?? 25;
            this.autoSave = settings.autoSave ?? true;
        },
        saveSettings() {
            const settings = {
                isDarkMode: this.isDarkMode,
                sidebarCollapsed: this.sidebarCollapsed,
                mapStyle: this.mapStyle,
                defaultZoom: this.defaultZoom,
                itemsPerPage: this.itemsPerPage,
                autoSave: this.autoSave
            };
            
            localStorage.setItem('appSettings', JSON.stringify(settings));
            
            // Emit event to parent components if needed
            this.$emit('settings-changed', settings);
        },
        toggleDarkMode() {
            this.isDarkMode = !this.isDarkMode;
            this.applyDarkMode();
            this.saveSettings();
        },
        applyDarkMode() {
            if (this.isDarkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        toggleSidebarCollapsed() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            this.saveSettings();
        },
        saveMapStyle() {
            this.saveSettings();
        },
        saveDefaultZoom() {
            this.saveSettings();
        },
        saveItemsPerPage() {
            this.saveSettings();
        },
        toggleAutoSave() {
            this.autoSave = !this.autoSave;
            this.saveSettings();
        },
        resetToDefaults() {
            if (confirm('Are you sure you want to reset all settings to defaults?')) {
                this.isDarkMode = false;
                this.sidebarCollapsed = false;
                this.mapStyle = 'openstreetmap';
                this.defaultZoom = 10;
                this.itemsPerPage = 25;
                this.autoSave = true;
                
                this.applyDarkMode();
                this.saveSettings();
                
                alert('Settings have been reset to defaults.');
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
                exportDate: new Date().toISOString()
            };
            
            const dataStr = JSON.stringify(settings, null, 2);
            const dataBlob = new Blob([dataStr], { type: 'application/json' });
            
            const link = document.createElement('a');
            link.href = URL.createObjectURL(dataBlob);
            link.download = 'gis-app-settings.json';
            link.click();
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
</style>
