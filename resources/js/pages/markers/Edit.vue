<template>
    <AppLayout>
        <div class="edit-marker-container">
            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1>Edit Marker</h1>
                    <p>Update marker information</p>
                </div>
                <div class="header-actions">
                    <router-link to="/markers" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Markers
                    </router-link>
                </div>
            </div>

            <div v-if="loading" class="loading-state">
                <div class="spinner"></div>
                <p>Loading marker data...</p>
            </div>

            <div v-else class="form-layout">
                <!-- Form Section -->
                <div class="form-section">
                    <form @submit.prevent="updateMarker" class="marker-form">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <h3>Basic Information</h3>
                            
                            <div class="input-group">
                                <label for="name">Marker Name *</label>
                                <input 
                                    type="text" 
                                    id="name"
                                    v-model="form.nama"
                                    placeholder="Enter marker name"
                                    required
                                >
                            </div>

                            <div class="input-group">
                                <label for="description">Description</label>
                                <textarea 
                                    id="description"
                                    v-model="form.deskripsi"
                                    placeholder="Enter marker description"
                                    rows="3"
                                ></textarea>
                            </div>

                            <div class="input-group">
                                <label for="category">Category *</label>
                                <select id="category" v-model="form.kategori_id" required>
                                    <option value="">Select a category</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="form-group">
                            <h3>Location</h3>
                            
                            <div class="coordinate-inputs">
                                <div class="input-group">
                                    <label for="latitude">Latitude *</label>
                                    <input 
                                        type="number" 
                                        id="latitude"
                                        v-model="form.latitude"
                                        step="any"
                                        placeholder="-8.4095"
                                        required
                                        @input="updateMapMarker"
                                    >
                                </div>
                                <div class="input-group">
                                    <label for="longitude">Longitude *</label>
                                    <input 
                                        type="number" 
                                        id="longitude"
                                        v-model="form.longitude"
                                        step="any"
                                        placeholder="115.1889"
                                        required
                                        @input="updateMapMarker"
                                    >
                                </div>
                            </div>

                            <div class="location-actions">
                                <button type="button" @click="getCurrentLocation" class="btn-outline">
                                    <i class="fas fa-location-arrow"></i>
                                    Use My Location
                                </button>
                                <button type="button" @click="centerMapToBali" class="btn-outline">
                                    <i class="fas fa-map"></i>
                                    Center to Bali
                                </button>
                            </div>
                        </div>

                        <!-- Appearance -->
                        <div class="form-group">
                            <h3>Appearance</h3>
                            
                            <div class="input-group">
                                <label for="icon">Icon</label>
                                <div class="icon-selector">
                                    <div 
                                        v-for="icon in availableIcons" 
                                        :key="icon.class"
                                        @click="form.icon = icon.class"
                                        :class="{ active: form.icon === icon.class }"
                                        class="icon-option"
                                    >
                                        <i :class="icon.class"></i>
                                        <span>{{ icon.name }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="color">Marker Color</label>
                                <div class="color-selector">
                                    <div 
                                        v-for="color in availableColors" 
                                        :key="color.value"
                                        @click="form.color = color.value"
                                        :class="{ active: form.color === color.value }"
                                        :style="{ backgroundColor: color.value }"
                                        class="color-option"
                                    >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="button" @click="deleteMarker" class="btn-danger">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                            <button type="button" @click="resetForm" class="btn-secondary">
                                <i class="fas fa-undo"></i>
                                Reset
                            </button>
                            <button type="submit" :disabled="loading" class="btn-primary">
                                <i class="fas fa-save"></i>
                                {{ loading ? 'Saving...' : 'Update Marker' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Map Preview -->
                <div class="map-section">
                    <div class="map-header">
                        <h3>Map Preview</h3>
                        <p>Click on the map to update marker location</p>
                    </div>
                    <div ref="mapContainer" class="map-container"></div>
                    <div class="map-info">
                        <div class="coordinate-display">
                            <span>Lat: {{ form.latitude || '-' }}</span>
                            <span>Lng: {{ form.longitude || '-' }}</span>
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
import GeoFeatureService from '../../services/GeoFeatureService.js';
import toast from '../../utils/toast.js';

export default {
    name: 'EditMarker',
    components: {
        AppLayout
    },
    data() {
        return {
            form: {
                nama: '',
                deskripsi: '',
                kategori_id: '',
                latitude: null,
                longitude: null,
                icon: 'fa-map-pin',
                color: '#3b82f6'
            },
            originalForm: {},
            categories: [],
            loading: true,
            isSaving: false,
            map: null,
            marker: null,
            availableIcons: [
                { class: 'fa-map-pin', name: 'Pin' },
                { class: 'fa-info-circle', name: 'Info' },
                { class: 'fa-star', name: 'Star' },
                { class: 'fa-heart', name: 'Heart' },
                { class: 'fa-camera', name: 'Camera' },
                { class: 'fa-building', name: 'Building' },
                { class: 'fa-tree', name: 'Tree' }
            ],
            availableColors: [
                { name: 'Blue', value: '#3b82f6' },
                { name: 'Red', value: '#ef4444' },
                { name: 'Green', value: '#10b981' },
                { name: 'Yellow', value: '#fbbf24' },
                { name: 'Purple', value: '#a855f7' },
                { name: 'Pink', value: '#ec4899' }
            ]
        };
    },
    async mounted() {
        await this.loadMarkerData();
        this.initMap();
    },
    methods: {
        async loadMarkerData() {
            try {
                const markerId = this.$route.params.id;
                const result = await GeoFeatureService.getFeatureById(markerId);
                
                if (result.data) {
                    const marker = result.data;
                    this.form = {
                        nama: marker.nama,
                        deskripsi: marker.deskripsi || '',
                        kategori_id: marker.kategori_id || '',
                        latitude: marker.coordinates[1],
                        longitude: marker.coordinates[0],
                        icon: marker.properties?.icon || 'fa-map-pin',
                        color: marker.properties?.color || '#3b82f6'
                    };
                    this.originalForm = JSON.parse(JSON.stringify(this.form));
                }
                
                this.categories = [
                    { id: 1, name: 'Tourist Attraction' },
                    { id: 2, name: 'Restaurant' },
                    { id: 3, name: 'Hotel' },
                    { id: 4, name: 'Hospital' },
                    { id: 5, name: 'School' }
                ];
                
                this.loading = false;
            } catch (error) {
                console.error('Error loading marker:', error);
                toast.error('Failed to load marker data', 'Error');
                this.$router.push('/markers');
            }
        },
        initMap() {
            this.map = L.map(this.$refs.mapContainer).setView(
                [this.form.latitude || -8.4095, this.form.longitude || 115.1889],
                12
            );

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);

            this.map.on('click', (e) => {
                this.form.latitude = parseFloat(e.latlng.lat.toFixed(6));
                this.form.longitude = parseFloat(e.latlng.lng.toFixed(6));
                this.updateMapMarker();
            });

            this.updateMapMarker();
        },
        updateMapMarker() {
            if (this.marker) {
                this.map.removeLayer(this.marker);
            }

            if (this.form.latitude && this.form.longitude) {
                this.marker = L.marker([this.form.latitude, this.form.longitude])
                    .addTo(this.map);
                this.map.setView([this.form.latitude, this.form.longitude], 12);
            }
        },
        getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    this.form.latitude = parseFloat(position.coords.latitude.toFixed(6));
                    this.form.longitude = parseFloat(position.coords.longitude.toFixed(6));
                    this.updateMapMarker();
                    toast.success('Location updated', 'Success');
                });
            } else {
                toast.error('Geolocation not supported', 'Error');
            }
        },
        centerMapToBali() {
            this.form.latitude = -8.4095;
            this.form.longitude = 115.1889;
            this.updateMapMarker();
        },
        resetForm() {
            this.form = JSON.parse(JSON.stringify(this.originalForm));
            this.updateMapMarker();
            toast.info('Form reset', 'Info');
        },
        async updateMarker() {
            if (!this.validateForm()) {
                return;
            }

            this.isSaving = true;
            try {
                const markerId = this.$route.params.id;
                const markerData = {
                    nama: this.form.nama,
                    deskripsi: this.form.deskripsi || '',
                    kategori_id: this.form.kategori_id ? parseInt(this.form.kategori_id) : null,
                    latitude: parseFloat(this.form.latitude),
                    longitude: parseFloat(this.form.longitude),
                    properties: { 
                        icon: this.form.icon, 
                        color: this.form.color 
                    }
                };

                const result = await GeoFeatureService.updateFeature(markerId, {
                    ...markerData,
                    geometry_type: 'marker',
                    coordinates: [markerData.longitude, markerData.latitude]
                });

                if (result.success) {
                    toast.success('Marker updated successfully!', 'Success');
                    this.$router.push('/markers');
                } else {
                    toast.error(result.message || 'Failed to update marker', 'Error');
                }
            } catch (error) {
                console.error('Error updating marker:', error);
                toast.error('Failed to update marker', 'Error');
            } finally {
                this.isSaving = false;
            }
        },
        async deleteMarker() {
            const confirmed = window.confirm(`Are you sure you want to delete "${this.form.nama}"?`);
            if (!confirmed) return;

            this.isSaving = true;
            try {
                const markerId = this.$route.params.id;
                await GeoFeatureService.deleteFeature(markerId);
                toast.success('Marker deleted successfully!', 'Success');
                this.$router.push('/markers');
            } catch (error) {
                console.error('Error deleting marker:', error);
                toast.error('Failed to delete marker', 'Error');
            } finally {
                this.isSaving = false;
            }
        },
        validateForm() {
            if (!this.form.nama.trim()) {
                toast.warning('Please enter marker name', 'Warning');
                return false;
            }

            if (!this.form.latitude || !this.form.longitude) {
                toast.warning('Please set marker location on map', 'Warning');
                return false;
            }

            return true;
        }
    }
}
</script>

