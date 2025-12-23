<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-emerald-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-3">
                        <div class="bg-gradient-to-br from-blue-600 to-emerald-600 rounded-xl p-2.5">
                            <i class="fas fa-map-marked-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">GIS Bali</h1>
                            <p class="text-xs text-gray-500">Sistem Informasi Geografis</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <router-link
                            to="/login"
                            class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors"
                        >
                            Masuk
                        </router-link>
                        <router-link
                            to="/register"
                            class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-emerald-600 rounded-lg hover:from-blue-700 hover:to-emerald-700 transition-all"
                        >
                            Daftar
                        </router-link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                    Sistem Informasi
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-emerald-600">
                        Geografis Bali
                    </span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Platform digital untuk mengelola dan memvisualisasikan data geografis Pulau Bali dengan teknologi modern dan interface yang user-friendly.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <router-link
                        to="/register"
                        class="px-8 py-4 bg-gradient-to-r from-blue-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl"
                    >
                        Mulai Sekarang
                    </router-link>
                    <router-link
                        to="/login"
                        class="px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:border-blue-600 hover:text-blue-600 transition-all"
                    >
                        Sudah Punya Akun?
                    </router-link>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Fitur Unggulan</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Kelola data geografis dengan mudah menggunakan fitur-fitur canggih yang kami sediakan
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-map text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Peta Interaktif</h3>
                        <p class="text-gray-600">
                            Visualisasi data geografis dengan peta interaktif yang mudah digunakan dan responsif
                        </p>
                    </div>
                    
                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-road text-emerald-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Manajemen Ruas Jalan</h3>
                        <p class="text-gray-600">
                            Kelola data ruas jalan dengan informasi lengkap kondisi dan spesifikasi teknis
                        </p>
                    </div>
                    
                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-bar text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Analisis Data</h3>
                        <p class="text-gray-600">
                            Analisis mendalam dengan dashboard yang informatif dan laporan yang komprehensif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <div class="flex items-center justify-center space-x-3 mb-4">
                        <div class="bg-gradient-to-br from-blue-600 to-emerald-600 rounded-xl p-2">
                            <i class="fas fa-map-marked-alt text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold">GIS Bali</h3>
                    </div>
                    <p class="text-gray-400 mb-8">
                        Sistem Informasi Geografis untuk Pulau Bali
                    </p>
                    <div class="border-t border-gray-800 pt-8">
                        <p class="text-sm text-gray-400">
                            &copy; 2024 GIS Bali. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
export default {
    name: 'WelcomeSimple'
};
</script>

<style scoped>
/* Simple styles without complex animations */
.transition-all {
    transition: all 0.3s ease;
}

.transition-colors {
    transition: color 0.3s ease;
}
</style>
