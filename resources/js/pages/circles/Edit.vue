<template>
    <AppLayout>
        <div class="edit-circle-container">
            <div class="page-header">
                <div class="header-content">
                    <h1>Edit Circle</h1>
                    <p>Update circle properties</p>
                </div>
                <div class="header-actions">
                    <router-link to="/circles" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Circles
                    </router-link>
                </div>
            </div>

            <div v-if="loading" class="loading-state">
                <div class="spinner"></div>
                <p>Loading circle data...</p>
            </div>

            <div v-else class="form-layout">
                <div class="form-section">
                    <form @submit.prevent="updateCircle" class="circle-form">
                        <div class="form-group">
                            <h3>Basic Information</h3>
                            <div class="input-group">
                                <label for="name">Circle Name *</label>
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
                            <h3>Location</h3>
                            <div class="coordinate-inputs">
                                <div class="input-group">
                                    <label for="latitude">Latitude *</label>
                                    <input type="number" id="latitude" v-model="form.lat" step="any" required @input="updateMapCircle">
                                </div>
                                <div class="input-group">
                                    <label for="longitude">Longitude *</label>
                                    <input type="number" id="longitude" v-model="form.lng" step="any" required @input="updateMapCircle">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <h3>Circle Properties</h3>
                            <div class="input-group">
                                <label for="radius">Radius (meters) *</label>
                                <input type="number" id="radius" v-model="form.radius" min="1" step="1" required @input="updateMapCircle">
                                <small>{{ (form.radius / 1000).toFixed(2) }} km</small>
                            </div>
                            <div class="input-group">
                                <label for="color">Color</label>
                                <input type="color" id="color" v-model="form.color" @change="updateMapCircle">
                            </div>
                            <div class="input-group">
                                <label for="opacity">Opacity</label>
                                <input type="range" id="opacity" v-model="form.opacity" min="0" max="1" step="0.1" @input="updateMapCircle">
                                <small>{{ (form.opacity * 100).toFixed(0) }}%</small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" @click="deleteCircle" class="btn-danger">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                            <button type="submit" :disabled="loading" class="btn-primary">
                                <i class="fas fa-save"></i>
                                {{ loading ? 'Saving...' : 'Update Circle' }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="map-section">
                    <div class="map-header">
                        <h3>Map Preview</h3>
                        <p>Circle with {{ (form.radius / 1000).toFixed(2) }} km radius</p>
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
    name: 'EditCircle',
    components: { AppLayout },
    data() {
        return {
            form: {
                name: '',
                description: '',
                category_id: '',
                lat: null,
                lng: null,
                radius: 1000,
                color: '#3b82f6',
                opacity: 0.5
            },
            originalForm: {},
            categories: [
                { id: 1, name: 'Buffer Zone' },
                { id: 2, name: 'Service Area' },
                { id: 3, name: 'Impact Zone' },
                { id: 4, name: 'Coverage Area' },
                { id: 5, name: 'Restricted Area' }
            ],
            loading: true,
            map: null,
            circle: null
        };
    },
    async mounted() {
        await this.loadCircleData();
        this.initMap();
    },
    methods: {
        async loadCircleData() {
            try {
                const circleId = this.$route.params.id;
                const result = await GeoFeatureService.getFeatureById(circleId);
                if (result.data) {
                    const circle = result.data;
                    this.form = {
                        name: circle.nama,
                        description: circle.deskripsi || '',
                        category_id: circle.kategori_id || '',
                        lat: circle.coordinates[1],
                        lng: circle.coordinates[0],
                        radius: circle.properties?.radius || 1000,
                        color: circle.properties?.color || '#3b82f6',
                        opacity: circle.properties?.opacity || 0.5
                    };
                    this.originalForm = JSON.parse(JSON.stringify(this.form));
                }
                this.loading = false;
            } catch (error) {
                console.error('Error loading circle:', error);
                toast.error('Failed to load circle', 'Error');
                this.$router.push('/circles');
            }
        },
        initMap() {
            this.map = L.map(this.$refs.mapContainer).setView(
                [this.form.lat || -8.4095, this.form.lng || 115.1889],
                12
            );
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);
            this.updateMapCircle();
        },
        updateMapCircle() {
            if (this.circle) {
                this.map.removeLayer(this.circle);
            }
            if (this.form.lat && this.form.lng && this.form.radius) {
                this.circle = L.circle([this.form.lat, this.form.lng], {
                    radius: parseFloat(this.form.radius),
                    color: this.form.color,
                    fillColor: this.form.color,
                    fillOpacity: parseFloat(this.form.opacity)
                }).addTo(this.map);
                this.map.fitBounds(this.circle.getBounds());
            }
        },
        async updateCircle() {
            if (!this.form.name.trim()) {
                toast.warning('Please enter circle name', 'Warning');
                return;
            }
            if (!this.form.lat || !this.form.lng || !this.form.radius) {
                toast.warning('Please set circle properties', 'Warning');
                return;
            }

            this.loading = true;
            try {
                const circleId = this.$route.params.id;
                const circleData = {
                    nama: this.form.name,
                    deskripsi: this.form.description || '',
                    kategori_id: this.form.category_id ? parseInt(this.form.category_id) : null,
                    coordinates: [this.form.lng, this.form.lat],
                    properties: {
                        radius: parseFloat(this.form.radius),
                        color: this.form.color,
                        opacity: parseFloat(this.form.opacity)
                    }
                };

                const result = await GeoFeatureService.updateFeature(circleId, {
                    ...circleData,
                    geometry_type: 'circle'
                });

                if (result.success) {
                    toast.success('Circle updated successfully!', 'Success');
                    this.$router.push('/circles');
                } else {
                    toast.error(result.message || 'Failed to update circle', 'Error');
                }
            } catch (error) {
                console.error('Error updating circle:', error);
                toast.error('Failed to update circle', 'Error');
            } finally {
                this.loading = false;
            }
        },
        async deleteCircle() {
            const confirmed = window.confirm(`Delete "${this.form.name}"?`);
            if (!confirmed) return;

            this.loading = true;
            try {
                const circleId = this.$route.params.id;
                await GeoFeatureService.deleteFeature(circleId);
                toast.success('Circle deleted successfully!', 'Success');
                this.$router.push('/circles');
            } catch (error) {
                console.error('Error deleting circle:', error);
                toast.error('Failed to delete circle', 'Error');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<style scoped>
.edit-circle-container { padding: 2rem; }
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
.coordinate-inputs { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.form-actions { display: flex; gap: 1rem; margin-top: 2rem; }
.form-actions .btn-danger { margin-right: auto; }
.map-section { background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden; }
.map-header { padding: 1rem 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.map-header h3 { margin: 0 0 0.25rem 0; font-size: 1.125rem; }
.map-header p { margin: 0; font-size: 0.875rem; opacity: 0.9; }
.map-container { width: 100%; height: 600px; }
</style>
