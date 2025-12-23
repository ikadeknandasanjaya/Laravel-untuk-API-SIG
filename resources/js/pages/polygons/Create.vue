<template>
    <AppLayout>
        <div class="create-polygon-container">
            <div class="page-header">
                <h1>Add New Polygon</h1>
                <p>Click on map to add polygon points. Click first point again to close polygon.</p>
            </div>

            <div class="content-grid">
                <!-- Form Section -->
                <div class="form-section">
                    <form @submit.prevent="savePolygon" class="polygon-form">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <h3>Basic Information</h3>
                            
                            <div class="input-group">
                                <label for="name">Polygon Name *</label>
                                <input 
                                    type="text" 
                                    id="name"
                                    v-model="form.name"
                                    placeholder="Enter polygon name"
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

                        <!-- Polygon Properties -->
                        <div class="form-group">
                            <h3>Polygon Style</h3>
                            
                            <div class="input-group">
                                <label for="strokeColor">Stroke Color</label>
                                <input 
                                    type="color" 
                                    id="strokeColor"
                                    v-model="form.strokeColor"
                                    @input="updatePolygonStyle"
                                >
                            </div>

                            <div class="input-group">
                                <label for="fillColor">Fill Color</label>
                                <input 
                                    type="color" 
                                    id="fillColor"
                                    v-model="form.fillColor"
                                    @input="updatePolygonStyle"
                                >
                            </div>

                            <div class="input-group">
                                <label for="strokeWidth">Stroke Width</label>
                                <input 
                                    type="number" 
                                    id="strokeWidth"
                                    v-model="form.strokeWidth"
                                    min="1"
                                    max="10"
                                    @input="updatePolygonStyle"
                                >
                                <small>{{ form.strokeWidth }} px</small>
                            </div>

                            <div class="input-group">
                                <label for="opacity">Fill Opacity</label>
                                <input 
                                    type="range" 
                                    id="opacity"
                                    v-model="form.opacity"
                                    min="0"
                                    max="1"
                                    step="0.1"
                                    @input="updatePolygonStyle"
                                >
                                <small>{{ (form.opacity * 100).toFixed(0) }}%</small>
                            </div>
                        </div>

                        <!-- Polygon Info -->
                        <div class="form-group">
                            <h3>Polygon Info</h3>
                            <div class="info-display">
                                <div class="info-row">
                                    <span class="label">Total Points:</span>
                                    <span class="value">{{ form.coordinates.length }}</span>
                                </div>
                                <div v-if="polygonArea" class="info-row">
                                    <span class="label">Area:</span>
                                    <span class="value">{{ polygonArea }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="button" @click="clearPolygon" class="btn-warning">
                                <i class="fas fa-trash"></i>
                                Clear Points
                            </button>
                            <button type="button" @click="resetForm" class="btn-secondary">
                                <i class="fas fa-undo"></i>
                                Reset All
                            </button>
                            <button type="submit" :disabled="loading || form.coordinates.length < 3" class="btn-primary">
                                <i class="fas fa-save"></i>
                                {{ loading ? 'Saving...' : 'Save Polygon' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Map Preview -->
                <div class="map-section">
                    <div class="map-header">
                        <h3>Map Preview</h3>
                        <p>Click to add points. Double-click or click first point to close polygon.</p>
                    </div>
                    <div ref="mapContainer" class="map-container"></div>
                    <div class="map-info">
                        <div class="info-item">
                            <span>Points: {{ form.coordinates.length }}</span>
                            <span v-if="form.coordinates.length >= 3" class="status-closed">Polygon ready</span>
                            <span v-else class="status-open">Need {{ 3 - form.coordinates.length }} more points</span>
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
    name: 'CreatePolygon',
    components: {
        AppLayout
    },
    data() {
        return {
            form: {
                name: '',
                description: '',
                category_id: '',
                coordinates: [],
                strokeColor: '#3b82f6',
                fillColor: '#3b82f6',
                strokeWidth: 3,
                opacity: 0.5
            },
            loading: false,
            map: null,
            polygon: null,
            markers: []
        };
    },
    computed: {
        polygonArea() {
            if (this.form.coordinates.length < 3) return null;
            
            // Simple area calculation using Shoelace formula (approximate)
            let area = 0;
            const coords = this.form.coordinates;
            for (let i = 0; i < coords.length; i++) {
                const j = (i + 1) % coords.length;
                area += coords[i].lng * coords[j].lat;
                area -= coords[j].lng * coords[i].lat;
            }
            area = Math.abs(area / 2);
            
            // Convert to square kilometers (very rough approximation)
            const areaKm2 = area * 12321; // Approximate conversion factor
            return areaKm2.toFixed(2) + ' km²';
        }
    },
    mounted() {
        this.initMap();
    },
    methods: {
        initMap() {
            this.map = L.map(this.$refs.mapContainer).setView(
                [-8.4095, 115.1889],
                12
            );

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);

            // Add click event to add polygon points
            this.map.on('click', (e) => {
                this.addPoint(e.latlng);
            });
        },
        addPoint(latlng) {
            const point = {
                lat: parseFloat(latlng.lat.toFixed(6)),
                lng: parseFloat(latlng.lng.toFixed(6))
            };
            
            // Check if clicking near first point to close polygon
            if (this.form.coordinates.length >= 3) {
                const firstPoint = this.form.coordinates[0];
                const distance = this.map.distance(
                    [firstPoint.lat, firstPoint.lng],
                    [point.lat, point.lng]
                );
                
                if (distance < 50) { // 50 meters threshold
                    toast.success('Polygon closed!', 'Success');
                    this.updatePolygon();
                    return;
                }
            }
            
            this.form.coordinates.push(point);
            
            // Add marker
            const marker = L.circleMarker([point.lat, point.lng], {
                radius: 6,
                color: '#ffffff',
                fillColor: this.form.strokeColor,
                fillOpacity: 1,
                weight: 2
            }).addTo(this.map);
            
            this.markers.push(marker);
            
            this.updatePolygon();
        },
        updatePolygon() {
            if (this.polygon) {
                this.map.removeLayer(this.polygon);
            }

            if (this.form.coordinates.length >= 2) {
                const latlngs = this.form.coordinates.map(coord => [coord.lat, coord.lng]);
                
                if (this.form.coordinates.length === 2) {
                    // Draw line if only 2 points
                    this.polygon = L.polyline(latlngs, {
                        color: this.form.strokeColor,
                        weight: this.form.strokeWidth
                    }).addTo(this.map);
                } else {
                    // Draw polygon if 3+ points
                    this.polygon = L.polygon(latlngs, {
                        color: this.form.strokeColor,
                        fillColor: this.form.fillColor,
                        fillOpacity: parseFloat(this.form.opacity),
                        weight: this.form.strokeWidth
                    }).addTo(this.map);
                }

                this.map.fitBounds(this.polygon.getBounds());
            }
        },
        updatePolygonStyle() {
            this.updatePolygon();
            
            // Update marker colors
            this.markers.forEach(marker => {
                marker.setStyle({
                    fillColor: this.form.strokeColor
                });
            });
        },
        clearPolygon() {
            this.form.coordinates = [];
            
            if (this.polygon) {
                this.map.removeLayer(this.polygon);
                this.polygon = null;
            }
            
            this.markers.forEach(marker => {
                this.map.removeLayer(marker);
            });
            this.markers = [];
        },
        resetForm() {
            this.form = {
                name: '',
                description: '',
                category_id: '',
                coordinates: [],
                strokeColor: '#3b82f6',
                fillColor: '#3b82f6',
                strokeWidth: 3,
                opacity: 0.5
            };
            this.clearPolygon();
        },
        async savePolygon() {
            if (!this.validateForm()) {
                return;
            }
            
            this.loading = true;
            try {
                // Convert coordinates to [lng, lat] format
                const coordinates = this.form.coordinates.map(coord => [coord.lng, coord.lat]);
                
                // Close the polygon by adding first point at the end
                if (coordinates.length > 0) {
                    coordinates.push([...coordinates[0]]);
                }
                
                const polygonData = {
                    nama: this.form.name,
                    deskripsi: this.form.description || '',
                    kategori_id: this.form.category_id ? parseInt(this.form.category_id) : null,
                    coordinates: coordinates,
                    properties: {
                        strokeColor: this.form.strokeColor,
                        fillColor: this.form.fillColor,
                        strokeWidth: parseInt(this.form.strokeWidth),
                        opacity: parseFloat(this.form.opacity)
                    }
                };
                
                console.log('Sending polygon data:', polygonData);
                
                const result = await GeoFeatureService.createPolygon(polygonData);
                
                console.log('API result:', result);
                
                if (result.success) {
                    toast.success('Polygon berhasil disimpan!', 'Sukses');
                    this.$router.push('/polygons');
                } else {
                    toast.error(result.message || 'Gagal menyimpan polygon', 'Error');
                }
            } catch (error) {
                console.error('Error saving polygon:', error);
                console.error('Error response:', error.response?.data);
                toast.error('Gagal menyimpan polygon. Silakan coba lagi.', 'Error');
            } finally {
                this.loading = false;
            }
        },
        validateForm() {
            if (!this.form.name.trim()) {
                toast.warning('Silakan masukkan nama polygon.', 'Peringatan');
                return false;
            }
            
            if (this.form.coordinates.length < 3) {
                toast.warning('Polygon membutuhkan minimal 3 titik.', 'Peringatan');
                return false;
            }
            
            return true;
        }
    }
}
</script>

<style scoped>
.create-polygon-container {
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

.info-display {
    background: #f9fafb;
    padding: 1rem;
    border-radius: 0.375rem;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row .label {
    color: #6b7280;
    font-size: 0.875rem;
}

.info-row .value {
    color: #1f2937;
    font-weight: 600;
    font-size: 0.875rem;
}

.form-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
    margin-top: 2rem;
}

.form-actions .btn-primary {
    grid-column: 1 / -1;
}

.btn-primary,
.btn-secondary,
.btn-warning {
    padding: 0.75rem;
    border-radius: 0.375rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.btn-primary {
    background: #3b82f6;
    color: white;
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

.btn-warning {
    background: #ef4444;
    color: white;
}

.btn-warning:hover {
    background: #dc2626;
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
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.info-item {
    display: flex;
    gap: 1rem;
    align-items: center;
    font-size: 0.875rem;
    color: #374151;
}

.status-closed {
    color: #10b981;
    font-weight: 600;
}

.status-open {
    color: #f59e0b;
    font-weight: 600;
}
</style>
