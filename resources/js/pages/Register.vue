<template>
    <div class="register-page">
        <div class="register-container">
            <div class="register-card">
                <div class="register-header">
                    <div class="logo">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h1>Daftar Akun</h1>
                    <p>Buat akun baru untuk mengakses GIS Bali</p>
                </div>

                <form @submit.prevent="handleRegister" class="register-form">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input
                                type="text"
                                id="name"
                                v-model="form.name"
                                placeholder="Masukkan nama lengkap Anda"
                                required
                                :disabled="loading"
                            />
                        </div>
                    </div>

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
                                placeholder="Masukkan password Anda (minimal 6 karakter)"
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
                        <div style="font-size: 0.85rem; color: #666; margin-top: 4px;">
                            Minimal 6 karakter (boleh huruf kecil, angka, atau gabungan)
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar" :class="passwordStrength.class">
                                <div class="strength-fill" :style="{ width: passwordStrength.width }"></div>
                            </div>
                            <span class="strength-text">{{ passwordStrength.text }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Konfirmasi Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input
                                :type="showConfirmPassword ? 'text' : 'password'"
                                id="confirmPassword"
                                v-model="form.confirmPassword"
                                placeholder="Konfirmasi password Anda"
                                required
                                :disabled="loading"
                            />
                            <button
                                type="button"
                                class="password-toggle"
                                @click="showConfirmPassword = !showConfirmPassword"
                                :disabled="loading"
                            >
                                <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" v-model="agreeTerms" :disabled="loading" required>
                            <span class="checkmark"></span>
                            Saya setuju dengan <a href="#" @click.prevent>syarat dan ketentuan</a>
                        </label>
                    </div>

                    <button type="submit" class="register-btn" :disabled="loading || !canSubmit">
                        <span v-if="loading" class="loading-spinner"></span>
                        <i v-else class="fas fa-user-plus"></i>
                        {{ loading ? 'Mendaftar...' : 'Daftar' }}
                    </button>
                </form>

                <div class="register-footer">
                    <p>Sudah punya akun? <router-link to="/login">Masuk di sini</router-link></p>
                </div>
            </div>
        </div>

        <!-- Background -->
        <div class="register-background">
            <div class="bg-overlay"></div>
        </div>
    </div>
</template>

<script>
import AuthService from '../services/AuthService.js';
import toast from '../utils/toast.js';

export default {
    name: 'Register',
    data() {
        return {
            form: {
                name: '',
                email: '',
                password: '',
                confirmPassword: ''
            },
            showPassword: false,
            showConfirmPassword: false,
            agreeTerms: false,
            loading: false
        }
    },
    computed: {
        passwordStrength() {
            const password = this.form.password;
            if (!password) return { class: '', width: '0%', text: '' };

            let score = 0;
            let text = '';
            let className = '';

            // Length check
            if (password.length >= 8) score += 1;
            if (password.length >= 12) score += 1;

            // Character variety checks
            if (/[a-z]/.test(password)) score += 1;
            if (/[A-Z]/.test(password)) score += 1;
            if (/[0-9]/.test(password)) score += 1;
            if (/[^A-Za-z0-9]/.test(password)) score += 1;

            if (score < 3) {
                text = 'Lemah';
                className = 'weak';
            } else if (score < 5) {
                text = 'Sedang';
                className = 'medium';
            } else {
                text = 'Kuat';
                className = 'strong';
            }

            return {
                class: className,
                width: `${(score / 6) * 100}%`,
                text: text
            };
        },
        canSubmit() {
            return this.form.name.trim() && 
                   this.form.email.trim() && 
                   this.form.password.length >= 6 && 
                   this.form.password === this.form.confirmPassword && 
                   this.agreeTerms;
        }
    },
    mounted() {
        // Redirect if already authenticated
        if (AuthService.isAuthenticated()) {
            this.$router.push('/dashboard');
        }
    },
    methods: {
        async handleRegister() {
            if (!this.validateForm()) {
                return;
            }

            this.loading = true;
            
            try {
                console.log('Form data:', {
                    name: this.form.name,
                    email: this.form.email,
                    password: this.form.password ? '***' : 'empty',
                    confirmPassword: this.form.confirmPassword ? '***' : 'empty'
                });
                
                const result = await AuthService.registerDual({
                    name: this.form.name,
                    email: this.form.email,
                    password: this.form.password,
                    password_confirmation: this.form.confirmPassword
                });
                
                if (result.success) {
                    const msg = result.remoteSuccess 
                        ? 'Registrasi berhasil di sistem lokal dan API!'
                        : 'Registrasi berhasil di sistem lokal (API tidak tersedia)';
                    toast.success(msg, 'Sukses');
                    
                    setTimeout(() => {
                        this.$router.push('/dashboard');
                    }, 1500);
                } else {
                    console.error('Registration failed:', result.message);
                    toast.error(result.message, 'Registrasi Gagal');
                }
            } catch (error) {
                console.error('Register error:', error);
                toast.error('Terjadi kesalahan saat registrasi. Silakan coba lagi.', 'Error');
            } finally {
                this.loading = false;
            }
        },

        validateForm() {
            if (!this.form.name.trim()) {
                toast.warning('Silakan masukkan nama lengkap Anda.', 'Validasi');
                return false;
            }

            if (!this.form.email.trim()) {
                toast.warning('Silakan masukkan email Anda.', 'Validasi');
                return false;
            }

            if (!this.form.password.trim()) {
                toast.warning('Silakan masukkan password Anda.', 'Validasi');
                return false;
            }

            if (this.form.password.length < 6) {
                toast.warning('Password minimal 6 karakter.', 'Validasi');
                return false;
            }

            if (this.form.password !== this.form.confirmPassword) {
                toast.warning('Konfirmasi password tidak cocok.', 'Validasi');
                return false;
            }

            if (!this.agreeTerms) {
                toast.warning('Silakan setujui syarat dan ketentuan.', 'Validasi');
                return false;
            }

            return true;
        }
    }
}
</script>

<style scoped>
.register-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    padding: 2rem 0;
}

