<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Lokasi Administrasi</h1>
                <p class="mt-1 text-sm text-gray-500">Statistik dan manajemen data provinsi, kabupaten, kecamatan, dan desa</p>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                <p class="mt-4 text-gray-600">Memuat data statistik...</p>
            </div>

            <!-- Statistics Cards -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Total Provinsi -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Provinsi</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.totalProvinsi }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-map text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Kabupaten -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Kabupaten</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.totalKabupaten }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-building text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Kecamatan -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Kecamatan</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.totalKecamatan }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <i class="fas fa-location-dot text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Desa -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total Desa</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.totalDesa }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="fas fa-home text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Tables Grid -->
            <div v-if="!loading" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Provinsi Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-map text-blue-600 mr-2"></i>
                            Daftar Provinsi
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">No</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Provinsi</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="provinsiList.length === 0" class="border-b border-gray-200">
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-3xl mb-2"></i>
                                        <p>Provinsi tidak ditemukan</p>
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in provinsiList" :key="item.id" class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-3 text-gray-700">{{ index + 1 }}</td>
                                    <td class="px-6 py-3 text-gray-900 font-medium">{{ item.provinsi }}</td>
                                    <td class="px-6 py-3">
                                        <button @click="viewDetail('provinsi', item.id)" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                            Lihat
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Kabupaten Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-building text-green-600 mr-2"></i>
                            Daftar Kabupaten
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">No</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Kabupaten</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="kabupatenList.length === 0" class="border-b border-gray-200">
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-3xl mb-2"></i>
                                        <p>Kabupaten tidak ditemukan</p>
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in kabupatenList" :key="item.id" class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-3 text-gray-700">{{ index + 1 }}</td>
                                    <td class="px-6 py-3 text-gray-900 font-medium">{{ item.kabupaten }}</td>
                                    <td class="px-6 py-3">
                                        <button @click="viewDetail('kabupaten', item.id)" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                            Lihat
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Kecamatan Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-location-dot text-yellow-600 mr-2"></i>
                            Daftar Kecamatan
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">No</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Kecamatan</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="kecamatanList.length === 0" class="border-b border-gray-200">
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-3xl mb-2"></i>
                                        <p>Pilih Kabupaten terlebih dahulu</p>
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in kecamatanList" :key="item.id" class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-3 text-gray-700">{{ index + 1 }}</td>
                                    <td class="px-6 py-3 text-gray-900 font-medium">{{ item.kecamatan }}</td>
                                    <td class="px-6 py-3">
                                        <button @click="viewDetail('kecamatan', item.id)" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                            Lihat
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Desa Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-home text-purple-600 mr-2"></i>
                            Daftar Desa
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">No</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Desa</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="desaList.length === 0" class="border-b border-gray-200">
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-inbox text-3xl mb-2"></i>
                                        <p>Pilih Kecamatan terlebih dahulu</p>
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in desaList" :key="item.id" class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-3 text-gray-700">{{ index + 1 }}</td>
                                    <td class="px-6 py-3 text-gray-900 font-medium">{{ item.desa }}</td>
                                    <td class="px-6 py-3">
                                        <button @click="viewDetail('desa', item.id)" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                            Lihat
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../components/AppLayout.vue';
import RegionService from '../services/RegionService.js';
import toast from '../utils/toast.js';

export default {
    name: 'Location',
    components: {
        AppLayout,
    },
    data() {
        return {
            loading: true,
            stats: {
                totalProvinsi: 0,
                totalKabupaten: 0,
                totalKecamatan: 0,
                totalDesa: 0,
            },
            provinsiList: [],
            kabupatenList: [],
            kecamatanList: [],
            desaList: [],
            selectedProvinsi: null,
            selectedKabupaten: null,
            selectedKecamatan: null,
        };
    },
    async mounted() {
        await this.loadData();
    },
    methods: {
        async loadData() {
            try {
                console.log('Loading location data...');
                
                // Load all regions from /mregion endpoint
                const result = await RegionService.getAllRegions();
                
                if (result.success && result.data) {
                    // Get the data arrays
                    const data = result.data;
                    
                    // Provinsi
                    if (Array.isArray(data.provinsi)) {
                        this.provinsiList = data.provinsi;
                        this.stats.totalProvinsi = this.provinsiList.length;
                        console.log('Provinsi:', this.provinsiList.length);
                    }
                    
                    // Kabupaten
                    if (Array.isArray(data.kabupaten)) {
                        this.kabupatenList = data.kabupaten;
                        this.stats.totalKabupaten = this.kabupatenList.length;
                        console.log('Kabupaten:', this.kabupatenList.length);
                    }
                    
                    // Kecamatan
                    if (Array.isArray(data.kecamatan)) {
                        this.kecamatanList = data.kecamatan;
                        this.stats.totalKecamatan = this.kecamatanList.length;
                        console.log('Kecamatan:', this.kecamatanList.length);
                    }
                    
                    // Desa
                    if (Array.isArray(data.desa)) {
                        this.desaList = data.desa;
                        this.stats.totalDesa = this.desaList.length;
                        console.log('Desa:', this.desaList.length);
                    }
                    
                    console.log('Location data loaded:', this.stats);
                } else {
                    console.error('Error loading data:', result.message);
                    toast.error(result.message || 'Gagal memuat data lokasi', 'Error');
                }
            } catch (error) {
                console.error('Error loading location data:', error);
                toast.error('Terjadi kesalahan saat memuat data', 'Error');
            } finally {
                this.loading = false;
            }
        },
        
        viewDetail(type, id) {
            toast.info(`Detail ${type} dengan ID ${id}`, 'Info');
            // Bisa diexpand dengan modal atau halaman detail
        },
    },
};
</script>

<style scoped>
/* Add any additional styling if needed */
</style>
