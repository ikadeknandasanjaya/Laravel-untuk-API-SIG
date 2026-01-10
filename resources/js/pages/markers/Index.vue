<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Markers</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage all your map markers in one place</p>
                </div>
                <router-link to="/markers/create" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>
                    Add Marker
                </router-link>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Markers</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ totalMarkers }}</p>
                            </div>
                            <div class="bg-blue-100 rounded-lg p-3">
                                <i class="fas fa-map-marker-alt text-2xl text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Categories</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ categories.length }}</p>
                            </div>
                            <div class="bg-green-100 rounded-lg p-3">
                                <i class="fas fa-tags text-2xl text-green-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">This Month</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ markersThisMonth }}</p>
                            </div>
                            <div class="bg-purple-100 rounded-lg p-3">
                                <i class="fas fa-calendar text-2xl text-purple-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input 
                                type="text" 
                                placeholder="Search markers..." 
                                v-model="searchQuery"
                                @input="filterMarkers"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                        <select v-model="selectedCategory" @change="filterMarkers" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Categories</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <select v-model="sortBy" @change="sortMarkers" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="name">Sort by Name</option>
                            <option value="created_at">Sort by Date</option>
                            <option value="category">Sort by Category</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                <p class="mt-4 text-gray-600">Loading markers...</p>
            </div>

            <!-- Markers Grid -->
            <div v-else>
                <div v-if="filteredMarkers.length === 0" class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <i class="fas fa-map-marker-alt text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 mb-2">No markers found</p>
                    <p class="text-sm text-gray-500 mb-4">Start by creating your first marker</p>
                    <router-link to="/markers/create" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Add Marker
                    </router-link>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="marker in filteredMarkers" :key="marker.id" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div 
                                    class="w-12 h-12 rounded-lg flex items-center justify-center text-white text-xl"
                                    :style="{ backgroundColor: marker.properties?.color || '#3b82f6' }"
                                >
                                    <i :class="getMarkerIcon(marker)"></i>
                                </div>
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-md">
                                    {{ getCategoryName(marker.kategori_id) }}
                                </span>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ marker.nama }}</h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ marker.deskripsi || 'No description' }}</p>
                            
                            <div class="flex items-center text-xs text-gray-500 mb-4">
                                <i class="fas fa-map-pin mr-1"></i>
                                {{ getMarkerCoordinates(marker) }}
                            </div>
                            
                            <div class="flex gap-2">
                                <button @click="viewOnMap(marker)" class="flex-1 px-3 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-eye mr-1"></i> View
                                </button>
                                <router-link :to="`/markers/${marker.id}/edit`" class="flex-1 px-3 py-2 bg-green-50 text-green-600 rounded-lg text-sm hover:bg-green-100 transition-colors text-center">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </router-link>
                                <button @click="deleteMarker(marker)" class="px-3 py-2 bg-red-50 text-red-600 rounded-lg text-sm hover:bg-red-100 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../../components/AppLayout.vue';
import GeoFeatureService from '../../services/GeoFeatureService.js';
import toast from '../../utils/toast.js';