<style scoped>
.edit-marker-container {
    padding: 2rem;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.header-content h1 {
    font-size: 2rem;
    font-weight: bold;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.header-content p {
    color: #6b7280;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn-secondary,
.btn-primary,
.btn-danger,
.btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.375rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    border: none;
}

.btn-secondary {
    background: white;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background: #f9fafb;
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

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-outline {
    background: white;
    color: #3b82f6;
    border: 1px solid #3b82f6;
}

.btn-outline:hover {
    background: #eff6ff;
}

.loading-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 400px;
    gap: 1rem;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #e5e7eb;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.form-layout {
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

.marker-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-group h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e5e7eb;
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.input-group label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.input-group input,
.input-group select,
.input-group textarea {
    padding: 0.625rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-family: inherit;
}

.input-group input:focus,
.input-group select:focus,
.input-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.coordinate-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.location-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.icon-selector {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}

.icon-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.2s;
    gap: 0.5rem;
}

.icon-option:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}

.icon-option.active {
    border-color: #3b82f6;
    background: #eff6ff;
}

.icon-option i {
    font-size: 1.5rem;
    color: #3b82f6;
}

.icon-option span {
    font-size: 0.75rem;
    color: #6b7280;
}

.color-selector {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 0.75rem;
}

.color-option {
    width: 100%;
    aspect-ratio: 1;
    border: 3px solid transparent;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.2s;
}

.color-option:hover {
    transform: scale(1.1);
}

.color-option.active {
    border-color: #1f2937;
    box-shadow: 0 0 0 2px white, 0 0 0 4px #1f2937;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.form-actions .btn-danger {
    margin-right: auto;
}

.map-section {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.map-header {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.map-header h3 {
    margin: 0 0 0.25rem 0;
    font-size: 1.125rem;
}

.map-header p {
    margin: 0;
    font-size: 0.875rem;
    opacity: 0.9;
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

.coordinate-display {
    display: flex;
    gap: 2rem;
    font-size: 0.875rem;
    color: #374151;
}

@media (max-width: 1024px) {
    .form-layout {
        grid-template-columns: 1fr;
    }

    .map-container {
        height: 400px;
    }
}
</style>
