<template>
    <AppLayout>
        <div class="edit-polygon-container">
            <div class="page-header">
                <div class="header-content">
                    <h1>Edit Polygon</h1>
                    <p>Update polygon properties and coordinates</p>
                </div>
                <div class="header-actions">
                    <router-link to="/polygons" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Polygons
                    </router-link>
                </div>
            </div>

            <div v-if="loading" class="loading-state">
                <div class="spinner"></div>
                <p>Loading polygon data...</p>
            </div>

            <div v-else class="form-layout">
                <div class="form-section">
                    <form @submit.prevent="updatePolygon" class="polygon-form">
                        <div class="form-group">
                            <h3>Basic Information</h3>
                            <div class="input-group">
                                <label for="name">Polygon Name *</label>
                                <input type="text" id="name" v-model="form.name" required>
                            </div>
                            <div class="input-group">
                                <label for="description">Description</label>
                                <textarea id="description" v-model="form.description" rows="3"></textarea>
                            </div>
                            <div class="input-group">
                                <label for="category">Category *</label>
                                <select id="category" v-model="form.category_id" required>
                                    <option value="">Select a category</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                        {{ cat.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <h3>Polygon Style</h3>
                            <div class="input-group">
                                <label for="strokeColor">Stroke Color</label>
                                <input type="color" id="strokeColor" v-model="form.strokeColor" @change="updateMapPolygon">
                            </div>
                            <div class="input-group">
                                <label for="fillColor">Fill Color</label>
                                <input type="color" id="fillColor" v-model="form.fillColor" @change="updateMapPolygon">
                            </div>
                            <div class="input-group">
                                <label for="strokeWidth">Stroke Width</label>
                                <input type="number" id="strokeWidth" v-model="form.strokeWidth" min="1" max="10" @input="updateMapPolygon">
                                <small>{{ form.strokeWidth }} px</small>
                            </div>
                            <div class="input-group">
                                <label for="opacity">Fill Opacity</label>
                                <input type="range" id="opacity" v-model="form.opacity" min="0" max="1" step="0.1" @input="updateMapPolygon">
                                <small>{{ (form.opacity * 100).toFixed(0) }}%</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <h3>Polygon Info</h3>
                            <div class="info-display">
                                <div class="info-row">
                                    <span class="label">Total Points:</span>
                                    <span class="value">{{ form.coordinates.length }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" @click="deletePolygon" class="btn-danger">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                            <button type="submit" :disabled="loading" class="btn-primary">
                                <i class="fas fa-save"></i>
                                {{ loading ? 'Saving...' : 'Update Polygon' }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="map-section">
                    <div class="map-header">
                        <h3>Map Preview</h3>
                        <p>Polygon with {{ form.coordinates.length }} points</p>
                    </div>
                    <div ref="mapContainer" class="map-container"></div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../../components/AppLayout.vue';
import L from 'leaflet';
import GeoFeatureService from '../../services/GeoFeatureService.js';
import toast from '../../utils/toast.js';

export default {
    name: 'EditPolygon',
    components: { AppLayout },
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
                opacity: 0.4
            },
            originalForm: {},
            categories: [
                { id: 1, name: 'Administrative Area' },
                { id: 2, name: 'Protected Area' },
                { id: 3, name: 'Urban Area' },
                { id: 4, name: 'Agricultural Area' },
                { id: 5, name: 'Water Body' }
            ],
            loading: true,
            map: null,
            polygon: null
        };
    },
    async mounted() {
        await this.loadPolygonData();
        this.initMap();
    },
    methods: {
        async loadPolygonData() {
            try {
                const polygonId = this.$route.params.id;
                const result = await GeoFeatureService.getFeatureById(polygonId);
                if (result.data) {
                    const polygon = result.data;
                    this.form = {
                        name: polygon.nama,
                        description: polygon.deskripsi || '',
                        category_id: polygon.kategori_id || '',
                        coordinates: polygon.coordinates || [],
                        strokeColor: polygon.properties?.strokeColor || '#3b82f6',
                        fillColor: polygon.properties?.fillColor || '#3b82f6',
                        strokeWidth: polygon.properties?.strokeWidth || 3,
                        opacity: polygon.properties?.opacity || 0.4
                    };
                    this.originalForm = JSON.parse(JSON.stringify(this.form));
                }
                this.loading = false;
            } catch (error) {
                console.error('Error loading polygon:', error);
                toast.error('Failed to load polygon', 'Error');
                this.$router.push('/polygons');
            }
        },
        initMap() {
            const center = this.form.coordinates.length > 0
                ? [this.form.coordinates[0][1], this.form.coordinates[0][0]]
                : [-8.4095, 115.1889];
            
            this.map = L.map(this.$refs.mapContainer).setView(center, 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);
            
            this.updateMapPolygon();
        },
        updateMapPolygon() {
            if (this.polygon) {
                this.map.removeLayer(this.polygon);
            }
            if (this.form.coordinates.length >= 3) {
                const coords = this.form.coordinates.map(c => [c[1], c[0]]);
                this.polygon = L.polygon(coords, {
                    color: this.form.strokeColor,
                    fillColor: this.form.fillColor,
                    fillOpacity: parseFloat(this.form.opacity),
                    weight: parseInt(this.form.strokeWidth)
                }).addTo(this.map);
                this.map.fitBounds(this.polygon.getBounds());
            }
        },
        async updatePolygon() {
            if (!this.form.name.trim()) {
                toast.warning('Please enter polygon name', 'Warning');
                return;
            }
            if (this.form.coordinates.length < 3) {
                toast.warning('Polygon needs at least 3 points', 'Warning');
                return;
            }

            this.loading = true;
            try {
                const polygonId = this.$route.params.id;
                const polygonData = {
                    nama: this.form.name,
                    deskripsi: this.form.description || '',
                    kategori_id: this.form.category_id ? parseInt(this.form.category_id) : null,
                    coordinates: this.form.coordinates,
                    properties: {
                        strokeColor: this.form.strokeColor,
                        fillColor: this.form.fillColor,
                        strokeWidth: parseInt(this.form.strokeWidth),
                        opacity: parseFloat(this.form.opacity)
                    }
                };

                const result = await GeoFeatureService.updateFeature(polygonId, {
                    ...polygonData,
                    geometry_type: 'polygon'
                });

                if (result.success) {
                    toast.success('Polygon updated successfully!', 'Success');
                    this.$router.push('/polygons');
                } else {
                    toast.error(result.message || 'Failed to update polygon', 'Error');
                }
            } catch (error) {
                console.error('Error updating polygon:', error);
                toast.error('Failed to update polygon', 'Error');
            } finally {
                this.loading = false;
            }
        },
        async deletePolygon() {
            const confirmed = window.confirm(`Delete "${this.form.name}"?`);
            if (!confirmed) return;

            this.loading = true;
            try {
                const polygonId = this.$route.params.id;
                await GeoFeatureService.deleteFeature(polygonId);
                toast.success('Polygon deleted successfully!', 'Success');
                this.$router.push('/polygons');
            } catch (error) {
                console.error('Error deleting polygon:', error);
                toast.error('Failed to delete polygon', 'Error');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<style scoped>
.edit-polygon-container { padding: 2rem; }
.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; }
.header-content h1 { font-size: 2rem; font-weight: bold; color: #111827; margin: 0 0 0.5rem 0; }
.header-content p { color: #6b7280; margin: 0; }
.btn-secondary { background: white; color: #374151; border: 1px solid #d1d5db; padding: 0.75rem 1.5rem; border-radius: 0.375rem; text-decoration: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; }
.btn-primary { background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; }
.btn-primary:hover:not(:disabled) { background: #2563eb; }
.btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-danger { background: #ef4444; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; }
.btn-danger:hover { background: #dc2626; }
.loading-state { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 400px; gap: 1rem; }
.spinner { width: 40px; height: 40px; border: 4px solid #e5e7eb; border-top-color: #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.form-layout { display: grid; grid-template-columns: 400px 1fr; gap: 2rem; }
.form-section { background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); padding: 1.5rem; height: fit-content; }
.form-group { margin-bottom: 1.5rem; }
.form-group h3 { font-size: 1.125rem; font-weight: 600; color: #1f2937; margin: 0 0 1rem 0; padding-bottom: 0.75rem; border-bottom: 2px solid #e5e7eb; }
.input-group { margin-bottom: 1rem; }
.input-group label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem; }
.input-group input, .input-group select, .input-group textarea { width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; font-family: inherit; }
.input-group input:focus, .input-group select:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
.info-display { background: #f9fafb; padding: 1rem; border-radius: 0.375rem; }
.info-row { display: flex; justify-content: space-between; padding: 0.5rem 0; }
.info-row .label { color: #6b7280; font-size: 0.875rem; }
.info-row .value { color: #1f2937; font-weight: 600; font-size: 0.875rem; }
.form-actions { display: flex; gap: 1rem; margin-top: 2rem; }
.form-actions .btn-danger { margin-right: auto; }
.map-section { background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden; }
.map-header { padding: 1rem 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.map-header h3 { margin: 0 0 0.25rem 0; font-size: 1.125rem; }
.map-header p { margin: 0; font-size: 0.875rem; opacity: 0.9; }
.map-container { width: 100%; height: 600px; }
</style>
