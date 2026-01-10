<template>
    <div class="h-full relative" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh;">
        <div class="flex min-h-screen flex-col justify-center py-12 sm:px-6 lg:px-8 relative z-10">
            <div class="sm:mx-auto sm:w-full sm:max-w-md animate-fade-in-down">
                <div class="flex justify-center mb-8">
                    <div class="bg-gradient-to-br from-emerald-600 to-cyan-700 rounded-2xl p-6 shadow-xl">
                        <i class="fas fa-user-plus text-4xl text-white"></i>
                    </div>
                </div>
                
                <h2 class="text-center text-3xl font-bold tracking-tight text-gray-900 mb-2">
                    Bergabung dengan Kami
                </h2>
                <h3 class="text-center text-xl font-semibold text-emerald-600 mb-4">
                    GIS Bali
                </h3>
                <p class="text-center text-gray-600 mb-8">
                    Daftar untuk mengakses sistem informasi geografis
                </p>
                <p class="text-center text-sm text-gray-500">
                    Sudah punya akun?
                    <router-link to="/login" class="font-semibold text-emerald-600 hover:text-emerald-500 transition-colors duration-300">
                        Masuk di sini
                    </router-link>
                </p>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md animate-fade-in-up">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 py-10 px-8">
                    <form @submit.prevent="handleSubmit" class="space-y-6">
                        <div v-if="errors.length > 0" class="rounded-xl bg-red-50 border border-red-200 p-4 animate-slide-in-left">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-400 text-lg"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-semibold text-red-800">
                                        Terjadi kesalahan
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc space-y-1 pl-5">
                                            <li v-for="error in errors" :key="error">{{ error }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div class="group">
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-user text-emerald-600 mr-2"></i>
                                    Nama Lengkap
                                </label>
                                <div class="relative">
                                    <input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        autocomplete="name"
                                        required
                                        placeholder="Masukkan nama lengkap"
                                        class="block w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-4 text-gray-900 placeholder-gray-500 shadow-sm focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 transition-all duration-300 text-sm"
                                    />
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="group">
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-envelope text-primary-600 mr-2"></i>
                                    Alamat Email
                                </label>
                                <div class="relative">
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        autocomplete="email"
                                        required
                                        placeholder="Masukkan alamat email"
                                        class="block w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-4 text-gray-900 placeholder-gray-500 shadow-sm focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-200 transition-all duration-300 text-sm"
                                    />
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <i class="fas fa-at text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="group">
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kata Sandi
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none z-10">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input
                                        id="password"
                                        v-model="form.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        autocomplete="new-password"
                                        required
                                        placeholder="Masukkan password Anda"
                                        class="block w-full rounded-xl border-2 border-gray-200 bg-gray-50 pl-12 pr-12 py-4 text-gray-900 placeholder-gray-500 shadow-sm focus:border-purple-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all duration-300 text-sm"
                                    />
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-purple-600 focus:outline-none transition-colors duration-200 cursor-pointer z-20"
                                    >
                                        <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
                                <div class="mt-2 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Minimal 8 karakter dengan kombinasi huruf dan angka
                                </div>
                            </div>

                            <div class="group">
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Konfirmasi Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none z-10">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input
                                        id="password_confirmation"
                                        v-model="form.password_confirmation"
                                        :type="showPasswordConfirmation ? 'text' : 'password'"
                                        autocomplete="new-password"
                                        required
                                        placeholder="Konfirmasi password Anda"
                                        class="block w-full rounded-xl border-2 border-gray-200 bg-gray-50 pl-12 pr-12 py-4 text-gray-900 placeholder-gray-500 shadow-sm focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-200 transition-all duration-300 text-sm"
                                    />
                                    <button
                                        type="button"
                                        @click="showPasswordConfirmation = !showPasswordConfirmation"
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-cyan-600 focus:outline-none transition-colors duration-200 cursor-pointer z-20"
                                    >
                                        <i :class="showPasswordConfirmation ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input
                                        id="terms"
                                        v-model="form.terms"
                                        type="checkbox"
                                        required
                                        class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                                    />
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="terms" class="text-gray-700">
                                        Saya menyetujui
                                        <a href="#" class="font-medium text-emerald-600 hover:text-emerald-500 underline">
                                            syarat dan ketentuan
                                        </a>
                                        yang berlaku
                                    </label>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button
                                    type="submit"
                                    :disabled="loading"
                                    class="group relative w-full flex justify-center py-4 px-6 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-emerald-600 to-cyan-700 hover:from-emerald-700 hover:to-cyan-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300 transform hover:scale-105 hover:shadow-lg disabled:opacity-50"
                                >
                                    <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                        <i class="fas fa-user-plus text-white/80 group-hover:text-white transition-colors"></i>
                                    </span>
                                    <span class="relative">{{ loading ? 'Memproses...' : 'Daftar Sekarang' }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AuthService from '../../services/AuthService.js';
import toast from '../../utils/toast.js';

export default {
    name: 'Register',
    data() {
        return {
            form: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                terms: false,
            },
            showPassword: false,
            showPasswordConfirmation: false,
            loading: false,
            errors: [],
        };
    },
    mounted() {
        // Redirect jika sudah login
        if (AuthService.isAuthenticated()) {
            this.$router.push('/dashboard');
        }
    },
    methods: {
        async handleSubmit() {
            // Validasi form
            if (!this.validateForm()) {
                return;
            }

            this.loading = true;
            this.errors = [];

            try {
                console.log('Starting dual registration...');
                console.log('Form data:', {
                    name: this.form.name,
                    email: this.form.email,
                    password: this.form.password ? '***' : 'empty',
                    password_confirmation: this.form.password_confirmation ? '***' : 'empty'
                });
                
                // Gunakan registerDual untuk register ke lokal dan remote sekaligus
                const result = await AuthService.registerDual({
                    name: this.form.name,
                    email: this.form.email,
                    password: this.form.password,
                    password_confirmation: this.form.password_confirmation
                });
                
                console.log('Registration result:', result);
                
                if (result.success) {
                    // Tampilkan notifikasi sukses
                    const message = result.remoteSuccess 
                        ? 'Registrasi berhasil di sistem lokal dan API!' 
                        : 'Registrasi berhasil di sistem lokal!';
                    toast.success('Registrasi Berhasil!', message);
                    
                    // Redirect setelah delay singkat
                    setTimeout(() => {
                        this.$router.push('/dashboard');
                    }, 1500);
                } else {
                    // Tampilkan notifikasi error
                    console.error('Registration failed:', result.message);
                    toast.error('Registrasi Gagal', result.message);
                    this.errors = [result.message];
                }
            } catch (error) {
                console.error('Register error:', error);
                const errorMsg = 'Terjadi kesalahan saat registrasi. Silakan coba lagi.';
                toast.error('Registrasi Gagal', errorMsg);
                this.errors = [errorMsg];
            } finally {
                this.loading = false;
            }
        },

        validateForm() {
            if (!this.form.name.trim()) {
                toast.warning('Validasi', 'Silakan masukkan nama lengkap Anda.');
                return false;
            }

            if (!this.form.email.trim()) {
                toast.warning('Validasi', 'Silakan masukkan email Anda.');
                return false;
            }

            if (!this.form.password.trim()) {
                toast.warning('Validasi', 'Silakan masukkan password Anda.');
                return false;
            }

            if (this.form.password.length < 8) {
                toast.warning('Validasi', 'Password minimal 8 karakter.');
                return false;
            }

            if (this.form.password !== this.form.password_confirmation) {
                toast.warning('Validasi', 'Konfirmasi password tidak cocok.');
                return false;
            }

            if (!this.form.terms) {
                toast.warning('Validasi', 'Silakan setujui syarat dan ketentuan.');
                return false;
            }

            return true;
        }
    },
};
</script>

<style scoped>
@keyframes fadeInDown {
    0% { opacity: 0; transform: translateY(-30px); }
    100% { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInUp {
    0% { opacity: 0; transform: translateY(30px); }
    100% { opacity: 1; transform: translateY(0); }
}

@keyframes slideInLeft {
    0% { opacity: 0; transform: translateX(-30px); }
    100% { opacity: 1; transform: translateX(0); }
}

.animate-fade-in-down {
    animation: fadeInDown 0.8s ease-out;
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out;
}

.animate-slide-in-left {
    animation: slideInLeft 0.8s ease-out;
}
</style>

