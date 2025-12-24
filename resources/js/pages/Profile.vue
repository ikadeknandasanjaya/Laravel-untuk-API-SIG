<template>
    <AppLayout>
        <div class="profile-container">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h1 class="profile-name">{{ user?.name || 'User Profile' }}</h1>
                </div>

                <div class="profile-content">
                    <div class="info-section">
                        <h2 class="section-title">Informasi Pribadi</h2>
                        
                        <div class="info-group">
                            <label class="info-label">Nama Lengkap</label>
                            <div class="info-value">{{ user?.name || '-' }}</div>
                        </div>

                        <div class="info-group">
                            <label class="info-label">Email</label>
                            <div class="info-value">{{ user?.email || '-' }}</div>
                        </div>

                        <div class="info-group">
                            <label class="info-label">User ID</label>
                            <div class="info-value">{{ user?.id || '-' }}</div>
                        </div>

                        <div class="info-group">
                            <label class="info-label">Status Email</label>
                            <div class="info-value">
                                <span v-if="user?.email_verified_at" class="badge verified">
                                    <i class="fas fa-check-circle"></i> Terverifikasi
                                </span>
                                <span v-else class="badge unverified">
                                    <i class="fas fa-exclamation-circle"></i> Belum Terverifikasi
                                </span>
                            </div>
                        </div>

                        <div class="info-group">
                            <label class="info-label">Tanggal Dibuat</label>
                            <div class="info-value">{{ formatDate(user?.created_at) }}</div>
                        </div>

                        <div class="info-group">
                            <label class="info-label">Terakhir Diperbarui</label>
                            <div class="info-value">{{ formatDate(user?.updated_at) }}</div>
                        </div>
                    </div>

                    <div class="info-section">
                        <h2 class="section-title">Sistem Login</h2>
                        <div class="info-group">
                            <label class="info-label">Sistem</label>
                            <div class="info-value">
                                <span class="badge system-badge">{{ loginSystem || 'Local' }}</span>
                            </div>
                        </div>
                        <div class="info-group">
                            <label class="info-label">Status Autentikasi</label>
                            <div class="info-value">
                                <span class="badge auth-active">
                                    <i class="fas fa-check-circle"></i> Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../components/AppLayout.vue';
import AuthService from '../services/AuthService.js';

export default {
    name: 'Profile',
    components: {
        AppLayout
    },
    data() {
        return {
            user: null,
            loginSystem: null
        }
    },
    computed: {
        currentUser() {
            return AuthService.getCurrentUser();
        }
    },
    methods: {
        loadProfileData() {
            this.user = AuthService.getCurrentUser();
            this.loginSystem = AuthService.getCurrentSystem();
            console.log('📋 Profile loaded:', this.user);
        },
        formatDate(dateString) {
            if (!dateString) return '-';
            try {
                const date = new Date(dateString);
                return new Intl.DateTimeFormat('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }).format(date);
            } catch (error) {
                return dateString;
            }
        }
    },
    mounted() {
        this.loadProfileData();
    }
}
</script>

<style scoped>
.profile-container {
    padding: 2rem;
    max-width: 900px;
    margin: 0 auto;
}

.profile-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.profile-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.profile-header {
    background: linear-gradient(135deg, #3b82f6, #10b981);
    color: white;
    padding: 3rem 2rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.profile-avatar {
    font-size: 4rem;
    opacity: 0.9;
}

.profile-name {
    font-size: 1.75rem;
    font-weight: 600;
    margin: 0;
}

.profile-content {
    padding: 2rem;
}

.info-section {
    margin-bottom: 2rem;
}

.info-section:last-child {
    margin-bottom: 0;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 1.5rem 0;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e5e7eb;
}

.info-group {
    margin-bottom: 1.5rem;
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 1rem;
    align-items: center;
}

.info-group:last-child {
    margin-bottom: 0;
}

.info-label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.95rem;
}

.info-value {
    color: #1f2937;
    font-size: 0.95rem;
    padding: 0.75rem 1rem;
    background: #f3f4f6;
    border-radius: 6px;
    border-left: 3px solid #3b82f6;
    word-break: break-word;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
}

.badge.verified {
    background: #d1fae5;
    color: #065f46;
}

.badge.unverified {
    background: #fee2e2;
    color: #7f1d1d;
}

.badge.system-badge {
    background: #dbeafe;
    color: #1e40af;
}

.badge.auth-active {
    background: #d1fae5;
    color: #065f46;
}

.badge i {
    font-size: 0.9rem;
}

/* Dark mode support */
.app-layout.dark .profile-card {
    background: #1f2937;
    color: #f3f4f6;
}

.app-layout.dark .section-title {
    color: #f3f4f6;
    border-bottom-color: #374151;
}

.app-layout.dark .info-label {
    color: #d1d5db;
}

.app-layout.dark .info-value {
    background: #111827;
    color: #f3f4f6;
    border-left-color: #10b981;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-container {
        padding: 1rem;
    }

    .profile-header {
        padding: 2rem 1rem;
    }

    .profile-content {
        padding: 1rem;
    }

    .info-group {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }

    .info-label {
        font-weight: 600;
        color: #4b5563;
    }

    .profile-name {
        font-size: 1.5rem;
    }

    .profile-avatar {
        font-size: 3rem;
    }
}
</style>
