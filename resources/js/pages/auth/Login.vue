<template>
    <div class="h-full relative" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh;">
        <div class="flex min-h-screen flex-col justify-center py-12 sm:px-6 lg:px-8 relative z-10">
            <div class="sm:mx-auto sm:w-full sm:max-w-md animate-fade-in-down">
                <div class="flex justify-center mb-8">
                    <div class="bg-gradient-to-br from-primary-600 to-purple-700 rounded-2xl p-6 shadow-xl">
                        <i class="fas fa-map-marked-alt text-4xl text-white"></i>
                    </div>
                </div>
                
                <h2 class="text-center text-3xl font-bold tracking-tight text-gray-900 mb-2">
                    Selamat Datang
                </h2>
                <h3 class="text-center text-xl font-semibold text-primary-600 mb-4">
                    GIS Bali
                </h3>
                <p class="text-center text-gray-600 mb-8">
                    Masuk untuk mengakses sistem informasi geografis
                </p>
                <p class="text-center text-sm text-gray-500">
                    Belum punya akun?
                    <router-link to="/register" class="font-semibold text-primary-600 hover:text-primary-500 transition-colors duration-300">
                        Daftar di sini
                    </router-link>
                </p>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md animate-fade-in-up">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 py-10 px-8">
                    <form @submit.prevent="handleSubmit" class="space-y-6">
                        <div v-if="errors.length > 0" class="rounded-xl bg-red-50 border border-red-200 p-4 animate-slide-in-right">
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
                                        placeholder="Masukkan email Anda"
                                        class="block w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-4 text-gray-900 placeholder-gray-500 shadow-sm focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-200 transition-all duration-300 text-sm"
                                    />
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <i class="fas fa-at text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="group">
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-lock text-primary-600 mr-2"></i>
                                    Kata Sandi
                                </label>
                                <div class="relative">
                                    <input
                                        id="password"
                                        v-model="form.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        autocomplete="current-password"
                                        required
                                        placeholder="Masukkan kata sandi"
                                        class="block w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-4 text-gray-900 placeholder-gray-500 shadow-sm focus:border-primary-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-200 transition-all duration-300 text-sm"
                                    />
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                        <button
                                            type="button"
                                            @click="showPassword = !showPassword"
                                            class="text-gray-400 hover:text-gray-600 transition-colors"
                                        >
                                            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input
                                        id="remember-me"
                                        v-model="form.remember"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                    />
                                    <label for="remember-me" class="ml-3 block text-sm text-gray-700">
                                        Ingat saya
                                    </label>
                                </div>
                                <div class="text-sm">
                                    <a href="#" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">
                                        Lupa kata sandi?
                                    </a>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button
                                    type="submit"
                                    :disabled="loading"
                                    class="group relative w-full flex justify-center py-4 px-6 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-primary-600 to-purple-700 hover:from-primary-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-300 transform hover:scale-105 hover:shadow-lg disabled:opacity-50"
                                >
                                    <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                        <i class="fas fa-sign-in-alt text-white/80 group-hover:text-white transition-colors"></i>
                                    </span>
                                    <span class="relative">{{ loading ? 'Memproses...' : 'Masuk ke Sistem' }}</span>
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
import axios from 'axios';

export default {
    name: 'Login',
    data() {
        return {
            form: {
                email: '',
                password: '',
                remember: false,
            },
            showPassword: false,
            loading: false,
            errors: [],
        };
    },
    methods: {
        async handleSubmit() {
            this.loading = true;
            this.errors = [];

            try {
                const response = await axios.post('/login', this.form);
                if (response.data.redirect) {
                    window.location.href = response.data.redirect;
                } else {
                    this.$router.push('/dashboard');
                }
            } catch (error) {
                if (error.response?.data?.errors) {
                    const errorData = error.response.data.errors;
                    this.errors = Object.values(errorData).flat();
                } else {
                    this.errors = [error.response?.data?.message || 'Terjadi kesalahan saat login'];
                }
            } finally {
                this.loading = false;
            }
        },
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

@keyframes slideInRight {
    0% { opacity: 0; transform: translateX(30px); }
    100% { opacity: 1; transform: translateX(0); }
}

.animate-fade-in-down {
    animation: fadeInDown 0.8s ease-out;
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out;
}

.animate-slide-in-right {
    animation: slideInRight 0.8s ease-out;
}
</style>

