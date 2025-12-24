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
                                <div class="instruction-item">
                                    <i class="fas fa-pencil-alt"></i>
                                    <span>Klik ikon <strong>Draw a polyline</strong> (garis) di pojok kiri atas peta</span>
                                </div>
                                <div class="instruction-item">
                                    <i class="fas fa-mouse-pointer"></i>
                                    <span>Klik pada peta untuk menambah titik-titik ruas jalan</span>
                                </div>
                                <div class="instruction-item">
                                    <i class="fas fa-check"></i>
                                    <span>Klik dua kali atau klik titik terakhir untuk selesai</span>
                                </div>
                                <div v-if="form.paths" class="path-info">
                                    <i class="fas fa-map-marked-alt"></i>
                                    <span>Path: <code>{{ form.paths.substring(0, 30) }}...</code></span>
                                </div>
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
import 'leaflet/dist/leaflet.css';
import 'leaflet-draw';
import 'leaflet-draw/dist/leaflet.draw.css';
import polyline from '@mapbox/polyline';

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
                keterangan: '-', // Default value to prevent null error
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
            currentPath: null,
            
            // Coordinate cache untuk menghindari repeated requests
            coordinateCache: {}
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
    watch: {
        'form.desa_id': function(newVal) {
            if (newVal && this.desaList.length > 0) {
                const selectedDesa = this.desaList.find(d => d.id === newVal);
                if (selectedDesa) {
                    this.zoomToDesa(selectedDesa.value);
                }
            }
        }
    },
    methods: {
        zoomToProvince(provinceName) {
            // Predefined coordinates for Indonesian provinces
            const provinceCoords = {
                'Aceh': [-5.135, 96.7246],
                'Sumatera Utara': [3.0973, 101.6228],
                'Sumatera Barat': [-0.3031, 100.6674],
                'Riau': [0.3031, 101.4374],
                'Jambi': [-1.4852, 103.6122],
                'Sumatera Selatan': [-3.6191, 104.7453],
                'Lampung': [-4.5586, 105.4067],
                'Kepulauan Bangka Belitung': [-2.7421, 107.5892],
                'Jawa Barat': [-6.9271, 107.5705],
                'Jawa Tengah': [-7.1506, 110.1429],
                'Jawa Timur': [-7.2575, 112.7521],
                'DKI Jakarta': [-6.2088, 106.8456],
                'DI Yogyakarta': [-7.7956, 110.3695],
                'Banten': [-6.2383, 106.1537],
                'Bali': [-8.4095, 115.1889],
                'Nusa Tenggara Barat': [-8.6500, 117.3616],
                'Nusa Tenggara Timur': [-8.6573, 120.8758],
                'Kalimantan Barat': [0.2127, 111.9673],
                'Kalimantan Tengah': [-1.6815, 113.7618],
                'Kalimantan Selatan': [-3.3200, 114.5900],
                'Kalimantan Timur': [0.5306, 116.7283],
                'Sulawesi Utara': [1.4527, 124.8581],
                'Sulawesi Tengah': [-1.4286, 119.8596],
                'Sulawesi Selatan': [-5.1477, 119.4327],
                'Sulawesi Tenggara': [-4.0511, 122.5808],
                'Gorontalo': [0.7167, 122.4333],
                'Sulawesi Barat': [-2.1338, 119.2471],
                'Maluku': [-3.2, 129.5],
                'Maluku Utara': [0.8028, 127.5363],
                'Papua': [-3.1913, 133.8687],
                'Papua Barat': [-1.3554, 132.7562]
            };
            
            const coords = provinceCoords[provinceName];
            if (coords && this.map) {
                this.map.setView(coords, 10);
            }
        },

        zoomToKabupaten(kabupatenName) {
            // Kabupaten coordinates within Bali (since default is Bali)
            const kabupatenCoords = {
                'Badung': [-8.65, 115.21],
                'Bangli': [-8.25, 115.37],
                'Buleleng': [-8.12, 115.04],
                'Denpasar': [-8.65, 115.21],
                'Gianyar': [-8.50, 115.36],
                'Jembrana': [-8.33, 114.36],
                'Karangasem': [-8.40, 115.48],
                'Klungkung': [-8.48, 115.36],
                'Tabanan': [-8.52, 115.13],
                'Ubud': [-8.51, 115.26]
            };
            
            const coords = kabupatenCoords[kabupatenName];
            if (coords && this.map) {
                this.map.setView(coords, 12);
            } else if (this.map) {
                // Fallback to Bali zoom if not found
                this.map.setView([-8.4095, 115.1889], 10);
            }
        },

        zoomToKecamatan(kecamatanName) {
            // Try to geocode kecamatan name
            if (this.map) {
                this.geocodeAndZoom(kecamatanName, 14, `Kecamatan ${kecamatanName}`);
            }
        },

        zoomToDesa(desaName) {
            // Try to geocode desa name
            if (this.map) {
                this.geocodeAndZoom(desaName, 15, `Siap untuk menandai ruas jalan di ${desaName}. Gunakan ikon "Draw a polyline" di pojok kiri.`);
            }
        },

        async geocodeAndZoom(locationName, zoomLevel, message) {
            try {
                // Check cache first
                const cacheKey = locationName.toLowerCase();
                if (this.coordinateCache[cacheKey]) {
                    const coords = this.coordinateCache[cacheKey];
                    this.map.setView(coords, zoomLevel);
                    toast.info(message, 'Info');
                    return;
                }

                // Use Nominatim (OpenStreetMap free geocoding)
                const response = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(locationName + ', Bali, Indonesia')}&format=json&limit=1`);
                const data = await response.json();
                
                if (data && data.length > 0) {
                    const coords = [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                    // Cache the result
                    this.coordinateCache[cacheKey] = coords;
                    this.map.setView(coords, zoomLevel);
                    toast.info(message, 'Info');
                } else {
                    // Fallback to just zoom in
                    this.map.setZoom(zoomLevel);
                    toast.info(`${message} (Lokasi pasti tidak ditemukan, silakan manual di peta)`, 'Info');
                }
            } catch (error) {
                console.error('Geocoding error:', error);
                // Fallback to just zoom in
                if (this.map) {
                    this.map.setZoom(zoomLevel);
                    toast.info(message, 'Info');
                }
            }
        },
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
                // Reset map to Bali
                if (this.map) {
                    this.map.setView([-8.4095, 115.1889], 10);
                }
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
                    
                    // Get selected province name and zoom to it
                    const selectedProvinsi = this.provinsiList.find(p => p.id === this.form.provinsi_id);
                    if (selectedProvinsi) {
                        this.zoomToProvince(selectedProvinsi.provinsi);
                    }
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
                // Reset to province view
                const selectedProvinsi = this.provinsiList.find(p => p.id === this.form.provinsi_id);
                if (selectedProvinsi && this.map) {
                    this.zoomToProvince(selectedProvinsi.provinsi);
                }
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
                    
                    // Get selected kabupaten name and zoom to it
                    const selectedKabupaten = this.kabupatenList.find(k => k.id === this.form.kabupaten_id);
                    if (selectedKabupaten) {
                        this.zoomToKabupaten(selectedKabupaten.value);
                    }
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
                // Reset to kabupaten view
                const selectedKabupaten = this.kabupatenList.find(k => k.id === this.form.kabupaten_id);
                if (selectedKabupaten && this.map) {
                    this.zoomToKabupaten(selectedKabupaten.value);
                }
                return;
            }

            this.loadingDesa = true;
            try {
                const result = await RegionService.getDesaByKecamatanId(this.form.kecamatan_id);
                if (result.success) {
                    this.desaList = result.data;
                    this.form.desa_id = '';
                    
                    // Get selected kecamatan name and zoom to it
                    const selectedKecamatan = this.kecamatanList.find(k => k.id === this.form.kecamatan_id);
                    if (selectedKecamatan) {
                        this.zoomToKecamatan(selectedKecamatan.value);
                    }
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
                
                // Calculate and set panjang (length in meters)
                const lengthInMeters = this.calculatePathLength(coordinates);
                this.form.panjang = Math.round(lengthInMeters);
                
                toast.success(`Ruas jalan berhasil ditandai! Panjang: ${Math.round(lengthInMeters)}m`, 'Sukses');
            });

            this.map.on('draw:edited', (e) => {
                const layers = e.layers;
                layers.eachLayer((layer) => {
                    const coordinates = layer.getLatLngs();
                    this.form.paths = this.encodePolyline(coordinates);
                    this.currentPath = coordinates;
                    
                    // Recalculate panjang
                    const lengthInMeters = this.calculatePathLength(coordinates);
                    this.form.panjang = Math.round(lengthInMeters);
                });
                
                toast.success('Lokasi ruas jalan berhasil diperbarui!', 'Sukses');
            });

            this.map.on('draw:deleted', () => {
                this.form.paths = '';
                this.currentPath = null;
                this.form.panjang = '';
                toast.info('Lokasi ruas jalan dihapus.', 'Info');
            });
        },

        calculatePathLength(coordinates) {
            let totalDistance = 0;
            
            for (let i = 0; i < coordinates.length - 1; i++) {
                const point1 = coordinates[i];
                const point2 = coordinates[i + 1];
                
                // Calculate distance between two points using Leaflet's built-in method
                const distance = this.map.distance(point1, point2);
                totalDistance += distance;
            }
            
            return totalDistance;
        },

        encodePolyline(coordinates) {
            // Convert Leaflet LatLng objects to [lat, lng] array format
            const points = coordinates.map(coord => [coord.lat, coord.lng]);
            
            // Encode using Google's polyline algorithm (precision 5)
            const encoded = polyline.encode(points);
            
            console.log('Coordinates:', points);
            console.log('Encoded polyline:', encoded);
            
            return encoded;
        },

        async handleSubmit() {
            if (!this.validateForm()) {
                return;
            }

            this.loading = true;
            
            try {
                // Check if remote token exists
                const remoteToken = localStorage.getItem('remote_auth_token');
                if (!remoteToken) {
                    toast.error('Token autentikasi tidak ditemukan. Silakan login kembali.', 'Error');
                    this.loading = false;
                    return;
                }
                
                console.log('Submitting ruas jalan data:', this.form);
                
                const result = await RuasJalanService.createRuasJalan(this.form);
                
                if (result.success) {
                    toast.success('Ruas jalan berhasil ditambahkan!', 'Sukses');
                    setTimeout(() => {
                        this.$router.push('/ruasjalan');
                    }, 1500);
                } else {
                    const errorMsg = result.message || 'Gagal menyimpan data ruas jalan';
                    toast.error(errorMsg, 'Error');
                    console.error('Create ruas jalan failed:', result);
                }
            } catch (error) {
                console.error('Error creating ruas jalan:', error);
                const errorMsg = error.response?.data?.message || error.message || 'Terjadi kesalahan saat menyimpan data.';
                toast.error(errorMsg, 'Error');
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

.instruction-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
    color: #374151;
}

.instruction-item:last-child {
    margin-bottom: 0;
}

.instruction-item i {
    color: #3b82f6;
    margin-top: 0.125rem;
    flex-shrink: 0;
}

.instruction-item strong {
    color: #1f2937;
}

.path-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 1rem;
    padding: 0.75rem;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    font-size: 0.75rem;
}

.path-info i {
    color: #3b82f6;
}

.path-info code {
    background: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-family: 'Monaco', 'Courier New', monospace;
    color: #1e40af;
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

/* Leaflet Draw Control Styling */
.leaflet-draw-toolbar {
    margin-top: 10px !important;
}

.leaflet-draw-toolbar a {
    background-color: white !important;
    border: 2px solid rgba(0,0,0,0.2) !important;
    width: 34px !important;
    height: 34px !important;
    line-height: 32px !important;
}

.leaflet-draw-toolbar a:hover {
    background-color: #f4f4f4 !important;
    border-color: #3b82f6 !important;
}

.leaflet-draw-draw-polyline {
    background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiMzYjgyZjYiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cGF0aCBkPSJNNCAyMGg0TDIwIDhsLTQtNEw0IDIweiIvPjxwYXRoIGQ9Ik0xMy41IDcuNWwyLjUgMi41Ii8+PC9zdmc+') !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    background-size: 20px 20px !important;
}

.leaflet-draw-edit-edit {
    background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM0YjU1NjMiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cGF0aCBkPSJNMTcgM2E0IDQgMCAwIDEgNCA0djJhNCA0IDAgMCAxLTQgNEg3YTQgNCAwIDAgMS00LTRWN2E0IDQgMCAwIDEgNC00eiIvPjxwYXRoIGQ9Ik0xMiA4djgiLz48cGF0aCBkPSJNOCAxMmg4Ii8+PC9zdmc+') !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    background-size: 18px 18px !important;
}

.leaflet-draw-edit-remove {
    background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiNlZjQ0NDQiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cG9seWxpbmUgcG9pbnRzPSIzIDYgNSA2IDIxIDYiLz48cGF0aCBkPSJNMTkgNnYxNGEyIDIgMCAwIDEtMiAySDdhMiAyIDAgMCAxLTItMlY2bTE0IDBWNGEyIDIgMCAwIDAtMi0ySDlhMiAyIDAgMCAwLTIgMnYyIi8+PC9zdmc+') !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    background-size: 18px 18px !important;
}

.leaflet-draw-actions {
    left: 34px !important;
}

.leaflet-draw-actions a {
    background-color: white !important;
    color: #333 !important;
    font-size: 12px !important;
    font-weight: 500 !important;
    padding: 0 10px !important;
    height: 28px !important;
    line-height: 28px !important;
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
