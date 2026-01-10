<template>
    <AppLayout>
        <div class="create-marker-container">
            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1>Add New Marker</h1>
                    <p>Create a new marker on the map</p>
                </div>
                <div class="header-actions">
                    <router-link to="/markers" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Markers
                    </router-link>
                </div>
            </div>

            <div class="form-layout">
                <!-- Form Section -->
                <div class="form-section">
                    <form @submit.prevent="saveMarker" class="marker-form">
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
                                        v-model.number="form.latitude"
                                        step="0.000001"
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
                                        v-model.number="form.longitude"
                                        step="0.000001"
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
                                        @click="selectIcon(icon.class)"
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
                            <button type="button" @click="resetForm" class="btn-secondary">
                                <i class="fas fa-undo"></i>
                                Reset
                            </button>
                            <button type="submit" :disabled="loading" class="btn-primary">
                                <i class="fas fa-save"></i>
                                {{ loading ? 'Saving...' : 'Save Marker' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Map Section -->
                <div class="map-section">
                    <div class="map-header">
                        <h3>Select Location on Map</h3>
                        <p class="map-instructions">Click on the map to set marker location</p>
                    </div>
                    <div ref="mapContainer" class="map-container"></div>
                    <div class="map-info" :class="{ 'has-location': form.latitude && form.longitude }">
                        <i class="fas fa-info-circle"></i>
                        <span><strong>Selected:</strong> {{ selectedCoordinates }}</span>
                    </div>
                </div>
            </div> 
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../../components/AppLayout.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import GeoFeatureService from '../../services/GeoFeatureService.js';
import toast from '../../utils/toast.js';

export default {
    name: 'CreateMarker',
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
                icon: 'fas fa-map-marker-alt',
                color: '#3b82f6'
            },
            categories: [],
            map: null,
            marker: null,
            loading: false,
            availableIcons: [
                { class: 'fas fa-map-marker-alt', name: 'Default' },
                { class: 'fas fa-home', name: 'Home' },
                { class: 'fas fa-building', name: 'Building' },
                { class: 'fas fa-store', name: 'Store' },
                { class: 'fas fa-utensils', name: 'Restaurant' },
                { class: 'fas fa-bed', name: 'Hotel' },
                { class: 'fas fa-gas-pump', name: 'Gas Station' },
                { class: 'fas fa-hospital', name: 'Hospital' },
                { class: 'fas fa-graduation-cap', name: 'School' },
                { class: 'fas fa-tree', name: 'Park' },
                { class: 'fas fa-camera', name: 'Tourist Spot' },
                { class: 'fas fa-car', name: 'Parking' }
            ],
            availableLineStyles: [
            { 
                value: 'solid', 
                name: 'Solid', 
                dashArray: null,
                preview: '━━━━━━━'
            },
            { 
                value: 'dashed', 
                name: 'Dashed', 
                dashArray: '10, 10',
                preview: '━ ━ ━ ━'
            },
            { 
                value: 'dotted', 
                name: 'Dotted', 
                dashArray: '2, 8',
                preview: '• • • • •'
            },
            { 
                value: 'dashdot', 
                name: 'Dash-Dot', 
                dashArray: '10, 5, 2, 5',
                preview: '━ • ━ •'
            },
            { 
                value: 'longdash', 
                name: 'Long Dash', 
                dashArray: '20, 10',
                preview: '━━ ━━ ━━'
            }
            ],
            availableColors: [
                { value: '#3b82f6', name: 'Blue' },
                { value: '#ef4444', name: 'Red' },
                { value: '#10b981', name: 'Green' },
                { value: '#f59e0b', name: 'Yellow' },
                { value: '#8b5cf6', name: 'Purple' },
                { value: '#06b6d4', name: 'Cyan' },
                { value: '#f97316', name: 'Orange' },
                { value: '#84cc16', name: 'Lime' }
            ]
        }
    },
    computed: {
        selectedCoordinates() {
            if (this.form.latitude && this.form.longitude) {
                return `${parseFloat(this.form.latitude).toFixed(6)}, ${parseFloat(this.form.longitude).toFixed(6)}`;
            }
            return 'No location selected';
        }
    },
    async mounted() {
        await this.loadCategories();
        this.initMap();
    },
    watch: {
        'form.icon'(newVal, oldVal) {
            console.log('Watch: Icon changed from', oldVal, 'to', newVal);
            this.updateMapMarker();
        },
        'form.color'(newVal, oldVal) {
            console.log('Watch: Color changed from', oldVal, 'to', newVal);
            this.updateMapMarker();
        }
    },
    methods: {
        selectIcon(iconClass) {
            console.log('=== Icon Selection ===');
            console.log('Icon selected:', iconClass);
            console.log('Before:', this.form.icon);
            
            // Direct assignment works in Vue 3
            this.form.icon = iconClass;
            
            console.log('After:', this.form.icon);
            console.log('Icon changed:', this.form.icon === iconClass);
        },
        async loadCategories() {
            try {
                // Local backend categories
                this.categories = [
                    { id: 1, name: 'Temple' },
                    { id: 2, name: 'Nature' },
                    { id: 3, name: 'Beach' },
                    { id: 4, name: 'Mountain' },
                    { id: 5, name: 'Shopping' },
                    { id: 6, name: 'Kuliner' },
                    { id: 7, name: 'Hotel' },
                    { id: 8, name: 'Other' }
                ];
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        },
        initMap() {
            // Initialize map centered on Bali
            this.map = L.map(this.$refs.mapContainer).setView([-8.4095, 115.1889], 10);
            
            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(this.map);
            
            // Add click event to set marker location
            this.map.on('click', (e) => {
                console.log('Map clicked at:', e.latlng);
                const lat = parseFloat(e.latlng.lat.toFixed(6));
                const lng = parseFloat(e.latlng.lng.toFixed(6));
                
                // Direct assignment works in Vue 3
                this.form.latitude = lat;
                this.form.longitude = lng;
                
                console.log('Form updated:', this.form.latitude, this.form.longitude);
                
                // Update marker
                this.$nextTick(() => {
                    this.updateMapMarker();
                });
            });
        },
        updateMapMarker() {
            console.log('=== updateMapMarker called ===');
            console.log('Map exists:', !!this.map);
            console.log('Latitude:', this.form.latitude, 'Longitude:', this.form.longitude);
            
            if (!this.map) {
                console.error('Map not initialized!');
                return;
            }
            
            if (this.form.latitude && this.form.longitude) {
                const lat = parseFloat(this.form.latitude);
                const lng = parseFloat(this.form.longitude);
                
                console.log('Parsed lat:', lat, 'lng:', lng);
                console.log('isNaN check:', !isNaN(lat) && !isNaN(lng));
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    // Remove existing marker
                    if (this.marker) {
                        console.log('Removing existing marker');
                        this.map.removeLayer(this.marker);
                    }
                    
                    console.log('Creating new marker with icon:', this.form.icon, 'color:', this.form.color);
                    
                    // Create custom icon with selected color
                    const customIcon = L.divIcon({
                        className: 'custom-marker-icon',
                        html: `<div style="
                            background-color: ${this.form.color};
                            width: 32px;
                            height: 32px;
                            border-radius: 50% 50% 50% 0;
                            transform: rotate(-45deg);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            border: 2px solid white;
                            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
                        ">
                            <i class="${this.form.icon}" style="
                                color: white;
                                font-size: 14px;
                                transform: rotate(45deg);
                            "></i>
                        </div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32]
                    });
                    
                    // Add new marker with custom icon
                    this.marker = L.marker([lat, lng], { icon: customIcon }).addTo(this.map);
                    
                    console.log('Marker added to map:', this.marker);
                    
                    // Center map on marker
                    this.map.setView([lat, lng], Math.max(this.map.getZoom(), 13));
                    
                    console.log('Map view updated to:', lat, lng);
                } else {
                    console.log('Invalid coordinates - NaN detected');
                }
            } else {
                console.log('No latitude or longitude in form');
            }
        },
        getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.form.latitude = position.coords.latitude.toFixed(6);
                        this.form.longitude = position.coords.longitude.toFixed(6);
                        this.updateMapMarker();
                    },
                    (error) => {
                        console.error('Error getting location:', error);
                        alert('Unable to get your current location. Please check your browser settings.');
                    }
                );
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        },
        centerMapToBali() {
            this.form.latitude = -8.4095;
            this.form.longitude = 115.1889;
            this.updateMapMarker();
        },
        resetForm() {
            this.form = {
                nama: '',
                deskripsi: '',
                kategori_id: '',
                latitude: null,
                longitude: null,
                icon: 'fas fa-map-marker-alt',
                color: '#3b82f6'
            };
            
            if (this.marker) {
                this.map.removeLayer(this.marker);
                this.marker = null;
            }
            
            this.map.setView([-8.4095, 115.1889], 10);
        },
        async saveMarker() {
            if (!this.validateForm()) {
                return;
            }
            
            this.loading = true;
            try {
                console.log('Saving marker to local backend:', this.form);
                
                // Prepare data for local API
                const markerData = {
                    nama: this.form.nama,
                    deskripsi: this.form.deskripsi || '',
                    kategori_id: parseInt(this.form.kategori_id),
                    latitude: parseFloat(this.form.latitude),
                    longitude: parseFloat(this.form.longitude),
                    properties: {
                        icon: this.form.icon,
                        color: this.form.color
                    }
                };
                
                // Call Local Backend API
                const result = await GeoFeatureService.createMarker(markerData);
                
                if (result.success) {
                    toast.success('Marker berhasil ditambahkan!', 'Sukses');
                    this.$router.push('/markers');
                } else {
                    toast.error(result.message || 'Gagal menyimpan marker', 'Error');
                }
            } catch (error) {
                console.error('Error saving marker:', error);
                toast.error('Gagal menyimpan marker. Silakan coba lagi.', 'Error');
            } finally {
                this.loading = false;
            }
        },
        validateForm() {
            if (!this.form.nama.trim()) {
                toast.warning('Silakan masukkan nama marker.', 'Validasi');
                return false;
            }
            
            if (!this.form.kategori_id) {
                toast.warning('Silakan pilih kategori.', 'Validasi');
                return false;
            }
            
            // Remove desa_id validation for local backend
            
            if (!this.form.latitude || !this.form.longitude) {
                toast.warning('Silakan tentukan lokasi marker pada peta.', 'Validasi');
                return false;
            }
            
            return true;
        }
    }
}
</script>

<style scoped>
.create-marker-container {
    max-width: 1400px;
    margin: 0 auto;
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

.btn-primary,
.btn-secondary,
.btn-outline {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
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
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

.btn-outline {
    background: transparent;
    color: #3b82f6;
    border: 1px solid #3b82f6;
}

.btn-outline:hover {
    background: #3b82f6;
    color: white;
}

.form-layout {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
}

.form-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.marker-form {
    padding: 2rem;
}

.form-group {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #f3f4f6;
}

.form-group:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.form-group h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 1rem 0;
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
.input-group textarea,
.input-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.875rem;
    transition: border-color 0.2s;
}

.input-group input:focus,
.input-group textarea:focus,
.input-group select:focus {
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
    display: flex;
    gap: 0.75rem;
    margin-top: 1rem;
}

.icon-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.icon-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    user-select: none;
}

.icon-option:hover {
    border-color: #93c5fd;
    background: #eff6ff;
    cursor: pointer;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
}

.icon-option.active {
    border-color: #3b82f6;
    background: #dbeafe;
    border-width: 3px;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.icon-option i {
    font-size: 1.25rem;
    margin-bottom: 0.25rem;
    color: #6b7280;
    transition: all 0.3s ease;
}

.icon-option:active {
    transform: scale(0.95);
}

.icon-option.active i {
    color: #2563eb;
    transform: scale(1.1);
}

.icon-option span {
    font-size: 0.75rem;
    color: #6b7280;
    transition: all 0.2s;
}

.icon-option.active span {
    color: #2563eb;
    font-weight: 600;
}

.color-selector {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.color-option {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    border: 3px solid transparent;
    transition: all 0.2s;
}

.color-option:hover,
.color-option.active {
    border-color: #374151;
    transform: scale(1.1);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #f3f4f6;
}

.map-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    overflow: hidden;
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.map-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.map-header h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.25rem 0;
}

.map-header p {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}

.map-container {
    height: 400px;
    width: 100%;
}

.map-info {
    padding: 1rem 1.5rem;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
    transition: all 0.3s ease;
}

.map-info.has-location {
    background: #dbeafe;
    border-top-color: #3b82f6;
    color: #1e40af;
}

.map-info i {
    color: #6b7280;
}

.map-info.has-location i {
    color: #3b82f6;
}

.map-info strong {
    font-weight: 600;
}

.coordinate-display {
    display: flex;
    justify-content: space-between;
    font-size: 0.875rem;
    color: #6b7280;
    font-family: monospace;
}

/* Dark mode styles */
:global(.dark) .header-content h1,
:global(.dark) .form-group h3,
:global(.dark) .map-header h3 {
    color: #f9fafb;
}

:global(.dark) .header-content p,
:global(.dark) .map-header p {
    color: #d1d5db;
}

:global(.dark) .form-section,
:global(.dark) .map-section {
    background: #1f2937;
    border-color: #374151;
}

:global(.dark) .form-group {
    border-bottom-color: #374151;
}

:global(.dark) .map-header,
:global(.dark) .form-actions {
    border-color: #374151;
}

:global(.dark) .input-group label {
    color: #d1d5db;
}

:global(.dark) .input-group input,
:global(.dark) .input-group textarea,
:global(.dark) .input-group select {
    background: #374151;
    border-color: #4b5563;
    color: #f9fafb;
}

:global(.dark) .input-group input:focus,
:global(.dark) .input-group textarea:focus,
:global(.dark) .input-group select:focus {
    border-color: #60a5fa;
    box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
}

:global(.dark) .icon-option {
    border-color: #4b5563;
    background: #374151;
}

:global(.dark) .icon-option:hover,
:global(.dark) .icon-option.active {
    border-color: #60a5fa;
    background: #1e40af;
}

:global(.dark) .icon-option i,
:global(.dark) .icon-option span {
    color: #d1d5db;
}

:global(.dark) .icon-option.active i,
:global(.dark) .icon-option.active span {
    color: #93c5fd;
}

:global(.dark) .map-info {
    background: #374151;
    border-top-color: #4b5563;
}

:global(.dark) .coordinate-display {
    color: #d1d5db;
}

:global(.dark) .btn-secondary {
    background: #374151;
    color: #d1d5db;
    border-color: #4b5563;
}

:global(.dark) .btn-secondary:hover {
    background: #4b5563;
}

@media (max-width: 1024px) {
    .form-layout {
        grid-template-columns: 1fr;
    }
    
    .map-section {
        position: static;
    }
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .coordinate-inputs {
        grid-template-columns: 1fr;
    }
    
    .location-actions {
        flex-direction: column;
    }
    
    .icon-selector {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>