.register-background {
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

.register-container {
    width: 100%;
    max-width: 450px;
    padding: 2rem;
    z-index: 1;
}

.register-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.register-header {
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

.register-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0 0 0.5rem;
}

.register-header p {
    color: #718096;
    font-size: 0.875rem;
    margin: 0;
}

.register-form {
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

.password-strength {
    margin-top: 0.5rem;
}

.strength-bar {
    height: 4px;
    background: #e2e8f0;
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 0.25rem;
}

.strength-fill {
    height: 100%;
    transition: all 0.3s;
    border-radius: 2px;
}

.strength-bar.weak .strength-fill {
    background: #f56565;
}

.strength-bar.medium .strength-fill {
    background: #ed8936;
}

.strength-bar.strong .strength-fill {
    background: #48bb78;
}

.strength-text {
    font-size: 0.75rem;
    color: #718096;
}

.checkbox-wrapper {
    display: flex;
    align-items: flex-start;
    cursor: pointer;
    font-size: 0.875rem;
    color: #4a5568;
    line-height: 1.4;
}

.checkbox-wrapper input {
    margin-right: 0.5rem;
    margin-top: 0.125rem;
}

.checkbox-wrapper a {
    color: #667eea;
    text-decoration: none;
}

.checkbox-wrapper a:hover {
    text-decoration: underline;
}

.register-btn {
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

.register-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.register-btn:disabled {
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

.register-footer {
    text-align: center;
}

.register-footer p {
    color: #718096;
    font-size: 0.875rem;
    margin: 0;
}

.register-footer a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.register-footer a:hover {
    text-decoration: underline;
}

@media (max-width: 640px) {
    .register-container {
        padding: 1rem;
    }
    
    .register-card {
        padding: 2rem;
    }
    
    .logo {
        width: 60px;
        height: 60px;
    }
    
    .logo i {
        font-size: 1.5rem;
    }
    
    .register-header h1 {
        font-size: 1.75rem;
    }
}
</style>
