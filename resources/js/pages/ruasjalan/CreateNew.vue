<template>
    <AppLayout>
        <div class="create-ruas-jalan">
            <div class="page-header">
                <div class="header-content">
                    <h1>Tambah Ruas Jalan</h1>
                    <p>Tambahkan data ruas jalan baru ke sistem GIS</p>
                </div>
                <router-link to="/ruasjalan" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </router-link>
            </div>

            <div class="form-container">
                <form @submit.prevent="handleSubmit" class="ruas-jalan-form">
                    <!-- Lokasi Administratif -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-location-dot"></i>
                            Lokasi Administratif
                        </h2>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="provinsi_id">Provinsi</label>
                                <select
                                    id="provinsi_id"
                                    v-model="form.provinsi_id"
                                    @change="onProvinsiChange"
                                    :disabled="loading || loadingProvinsi"
                                    required
                                >
                                    <option value="">Pilih Provinsi</option>
                                    <option
                                        v-for="provinsi in provinsiList"
                                        :key="provinsi.id"
                                        :value="provinsi.id"
                                    >
                                        {{ provinsi.provinsi }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="kabupaten_id">Kabupaten</label>
                                <select
                                    id="kabupaten_id"
                                    v-model="form.kabupaten_id"
                                    @change="onKabupatenChange"
                                    :disabled="loading || loadingKabupaten || !form.provinsi_id"
                                    required
                                >
                                    <option value="">Pilih Kabupaten</option>
                                    <option
                                        v-for="kabupaten in kabupatenList"
                                        :key="kabupaten.id"
                                        :value="kabupaten.id"
                                    >
                                        {{ kabupaten.value }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="kecamatan_id">Kecamatan</label>
                                <select
                                    id="kecamatan_id"
                                    v-model="form.kecamatan_id"
                                    @change="onKecamatanChange"
                                    :disabled="loading || loadingKecamatan || !form.kabupaten_id"
                                    required
                                >
                                    <option value="">Pilih Kecamatan</option>
                                    <option
                                        v-for="kecamatan in kecamatanList"
                                        :key="kecamatan.id"
                                        :value="kecamatan.id"
                                    >
                                        {{ kecamatan.value }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="desa_id">Desa</label>
                                <select
                                    id="desa_id"
                                    v-model="form.desa_id"
                                    :disabled="loading || loadingDesa || !form.kecamatan_id"
                                    required
                                >
                                    <option value="">Pilih Desa</option>
                                    <option
                                        v-for="desa in desaList"
                                        :key="desa.id"
                                        :value="desa.id"
                                    >
                                        {{ desa.value }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Data Ruas Jalan -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-road"></i>
                            Data Ruas Jalan
                        </h2>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="kode_ruas">Kode Ruas</label>
                                <input
                                    type="text"
                                    id="kode_ruas"
                                    v-model="form.kode_ruas"
                                    placeholder="Masukkan kode ruas"
                                    :disabled="loading"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label for="nama_ruas">Nama Ruas</label>
                                <input
                                    type="text"
                                    id="nama_ruas"
                                    v-model="form.nama_ruas"
                                    placeholder="Masukkan nama ruas"
                                    :disabled="loading"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label for="panjang">Panjang (meter)</label>
                                <input
                                    type="number"
                                    id="panjang"
                                    v-model="form.panjang"
                                    placeholder="Masukkan panjang"
                                    :disabled="loading"
                                    min="1"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label for="lebar">Lebar (meter)</label>
                                <input
                                    type="number"
                                    id="lebar"
                                    v-model="form.lebar"
                                    placeholder="Masukkan lebar"
                                    :disabled="loading"
                                    min="1"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label for="eksisting_id">Kondisi Eksisting</label>
                                <select
                                    id="eksisting_id"
                                    v-model="form.eksisting_id"
                                    :disabled="loading || loadingMaster"
                                    required
                                >
                                    <option value="">Pilih Kondisi Eksisting</option>
                                    <option
                                        v-for="eksisting in eksistingList"
                                        :key="eksisting.id"
                                        :value="eksisting.id"
                                    >
                                        {{ eksisting.eksisting }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="kondisi_id">Kondisi Jalan</label>
                                <select
                                    id="kondisi_id"
                                    v-model="form.kondisi_id"
                                    :disabled="loading || loadingMaster"
                                    required
                                >
                                    <option value="">Pilih Kondisi Jalan</option>
                                    <option
                                        v-for="kondisi in kondisiList"
                                        :key="kondisi.id"
                                        :value="kondisi.id"
                                    >
                                        {{ kondisi.kondisi }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="jenisjalan_id">Jenis Jalan</label>
                                <select
                                    id="jenisjalan_id"
                                    v-model="form.jenisjalan_id"
                                    :disabled="loading || loadingMaster"
                                    required
                                >
                                    <option value="">Pilih Jenis Jalan</option>
                                    <option
                                        v-for="jenis in jenisJalanList"
                                        :key="jenis.id"
                                        :value="jenis.id"
                                    >
                                        {{ jenis.jenisjalan }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group full-width">
                                <label for="keterangan">Keterangan</label>
                                <textarea
                                    id="keterangan"
                                    v-model="form.keterangan"
                                    placeholder="Masukkan keterangan tambahan"
                                    :disabled="loading"
                                    rows="3"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Peta -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-map"></i>
                            Lokasi di Peta
                        </h2>
                        
                        <div class="map-container">
                            <div id="map" class="map"></div>
                            <div class="map-instructions">
                                <p><i class="fas fa-info-circle"></i> Klik pada peta untuk menentukan lokasi ruas jalan</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-actions">
                        <button type="submit" class="submit-btn" :disabled="loading || !canSubmit">
                            <span v-if="loading" class="loading-spinner"></span>
                            <i v-else class="fas fa-save"></i>
                            {{ loading ? 'Menyimpan...' : 'Simpan Ruas Jalan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../../components/AppLayout.vue';
import RegionService from '../../services/RegionService.js';
import RuasJalanService from '../../services/RuasJalanService.js';
import toast from '../../utils/toast.js';
import L from 'leaflet';
import 'leaflet-draw';

export default {
    name: 'CreateRuasJalan',
    components: {
        AppLayout
    },
    data() {
        return {
            form: {
                provinsi_id: '',
                kabupaten_id: '',
                kecamatan_id: '',
                desa_id: '',
                kode_ruas: '',
                nama_ruas: '',
                panjang: '',
                lebar: '',
                eksisting_id: '',
                kondisi_id: '',
                jenisjalan_id: '',
                keterangan: '',
                paths: ''
            },
            
            // Data lists
            provinsiList: [],
            kabupatenList: [],
            kecamatanList: [],
            desaList: [],
            eksistingList: [],
            kondisiList: [],
            jenisJalanList: [],
            
            // Loading states
            loading: false,
            loadingProvinsi: false,
            loadingKabupaten: false,
            loadingKecamatan: false,
            loadingDesa: false,
            loadingMaster: false,
            
            // Map
            map: null,
            drawnItems: null,
            currentPath: null
        }
    },
    computed: {
        canSubmit() {
            return this.form.desa_id && 
                   this.form.kode_ruas && 
                   this.form.nama_ruas && 
                   this.form.panjang && 
                   this.form.lebar && 
                   this.form.eksisting_id && 
                   this.form.kondisi_id && 
                   this.form.jenisjalan_id &&
                   this.form.paths;
        }
    },
    async mounted() {
        await this.loadInitialData();
        this.initMap();
    },
    methods: {
        async loadInitialData() {
            this.loadingProvinsi = true;
            this.loadingMaster = true;
            
            try {
                // Load regions
                const regionsResult = await RegionService.getAllRegions();
                if (regionsResult.success) {
                    this.provinsiList = regionsResult.data.provinsi;
                }
                
                // Load master data (eksisting, kondisi, jenisjalan)
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
                console.error('Error loading initial data:', error);
                toast.error('Gagal memuat data awal.', 'Error');
            } finally {
                this.loadingProvinsi = false;
                this.loadingMaster = false;
            }
        },

        async onProvinsiChange() {
            if (!this.form.provinsi_id) {
                this.kabupatenList = [];
                this.kecamatanList = [];
                this.desaList = [];
                this.form.kabupaten_id = '';
                this.form.kecamatan_id = '';
                this.form.desa_id = '';
                return;
            }

            this.loadingKabupaten = true;
            try {
                const result = await RegionService.getKabupatenByProvinsiId(this.form.provinsi_id);
                if (result.success) {
                    this.kabupatenList = result.data;
                    this.kecamatanList = [];
                    this.desaList = [];
                    this.form.kabupaten_id = '';
                    this.form.kecamatan_id = '';
                    this.form.desa_id = '';
                }
            } catch (error) {
                console.error('Error loading kabupaten:', error);
                toast.error('Gagal memuat data kabupaten.', 'Error');
            } finally {
                this.loadingKabupaten = false;
            }
        },

        async onKabupatenChange() {
            if (!this.form.kabupaten_id) {
                this.kecamatanList = [];
                this.desaList = [];
                this.form.kecamatan_id = '';
                this.form.desa_id = '';
                return;
            }

            this.loadingKecamatan = true;
            try {
                const result = await RegionService.getKecamatanByKabupatenId(this.form.kabupaten_id);
                if (result.success) {
                    this.kecamatanList = result.data;
                    this.desaList = [];
                    this.form.kecamatan_id = '';
                    this.form.desa_id = '';
                }
            } catch (error) {
                console.error('Error loading kecamatan:', error);
                toast.error('Gagal memuat data kecamatan.', 'Error');
            } finally {
                this.loadingKecamatan = false;
            }
        },

        async onKecamatanChange() {
            if (!this.form.kecamatan_id) {
                this.desaList = [];
                this.form.desa_id = '';
                return;
            }

            this.loadingDesa = true;
            try {
                const result = await RegionService.getDesaByKecamatanId(this.form.kecamatan_id);
                if (result.success) {
                    this.desaList = result.data;
                    this.form.desa_id = '';
                }
            } catch (error) {
                console.error('Error loading desa:', error);
                toast.error('Gagal memuat data desa.', 'Error');
            } finally {
                this.loadingDesa = false;
            }
        },

        initMap() {
            // Initialize map centered on Bali
            this.map = L.map('map').setView([-8.4095, 115.1889], 10);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);

            // Initialize drawn items layer
            this.drawnItems = new L.FeatureGroup();
            this.map.addLayer(this.drawnItems);

            // Add drawing controls
            const drawControl = new L.Control.Draw({
                edit: {
                    featureGroup: this.drawnItems
                },
                draw: {
                    polygon: false,
                    circle: false,
                    rectangle: false,
                    marker: false,
                    circlemarker: false,
                    polyline: {
                        shapeOptions: {
                            color: '#3b82f6',
                            weight: 4
                        }
                    }
                }
            });
            this.map.addControl(drawControl);

            // Handle drawing events
            this.map.on('draw:created', (e) => {
                const layer = e.layer;
                
                // Clear previous drawings
                this.drawnItems.clearLayers();
                
                // Add new drawing
                this.drawnItems.addLayer(layer);
                
                // Get coordinates and encode as polyline
                const coordinates = layer.getLatLngs();
                this.form.paths = this.encodePolyline(coordinates);
                this.currentPath = coordinates;
                
                toast.success('Lokasi ruas jalan berhasil ditandai!', 'Sukses');
            });

            this.map.on('draw:edited', (e) => {
                const layers = e.layers;
                layers.eachLayer((layer) => {
                    const coordinates = layer.getLatLngs();
                    this.form.paths = this.encodePolyline(coordinates);
                    this.currentPath = coordinates;
                });
                
                toast.success('Lokasi ruas jalan berhasil diperbarui!', 'Sukses');
            });

            this.map.on('draw:deleted', () => {
                this.form.paths = '';
                this.currentPath = null;
                toast.info('Lokasi ruas jalan dihapus.', 'Info');
            });
        },

        encodePolyline(coordinates) {
            // Simple JSON encoding for now
            // You might want to use a proper polyline encoding library
            return JSON.stringify(coordinates.map(coord => [coord.lat, coord.lng]));
        },

        async handleSubmit() {
            if (!this.validateForm()) {
                return;
            }

            this.loading = true;
            
            try {
                const result = await RuasJalanService.createRuasJalan(this.form);
                
                if (result.success) {
                    toast.success('Ruas jalan berhasil ditambahkan!', 'Sukses');
                    this.$router.push('/ruasjalan');
                } else {
                    toast.error(result.message, 'Error');
                }
            } catch (error) {
                console.error('Error creating ruas jalan:', error);
                toast.error('Terjadi kesalahan saat menyimpan data.', 'Error');
            } finally {
                this.loading = false;
            }
        },

        validateForm() {
            if (!this.form.desa_id) {
                toast.warning('Silakan pilih desa.', 'Validasi');
                return false;
            }

            if (!this.form.kode_ruas.trim()) {
                toast.warning('Silakan masukkan kode ruas.', 'Validasi');
                return false;
            }

            if (!this.form.nama_ruas.trim()) {
                toast.warning('Silakan masukkan nama ruas.', 'Validasi');
                return false;
            }

            if (!this.form.paths) {
                toast.warning('Silakan tandai lokasi ruas jalan di peta.', 'Validasi');
                return false;
            }

            return true;
        }
    }
}
</script>

<style scoped>
.create-ruas-jalan {
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    gap: 1rem;
}

.header-content h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.5rem;
}

.header-content p {
    color: #6b7280;
    margin: 0;
}

.back-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: #f9fafb;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    color: #374151;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
}

.back-btn:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.form-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.ruas-jalan-form {
    padding: 2rem;
}

.form-section {
    margin-bottom: 2rem;
}

.form-section:last-child {
    margin-bottom: 0;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f3f4f6;
}

.section-title i {
    color: #3b82f6;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-group input:disabled,
.form-group select:disabled,
.form-group textarea:disabled {
    background: #f9fafb;
    cursor: not-allowed;
}

.map-container {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    overflow: hidden;
}

.map {
    height: 400px;
    width: 100%;
}

.map-instructions {
    padding: 1rem;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
}

.map-instructions p {
    margin: 0;
    font-size: 0.875rem;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.map-instructions i {
    color: #3b82f6;
}

.form-actions {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
}

.submit-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 2rem;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.submit-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
}

.submit-btn:disabled {
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

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .ruas-jalan-form {
        padding: 1.5rem;
    }
}
</style>
