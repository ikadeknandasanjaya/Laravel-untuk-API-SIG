<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ featureData ? featureData.nama : 'Map View' }}</h1>
                    <p class="mt-1 text-sm text-gray-500">{{ getFeatureTypeLabel() }} detail view</p>
                </div>
                <div class="flex gap-3">
                    <button @click="$router.back()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
                    </button>
                    <router-link v-if="featureData" :to="getEditRoute()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-pen mr-2"></i>
                        Edit
                    </router-link>
                </div>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                <p class="mt-4 text-gray-600">Loading data...</p>
            </div>

            <template v-else>
                <!-- Map -->
                <div class="mb-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-map text-blue-600 mr-2"></i>
                                Interactive Map
                            </h2>
                        </div>
                        <div class="p-4">
                            <div ref="mapContainer" class="w-full h-96 rounded-lg border border-gray-200"></div>
                        </div>
                    </div>
                </div>

                <!-- Content Grid -->
                <div v-if="featureData" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                    <i :class="getFeatureIcon()" class="text-blue-600 mr-2"></i>
                                    Basic Information
                                </h2>
                            </div>
                            <div class="px-6 py-4">
                                <dl class="space-y-3">
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <dt class="text-sm text-gray-600">Name</dt>
                                        <dd class="text-sm font-semibold text-gray-900">{{ featureData.nama || '-' }}</dd>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <dt class="text-sm text-gray-600">Type</dt>
                                        <dd class="text-sm font-semibold text-gray-900">{{ getFeatureTypeLabel() }}</dd>
                                    </div>
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <dt class="text-sm text-gray-600">Category</dt>
                                        <dd class="text-sm font-semibold text-gray-900">{{ getCategoryName() }}</dd>
                                    </div>
                                    <div class="flex justify-between py-2" v-if="featureData.geometry_type === 'marker'">
                                        <dt class="text-sm text-gray-600">Coordinates</dt>
                                        <dd class="text-sm font-semibold text-gray-900 font-mono">
                                            {{ getCoordinates() }}
                                        </dd>
                                    </div>
                                    <div class="flex justify-between py-2" v-if="featureData.geometry_type === 'line'">
                                        <dt class="text-sm text-gray-600">Length</dt>
                                        <dd class="text-sm font-semibold text-gray-900">
                                            {{ featureData.properties?.length ? featureData.properties.length.toFixed(2) + ' km' : '-' }}
                                        </dd>
                                    </div>
                                    <div class="flex justify-between py-2" v-if="featureData.geometry_type === 'circle'">
                                        <dt class="text-sm text-gray-600">Radius</dt>
                                        <dd class="text-sm font-semibold text-gray-900">
                                            {{ featureData.properties?.radius ? featureData.properties.radius + ' m' : '-' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-align-left text-green-600 mr-2"></i>
                                    Description
                                </h2>
                            </div>
                            <div class="px-6 py-4">
                                <p class="text-sm text-gray-700">{{ featureData.deskripsi || 'No description available' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Appearance -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-palette text-purple-600 mr-2"></i>
                                    Appearance
                                </h2>
                            </div>
                            <div class="px-6 py-4 space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100" v-if="featureData.properties?.icon">
                                    <dt class="text-sm text-gray-600">Icon</dt>
                                    <dd class="text-sm font-semibold text-gray-900">
                                        <i :class="featureData.properties.icon" class="text-lg"></i>
                                    </dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100" v-if="featureData.properties?.color">
                                    <dt class="text-sm text-gray-600">Color</dt>
                                    <dd class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded" :style="{ backgroundColor: featureData.properties.color }"></div>
                                        <span class="text-sm font-mono">{{ featureData.properties.color }}</span>
                                    </dd>
                                </div>
                                <div class="flex justify-between py-2" v-if="featureData.properties?.opacity">
                                    <dt class="text-sm text-gray-600">Opacity</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ Math.round(featureData.properties.opacity * 100) }}%</dd>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-base font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-tools text-orange-600 mr-2"></i>
                                    Quick Actions
                                </h2>
                            </div>
                            <div class="px-6 py-4">
                                <button @click="handleDelete" class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100">
                                    <i class="fas fa-trash mr-2"></i>
                                    Delete {{ getFeatureTypeLabel() }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No Data State -->
                <div v-else class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                    <i class="fas fa-info-circle text-4xl text-yellow-500 mb-4"></i>
                    <p class="text-gray-600 mb-2">No data to display</p>
                    <p class="text-xs text-gray-500 mb-4">The requested feature could not be found</p>
                    <button @click="$router.back()" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                        Go Back
                    </button>
                </div>
            </template>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../components/AppLayout.vue';
import L from 'leaflet';
import GeoFeatureService from '../services/GeoFeatureService.js';

export default {
    name: 'MapPage',
    components: {
        AppLayout,
    },
    data() {
        return {
            map: null,
            featureData: null,
            loading: true,
            categories: [],
        };
    },
    mounted() {
        this.loadCategories();
        this.loadDataFromQuery();
    },
    methods: {
        initMap() {
            if (!this.$refs.mapContainer) {
                console.error('Map container not found');
                return;
            }
            
            if (this.map) {
                this.map.remove(); // Remove existing map if any
            }
            
            // Don't set view here, let displayFeatureOnMap handle it
            this.map = L.map(this.$refs.mapContainer);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
            }).addTo(this.map);
            
            // Set default view if no feature data
            if (!this.featureData) {
                this.map.setView([-8.4095, 115.1889], 10);
            }
        },
        async loadCategories() {
            try {
                const response = await GeoFeatureService.getCategories();
                this.categories = response.data || [];
            } catch (error) {
                console.error('Failed to load categories:', error);
            }
        },
        async loadDataFromQuery() {
            const query = this.$route.query;
            this.loading = true;
            
            try {
                // Check if marker query parameter exists
                if (query.marker) {
                    const markerResult = await GeoFeatureService.getFeatureById(query.marker);
                    if (markerResult.data) {
                        this.featureData = markerResult.data;
                    }
                }
                
                // Check if line query parameter exists
                if (query.line) {
                    const lineResult = await GeoFeatureService.getFeatureById(query.line);
                    if (lineResult.data && lineResult.data.coordinates) {
                        this.featureData = lineResult.data;
                    }
                }
                
                // Check if circle query parameter exists
                if (query.circle) {
                    const circleResult = await GeoFeatureService.getFeatureById(query.circle);
                    if (circleResult.data) {
                        this.featureData = circleResult.data;
                    }
                }
                
                // Check if polygon query parameter exists
                if (query.polygon) {
                    const polygonResult = await GeoFeatureService.getFeatureById(query.polygon);
                    if (polygonResult.data && polygonResult.data.coordinates) {
                        this.featureData = polygonResult.data;
                    }
                }
            } catch (error) {
                console.error('Error loading data from query:', error);
            } finally {
                this.loading = false;
                // Initialize map after loading is done and display feature
                this.$nextTick(() => {
                    this.initMap();
                    this.displayFeatureOnMap();
                });
            }
        },
        displayFeatureOnMap() {
            if (!this.featureData || !this.map) return;
            
            const query = this.$route.query;
            
            if (this.featureData.geometry_type === 'marker') {
                this.displayMarker(this.featureData);
                const lat = this.featureData.coordinates[1];
                const lng = this.featureData.coordinates[0];
                this.map.setView([lat, lng], parseInt(query.zoom) || 15);
            } else if (this.featureData.geometry_type === 'line' || this.featureData.geometry_type === 'polyline') {
                this.displayPolyline(this.featureData);
                const bounds = L.latLngBounds(
                    this.featureData.coordinates.map(coord => [coord[1], coord[0]])
                );
                this.map.fitBounds(bounds);
            } else if (this.featureData.geometry_type === 'circle') {
                this.displayCircle(this.featureData);
                const lat = this.featureData.coordinates[1];
                const lng = this.featureData.coordinates[0];
                this.map.setView([lat, lng], parseInt(query.zoom) || 13);
            } else if (this.featureData.geometry_type === 'polygon') {
                this.displayPolygon(this.featureData);
                const bounds = L.latLngBounds(
                    this.featureData.coordinates.map(coord => [coord[1], coord[0]])
                );
                this.map.fitBounds(bounds);
            }
        },
        displayMarker(markerData) {
            // Get color and icon from properties or use defaults
            const color = markerData.properties?.color || '#3b82f6';
            const iconClass = markerData.properties?.icon || 'fas fa-map-marker-alt';
            
            // Create custom icon with color and icon
            const icon = L.divIcon({
                className: 'custom-marker-icon',
                html: `<div style="
                    background-color: ${color};
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
                    <i class="${iconClass}" style="
                        color: white;
                        font-size: 14px;
                        transform: rotate(45deg);
                    "></i>
                </div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32]
            });
            
            const marker = L.marker([markerData.coordinates[1], markerData.coordinates[0]], { icon })
                .bindPopup(`<strong>${markerData.nama}</strong><br>${markerData.deskripsi || ''}`)
                .addTo(this.map)
                .openPopup();
        },
        displayPolyline(lineData) {
            const coords = lineData.coordinates.map(coord => [coord[1], coord[0]]);
            const polyline = L.polyline(coords, {
                color: lineData.properties?.color || '#3b82f6',
                weight: lineData.properties?.width || 3,
                opacity: lineData.properties?.opacity || 0.8,
            })
                .bindPopup(`<strong>${lineData.nama}</strong><br>${lineData.deskripsi || ''}`)
                .addTo(this.map)
                .openPopup();
        },
        displayCircle(circleData) {
            const radius = circleData.properties?.radius || 1000;
            const circle = L.circle([circleData.coordinates[1], circleData.coordinates[0]], {
                radius: radius,
                color: circleData.properties?.color || '#3b82f6',
                fillColor: circleData.properties?.color || '#3b82f6',
                fillOpacity: circleData.properties?.opacity || 0.5,
            })
                .bindPopup(`<strong>${circleData.nama}</strong><br>${circleData.deskripsi || ''}`)
                .addTo(this.map)
                .openPopup();
        },
        displayPolygon(polygonData) {
            const coords = polygonData.coordinates.map(coord => [coord[1], coord[0]]);
            const polygon = L.polygon(coords, {
                color: polygonData.properties?.strokeColor || '#1e40af',
                fillColor: polygonData.properties?.fillColor || '#3b82f6',
                fillOpacity: polygonData.properties?.opacity || 0.5,
                weight: polygonData.properties?.strokeWidth || 2,
            })
                .bindPopup(`<strong>${polygonData.nama}</strong><br>${polygonData.deskripsi || ''}`)
                .addTo(this.map)
                .openPopup();
        },
        getFeatureTypeLabel() {
            if (!this.featureData) return '';
            
            const typeMap = {
                'marker': 'Marker',
                'line': 'Line/Polyline',
                'polyline': 'Line/Polyline',
                'circle': 'Circle',
                'polygon': 'Polygon'
            };
            
            return typeMap[this.featureData.geometry_type] || this.featureData.geometry_type;
        },
        getFeatureIcon() {
            if (!this.featureData) return 'fas fa-map-marker-alt';
            
            const iconMap = {
                'marker': 'fas fa-map-marker-alt',
                'line': 'fas fa-route',
                'polyline': 'fas fa-route',
                'circle': 'fas fa-circle',
                'polygon': 'fas fa-draw-polygon'
            };
            
            return iconMap[this.featureData.geometry_type] || 'fas fa-map-marker-alt';
        },
        getCategoryName() {
            if (!this.featureData || !this.featureData.category_id) return '-';
            
            const category = this.categories.find(cat => cat.id === this.featureData.category_id);
            return category ? category.nama : '-';
        },
        getCoordinates() {
            if (!this.featureData || !this.featureData.coordinates) return '-';
            
            const lng = this.featureData.coordinates[0].toFixed(6);
            const lat = this.featureData.coordinates[1].toFixed(6);
            return `${lat}, ${lng}`;
        },
        getEditRoute() {
            if (!this.featureData) return '#';
            
            const routeMap = {
                'marker': '/markers/',
                'line': '/lines/',
                'polyline': '/lines/',
                'circle': '/circles/',
                'polygon': '/polygons/'
            };
            
            const basePath = routeMap[this.featureData.geometry_type] || '/markers/';
            return basePath + this.featureData.id + '/edit';
        },
        async handleDelete() {
            if (!this.featureData) return;
            
            if (!confirm(`Are you sure you want to delete this ${this.getFeatureTypeLabel()}?`)) {
                return;
            }
            
            try {
                await GeoFeatureService.deleteFeature(this.featureData.id);
                alert('Feature deleted successfully');
                this.$router.back();
            } catch (error) {
                console.error('Error deleting feature:', error);
                alert('Failed to delete feature');
            }
        }
    },
};
</script>

