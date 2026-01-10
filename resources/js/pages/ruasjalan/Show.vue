<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Ruas Jalan</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Data berikut berasal dari endpoint <code class="bg-gray-100 px-2 py-1 rounded text-xs">/ruasjalan/{id}</code>
                    </p>
                </div>
                <div class="flex gap-3">
                    <router-link to="/ruasjalan" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </router-link>
                    <router-link :to="`/ruasjalan/${$route.params.id}/edit`" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-pen mr-2"></i>
                        Edit
                    </router-link>
                </div>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                <p class="mt-4 text-gray-600">Memuat data...</p>
            </div>

            <template v-else>
                <!-- Map - Always show even if data fails -->
                <div class="mb-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-map text-blue-600 mr-2"></i>
                                Peta Lokasi Ruas Jalan
                            </h2>
                        </div>
                        <div class="p-4">
                            <div id="map" class="w-full h-96 rounded-lg border border-gray-200"></div>
                        </div>
                    </div>
                </div>

                <!-- Content Grid -->
                <div v-if="ruasJalan" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informasi Utama -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-road text-blue-600 mr-2"></i>
                                Informasi Utama
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <dt class="text-sm text-gray-600">Kode Ruas</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ ruasJalan.kode_ruas || '-' }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <dt class="text-sm text-gray-600">Nama Ruas</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ ruasJalan.nama_ruas || '-' }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <dt class="text-sm text-gray-600">Desa ID</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ ruasJalan.desa_id || '-' }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <dt class="text-sm text-gray-600">Panjang</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ ruasJalan.panjang ? ruasJalan.panjang + ' m' : '-' }}</dd>
                                </div>
                                <div class="flex justify-between py-2">
                                    <dt class="text-sm text-gray-600">Lebar</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ ruasJalan.lebar ? ruasJalan.lebar + ' m' : '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Klasifikasi -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-list-check text-green-600 mr-2"></i>
                                Klasifikasi & Keterangan
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <dt class="text-sm text-gray-600">Kondisi Eksisting</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ getEksistingName(ruasJalan.eksisting_id) }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <dt class="text-sm text-gray-600">Kondisi Jalan</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ getKondisiName(ruasJalan.kondisi_id) }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <dt class="text-sm text-gray-600">Jenis Jalan</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ getJenisJalanName(ruasJalan.jenisjalan_id) }}</dd>
                                </div>
                                <div class="flex justify-between py-2">
                                    <dt class="text-sm text-gray-600">Keterangan</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ ruasJalan.keterangan || '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Geometry -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-code text-purple-600 mr-2"></i>
                                Geometry (Paths)
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <p class="text-xs text-gray-500 mb-3">
                                Nilai mentah dari field <code class="bg-gray-100 px-1 rounded">paths</code> yang dikirim/diterima dari API.
                            </p>
                            <pre class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-xs text-gray-700 overflow-x-auto max-h-40">{{ ruasJalan.paths || '-' }}</pre>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-tools text-orange-600 mr-2"></i>
                                Aksi Cepat
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <p class="text-xs text-gray-500 mb-3">- Klik <strong>Edit</strong> untuk memperbarui data ini.</p>
                            <button @click="handleDelete" class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100">
                                <i class="fas fa-trash mr-2"></i>
                                Hapus Ruas Jalan
                            </button>
                        </div>
                    </div>
                </div>
                </div>

                <!-- Error/Empty State -->
                <div v-else class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                    <i class="fas fa-info-circle text-4xl text-yellow-500 mb-4"></i>
                    <p class="text-gray-600 mb-2">Data ruas jalan tidak ditemukan atau gagal dimuat</p>
                    <p class="text-xs text-gray-500 mb-4">Periksa console untuk detail error atau coba refresh page</p>
                    <router-link to="/ruasjalan" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                        Kembali ke Daftar
                    </router-link>
                </div>
            </template>        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../../components/AppLayout.vue';
import RuasJalanService from '../../services/RuasJalanService.js';
import toast from '../../utils/toast.js';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import polyline from '@mapbox/polyline';

