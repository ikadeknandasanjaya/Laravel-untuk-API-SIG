<template>
    <div class="sidebar-container" :class="{ 'dark': isDarkMode }">
        <!-- Sidebar -->
        <div class="sidebar" :class="{ 'collapsed': isCollapsed }">
            <!-- Header -->
            <div class="sidebar-header">
                <div class="logo" v-if="!isCollapsed">
                    <div class="logo-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="logo-text">
                        <h1>GISBali</h1>
                        <span>v3.1.0</span>
                    </div>
                </div>
                <button @click="toggleSidebar" class="toggle-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="sidebar-nav">
                <!-- Main Map Section -->
                <div class="nav-section">
                    <div class="section-title" v-if="!isCollapsed">Peta Utama</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <router-link to="/dashboard" class="nav-link" :class="{ active: $route.name === 'dashboard' }">
                                <i class="fas fa-chart-pie"></i>
                                <span v-if="!isCollapsed">Ikhtisar</span>
                            </router-link>
                        </li>
                    </ul>
                </div>

                <!-- Street Section -->
                <div class="nav-section">
                    <div class="section-title" v-if="!isCollapsed">Ruas Jalan</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <router-link to="/ruasjalan" class="nav-link" :class="{ active: $route.name?.includes('ruasjalan') }">
                                <i class="fas fa-list"></i>
                                <span v-if="!isCollapsed">Daftar Ruas Jalan</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/location" class="nav-link">
                                <i class="fas fa-map-marker-alt"></i>
                                <span v-if="!isCollapsed">Lokasi</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/ruasjalan/create" class="nav-link">
                                <i class="fas fa-plus"></i>
                                <span v-if="!isCollapsed">Tambah Ruas Jalan</span>
                            </router-link>
                        </li>
                    </ul>
                </div>

                <!-- Marker Section -->
                <div class="nav-section">
                    <div class="section-title" v-if="!isCollapsed">Marker</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <router-link to="/markers" class="nav-link" :class="{ active: $route.name?.includes('marker') }">
                                <i class="fas fa-list"></i>
                                <span v-if="!isCollapsed">Daftar Marker</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/marker-categories" class="nav-link">
                                <i class="fas fa-tags"></i>
                                <span v-if="!isCollapsed">Kategori Marker</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/markers/create" class="nav-link">
                                <i class="fas fa-plus"></i>
                                <span v-if="!isCollapsed">Tambah Marker</span>

                            </router-link>
                        </li>
                    </ul>
                </div>

                <!-- Line Section -->
                <div class="nav-section">
                    <div class="section-title" v-if="!isCollapsed">Line</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <router-link to="/lines" class="nav-link" :class="{ active: $route.name?.includes('line') }">
                                <i class="fas fa-list"></i>
                                <span v-if="!isCollapsed">Daftar Line</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/line-categories" class="nav-link">
                                <i class="fas fa-tags"></i>
                                <span v-if="!isCollapsed">Kategori Line</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/lines/create" class="nav-link">
                                <i class="fas fa-plus"></i>
                                <span v-if="!isCollapsed">Tambah Line</span>
                            </router-link>
                        </li>
                    </ul>
                </div>

                <!-- Polygon Section -->
                <div class="nav-section">
                    <div class="section-title" v-if="!isCollapsed">Polygon</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <router-link to="/polygons" class="nav-link" :class="{ active: $route.name?.includes('polygon') }">
                                <i class="fas fa-list"></i>
                                <span v-if="!isCollapsed">Daftar Polygon</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/polygon-categories" class="nav-link">
                                <i class="fas fa-tags"></i>
                                <span v-if="!isCollapsed">Kategori Polygon</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/polygons/create" class="nav-link">
                                <i class="fas fa-plus"></i>
                                <span v-if="!isCollapsed">Tambah Polygon</span>
                            </router-link>
                        </li>
                    </ul>
                </div>

                <!-- Circle Section -->
                <div class="nav-section">
                    <div class="section-title" v-if="!isCollapsed">Circle</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <router-link to="/circles" class="nav-link" :class="{ active: $route.name?.includes('circle') }">
                                <i class="fas fa-list"></i>
                                <span v-if="!isCollapsed">Daftar Circle</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/circle-categories" class="nav-link">
                                <i class="fas fa-tags"></i>
                                <span v-if="!isCollapsed">Kategori Circle</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/circles/create" class="nav-link">
                                <i class="fas fa-plus"></i>
                                <span v-if="!isCollapsed">Tambah Circle</span>

                            </router-link>
                        </li>
                    </ul>
                </div>

                <!-- Customan Section -->
                <div class="nav-section">
                    <div class="section-title" v-if="!isCollapsed">Customan</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <router-link to="/customan" class="nav-link" :class="{ active: $route.name?.includes('customan') }">
                                <i class="fas fa-list"></i>
                                <span v-if="!isCollapsed">Daftar Customan</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/customan-categories" class="nav-link">
                                <i class="fas fa-tags"></i>
                                <span v-if="!isCollapsed">Kategori Customan</span>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/customan/create" class="nav-link">
                                <i class="fas fa-plus"></i>
                                <span v-if="!isCollapsed">Tambah Customan</span>

                            </router-link>
                        </li>
                    </ul>
                </div>

                <!-- Profile & Settings -->
                <div class="nav-section">
                    <div class="section-title" v-if="!isCollapsed">Profil</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <router-link to="/profile" class="nav-link" :class="{ active: $route.name === 'profile' }">
                                <i class="fas fa-user"></i>
                                <span v-if="!isCollapsed">Profil</span>
                            </router-link>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <div class="section-title" v-if="!isCollapsed">Pengaturan</div>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <router-link to="/settings" class="nav-link" :class="{ active: $route.name === 'settings' }">
                                <i class="fas fa-cog"></i>
                                <span v-if="!isCollapsed">Pengaturan</span>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- User Info -->
            <div class="user-info" v-if="!isCollapsed">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-details">
                    <span class="username">Halo, {{ username }}</span>
                    <button @click="logout" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AuthService from '../services/AuthService.js';
