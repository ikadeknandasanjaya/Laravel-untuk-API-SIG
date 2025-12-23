<template>
    <Layout>
        <div class="bg-gray-50 min-h-screen">
            <!-- Content similar to map/index.blade.php -->
            <div class="bg-gradient-to-br from-gray-50 to-white">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="py-8">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            <!-- Stats cards - similar to original -->
                            <div class="group relative overflow-hidden bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                                <div class="relative p-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-blue-100 mb-1">Total Lokasi</p>
                                            <p class="text-3xl font-bold text-white">{{ totalLokasi }}</p>
                                        </div>
                                        <div class="bg-white/20 rounded-xl p-3">
                                            <i class="fas fa-map-pin text-2xl text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add other stat cards similarly -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main map content -->
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Sidebar with tools and form -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Tools, Form, Legend sections - similar to original -->
                    </div>

                    <!-- Map -->
                    <div class="lg:col-span-3">
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-xl border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 px-6 py-5">
                                <h3 class="text-xl font-bold text-white flex items-center">
                                    <div class="bg-white/20 rounded-lg p-2 mr-3">
                                        <i class="fas fa-map text-white"></i>
                                    </div>
                                    Peta Interaktif Bali
                                </h3>
                            </div>
                            <div class="relative">
                                <div ref="mapContainer" class="h-[650px] w-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script>
import Layout from '../components/Layout.vue';
import L from 'leaflet';

export default {
    name: 'MapPage',
    components: {
        Layout,
    },
    data() {
        return {
            totalLokasi: 0,
            map: null,
        };
    },
    mounted() {
        this.initMap();
    },
    methods: {
        initMap() {
            this.map = L.map(this.$refs.mapContainer).setView([-8.4095, 115.1889], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
            }).addTo(this.map);
        },
    },
};
</script>

