<template>
    <AppLayout>
        <div class="create-line-container">
            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1>Add New Line</h1>
                    <p>Create a new line or polyline on the map</p>
                </div>
                <div class="header-actions">
                    <router-link to="/lines" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Lines
                    </router-link>
                </div>
            </div>

            <div class="form-layout">
                <!-- Form Section -->
                <div class="form-section">
                    <form @submit.prevent="saveLine" class="line-form">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <h3>Basic Information</h3>
                            
                            <div class="input-group">
                                <label for="name">Line Name *</label>
                                <input 
                                    type="text" 
                                    id="name"
                                    v-model="form.name"
                                    placeholder="Enter line name"
                                    required
                                >
                            </div>

                            <div class="input-group">
                                <label for="description">Description</label>
                                <textarea 
                                    id="description"
                                    v-model="form.description"
                                    placeholder="Enter line description"
                                    rows="3"
                                ></textarea>
                            </div>

                            <div class="input-group">
                                <label for="category">Category *</label>
                                <select id="category" v-model="form.category_id" required>
                                    <option value="">Select a category</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Line Coordinates -->
                        <div class="form-group">
                            <h3>Line Coordinates</h3>
                            <p class="help-text">Click on the map to add points to your line. Minimum 2 points required.</p>
                            
                            <div class="coordinates-list">
                                <div class="coordinates-header">
                                    <span>Point</span>
                                    <span>Latitude</span>
                                    <span>Longitude</span>
                                    <span>Actions</span>
                                </div>
                                <div 
                                    v-for="(coord, index) in form.coordinates" 
                                    :key="index"
                                    class="coordinate-row"
                                >
                                    <span class="point-number">{{ index + 1 }}</span>
                                    <input 
                                        type="number" 
                                        v-model="coord.lat"
                                        step="any"
                                        @input="updateMapLine"
                                        class="coord-input"
                                    >
                                    <input 
                                        type="number" 
                                        v-model="coord.lng"
                                        step="any"
                                        @input="updateMapLine"
                                        class="coord-input"
                                    >
                                    <div class="coord-actions">
                                        <button 
                                            type="button" 
                                            @click="removeCoordinate(index)"
                                            :disabled="form.coordinates.length <= 2"
                                            class="btn-remove"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div v-if="form.coordinates.length === 0" class="empty-coordinates">
                                    <i class="fas fa-map-pin"></i>
                                    <span>Click on the map to add points</span>
                                </div>
                            </div>

                            <div class="coordinate-actions">
                                <button type="button" @click="clearCoordinates" class="btn-outline">
                                    <i class="fas fa-trash"></i>
                                    Clear All Points
                                </button>
                                <button type="button" @click="addManualCoordinate" class="btn-outline">
                                    <i class="fas fa-plus"></i>
                                    Add Point Manually
                                </button>
                            </div>
                        </div>

                        <!-- Appearance -->
                        <div class="form-group">
                            <h3>Appearance</h3>
                            
                            <div class="input-group">
                                <label for="color">Line Color</label>
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
                                <input 
                                    type="color" 
                                    v-model="form.color"
                                    @change="updateMapLine"
                                    class="color-picker"
                                >
                            </div>

                            <div class="input-group">
                                <label for="width">Line Width</label>
                                <div class="width-selector">
                                    <input 
                                        type="range" 
                                        id="width"
                                        v-model="form.width"
                                        min="1" 
                                        max="10" 
                                        step="1"
                                        @input="updateMapLine"
                                        class="width-slider"
                                    >
                                    <span class="width-value">{{ form.width }}px</span>
                                </div>
                                <div class="width-preview">
                                    <div 
                                        class="preview-line" 
                                        :style="{ 
                                            backgroundColor: form.color, 
                                            height: form.width + 'px' 
                                        }"
                                    ></div>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="opacity">Opacity</label>
                                <div class="opacity-selector">
                                    <input 
                                        type="range" 
                                        id="opacity"
                                        v-model="form.opacity"
                                        min="0.1" 
                                        max="1" 
                                        step="0.1"
                                        @input="updateMapLine"
                                        class="opacity-slider"
                                    >
                                    <span class="opacity-value">{{ Math.round(form.opacity * 100) }}%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="form-group">
                            <h3>Additional Information</h3>
                            
                            <div class="input-group">
                                <label for="length">Estimated Length (km)</label>
                                <input 
                                    type="number" 
                                    id="length"
                                    v-model="form.length"
                                    step="0.01"
                                    placeholder="Auto-calculated"
                                    readonly
                                >
                                <small>This will be automatically calculated based on coordinates</small>
                            </div>

                            <div class="input-group">
                                <label for="tags">Tags</label>
                                <input 
                                    type="text" 
                                    id="tags"
                                    v-model="form.tags"
                                    placeholder="Enter tags separated by commas"
                                >
                                <small>e.g., highway, main-road, scenic-route</small>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="button" @click="resetForm" class="btn-secondary">
                                <i class="fas fa-undo"></i>
                                Reset
                            </button>
                            <button type="submit" :disabled="loading || form.coordinates.length < 2" class="btn-primary">
                                <i class="fas fa-save"></i>
                                {{ loading ? 'Saving...' : 'Save Line' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Map Preview -->
                <div class="map-section">
                    <div class="map-header">
                        <h3>Map Preview</h3>
                        <p>Click on the map to add points to your line</p>
                    </div>
                    <div ref="mapContainer" class="map-container"></div>
                    <div class="map-info">
                        <div class="line-stats">
                            <span>Points: {{ form.coordinates.length }}</span>
                            <span>Length: {{ calculatedLength.toFixed(2) }} km</span>
                        </div>
                        <div class="map-controls">
                            <button @click="centerMapToBali" class="map-btn">
                                <i class="fas fa-map"></i>
                                Center to Bali
                            </button>
                            <button @click="fitLineToView" class="map-btn" :disabled="form.coordinates.length === 0">
                                <i class="fas fa-expand-arrows-alt"></i>
                                Fit to View
                            </button>
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

export default {
    name: 'CreateLine',
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
                color: '#3b82f6',
                width: 3,
                opacity: 0.8,
                length: 0,
                tags: ''
            },
            categories: [],
            map: null,
            polyline: null,
            loading: false,
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
        calculatedLength() {
            if (this.form.coordinates.length < 2) return 0;
            
            let totalDistance = 0;
            for (let i = 0; i < this.form.coordinates.length - 1; i++) {
                const coord1 = this.form.coordinates[i];
                const coord2 = this.form.coordinates[i + 1];
                totalDistance += this.calculateDistance(coord1.lat, coord1.lng, coord2.lat, coord2.lng);
            }
            
            this.form.length = totalDistance;
            return totalDistance;
        }
    },
    async mounted() {
        await this.loadCategories();
        this.initMap();
    },
    methods: {
        async loadCategories() {
            try {
                // TODO: Replace with actual API call
                this.categories = [
                    { id: 1, name: 'Main Road' },
                    { id: 2, name: 'Highway' },
                    { id: 3, name: 'Local Road' },
                    { id: 4, name: 'Trail' },
                    { id: 5, name: 'River' },
                    { id: 6, name: 'Border' }
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
            
            // Add click event to add coordinates
            this.map.on('click', (e) => {
                this.addCoordinate(e.latlng.lat, e.latlng.lng);
            });
        },
        addCoordinate(lat, lng) {
            this.form.coordinates.push({
                lat: parseFloat(lat.toFixed(6)),
                lng: parseFloat(lng.toFixed(6))
            });
            this.updateMapLine();
        },
        addManualCoordinate() {
            const lat = prompt('Enter latitude:');
            const lng = prompt('Enter longitude:');
            
            if (lat && lng && !isNaN(lat) && !isNaN(lng)) {
                this.addCoordinate(parseFloat(lat), parseFloat(lng));
            }
        },
        removeCoordinate(index) {
            this.form.coordinates.splice(index, 1);
            this.updateMapLine();
        },
        clearCoordinates() {
            if (confirm('Are you sure you want to clear all points?')) {
                this.form.coordinates = [];
                this.updateMapLine();
            }
        },
        updateMapLine() {
            // Remove existing polyline
            if (this.polyline) {
                this.map.removeLayer(this.polyline);
            }
            
            // Add new polyline if we have at least 2 points
            if (this.form.coordinates.length >= 2) {
                const latlngs = this.form.coordinates.map(coord => [coord.lat, coord.lng]);
                
                this.polyline = L.polyline(latlngs, {
                    color: this.form.color,
                    weight: this.form.width,
                    opacity: this.form.opacity,
                    lineJoin: 'round',
                    lineCap: 'round'
                }).addTo(this.map);
            }
        },
        calculateDistance(lat1, lng1, lat2, lng2) {
            const R = 6371; // Earth's radius in kilometers
            const dLat = this.toRadians(lat2 - lat1);
            const dLng = this.toRadians(lng2 - lng1);
            const a = 
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(this.toRadians(lat1)) * Math.cos(this.toRadians(lat2)) *
                Math.sin(dLng / 2) * Math.sin(dLng / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        },
        toRadians(degrees) {
            return degrees * (Math.PI / 180);
        },
        centerMapToBali() {
            this.map.setView([-8.4095, 115.1889], 10);
        },
        fitLineToView() {
            if (this.form.coordinates.length > 0) {
                const latlngs = this.form.coordinates.map(coord => [coord.lat, coord.lng]);
                const bounds = L.latLngBounds(latlngs);
                this.map.fitBounds(bounds, { padding: [20, 20] });
            }
        },
        resetForm() {
            this.form = {
                name: '',
                description: '',
                category_id: '',
                coordinates: [],
                color: '#3b82f6',
                width: 3,
                opacity: 0.8,
                length: 0,
                tags: ''
            };
            
            if (this.polyline) {
                this.map.removeLayer(this.polyline);
                this.polyline = null;
            }
            
            this.map.setView([-8.4095, 115.1889], 10);
        },
        async saveLine() {
            if (!this.validateForm()) {
                return;
            }
            
            this.loading = true;
            try {
                // TODO: Replace with actual API call
                console.log('Saving line:', this.form);
                
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                alert('Line saved successfully!');
                this.$router.push('/lines');
            } catch (error) {
                console.error('Error saving line:', error);
                alert('Error saving line. Please try again.');
            } finally {
                this.loading = false;
            }
        },
        validateForm() {
            if (!this.form.name.trim()) {
                alert('Please enter a line name.');
                return false;
            }
            
            if (!this.form.category_id) {
                alert('Please select a category.');
                return false;
            }
            
            if (this.form.coordinates.length < 2) {
                alert('Please add at least 2 points to create a line.');
                return false;
            }
            
            return true;
        }
    }
}
</script>

<style scoped>
/* Reuse most styles from markers/Create.vue with line-specific modifications */
.create-line-container {
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
.btn-outline,
.map-btn {
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
    font-size: 0.875rem;
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
    padding: 0.5rem 1rem;
}

.btn-outline:hover {
    background: #3b82f6;
    color: white;
}

.map-btn {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 0.5rem 1rem;
    font-size: 0.75rem;
}

.map-btn:hover:not(:disabled) {
    background: #e5e7eb;
}

.map-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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

.line-form {
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

.help-text {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 1rem;
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

.input-group small {
    display: block;
    font-size: 0.75rem;
    color: #9ca3af;
    margin-top: 0.25rem;
}

/* Coordinates List */
.coordinates-list {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 1rem;
}

.coordinates-header {
    display: grid;
    grid-template-columns: 60px 1fr 1fr 80px;
    gap: 0.5rem;
    padding: 0.75rem;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.coordinate-row {
    display: grid;
    grid-template-columns: 60px 1fr 1fr 80px;
    gap: 0.5rem;
    padding: 0.75rem;
    border-bottom: 1px solid #f3f4f6;
    align-items: center;
}

.coordinate-row:last-child {
    border-bottom: none;
}

.point-number {
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    text-align: center;
}

.coord-input {
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    font-size: 0.75rem;
    font-family: monospace;
}

.coord-actions {
    display: flex;
    justify-content: center;
}

.btn-remove {
    padding: 0.25rem;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-remove:hover:not(:disabled) {
    background: #dc2626;
}

.btn-remove:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.empty-coordinates {
    padding: 2rem;
    text-align: center;
    color: #9ca3af;
}

.empty-coordinates i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.coordinate-actions {
    display: flex;
    gap: 0.75rem;
}

/* Color Selector */
.color-selector {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
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

.color-picker {
    width: 60px;
    height: 40px;
    padding: 0;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    cursor: pointer;
}

/* Width Selector */
.width-selector {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.75rem;
}

.width-slider {
    flex: 1;
}

.width-value {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    min-width: 40px;
}

.width-preview {
    margin-top: 0.5rem;
}

.preview-line {
    width: 100px;
    border-radius: 2px;
    transition: all 0.2s;
}

/* Opacity Selector */
.opacity-selector {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.opacity-slider {
    flex: 1;
}

.opacity-value {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    min-width: 40px;
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
}

.line-stats {
    display: flex;
    justify-content: space-between;
    font-size: 0.875rem;
    color: #6b7280;
    font-family: monospace;
    margin-bottom: 0.75rem;
}

.map-controls {
    display: flex;
    gap: 0.5rem;
}

/* Dark mode styles */
:global(.dark) .header-content h1,
:global(.dark) .form-group h3,
:global(.dark) .map-header h3 {
    color: #f9fafb;
}

:global(.dark) .header-content p,
:global(.dark) .map-header p,
:global(.dark) .help-text {
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

:global(.dark) .coordinates-list {
    border-color: #4b5563;
}

:global(.dark) .coordinates-header {
    background: #374151;
    border-bottom-color: #4b5563;
    color: #d1d5db;
}

:global(.dark) .coordinate-row {
    border-bottom-color: #4b5563;
}

:global(.dark) .coord-input {
    background: #4b5563;
    border-color: #6b7280;
    color: #f9fafb;
}

:global(.dark) .map-info {
    background: #374151;
    border-top-color: #4b5563;
}

:global(.dark) .line-stats {
    color: #d1d5db;
}

:global(.dark) .btn-secondary,
:global(.dark) .map-btn {
    background: #374151;
    color: #d1d5db;
    border-color: #4b5563;
}

:global(.dark) .btn-secondary:hover,
:global(.dark) .map-btn:hover:not(:disabled) {
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
    
    .coordinate-actions {
        flex-direction: column;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .coordinates-header,
    .coordinate-row {
        grid-template-columns: 40px 1fr 1fr 60px;
        font-size: 0.75rem;
    }
    
    .map-controls {
        flex-direction: column;
    }
}
</style>
