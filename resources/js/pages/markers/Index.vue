<template>
    <AppLayout>
        <div class="markers-container">
            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1>Marker Overview</h1>
                    <p>Manage all your map markers in one place</p>
                </div>
                <div class="header-actions">
                    <router-link to="/markers/create" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        Add Marker
                    </router-link>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="filters-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input 
                        type="text" 
                        placeholder="Search markers..." 
                        v-model="searchQuery"
                        @input="filterMarkers"
                    >
                </div>
                <div class="filter-controls">
                    <select v-model="selectedCategory" @change="filterMarkers" class="filter-select">
                        <option value="">All Categories</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    <select v-model="sortBy" @change="sortMarkers" class="filter-select">
                        <option value="name">Sort by Name</option>
                        <option value="created_at">Sort by Date</option>
                        <option value="category">Sort by Category</option>
                    </select>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ totalMarkers }}</div>
                        <div class="stat-label">Total Markers</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ categories.length }}</div>
                        <div class="stat-label">Categories</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ markersThisMonth }}</div>
                        <div class="stat-label">This Month</div>
                    </div>
                </div>
            </div>

            <!-- Markers List -->
            <div class="markers-list">
                <div class="list-header">
                    <h2>Markers ({{ filteredMarkers.length }})</h2>
                    <div class="view-toggle">
                        <button 
                            @click="viewMode = 'grid'" 
                            :class="{ active: viewMode === 'grid' }"
                            class="view-btn"
                        >
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button 
                            @click="viewMode = 'list'" 
                            :class="{ active: viewMode === 'list' }"
                            class="view-btn"
                        >
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>

                <!-- Grid View -->
                <div v-if="viewMode === 'grid'" class="markers-grid">
                    <div v-if="filteredMarkers.length === 0" class="empty-state">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3>No markers found</h3>
                        <p>Start by creating your first marker</p>
                        <router-link to="/markers/create" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            Add Marker
                        </router-link>
                    </div>
                    <div 
                        v-for="marker in filteredMarkers" 
                        :key="marker.id" 
                        class="marker-card"
                    >
                        <div class="marker-header">
                            <div class="marker-icon" :style="{ background: marker.properties?.color || '#3b82f6' }">
                                <i :class="getMarkerIcon(marker)"></i>
                            </div>
                            <div class="marker-category">
                                {{ getCategoryName(marker.kategori_id) }}
                            </div>
                        </div>
                        <div class="marker-content">
                            <h3>{{ marker.nama }}</h3>
                            <p>{{ marker.deskripsi || 'No description' }}</p>
                            <div class="marker-location">
                                <i class="fas fa-map-pin"></i>
                                {{ getMarkerCoordinates(marker) }}
                            </div>
                        </div>
                        <div class="marker-actions">
                            <button @click="viewOnMap(marker)" class="action-btn view">
                                <i class="fas fa-eye"></i>
                            </button>
                            <router-link :to="`/markers/${marker.id}/edit`" class="action-btn edit">
                                <i class="fas fa-edit"></i>
                            </router-link>
                            <button @click="deleteMarker(marker)" class="action-btn delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- List View -->
                <div v-if="viewMode === 'list'" class="markers-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredMarkers.length === 0">
                                <td colspan="5" class="empty-row">
                                    <div class="empty-state">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>No markers found</span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="marker in filteredMarkers" :key="marker.id">
                                <td>
                                    <div class="marker-name">
                                        <i :class="getMarkerIcon(marker)"></i>
                                        {{ marker.nama }}
                                    </div>
                                </td>
                                <td>
                                    <span class="category-badge">
                                        {{ getCategoryName(marker.kategori_id) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="location">
                                        {{ getMarkerCoordinates(marker) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="date">
                                        {{ formatDate(marker.created_at) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <button @click="viewOnMap(marker)" class="action-btn view">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <router-link :to="`/markers/${marker.id}/edit`" class="action-btn edit">
                                            <i class="fas fa-edit"></i>
                                        </router-link>
                                        <button @click="deleteMarker(marker)" class="action-btn delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
            return marker.properties?.icon ? `fas fa-${marker.properties.icon}` : 'fas fa-map-marker-alt';
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