export default {
    name: 'RuasJalanShow',
    components: {
        AppLayout,
    },
    data() {
        return {
            ruasJalan: null,
            loading: true,
            map: null,
            polylineLayer: null,
            eksistingList: [],
            kondisiList: [],
            jenisJalanList: []
        };
    },
    async mounted() {
        console.log('Show.vue mounted');
        await Promise.all([this.loadMasterData(), this.loadData()]);
    },
    beforeUnmount() {
        if (this.map) {
            this.map.remove();
        }
    },
    methods: {
        async loadMasterData() {
            try {
                const results = await Promise.allSettled([
                    RuasJalanService.getMasterEksisting(),
                    RuasJalanService.getMasterKondisi(),
                    RuasJalanService.getMasterJenisJalan()
                ]);
                
                const [eksistingResult, kondisiResult, jenisResult] = results.map(r => 
                    r.status === 'fulfilled' ? r.value : { success: false }
                );
                
                if (eksistingResult.success) {
                    this.eksistingList = eksistingResult.data;
                }
                
                if (kondisiResult.success) {
                    this.kondisiList = kondisiResult.data;
                }
                
                if (jenisResult.success) {
                    this.jenisJalanList = jenisResult.data;
                }
                
            } catch (error) {
                console.error('Error loading master data:', error);
            }
        },
        
        getEksistingName(id) {
            if (!id) return null;
            const item = this.eksistingList.find(e => e.id == id);
            return item ? item.eksisting : null;
        },
        
        getKondisiName(id) {
            if (!id) return null;
            const item = this.kondisiList.find(k => k.id == id);
            return item ? item.kondisi : null;
        },
        
        getJenisJalanName(id) {
            if (!id) return null;
            const item = this.jenisJalanList.find(j => j.id == id);
            return item ? item.jenisjalan : null;
        },
        
        async loadData() {
            try {
                const id = this.$route.params.id;
                console.log('Loading ruas jalan detail for ID:', id);
                
                const remoteToken = localStorage.getItem('remote_auth_token');
                console.log('Remote token from localStorage:', remoteToken ? 'Present (' + remoteToken.substring(0, 20) + '...)' : 'MISSING!');
                
                const result = await RuasJalanService.getRuasJalanById(id);
                
                console.log('API Response:', result);
                
                if (result.success) {
                    this.ruasJalan = result.data.ruasjalan;
                    console.log('Data loaded successfully:', this.ruasJalan);
                } else {
                    console.error('API Error:', result.message);
                    toast.error(result.message || 'Gagal memuat data', 'Error');
                }
            } catch (error) {
                console.error('Error loading ruas jalan:', error);
                toast.error('Terjadi kesalahan saat memuat data', 'Error');
            } finally {
                this.loading = false;
                
                // Initialize map after loading finishes (with or without data)
                console.log('⏳ Waiting for DOM to be ready...');
                await this.$nextTick();
                await new Promise(resolve => setTimeout(resolve, 500)); // Wait 500ms for DOM rendering
                console.log('Calling initializeMap...');
                this.initializeMap();
            }
        },

        initializeMap() {
            // Check if map element exists
            const mapElement = document.getElementById('map');
            if (!mapElement) {
                console.warn('Map element not found in DOM');
                return;
            }

            console.log('🗺️ Initializing map...');
            console.log('Leaflet L object:', typeof L);

            try {
                // Clear any existing map instance
                if (this.map) {
                    this.map.remove();
                }

                // Initialize map centered on Bali
                this.map = L.map('map', {
                    center: [-8.4095, 115.1889],
                    zoom: 10
                });

                console.log('Map instance created:', this.map);

                // Add tile layer
                const tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    maxZoom: 19
                });
                
                tileLayer.addTo(this.map);
                console.log('Tile layer added');

                // Invalidate map size
                this.map.invalidateSize();
                console.log('Map size invalidated');

                // If no data, just show empty map
                if (!this.ruasJalan || !this.ruasJalan.paths) {
                    console.log('No paths data to display');
                    return;
                }

                // Decode the polyline
                const decoded = polyline.decode(this.ruasJalan.paths);
                console.log('Decoded polyline points:', decoded.length, decoded);
                
                // Convert to Leaflet LatLng format
                const latlngs = decoded.map(point => [point[0], point[1]]);
                console.log('LatLngs:', latlngs);
                
         
                
                const firstPoint = latlngs[0];
                const lastPoint = latlngs[latlngs.length - 1];
                
           
                
                // Create polyline
                this.polylineLayer = L.polyline(latlngs, {
                    color: '#3b82f6',
                    weight: 4,
                    opacity: 0.8,
                }).addTo(this.map);

                console.log('Polyline rendered');

                // Fit map bounds to polyline
                const bounds = this.polylineLayer.getBounds();
                console.log('Polyline bounds:', bounds);
                
                this.map.fitBounds(bounds, {
                    padding: [50, 50]
                });

                // Add popup
                const popupContent = `
                    <div class="text-sm">
                        <strong class="text-base">${this.ruasJalan.nama_ruas || 'Ruas Jalan'}</strong><br>
                        <span class="text-gray-600">Kode: ${this.ruasJalan.kode_ruas || '-'}</span><br>
                        <span class="text-gray-600">Panjang: ${this.ruasJalan.panjang || '-'} m</span>
                    </div>
                `;
                this.polylineLayer.bindPopup(popupContent).openPopup();
                console.log('Map initialized successfully');

            } catch (error) {
                console.error('Error initializing map:', error);
                console.error('Stack:', error.stack);
                toast.warning('Gagal menampilkan peta', 'Peringatan');
            }
        },

        async handleDelete() {
            if (!confirm('Yakin ingin menghapus ruas jalan ini?')) return;
            
            try {
                const result = await RuasJalanService.deleteRuasJalan(this.$route.params.id);
                
                if (result.success) {
                    toast.success('Ruas jalan berhasil dihapus!', 'Sukses');
                    setTimeout(() => {
                        this.$router.push('/ruasjalan');
                    }, 1000);
                } else {
                    toast.error(result.message || 'Gagal menghapus ruas jalan', 'Error');
                }
            } catch (error) {
                console.error('Error deleting ruas jalan:', error);
                toast.error('Terjadi kesalahan saat menghapus data', 'Error');
            }
        },
    },
};
</script>

<style scoped>
/* Map container styling */
#map {
    z-index: 0;
    background-color: #e5e5e5;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    min-height: 300px;
}

/* Ensure map is visible */
:deep(.leaflet-container) {
    font-family: inherit;
    background-color: #e5e5e5;
}

:deep(.leaflet-pane),
:deep(.leaflet-tile-pane),
:deep(.leaflet-object-pane) {
    z-index: 1;
}

:deep(.leaflet-popup-pane) {
    z-index: 700;
}

:deep(.leaflet-top),
:deep(.leaflet-bottom) {
    position: absolute;
    z-index: 1000;
    pointer-events: none;
}
</style>


