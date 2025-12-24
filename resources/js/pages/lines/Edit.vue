<template>
    <AppLayout>
        <div class="edit-line-container">
            <div class="page-header">
                <div class="header-content">
                    <h1>Edit Line</h1>
                    <p>Update line properties and coordinates</p>
                </div>
                <div class="header-actions">
                    <router-link to="/lines" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Lines
                    </router-link>
                </div>
            </div>

            <div v-if="loading" class="loading-state">
                <div class="spinner"></div>
                <p>Loading line data...</p>
            </div>

            <div v-else class="form-layout">
                <div class="form-section">
                    <form @submit.prevent="updateLine" class="line-form">
                        <div class="form-group">
                            <h3>Basic Information</h3>
                            <div class="input-group">
                                <label for="name">Line Name *</label>
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
                            <h3>Line Appearance</h3>
                            <div class="input-group">
                                <label for="color">Color</label>
                                <input type="color" id="color" v-model="form.color" @change="updateMapLine">
                            </div>
                            <div class="input-group">
                                <label for="width">Width (px)</label>
                                <input type="number" id="width" v-model="form.width" min="1" max="10" @input="updateMapLine">
                            </div>
                            <div class="input-group">
                                <label for="opacity">Opacity</label>
                                <input type="range" id="opacity" v-model="form.opacity" min="0" max="1" step="0.1" @input="updateMapLine">
                                <small>{{ (form.opacity * 100).toFixed(0) }}%</small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" @click="deleteLine" class="btn-danger">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                            <button type="submit" :disabled="loading" class="btn-primary">
                                <i class="fas fa-save"></i>
                                {{ loading ? 'Saving...' : 'Update Line' }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="map-section">
                    <div class="map-header">
                        <h3>Map Preview</h3>
                        <p>Line with {{ form.coordinates.length }} points</p>
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
    name: 'EditLine',
    components: { AppLayout },
    data() {
        return {
            form: {
                name: '',
                description: '',
                category_id: '',
                coordinates: [],
                color: '#3b82f6',
                width: 3,
                opacity: 0.8
            },
            originalForm: {},
            categories: [
                { id: 1, name: 'Main Road' },
                { id: 2, name: 'Highway' },
                { id: 3, name: 'Local Road' },
                { id: 4, name: 'Trail' }
            ],
            loading: true,
            map: null,
            polyline: null
        };
    },
    async mounted() {
        await this.loadLineData();
        this.initMap();
    },
    methods: {
        async loadLineData() {
            try {
                const lineId = this.$route.params.id;
                const result = await GeoFeatureService.getFeatureById(lineId);
                if (result.data) {
                    const line = result.data;
                    this.form = {
                        name: line.nama,
                        description: line.deskripsi || '',
                        category_id: line.kategori_id || '',
                        coordinates: line.coordinates || [],
                        color: line.properties?.color || '#3b82f6',
                        width: line.properties?.width || 3,
                        opacity: line.properties?.opacity || 0.8
                    };
                    this.originalForm = JSON.parse(JSON.stringify(this.form));
                }
                this.loading = false;
            } catch (error) {
                console.error('Error loading line:', error);
                toast.error('Failed to load line', 'Error');
                this.$router.push('/lines');
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
            
            this.updateMapLine();
        },
        updateMapLine() {
            if (this.polyline) {
                this.map.removeLayer(this.polyline);
            }
            if (this.form.coordinates.length >= 2) {
                const coords = this.form.coordinates.map(c => [c[1], c[0]]);
                this.polyline = L.polyline(coords, {
                    color: this.form.color,
                    weight: parseInt(this.form.width),
                    opacity: parseFloat(this.form.opacity)
                }).addTo(this.map);
                this.map.fitBounds(this.polyline.getBounds());
            }
        },
        async updateLine() {
            if (!this.form.name.trim()) {
                toast.warning('Please enter line name', 'Warning');
                return;
            }
            if (this.form.coordinates.length < 2) {
                toast.warning('Line needs at least 2 points', 'Warning');
                return;
            }

            this.loading = true;
            try {
                const lineId = this.$route.params.id;
                const lineData = {
                    nama: this.form.name,
                    deskripsi: this.form.description || '',
                    kategori_id: this.form.category_id ? parseInt(this.form.category_id) : null,
                    coordinates: this.form.coordinates,
                    properties: {
                        color: this.form.color,
                        width: parseInt(this.form.width),
                        opacity: parseFloat(this.form.opacity)
                    }
                };

                const result = await GeoFeatureService.updateFeature(lineId, {
                    ...lineData,
                    geometry_type: 'polyline'
                });

                if (result.success) {
                    toast.success('Line updated successfully!', 'Success');
                    this.$router.push('/lines');
                } else {
                    toast.error(result.message || 'Failed to update line', 'Error');
                }
            } catch (error) {
                console.error('Error updating line:', error);
                toast.error('Failed to update line', 'Error');
            } finally {
                this.loading = false;
            }
        },
        async deleteLine() {
            const confirmed = window.confirm(`Delete "${this.form.name}"?`);
            if (!confirmed) return;

            this.loading = true;
            try {
                const lineId = this.$route.params.id;
                await GeoFeatureService.deleteFeature(lineId);
                toast.success('Line deleted successfully!', 'Success');
                this.$router.push('/lines');
            } catch (error) {
                console.error('Error deleting line:', error);
                toast.error('Failed to delete line', 'Error');
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<style scoped>
.edit-line-container { padding: 2rem; }
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
.form-actions { display: flex; gap: 1rem; margin-top: 2rem; }
.form-actions .btn-danger { margin-right: auto; }
.map-section { background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden; }
.map-header { padding: 1rem 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.map-header h3 { margin: 0 0 0.25rem 0; font-size: 1.125rem; }
.map-header p { margin: 0; font-size: 0.875rem; opacity: 0.9; }
.map-container { width: 100%; height: 600px; }
</style>
