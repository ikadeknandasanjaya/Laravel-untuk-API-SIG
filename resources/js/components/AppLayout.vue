<template>
    <div class="app-layout" :class="{ 'dark': isDarkMode }">
        <!-- Sidebar -->
        <Sidebar :isDarkMode="isDarkMode" />
        
        <!-- Main Content -->
        <div class="main-content" :class="{ 'sidebar-collapsed': sidebarCollapsed }">
            <!-- Top Bar -->
            <header class="top-bar">
                <div class="top-bar-left">
                    <h1 class="page-title">{{ pageTitle }}</h1>
                </div>
                <div class="top-bar-right">
                    <!-- Dark Mode Toggle -->
                    <button @click="toggleDarkMode" class="dark-mode-toggle" :title="isDarkMode ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'">
                        <i :class="isDarkMode ? 'fas fa-sun' : 'fas fa-moon'"></i>
                    </button>
                    
                        <!-- User Menu -->
                        <div class="user-menu">
                            <span class="greeting">Halo, {{ userName }}</span>
                            <div class="user-avatar" @click="toggleUserDropdown">
                                <i class="fas fa-user-circle"></i>
                                <div v-if="showUserDropdown" class="user-dropdown">
                                    <button @click="handleLogout" class="dropdown-item logout-btn" style="width: 100%; text-align: left;">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Keluar
                                    </button>
                                </div>
                            </div>
                        </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="page-content">
                <slot />
            </main>
        </div>
        
        <!-- Toast Notifications -->
        <Toast />
    </div>
</template>

<script>
import Sidebar from './Sidebar.vue';
import Toast from './Toast.vue';
import AuthService from '../services/AuthService.js';
import toast from '../utils/toast.js';

export default {
    name: 'AppLayout',
    components: {
        Sidebar,
        Toast
    },
    data() {
        return {
            isDarkMode: false,
            sidebarCollapsed: false,
            showUserDropdown: false,
            user: null,
            forceUpdateCounter: 0
        }
    },
    computed: {
        pageTitle() {
            const routeNames = {
                'dashboard': 'Peta Utama',
                'ruasjalan.index': 'Daftar Ruas Jalan',
                'ruasjalan.create': 'Tambah Ruas Jalan',
                'ruasjalan.edit': 'Edit Ruas Jalan',
                'ruasjalan.show': 'Detail Ruas Jalan',
                'markers': 'Marker Overview',
                'markers.create': 'Add Marker',
                'lines': 'Line Overview',
                'lines.create': 'Add Line',
                'polygons': 'Polygon Overview',
                'polygons.create': 'Add Polygon',
                'circles': 'Circle Overview',
                'circles.create': 'Add Circle',
                'profile': 'Profil',
                'settings': 'Pengaturan'
            };
            return routeNames[this.$route.name] || 'GIS Bali';
        },
        currentUser() {
            this.forceUpdateCounter; // Dependency for reactivity
            return AuthService.getCurrentUser();
        },
        userName() {
            this.forceUpdateCounter; // Dependency for reactivity
            const user = this.user || AuthService.getCurrentUser();
            console.log('🔍 userName computed - user:', user, 'localUser:', AuthService.localUser, 'remoteUser:', AuthService.remoteUser);
            if (user && user.name) {
                return user.name;
            }
            const remoteToken = localStorage.getItem('remote_auth_token');
            return remoteToken ? 'Pengguna' : 'Guest';
        }
    },
    methods: {
        toggleDarkMode() {
            this.isDarkMode = !this.isDarkMode;
            localStorage.setItem('darkMode', JSON.stringify(this.isDarkMode));
            this.applyDarkMode();
        },
        applyDarkMode() {
            if (this.isDarkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        toggleUserDropdown() {
            this.showUserDropdown = !this.showUserDropdown;
        },
        async handleLogout() {
            try {
                await AuthService.logout();
                toast.success('Logout berhasil!', 'Sukses');
                this.$router.push('/login');
            } catch (error) {
                console.error('Logout error:', error);
                toast.error('Terjadi kesalahan saat logout.', 'Error');
            }
        },
        refreshUserData() {
            const user = AuthService.getCurrentUser();
            if (user) {
                this.user = user;
            }
            // Force recompute
            this.forceUpdateCounter++;
        },
        async loadUserData() {
            // Refresh user data from API first
            console.log('📥 loadUserData called - refreshing from API...');
            await AuthService.refreshUserData();
            // Then update local component state
            console.log('✅ loadUserData - after refresh, user:', AuthService.getCurrentUser());
            this.refreshUserData();
        }
    },
    mounted() {
        // Load dark mode preference from localStorage
        const savedDarkMode = localStorage.getItem('darkMode');
        if (savedDarkMode !== null) {
            this.isDarkMode = JSON.parse(savedDarkMode);
        }
        
        // Apply dark mode to document
        this.applyDarkMode();

        // Load user data
        this.loadUserData();

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.user-menu')) {
                this.showUserDropdown = false;
            }
        });
    }
}
</script>

