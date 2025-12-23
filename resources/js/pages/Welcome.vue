<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-emerald-50">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-sm shadow-sm border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-3">
                        <div class="bg-gradient-to-br from-primary-600 to-emerald-600 rounded-xl p-2.5 shadow-lg">
                            <i class="fas fa-map-marked-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">GIS Bali</h1>
                            <p class="text-xs text-gray-500">Sistem Informasi Geografis</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <router-link
                            v-if="!isAuthenticated"
                            to="/login"
                            class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors"
                        >
                            Masuk
                        </router-link>
                        <router-link
                            v-if="!isAuthenticated"
                            to="/register"
                            class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-primary-600 to-emerald-600 rounded-lg hover:from-primary-700 hover:to-emerald-700 transition-all shadow-md hover:shadow-lg"
                        >
                            Daftar
                        </router-link>
                        <router-link
                            v-else
                            to="/dashboard"
                            class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-primary-600 to-emerald-600 rounded-lg hover:from-primary-700 hover:to-emerald-700 transition-all shadow-md hover:shadow-lg"
                        >
                            Dashboard
                        </router-link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="space-y-8 animate-fade-in-up">
                        <div class="space-y-4">
                            <div class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold">
                                <i class="fas fa-map-location-dot mr-2"></i>
                                Sistem Informasi Geografis
                            </div>
                            <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                                Selamat Datang di
                                <span class="bg-gradient-to-r from-primary-600 to-emerald-600 bg-clip-text text-transparent">
                                    GIS Bali
                                </span>
                            </h1>
                            <p class="text-xl text-gray-600 leading-relaxed">
                                Platform terintegrasi untuk mengelola dan memvisualisasikan data geografis 
                                Provinsi Bali. Kelola ruas jalan, wisata, dan informasi geografis lainnya 
                                dengan mudah dan efisien.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <router-link
                                to="/register"
                                class="px-8 py-4 bg-gradient-to-r from-primary-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 text-center"
                            >
                                <i class="fas fa-rocket mr-2"></i>
                                Mulai Sekarang
                            </router-link>
                            <router-link
                                to="/login"
                                class="px-8 py-4 bg-white text-primary-600 font-semibold rounded-xl border-2 border-primary-600 hover:bg-primary-50 transition-all shadow-md hover:shadow-lg text-center"
                            >
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Masuk ke Sistem
                            </router-link>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-6 pt-8 border-t border-gray-200">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-primary-600">100+</div>
                                <div class="text-sm text-gray-600 mt-1">Ruas Jalan</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-emerald-600">50+</div>
                                <div class="text-sm text-gray-600 mt-1">Lokasi Wisata</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-purple-600">9</div>
                                <div class="text-sm text-gray-600 mt-1">Kabupaten</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content - Map Illustration -->
                    <div class="relative animate-fade-in-right">
                        <div class="relative bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
                            <!-- Map Preview -->
                            <div class="relative bg-gradient-to-br from-blue-100 to-emerald-100 rounded-xl p-6 h-96 flex items-center justify-center overflow-hidden">
                                <div class="absolute inset-0 opacity-20">
                                    <div class="absolute top-10 left-10 w-20 h-20 bg-primary-400 rounded-full"></div>
                                    <div class="absolute top-32 right-20 w-16 h-16 bg-emerald-400 rounded-full"></div>
                                    <div class="absolute bottom-20 left-32 w-24 h-24 bg-purple-400 rounded-full"></div>
                                    <div class="absolute bottom-32 right-10 w-12 h-12 bg-yellow-400 rounded-full"></div>
                                </div>
                                <div class="relative z-10 text-center">
                                    <i class="fas fa-map text-6xl text-primary-600 mb-4"></i>
                                    <p class="text-gray-700 font-semibold">Peta Interaktif Bali</p>
                                </div>
                            </div>
                            
                            <!-- Feature Cards -->
                            <div class="grid grid-cols-2 gap-4 mt-6">
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                                    <i class="fas fa-road text-blue-600 text-2xl mb-2"></i>
                                    <p class="text-sm font-semibold text-gray-800">Ruas Jalan</p>
                                    <p class="text-xs text-gray-600">Kelola data jalan</p>
                                </div>
                                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg p-4 border border-emerald-200">
                                    <i class="fas fa-map-pin text-emerald-600 text-2xl mb-2"></i>
                                    <p class="text-sm font-semibold text-gray-800">Lokasi Wisata</p>
                                    <p class="text-xs text-gray-600">Data destinasi</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Decorative Elements -->
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-gradient-to-br from-primary-400 to-emerald-400 rounded-full opacity-20 blur-2xl"></div>
                        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full opacity-20 blur-2xl"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Fitur Utama</h2>
                    <p class="text-xl text-gray-600">Semua yang Anda butuhkan untuk mengelola data geografis</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl p-8 border border-gray-100 shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 w-16 h-16 flex items-center justify-center mb-6">
                            <i class="fas fa-map-marked-alt text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Peta Interaktif</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Visualisasi data geografis dengan peta interaktif menggunakan Leaflet. 
                            Tambah, edit, dan hapus marker, polyline, dan polygon dengan mudah.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-gradient-to-br from-white to-emerald-50 rounded-2xl p-8 border border-gray-100 shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-4 w-16 h-16 flex items-center justify-center mb-6">
                            <i class="fas fa-road text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Manajemen Ruas Jalan</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Kelola data ruas jalan secara lengkap. Tambah informasi panjang, lebar, 
                            kondisi, dan jenis jalan dengan interface yang user-friendly.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-gradient-to-br from-white to-purple-50 rounded-2xl p-8 border border-gray-100 shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 w-16 h-16 flex items-center justify-center mb-6">
                            <i class="fas fa-database text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">API Terintegrasi</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Terhubung langsung dengan API GIS untuk sinkronisasi data real-time. 
                            Semua perubahan tersimpan dan tersinkronisasi secara otomatis.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-primary-600 via-emerald-600 to-primary-600 py-20">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl font-bold text-white mb-4">
                    Siap Memulai?
                </h2>
                <p class="text-xl text-white/90 mb-8">
                    Bergabunglah dengan kami dan mulai mengelola data geografis Bali dengan lebih efisien
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <router-link
                        to="/register"
                        class="px-8 py-4 bg-white text-primary-600 font-semibold rounded-xl hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:scale-105"
                    >
                        <i class="fas fa-user-plus mr-2"></i>
                        Daftar Gratis
                    </router-link>
                    <router-link
                        to="/login"
                        class="px-8 py-4 bg-white/10 backdrop-blur-sm text-white font-semibold rounded-xl border-2 border-white hover:bg-white/20 transition-all"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Masuk
                    </router-link>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="bg-gradient-to-br from-primary-600 to-emerald-600 rounded-xl p-2">
                                <i class="fas fa-map-marked-alt text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold">GIS Bali</h3>
                        </div>
                        <p class="text-gray-400 text-sm">
                            Sistem Informasi Geografis untuk Provinsi Bali. 
                            Platform terintegrasi untuk mengelola data geografis.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Tautan Cepat</h4>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li>
                                <router-link to="/dashboard" class="hover:text-white transition-colors">
                                    <i class="fas fa-gauge-high mr-2"></i>
                                    Dashboard
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/ruasjalan" class="hover:text-white transition-colors">
                                    <i class="fas fa-road mr-2"></i>
                                    Ruas Jalan
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/map" class="hover:text-white transition-colors">
                                    <i class="fas fa-map mr-2"></i>
                                    Peta Interaktif
                                </router-link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Kontak</h4>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li>
                                <i class="fas fa-envelope mr-2"></i>
                                info@gisbali.com
                            </li>
                            <li>
                                <i class="fas fa-phone mr-2"></i>
                                +62 XXX XXX XXXX
                            </li>
                            <li>
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                Denpasar, Bali
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                    <p>&copy; 2024 GIS Bali. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
import AuthService from '../services/AuthService.js';

export default {
    name: 'Welcome',
    computed: {
        isAuthenticated() {
            return AuthService.isAuthenticated();
        },
    },
};
</script>

<style scoped>
@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInRight {
    0% {
        opacity: 0;
        transform: translateX(30px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out;
}

.animate-fade-in-right {
    animation: fadeInRight 0.8s ease-out;
}
</style>
