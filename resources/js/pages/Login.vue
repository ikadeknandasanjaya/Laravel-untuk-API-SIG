<template>
    <div class="login-page">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="logo">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h1>GIS Bali</h1>
                    <p>Sistem Informasi Geografis Bali</p>
                </div>

                <form @submit.prevent="handleLogin" class="login-form">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input
                                type="email"
                                id="email"
                                v-model="form.email"
                                placeholder="Masukkan email Anda"
                                required
                                :disabled="loading"
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                id="password"
                                v-model="form.password"
                                placeholder="Masukkan password Anda"
                                required
                                :disabled="loading"
                            />
                            <button
                                type="button"
                                class="password-toggle"
                                @click="showPassword = !showPassword"
                                :disabled="loading"
                            >
                                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="login-btn" :disabled="loading">
                        <span v-if="loading" class="loading-spinner"></span>
                        <i v-else class="fas fa-sign-in-alt"></i>
                        {{ loading ? 'Masuk...' : 'Masuk ke ' + (selectedSystem === 'local' ? 'Lokal' : 'API') }}
                    </button>
                </form>

                <div class="login-footer">
                    <p>Belum punya akun? <router-link to="/register">Daftar di sini</router-link></p>
                </div>
            </div>
        </div>

        <!-- Background -->
        <div class="login-background">
            <div class="bg-overlay"></div>
        </div>
    </div>
</template>

<script>
import AuthService from '../services/AuthService.js';
import toast from '../utils/toast.js';

export default {
    name: 'Login',
    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            selectedSystem: 'local',
            showPassword: false,
            loading: false
        }
    },
    mounted() {
        if (AuthService.isAuthenticated()) {
            this.$router.push('/dashboard');
        }
    },
    methods: {
        async handleLogin() {
            if (!this.validateForm()) {
                return;
            }

            this.loading = true;
            
            try {
                console.log(`Starting ${this.selectedSystem} login...`);
                let result;
                
                if (this.selectedSystem === 'local') {
                    result = await AuthService.loginLocal(this.form);
                } else {
                    result = await AuthService.loginRemote(this.form);
                }
                
                console.log('Login result:', result);
                
                if (result.success) {
                    toast.success(result.message, 'Sukses');
                    
                    setTimeout(() => {
                        const redirectTo = this.$route.query.redirect || '/dashboard';
                        this.$router.push(redirectTo).catch(() => {
                            window.location.href = redirectTo;
                        });
                    }, 1000);
                } else {
                    toast.error(result.message, 'Login Gagal');
                }
            } catch (error) {
                console.error('Login error:', error);
                toast.error('Terjadi kesalahan saat login.', 'Error');
            } finally {
                this.loading = false;
            }
        },

        validateForm() {
            if (!this.form.email.trim()) {
                toast.warning('Silakan masukkan email Anda.', 'Validasi');
                return false;
            }
            if (!this.form.password.trim()) {
                toast.warning('Silakan masukkan password Anda.', 'Validasi');
                return false;
            }
            return true;
        }
    }
}
</script>

<style scoped>
.login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.login-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    z-index: -2;
}

.bg-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    z-index: -1;
}

.login-container {
    width: 100%;
    max-width: 400px;
    padding: 2rem;
    z-index: 1;
}

.login-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.login-header {
    text-align: center;
    margin-bottom: 2rem;
}

.logo {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.logo i {
    font-size: 2rem;
    color: white;
}

.login-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0 0 0.5rem;
}

.login-header p {
    color: #718096;
    font-size: 0.875rem;
    margin: 0;
}

.system-selection {
    margin-bottom: 2rem;
}

.system-selection label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
}

.system-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.system-btn {
    padding: 1rem;
    border: 2px solid #e2e8f0;
    background: white;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: #4a5568;
    font-size: 0.875rem;
}

.system-btn:hover:not(:disabled) {
    border-color: #667eea;
    background: #f7fafc;
}

.system-btn.active {
    border-color: #667eea;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    color: #667eea;
}

.system-btn i {
    font-size: 1.5rem;
}

.system-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.login-form {
    margin-bottom: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-wrapper i {
    position: absolute;
    left: 1rem;
    color: #a0aec0;
    z-index: 2;
}

.input-wrapper input {
    width: 100%;
    padding: 0.875rem 1rem 0.875rem 3rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.875rem;
    transition: all 0.2s;
    background: white;
}

.input-wrapper input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.input-wrapper input:disabled {
    background: #f7fafc;
    cursor: not-allowed;
}

.password-toggle {
    position: absolute;
    right: 1rem;
    background: none;
    border: none;
    color: #a0aec0;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: color 0.2s;
}

.password-toggle:hover {
    color: #667eea;
}

.password-toggle:disabled {
    cursor: not-allowed;
    opacity: 0.5;
}

.checkbox-wrapper {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 0.875rem;
    color: #4a5568;
}

.checkbox-wrapper input {
    margin-right: 0.5rem;
}

.login-btn {
    width: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 0.875rem 1.5rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.login-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.login-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.loading-spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.login-footer {
    text-align: center;
}

.login-footer p {
    color: #718096;
    font-size: 0.875rem;
    margin: 0;
}

.login-footer a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.login-footer a:hover {
    text-decoration: underline;
}

.debug-section {
    text-align: center;
    margin-top: 1rem;
}

.debug-btn {
    background: #f59e0b;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
}

.debug-btn:hover {
    background: #d97706;
}

.emergency-btn {
    background: #dc2626;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
}

.emergency-btn:hover {
    background: #b91c1c;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
}

@media (max-width: 640px) {
    .login-container {
        padding: 1rem;
    }
    
    .login-card {
        padding: 2rem;
    }
    
    .logo {
        width: 60px;
        height: 60px;
    }
    
    .logo i {
        font-size: 1.5rem;
    }
    
    .login-header h1 {
        font-size: 1.75rem;
    }
}
</style>