export default {
    name: 'MarkersIndex',
    components: {
        AppLayout
    },
    data() {
        return {
            markers: [],
            categories: [],
            filteredMarkers: [],
            searchQuery: '',
            selectedCategory: '',
            sortBy: 'name',
            viewMode: 'grid',
            loading: false
        }
    },
    computed: {
        totalMarkers() {
            return this.markers.length;
        },
        markersThisMonth() {
            const now = new Date();
            const thisMonth = now.getMonth();
            const thisYear = now.getFullYear();
            
            return this.markers.filter(marker => {
                const createdDate = new Date(marker.created_at);
                return createdDate.getMonth() === thisMonth && createdDate.getFullYear() === thisYear;
            }).length;
        }
    },
    async mounted() {
        await this.loadData();
    },
    methods: {
        async loadData() {
            this.loading = true;
            try {
                // Load categories
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
                
                // Load markers from local backend API
                const result = await GeoFeatureService.getMarkers();
                console.log('API Response:', result);
                
                if (result.success && result.data) {
                    // Transform data if needed - support both old and new format
                    this.markers = result.data.map(marker => ({
                        ...marker,
                        // Ensure backward compatibility with old coordinate structure
                        latitude: marker.coordinates?.lat || marker.latitude,
                        longitude: marker.coordinates?.lng || marker.longitude
                    }));
                    this.filteredMarkers = [...this.markers];
                    console.log('Markers loaded from local backend:', this.markers);
                } else {
                    toast.error(result.message || 'Gagal memuat data markers', 'Error');
                }
            } catch (error) {
                console.error('Error loading markers:', error);
                toast.error('Gagal memuat data markers dari server lokal.', 'Error');
            } finally {
                this.loading = false;
            }
        },
        getMarkerIcon(marker) {
            // Get icon from properties or use default
            return marker.properties?.icon || 'fas fa-map-marker-alt';
        },
        getMarkerCoordinates(marker) {
            // Handle both old and new coordinate format
            const lat = marker.coordinates?.lat || marker.latitude || 0;
            const lng = marker.coordinates?.lng || marker.longitude || 0;
            return `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        },
        filterMarkers() {
            let filtered = [...this.markers];
            
            // Filter by search query
            if (this.searchQuery) {
                filtered = filtered.filter(marker => 
                    (marker.nama && marker.nama.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
                    (marker.deskripsi && marker.deskripsi.toLowerCase().includes(this.searchQuery.toLowerCase()))
                );
            }
            
            // Filter by category
            if (this.selectedCategory) {
                filtered = filtered.filter(marker => marker.kategori_id == this.selectedCategory);
            }
            
            this.filteredMarkers = filtered;
            this.sortMarkers();
        },
        sortMarkers() {
            this.filteredMarkers.sort((a, b) => {
                switch (this.sortBy) {
                    case 'name':
                        return (a.nama || '').localeCompare(b.nama || '');
                    case 'created_at':
                        return new Date(b.created_at) - new Date(a.created_at);
                    case 'category':
                        return this.getCategoryName(a.kategori_id).localeCompare(this.getCategoryName(b.kategori_id));
                    default:
                        return 0;
                }
            });
        },
        getCategoryName(categoryId) {
            const category = this.categories.find(c => c.id === categoryId);
            return category ? category.name : 'Uncategorized';
        },
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('id-ID');
        },
        viewOnMap(marker) {
            // Navigate to map with marker focused
            const lat = marker.coordinates?.lat || marker.latitude;
            const lng = marker.coordinates?.lng || marker.longitude;
            
            this.$router.push({
                name: 'map',
                query: { 
                    lat: lat, 
                    lng: lng, 
                    zoom: 15,
                    marker: marker.id 
                }
            });
        },
        async deleteMarker(marker) {
            if (confirm(`Apakah Anda yakin ingin menghapus "${marker.nama}"?`)) {
                try {
                    const result = await GeoFeatureService.deleteFeature(marker.id);
                    if (result.success) {
                        // Reload markers after delete
                        await this.loadData();
                        toast.success('Marker berhasil dihapus!', 'Sukses');
                    } else {
                        toast.error(result.message || 'Gagal menghapus marker', 'Error');
                    }
                } catch (error) {
                    console.error('Error deleting marker:', error);
                    toast.error('Gagal menghapus marker.', 'Error');
                }
            }
        }
    }
}
</script>

<style scoped>
.markers-container {
    max-width: 1200px;
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

.btn-primary {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: #3b82f6;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    background: #2563eb;
    transform: translateY(-1px);
}

.filters-section {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
    flex: 1;
    min-width: 300px;
}

.search-box i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
}

.search-box input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
}

.search-box input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-controls {
    display: flex;
    gap: 0.75rem;
}

.filter-select {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: white;
    font-size: 0.875rem;
    min-width: 150px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: #3b82f6;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #111827;
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
}

.markers-list {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.list-header h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.view-toggle {
    display: flex;
    gap: 0.25rem;
}

.view-btn {
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    background: white;
    color: #6b7280;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.view-btn:hover,
.view-btn.active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.markers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
    padding: 1.5rem;
}

.marker-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1rem;
    transition: all 0.2s;
}

.marker-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.marker-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.marker-icon {
    width: 32px;
    height: 32px;
    background: #3b82f6;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.marker-category {
    font-size: 0.75rem;
    background: #f3f4f6;
    color: #374151;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.marker-content h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.marker-content p {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0 0 0.75rem 0;
    line-height: 1.4;
}

.marker-location {
    font-size: 0.75rem;
    color: #9ca3af;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.marker-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f3f4f6;
}

.action-btn {
    padding: 0.5rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-btn.view {
    background: #3b82f6;
    color: white;
}

.action-btn.edit {
    background: #10b981;
    color: white;
}

.action-btn.delete {
    background: #ef4444;
    color: white;
}

.action-btn:hover {
    transform: scale(1.1);
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6b7280;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 1.25rem;
    margin: 0 0 0.5rem 0;
}

.empty-state p {
    margin: 0 0 1.5rem 0;
}

/* Table styles */
.markers-table {
    overflow-x: auto;
}

.markers-table table {
    width: 100%;
    border-collapse: collapse;
}

.markers-table th,
.markers-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #f3f4f6;
}

.markers-table th {
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.marker-name {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.marker-name i {
    color: #3b82f6;
}

.category-badge {
    background: #e0f2fe;
    color: #0369a1;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.location {
    font-family: monospace;
    font-size: 0.75rem;
    color: #6b7280;
}

.date {
    font-size: 0.875rem;
    color: #6b7280;
}

.table-actions {
    display: flex;
    gap: 0.5rem;
}

.empty-row {
    text-align: center;
}

.empty-row .empty-state {
    padding: 2rem;
}

.empty-row .empty-state i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

/* Dark mode styles */
:global(.dark) .header-content h1,
:global(.dark) .list-header h2,
:global(.dark) .marker-content h3,
:global(.dark) .stat-number {
    color: #f9fafb;
}

:global(.dark) .header-content p,
:global(.dark) .marker-content p,
:global(.dark) .stat-label {
    color: #d1d5db;
}

:global(.dark) .markers-list,
:global(.dark) .stat-card,
:global(.dark) .marker-card {
    background: #1f2937;
    border-color: #374151;
}

:global(.dark) .list-header {
    border-bottom-color: #374151;
}

:global(.dark) .search-box input,
:global(.dark) .filter-select {
    background: #374151;
    border-color: #4b5563;
    color: #f9fafb;
}

:global(.dark) .markers-table th {
    background: #374151;
    color: #d1d5db;
}

:global(.dark) .markers-table td {
    border-bottom-color: #374151;
}

:global(.dark) .marker-category {
    background: #374151;
    color: #d1d5db;
}

:global(.dark) .marker-actions {
    border-top-color: #374151;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .filters-section {
        flex-direction: column;
    }
    
    .filter-controls {
        flex-direction: column;
    }
    
    .markers-grid {
        grid-template-columns: 1fr;
    }
}
</style>
