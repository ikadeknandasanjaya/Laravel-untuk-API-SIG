<template>
    <AppLayout>
        <div class="create-circle-container">
            <div class="page-header">
                <h1>Add New Circle</h1>
                <p>Click on map to set circle center, then adjust radius</p>
            </div>

            <div class="content-grid">
                <!-- Form Section -->
                <div class="form-section">
                    <form @submit.prevent="saveCircle" class="circle-form">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <h3>Basic Information</h3>
                            
                            <div class="input-group">
                                <label for="name">Circle Name *</label>
                                <input 
                                    type="text" 
                                    id="name"
                                    v-model="form.name"
                                    placeholder="Enter circle name"
                                    required
                                >
                            </div>

                            <div class="input-group">
                                <label for="description">Description</label>
                                <textarea 
                                    id="description"
                                    v-model="form.description"
                                    placeholder="Enter description"
                                    rows="3"
                                ></textarea>
                            </div>

                            <div class="input-group">
                                <label for="category">Category *</label>
                                <select id="category" v-model="form.category_id" required>
                                    <option value="">Select category</option>
                                    <option value="1">Area 1</option>
                                    <option value="2">Area 2</option>
                                    <option value="3">Area 3</option>
                                </select>
                            </div>
                        </div>

                        <!-- Center Coordinates -->
                        <div class="form-group">
                            <h3>Center Location</h3>
                            
                            <div class="coordinate-inputs">
                                <div class="input-group">
                                    <label for="latitude">Latitude *</label>
                                    <input 
                                        type="number" 
                                        id="latitude"
                                        v-model="form.center.lat"
                                        step="any"
                                        placeholder="-8.4095"
                                        required
                                        @input="updateMapCircle"
                                    >
                                </div>
                                <div class="input-group">
                                    <label for="longitude">Longitude *</label>
                                    <input 
                                        type="number" 
                                        id="longitude"
                                        v-model="form.center.lng"
                                        step="any"
                                        placeholder="115.1889"
                                        required
                                        @input="updateMapCircle"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Circle Properties -->
                        <div class="form-group">
                            <h3>Circle Properties</h3>
                            
                            <div class="input-group">
                                <label for="radius">Radius (meters) *</label>
                                <input 
                                    type="number" 
                                    id="radius"
                                    v-model="form.radius"
                                    min="1"
                                    step="1"
                                    placeholder="1000"
                                    required
                                    @input="updateMapCircle"
                                >
                                <small>{{ (form.radius / 1000).toFixed(2) }} km</small>
                            </div>

                            <div class="input-group">
                                <label for="color">Color</label>
                                <input 
                                    type="color" 
                                    id="color"
                                    v-model="form.color"
                                    @input="updateMapCircle"
                                >
                            </div>

                            <div class="input-group">
                                <label for="opacity">Opacity</label>
                                <input 
                                    type="range" 
                                    id="opacity"
                                    v-model="form.opacity"
                                    min="0"
                                    max="1"
                                    step="0.1"
                                    @input="updateMapCircle"
                                >
                                <small>{{ (form.opacity * 100).toFixed(0) }}%</small>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="button" @click="resetForm" class="btn-secondary">
                                <i class="fas fa-undo"></i>
                                Reset
                            </button>
                            <button type="submit" :disabled="loading" class="btn-primary">
                                <i class="fas fa-save"></i>
                                {{ loading ? 'Saving...' : 'Save Circle' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Map Preview -->
                <div class="map-section">
                    <div class="map-header">
                        <h3>Map Preview</h3>
                        <p>Click on map to set circle center</p>
                    </div>
                    <div ref="mapContainer" class="map-container"></div>
                    <div class="map-info">
                        <div class="info-item">
                            <span>Radius: {{ form.radius }} m</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../../components/AppLayout.vue';
import L from 'leaflet';
import toast from '../../utils/toast.js';
import GeoFeatureService from '../../services/GeoFeatureService.js';

export default {
    name: 'CreateCircle',
    components: {
        AppLayout
    },
    data() {
        return {
            form: {
                name: '',
                description: '',
                category_id: '',
                center: {
                    lat: -8.4095,
                    lng: 115.1889
                },
                radius: 1000,
                color: '#3b82f6',
                opacity: 0.5
            },
            loading: false,
            map: null,
            circle: null
        };
    },
    mounted() {
        this.initMap();
    },
    methods: {
        initMap() {
            this.map = L.map(this.$refs.mapContainer).setView(
                [this.form.center.lat, this.form.center.lng],
                12
            );

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);

            // Add click event to set center
            this.map.on('click', (e) => {
                this.form.center.lat = parseFloat(e.latlng.lat.toFixed(6));
                this.form.center.lng = parseFloat(e.latlng.lng.toFixed(6));
                this.updateMapCircle();
            });

            this.updateMapCircle();
        },
        updateMapCircle() {
            if (this.circle) {
                this.map.removeLayer(this.circle);
            }

            if (this.form.center.lat && this.form.center.lng && this.form.radius) {
                this.circle = L.circle(
                    [this.form.center.lat, this.form.center.lng],
                    {
                        radius: parseFloat(this.form.radius),
                        color: this.form.color,
                        fillColor: this.form.color,
                        fillOpacity: parseFloat(this.form.opacity)
                    }
                ).addTo(this.map);

                this.map.fitBounds(this.circle.getBounds());
            }
        },
        resetForm() {
            this.form = {
                name: '',
                description: '',
                category_id: '',
                center: {
                    lat: -8.4095,
                    lng: 115.1889
                },
                radius: 1000,
                color: '#3b82f6',
                opacity: 0.5
            };
            this.updateMapCircle();
        },
        async saveCircle() {
            if (!this.validateForm()) {
                return;
            }
            
            this.loading = true;
            try {
                const circleData = {
                    nama: this.form.name,
                    deskripsi: this.form.description || '',
                    kategori_id: this.form.category_id ? parseInt(this.form.category_id) : null,
                    coordinates: [this.form.center.lng, this.form.center.lat],
                    properties: {
                        radius: parseFloat(this.form.radius),
                        color: this.form.color,
                        opacity: parseFloat(this.form.opacity)
                    }
                };
                
                const result = await GeoFeatureService.createCircle(circleData);
                
                if (result.success) {
                    toast.success('Circle berhasil disimpan!', 'Sukses');
                    this.$router.push('/circles');
                } else {
                    toast.error(result.message || 'Gagal menyimpan circle', 'Error');
                }
            } catch (error) {
                console.error('Error saving circle:', error);
                toast.error('Gagal menyimpan circle. Silakan coba lagi.', 'Error');
            } finally {
                this.loading = false;
            }
        },
        validateForm() {
            if (!this.form.name.trim()) {
                toast.warning('Silakan masukkan nama circle.', 'Peringatan');
                return false;
            }
            
            if (!this.form.center.lat || !this.form.center.lng) {
                toast.warning('Silakan set center circle di map.', 'Peringatan');
                return false;
            }
            
            if (!this.form.radius || this.form.radius <= 0) {
                toast.warning('Silakan masukkan radius yang valid.', 'Peringatan');
                return false;
            }
            
            return true;
        }
    }
}
</script>

<style scoped>
.create-circle-container {
    padding: 2rem;
}

.page-header {
    margin-bottom: 2rem;
}

.page-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.page-header p {
    color: #6b7280;
}

.content-grid {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 2rem;
}

.form-section {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
    height: fit-content;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e5e7eb;
}

.input-group {
    margin-bottom: 1rem;
}

.input-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
}

.input-group input,
.input-group select,
.input-group textarea {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
}

.input-group small {
    display: block;
    margin-top: 0.25rem;
    color: #6b7280;
    font-size: 0.75rem;
}

.coordinate-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.btn-primary,
.btn-secondary {
    flex: 1;
    padding: 0.75rem;
    border-radius: 0.375rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: white;
    border: none;
}

.btn-primary:hover:not(:disabled) {
    background: #2563eb;
}

.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-secondary {
    background: white;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background: #f9fafb;
}

.map-section {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.map-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.map-header h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.map-header p {
    color: #6b7280;
    font-size: 0.875rem;
}

.map-container {
    width: 100%;
    height: 600px;
}

.map-info {
    padding: 1rem 1.5rem;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
}

.info-item {
    font-size: 0.875rem;
    color: #374151;
}
</style>
