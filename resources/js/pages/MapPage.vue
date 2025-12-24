<template>
    <Layout>
        <div class="bg-gray-50 min-h-screen">
            <!-- Content similar to map/index.blade.php -->
            <div class="bg-gradient-to-br from-gray-50 to-white">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="py-8">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            <!-- Stats cards - similar to original -->
                            <div class="group relative overflow-hidden bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                                <div class="relative p-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-blue-100 mb-1">Total Lokasi</p>
                                            <p class="text-3xl font-bold text-white">{{ totalLokasi }}</p>
                                        </div>
                                        <div class="bg-white/20 rounded-xl p-3">
                                            <i class="fas fa-map-pin text-2xl text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add other stat cards similarly -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main map content -->
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Sidebar with tools and form -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Tools, Form, Legend sections - similar to original -->
                    </div>

                    <!-- Map -->
                    <div class="lg:col-span-3">
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-xl border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 px-6 py-5">
                                <h3 class="text-xl font-bold text-white flex items-center">
                                    <div class="bg-white/20 rounded-lg p-2 mr-3">
                                        <i class="fas fa-map text-white"></i>
                                    </div>
                                    Peta Interaktif Bali
                                </h3>
                            </div>
                            <div class="relative">
                                <div ref="mapContainer" class="h-[650px] w-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script>
import Layout from '../components/Layout.vue';
import L from 'leaflet';
import GeoFeatureService from '../services/GeoFeatureService.js';

export default {
    name: 'MapPage',
    components: {
        Layout,
    },
    data() {
        return {
            totalLokasi: 0,
            map: null,
            markers: [],
            lines: [],
            circles: [],
            polygons: [],
        };
    },
    mounted() {
        this.initMap();
        this.loadDataFromQuery();
    },
    methods: {
        initMap() {
            this.map = L.map(this.$refs.mapContainer).setView([-8.4095, 115.1889], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
            }).addTo(this.map);
        },
        async loadDataFromQuery() {
            const query = this.$route.query;
            
            try {
                // Check if marker query parameter exists
                if (query.marker) {
                    const markerResult = await GeoFeatureService.getFeatureById(query.marker);
                    if (markerResult.data) {
                        this.displayMarker(markerResult.data);
                        // Center map on marker
                        const lat = markerResult.data.coordinates[1];
                        const lng = markerResult.data.coordinates[0];
                        this.map.setView([lat, lng], parseInt(query.zoom) || 13);
                    }
                }
                
                // Check if line query parameter exists
                if (query.line) {
                    const lineResult = await GeoFeatureService.getFeatureById(query.line);
                    if (lineResult.data && lineResult.data.coordinates) {
                        this.displayPolyline(lineResult.data);
                        // Fit bounds to line
                        const bounds = L.latLngBounds(
                            lineResult.data.coordinates.map(coord => [coord[1], coord[0]])
                        );
                        this.map.fitBounds(bounds);
                    }
                }
                
                // Check if circle query parameter exists
                if (query.circle) {
                    const circleResult = await GeoFeatureService.getFeatureById(query.circle);
                    if (circleResult.data) {
                        this.displayCircle(circleResult.data);
                        // Center map on circle
                        const lat = circleResult.data.coordinates[1];
                        const lng = circleResult.data.coordinates[0];
                        this.map.setView([lat, lng], parseInt(query.zoom) || 13);
                    }
                }
                
                // Check if polygon query parameter exists
                if (query.polygon) {
                    const polygonResult = await GeoFeatureService.getFeatureById(query.polygon);
                    if (polygonResult.data && polygonResult.data.coordinates) {
                        this.displayPolygon(polygonResult.data);
                        // Fit bounds to polygon
                        const bounds = L.latLngBounds(
                            polygonResult.data.coordinates.map(coord => [coord[1], coord[0]])
                        );
                        this.map.fitBounds(bounds);
                    }
                }
            } catch (error) {
                console.error('Error loading data from query:', error);
            }
        },
        displayMarker(markerData) {
            const icon = L.icon({
                iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
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
                fillOpacity: polygonData.properties?.opacity || 0.4,
                weight: polygonData.properties?.strokeWidth || 2,
            })
                .bindPopup(`<strong>${polygonData.nama}</strong><br>${polygonData.deskripsi || ''}`)
                .addTo(this.map)
                .openPopup();
        },
    },
};
</script>

