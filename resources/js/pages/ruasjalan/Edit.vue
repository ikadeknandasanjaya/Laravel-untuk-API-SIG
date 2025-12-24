<template>
    <AppLayout>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Ruas Jalan</h1>
                    <p class="mt-1 text-sm text-gray-500">Perbarui data ruas jalan dan simpan kembali ke API dosenmu.</p>
                </div>
                <router-link
                    to="/ruasjalan"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                >
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </router-link>
            </div>

            <div v-if="errors.length > 0" class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-red-400 mt-0.5"></i>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc pl-5 space-y-1">
                            <li v-for="error in errors" :key="error">{{ error }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div v-if="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                <p class="mt-4 text-gray-600">Memuat data...</p>
            </div>

            <div v-else class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <form @submit.prevent="handleSubmit" class="px-6 py-6 space-y-6">
                    <!-- Lokasi Administratif -->
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-location-dot text-blue-600 mr-2"></i>
                            Lokasi Administratif
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Desa ID</label>
                                <input
                                    v-model.number="form.desa_id"
                                    type="number"
                                    class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Data Ruas Jalan -->
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-road text-green-600 mr-2"></i>
                            Data Ruas Jalan
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="kode_ruas" class="block text-sm font-medium text-gray-700 mb-1">Kode Ruas</label>
                                <input
                                    id="kode_ruas"
                                    v-model="form.kode_ruas"
                                    type="text"
                                    class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                />
                            </div>
                            <div>
                                <label for="nama_ruas" class="block text-sm font-medium text-gray-700 mb-1">Nama Ruas</label>
                                <input
                                    id="nama_ruas"
                                    v-model="form.nama_ruas"
                                    type="text"
                                    class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                />
                            </div>
                            <div>
                                <label for="panjang" class="block text-sm font-medium text-gray-700 mb-1">Panjang (meter)</label>
                                <input
                                    id="panjang"
                                    v-model.number="form.panjang"
                                    type="number"
                                    step="0.01"
                                    class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                />
                            </div>
                            <div>
                                <label for="lebar" class="block text-sm font-medium text-gray-700 mb-1">Lebar (meter)</label>
                                <input
                                    id="lebar"
                                    v-model.number="form.lebar"
                                    type="number"
                                    step="0.01"
                                    class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Klasifikasi -->
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-list-check text-purple-600 mr-2"></i>
                            Klasifikasi Jalan
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="eksisting_id" class="block text-sm font-medium text-gray-700 mb-1">Kondisi Eksisting</label>
                                <select
                                    id="eksisting_id"
                                    v-model.number="form.eksisting_id"
                                    class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                >
                                    <option value="">Pilih Kondisi Eksisting</option>
                                    <option
                                        v-for="item in eksistingList"
                                        :key="item.id"
                                        :value="item.id"
                                    >
                                        {{ item.eksisting }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label for="jenisjalan_id" class="block text-sm font-medium text-gray-700 mb-1">Jenis Jalan</label>
                                <select
                                    id="jenisjalan_id"
                                    v-model.number="form.jenisjalan_id"
                                    class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                >
                                    <option value="">Pilih Jenis Jalan</option>
                                    <option
                                        v-for="item in jenisJalanList"
                                        :key="item.id"
                                        :value="item.id"
                                    >
                                        {{ item.jenisjalan }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label for="kondisi_id" class="block text-sm font-medium text-gray-700 mb-1">Kondisi Jalan</label>
                                <select
                                    id="kondisi_id"
                                    v-model.number="form.kondisi_id"
                                    class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required
                                >
                                    <option value="">Pilih Kondisi Jalan</option>
                                    <option
                                        v-for="item in kondisiList"
                                        :key="item.id"
                                        :value="item.id"
                                    >
                                        {{ item.kondisi }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Geometry & Map -->
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-map text-green-600 mr-2"></i>
                            Edit Lokasi di Peta
                        </h2>
                        <div class="border border-gray-200 rounded-lg overflow-hidden mb-4">
                            <div id="editMap" class="w-full h-96"></div>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-700 mb-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Klik ikon <strong>Edit layers</strong> di pojok kiri atas peta untuk mengedit polyline. Setelah selesai, encoded polyline akan otomatis diperbarui di bawah.</span>
                        </div>
                    </div>

                    <!-- Geometry -->
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 mb-2 flex items-center">
                            <i class="fas fa-code text-indigo-600 mr-2"></i>
                            Geometry (Encoded Polyline)
                        </h2>
                        <p class="text-xs text-gray-500 mb-2">Format: Encoded polyline string (e.g. l`is@asr~T@UHiAZyCD_D)</p>
                        <textarea
                            id="paths"
                            v-model="form.paths"
                            rows="3"
                            class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-sm font-mono focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        ></textarea>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan (opsional)</label>
                        <textarea
                            id="keterangan"
                            v-model="form.keterangan"
                            rows="2"
                            class="block w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        ></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 flex justify-end space-x-3">
                        <router-link
                            :to="`/ruasjalan/${$route.params.id}`"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                        >
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </router-link>
                        <button
                            type="submit"
                            :disabled="submitting"
                            class="inline-flex items-center px-6 py-2 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <i class="fas fa-save mr-2"></i>
                            {{ submitting ? 'Menyimpan...' : 'Simpan Perubahan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../../components/AppLayout.vue';
import RuasJalanService from '../../services/RuasJalanService.js';
import toast from '../../utils/toast.js';
import L from 'leaflet';
import 'leaflet-draw';
import 'leaflet-draw/dist/leaflet.draw.css';
import polyline from '@mapbox/polyline';

export default {
    name: 'RuasJalanEdit',
    components: {
        AppLayout,
    },
    data() {
        return {
            form: {
                desa_id: '',
                kode_ruas: '',
                nama_ruas: '',
                panjang: '',
                lebar: '',
                eksisting_id: '',
                jenisjalan_id: '',
                kondisi_id: '',
                paths: '',
                keterangan: '-',
            },
            loading: true,
            submitting: false,
            errors: [],
            eksistingList: [],
            kondisiList: [],
            jenisJalanList: [],
            map: null,
            drawnItems: null,
            drawControl: null,
        };
    },
    async mounted() {
        await Promise.all([this.loadData(), this.loadMasterData()]);
        this.initializeMap();
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
                toast.error('Gagal memuat data klasifikasi.', 'Error');
            }
        },
        
        async loadData() {
            try {
                const id = this.$route.params.id;
                console.log('📍 Loading ruas jalan for edit, ID:', id);
                
                const remoteToken = localStorage.getItem('remote_auth_token');
                console.log('🔑 Remote token:', remoteToken ? 'Present' : 'MISSING');
                
                const result = await RuasJalanService.getRuasJalanById(id);
                
                console.log('📦 API Response:', result);
                
                if (result.success && result.data.ruasjalan) {
                    const ruas = result.data.ruasjalan;
                    this.form = {
                        desa_id: ruas.desa_id || '',
                        kode_ruas: ruas.kode_ruas || '',
                        nama_ruas: ruas.nama_ruas || '',
                        panjang: ruas.panjang || '',
                        lebar: ruas.lebar || '',
                        eksisting_id: ruas.eksisting_id || '',
                        jenisjalan_id: ruas.jenisjalan_id || '',
                        kondisi_id: ruas.kondisi_id || '',
                        paths: ruas.paths || '',
                        keterangan: ruas.keterangan || '-',
                    };
                    console.log('✅ Data loaded:', this.form);
                } else {
                    this.errors = [result.message || 'Gagal memuat data ruas jalan'];
                    console.error('❌ Error:', result.message);
                }
            } catch (error) {
                console.error('Error loading data:', error);
                this.errors = [error.message || 'Terjadi kesalahan saat memuat data'];
            } finally {
                this.loading = false;
            }
        },
        
        initializeMap() {
            try {
                console.log('🗺️ Initializing edit map...');
                
                // Check if map DOM exists
                const mapDom = document.getElementById('editMap');
                if (!mapDom) {
                    console.error('❌ Map DOM not found');
                    return;
                }
                
                // Create map centered on Indonesia
                this.map = L.map('editMap', {
                    center: [-2.548926, 118.0148634],
                    zoom: 10,
                    preferCanvas: true
                });
                
                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(this.map);
                
                // Create feature group for drawing
                this.drawnItems = new L.FeatureGroup();
                this.map.addLayer(this.drawnItems);
                
                // Add draw control
                this.drawControl = new L.Control.Draw({
                    position: 'topleft',
                    draw: {
                        polygon: false,
                        polyline: true,
                        rectangle: false,
                        circle: false,
                        marker: false,
                        circlemarker: false
                    },
                    edit: {
                        featureGroup: this.drawnItems,
                        poly: {
                            allowIntersection: true
                        }
                    }
                });
                
                this.map.addControl(this.drawControl);
                
                // Handle draw events
                this.map.on('draw:created', (e) => this.onDrawCreated(e));
                this.map.on('draw:edited', (e) => this.onDrawEdited(e));
                
                // Load existing polyline if present
                if (this.form.paths) {
                    this.loadPolylineToMap();
                }
                
                // Fix map sizing
                setTimeout(() => {
                    this.map.invalidateSize();
                }, 200);
                
                console.log('✅ Edit map initialized');
            } catch (error) {
                console.error('Error initializing map:', error);
                toast.error('Gagal menginisialisasi peta.', 'Error');
            }
        },
        
        loadPolylineToMap() {
            try {
                if (!this.form.paths) return;
                
                console.log('📍 Decoding polyline:', this.form.paths);
                const coords = polyline.decode(this.form.paths);
                console.log('✅ Decoded coords:', coords);
                
                if (coords.length > 0) {
                    // Convert [lat, lng] to [lng, lat] for Leaflet
                    const latLngs = coords.map(c => [c[0], c[1]]);
                    
                    // Clear existing layers
                    this.drawnItems.clearLayers();
                    
                    // Create and add polyline
                    const polylineLayer = L.polyline(latLngs, {
                        color: '#3B82F6',
                        weight: 3,
                        opacity: 0.8
                    });
                    
                    this.drawnItems.addLayer(polylineLayer);
                    
                    // Fit bounds
                    this.map.fitBounds(polylineLayer.getBounds());
                    
                    console.log('✅ Polyline loaded to map');
                }
            } catch (error) {
                console.error('Error loading polyline to map:', error);
            }
        },
        
        onDrawCreated(e) {
            console.log('🎨 Draw created event');
            const layer = e.layer;
            this.drawnItems.addLayer(layer);
            this.encodePolyline();
        },
        
        onDrawEdited(e) {
            console.log('✏️ Draw edited event');
            this.encodePolyline();
        },
        
        encodePolyline() {
            try {
                const layers = this.drawnItems.getLayers();
                
                if (layers.length === 0) {
                    this.form.paths = '';
                    return;
                }
                
                // Get coordinates from polyline
                const polylineLayer = layers[0];
                if (!polylineLayer.getLatLngs) {
                    console.warn('No polyline found');
                    return;
                }
                
                const latLngs = polylineLayer.getLatLngs();
                const coords = latLngs.map(latlng => [latlng.lat, latlng.lng]);
                
                console.log('📍 Coords to encode:', coords);
                
                // Encode to Google polyline format
                const encoded = polyline.encode(coords);
                console.log('📦 Encoded polyline:', encoded);
                
                this.form.paths = encoded;
                toast.success('Polyline diperbarui!', 'Sukses');
                
            } catch (error) {
                console.error('Error encoding polyline:', error);
                toast.error('Gagal mengkode polyline.', 'Error');
            }
        },
        
        async handleSubmit() {
            this.submitting = true;
            this.errors = [];
            
            try {
                console.log('💾 Submitting form data:', this.form);
                
                const result = await RuasJalanService.updateRuasJalan(this.$route.params.id, this.form);
                
                if (result.success) {
                    toast.success('Ruas jalan berhasil diperbarui!', 'Sukses');
                    setTimeout(() => {
                        this.$router.push({
                            path: `/ruasjalan/${this.$route.params.id}`,
                            query: { updated: 'true' }
                        });
                    }, 1000);
                } else {
                    this.errors = [result.message || 'Gagal mengupdate ruas jalan'];
                    toast.error(result.message, 'Error');
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                this.errors = [error.message || 'Terjadi kesalahan saat menyimpan'];
                toast.error('Gagal menyimpan data', 'Error');
            } finally {
                this.submitting = false;
            }
        },
    },
};
</script>

<style scoped>
input[type="text"],
input[type="number"],
textarea {
    border-color: #d1d5db;
}

input[type="text"]:focus,
input[type="number"]:focus,
textarea:focus {
    border-color: #3b82f6;
    outline: none;
}
</style>