import toast from '../utils/toast.js';

export default {
    name: 'Sidebar',
    props: {
        isDarkMode: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            isCollapsed: false,
            user: null,
            forceUpdateCounter: 0
        }
    },
    computed: {
        username() {
            this.forceUpdateCounter; // Dependency for reactivity
            const user = this.user || AuthService.getCurrentUser();
            if (user && user.name) {
                return user.name;
            }
            const remoteToken = localStorage.getItem('remote_auth_token');
            return remoteToken ? 'Pengguna' : 'Guest';
        }
    },
    methods: {
        toggleSidebar() {
            this.isCollapsed = !this.isCollapsed;
        },
        async logout() {
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
            this.forceUpdateCounter++;
        },
        async loadUserData() {
            // Refresh user data from API first
            await AuthService.refreshUserData();
            // Then update local component state
            this.refreshUserData();
        }
    },
    mounted() {
        this.loadUserData();
    }
}
</script>

<style scoped>
.sidebar-container {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    z-index: 1000;
    transition: all 0.3s ease;
}

.sidebar {
    width: 280px;
    height: 100%;
    background: #1a1a1a;
    color: #ffffff;
    display: flex;
    flex-direction: column;
    transition: width 0.3s ease;
    overflow-y: auto;
    overflow-x: hidden;
}

.sidebar.collapsed {
    width: 70px;
}

.sidebar-header {
    padding: 1rem;
    border-bottom: 1px solid #333;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.logo-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #3b82f6, #10b981);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.logo-text h1 {
    font-size: 1.25rem;
    font-weight: bold;
    margin: 0;
    color: #ffffff;
}

.logo-text span {
    font-size: 0.75rem;
    color: #9ca3af;
}

.toggle-btn {
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 4px;
    transition: all 0.2s;
}

.toggle-btn:hover {
    background: #333;
    color: #ffffff;
}

.sidebar-nav {
    flex: 1;
    padding: 1rem 0;
}

.nav-section {
    margin-bottom: 1.5rem;
}

.section-title {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #9ca3af;
    padding: 0 1rem;
    margin-bottom: 0.5rem;
    letter-spacing: 0.05em;
}

.nav-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-item {
    margin-bottom: 0.25rem;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: #d1d5db;
    text-decoration: none;
    transition: all 0.2s;
    border-radius: 0;
}

.nav-link:hover {
    background: #333;
    color: #ffffff;
}

.nav-link.active {
    background: linear-gradient(135deg, #3b82f6, #10b981);
    color: #ffffff;
}

.nav-link i {
    width: 20px;
    text-align: center;
    font-size: 1rem;
}

.user-info {
    padding: 1rem;
    border-top: 1px solid #333;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    font-size: 2rem;
    color: #9ca3af;
}

.user-details {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.username {
    font-size: 0.875rem;
    color: #d1d5db;
}

.logout-btn {
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: all 0.2s;
}

.logout-btn:hover {
    background: #333;
    color: #ffffff;
}

/* Dark mode styles */
.sidebar-container.dark .sidebar {
    background: #0f0f0f;
}

.sidebar-container.dark .sidebar-header {
    border-bottom-color: #262626;
}

.sidebar-container.dark .nav-link:hover {
    background: #262626;
}

.sidebar-container.dark .user-info {
    border-top-color: #262626;
}

.sidebar-container.dark .toggle-btn:hover,
.sidebar-container.dark .logout-btn:hover {
    background: #262626;
}

/* Scrollbar styling */
.sidebar::-webkit-scrollbar {
    width: 4px;
}

.sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #333;
    border-radius: 2px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