<style scoped>
.app-layout {
    display: flex;
    min-height: 100vh;
    background: #f8fafc;
    transition: all 0.3s ease;
}

.app-layout.dark {
    background: #111827;
}

.main-content {
    flex: 1;
    margin-left: 280px;
    display: flex;
    flex-direction: column;
    transition: margin-left 0.3s ease;
}

.main-content.sidebar-collapsed {
    margin-left: 70px;
}

.top-bar {
    height: 64px;
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 2rem;
    position: sticky;
    top: 0;
    z-index: 100;
    transition: all 0.3s ease;
}

.app-layout.dark .top-bar {
    background: #1f2937;
    border-bottom-color: #374151;
}

.top-bar-left {
    display: flex;
    align-items: center;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.app-layout.dark .page-title {
    color: #f9fafb;
}

.top-bar-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.dark-mode-toggle {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 8px;
    background: #f3f4f6;
    color: #6b7280;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.dark-mode-toggle:hover {
    background: #e5e7eb;
    color: #374151;
}

.app-layout.dark .dark-mode-toggle {
    background: #374151;
    color: #d1d5db;
}

.app-layout.dark .dark-mode-toggle:hover {
    background: #4b5563;
    color: #f9fafb;
}

.user-menu {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.greeting {
    font-size: 0.875rem;
    color: #6b7280;
}

.app-layout.dark .greeting {
    color: #d1d5db;
}

.user-avatar {
    position: relative;
    font-size: 2rem;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s ease;
}

.user-avatar:hover {
    color: #374151;
}

.app-layout.dark .user-avatar {
    color: #d1d5db;
}

.app-layout.dark .user-avatar:hover {
    color: #f9fafb;
}

.user-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 0.5rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    border: 1px solid #e2e8f0;
    min-width: 200px;
    z-index: 1000;
    overflow: hidden;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: #4a5568;
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}

.dropdown-item:hover {
    background: #f7fafc;
    color: #2d3748;
}

.dropdown-item i {
    width: 16px;
    text-align: center;
}

.dropdown-divider {
    border: none;
    border-top: 1px solid #e2e8f0;
    margin: 0.25rem 0;
}

.logout-btn {
    color: #e53e3e;
}

.logout-btn:hover {
    background: #fed7d7;
    color: #c53030;
}

.app-layout.dark .user-dropdown {
    background: #2d3748;
    border-color: #4a5568;
}

.app-layout.dark .dropdown-item {
    color: #e2e8f0;
}

.app-layout.dark .dropdown-item:hover {
    background: #4a5568;
    color: #f7fafc;
}

.app-layout.dark .dropdown-divider {
    border-color: #4a5568;
}

.app-layout.dark .logout-btn {
    color: #fc8181;
}

.app-layout.dark .logout-btn:hover {
    background: #742a2a;
    color: #feb2b2;
}

.page-content {
    flex: 1;
    padding: 2rem;
    overflow-y: auto;
}

/* Responsive */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
    }
    
    .page-content {
        padding: 1rem;
    }
    
    .top-bar {
        padding: 0 1rem;
    }
    
    .greeting {
        display: none;
    }
}
</style>
