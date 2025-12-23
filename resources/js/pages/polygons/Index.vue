<template>
    <AppLayout>
        <div class="polygons-container">
            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1>Polygon Overview</h1>
                    <p>Manage all your map polygons and areas</p>
                </div>
                <div class="header-actions">
                    <router-link to="/polygons/create" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        Add Polygon
                    </router-link>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="filters-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input 
                        type="text" 
                        placeholder="Search polygons..." 
                        v-model="searchQuery"
                        @input="filterPolygons"
                    >
                </div>
                <div class="filter-controls">
                    <select v-model="selectedCategory" @change="filterPolygons" class="filter-select">
                        <option value="">All Categories</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    <select v-model="sortBy" @change="sortPolygons" class="filter-select">
                        <option value="name">Sort by Name</option>
                        <option value="created_at">Sort by Date</option>
                        <option value="area">Sort by Area</option>
                    </select>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-draw-polygon"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ totalPolygons }}</div>
                        <div class="stat-label">Total Polygons</div>
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
                        <i class="fas fa-expand-arrows-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ totalArea.toFixed(1) }}</div>
                        <div class="stat-label">Total Area (km²)</div>
                    </div>
                </div>
            </div>

            <!-- Polygons List -->
            <div class="polygons-list">
                <div class="list-header">
                    <h2>Polygons ({{ filteredPolygons.length }})</h2>
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
                <div v-if="viewMode === 'grid'" class="polygons-grid">
                    <div v-if="filteredPolygons.length === 0" class="empty-state">
                        <i class="fas fa-draw-polygon"></i>
                        <h3>No polygons found</h3>
                        <p>Start by creating your first polygon</p>
                        <router-link to="/polygons/create" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            Add Polygon
                        </router-link>
                    </div>
                    <div 
                        v-for="polygon in filteredPolygons" 
                        :key="polygon.id" 
                        class="polygon-card"
                    >
                        <div class="polygon-header">
                            <div class="polygon-preview">
                                <svg width="60" height="40" viewBox="0 0 60 40">
                                    <polygon 
                                        :points="generatePreviewPoints(polygon)" 
                                        :fill="polygon.fillColor || '#3b82f6'" 
                                        :stroke="polygon.strokeColor || '#1e40af'"
                                        :stroke-width="polygon.strokeWidth || 2"
                                        :fill-opacity="polygon.fillOpacity || 0.3"
                                    />
                                </svg>
                            </div>
                            <div class="polygon-category">
                                {{ getCategoryName(polygon.category_id) }}
                            </div>
                        </div>
                        <div class="polygon-content">
                            <h3>{{ polygon.name }}</h3>
                            <p>{{ polygon.description || 'No description' }}</p>
                            <div class="polygon-stats">
                                <span class="stat">
                                    <i class="fas fa-expand-arrows-alt"></i>
                                    {{ (polygon.area || 0).toFixed(2) }} km²
                                </span>
                                <span class="stat">
                                    <i class="fas fa-map-pin"></i>
                                    {{ (polygon.coordinates || []).length }} points
                                </span>
                            </div>
                        </div>
                        <div class="polygon-actions">
                            <button @click="viewOnMap(polygon)" class="action-btn view">
                                <i class="fas fa-eye"></i>
                            </button>
                            <router-link :to="`/polygons/${polygon.id}/edit`" class="action-btn edit">
                                <i class="fas fa-edit"></i>
                            </router-link>
                            <button @click="deletePolygon(polygon)" class="action-btn delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- List View -->
                <div v-if="viewMode === 'list'" class="polygons-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Area</th>
                                <th>Points</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredPolygons.length === 0">
                                <td colspan="6" class="empty-row">
                                    <div class="empty-state">
                                        <i class="fas fa-draw-polygon"></i>
                                        <span>No polygons found</span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="polygon in filteredPolygons" :key="polygon.id">
                                <td>
                                    <div class="polygon-name">
                                        <div class="polygon-color" :style="{ backgroundColor: polygon.fillColor || '#3b82f6' }"></div>
                                        {{ polygon.name }}
                                    </div>
                                </td>
                                <td>
                                    <span class="category-badge">
                                        {{ getCategoryName(polygon.category_id) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="area">
                                        {{ (polygon.area || 0).toFixed(2) }} km²
                                    </span>
                                </td>
                                <td>
                                    <span class="points">
                                        {{ (polygon.coordinates || []).length }} points
                                    </span>
                                </td>
                                <td>
                                    <span class="date">
                                        {{ formatDate(polygon.created_at) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <button @click="viewOnMap(polygon)" class="action-btn view">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <router-link :to="`/polygons/${polygon.id}/edit`" class="action-btn edit">
                                            <i class="fas fa-edit"></i>
                                        </router-link>
                                        <button @click="deletePolygon(polygon)" class="action-btn delete">
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
    name: 'PolygonsIndex',
    components: {
        AppLayout
    },
    data() {
        return {
            polygons: [],
            categories: [],
            filteredPolygons: [],
            searchQuery: '',
            selectedCategory: '',
            sortBy: 'name',
            viewMode: 'grid',
            loading: false
        }
    },
    computed: {
        totalPolygons() {
            return this.polygons.length;
        },
        totalArea() {
            return this.polygons.reduce((total, polygon) => total + (polygon.area || 0), 0);
        }
    },
    async mounted() {
        await this.loadData();
    },
    methods: {
        async loadData() {
            this.loading = true;
            try {
                const result = await GeoFeatureService.getPolygons();
                if (result.success) {
                    this.polygons = result.data.map(polygon => ({
                        id: polygon.id,
                        name: polygon.nama,
                        description: polygon.deskripsi || '',
                        category_id: polygon.kategori_id,
                        fillColor: polygon.properties?.fillColor || '#3b82f6',
                        strokeColor: polygon.properties?.strokeColor || '#1e40af',
                        strokeWidth: polygon.properties?.strokeWidth || 2,
                        fillOpacity: polygon.properties?.opacity || 0.4,
                        area: 15.7, // TODO: Calculate actual area
                        coordinates: polygon.coordinates || [],
                        created_at: polygon.created_at
                    }));
                } else {
                    this.polygons = [];
                }
                
                this.categories = [
                    { id: 1, name: 'Administrative Area' },
                    { id: 2, name: 'Protected Area' },
                    { id: 3, name: 'Urban Area' },
                    { id: 4, name: 'Agricultural Area' },
                    { id: 5, name: 'Water Body' }
                ];
                
                this.filteredPolygons = [...this.polygons];
            } catch (error) {
                console.error('Error loading polygons:', error);
                toast.error('Failed to load polygons', 'Error');
                this.polygons = [];
                this.filteredPolygons = [];
            } finally {
                this.loading = false;
            }
        },
        filterPolygons() {
            let filtered = [...this.polygons];
            
            // Filter by search query
            if (this.searchQuery) {
                filtered = filtered.filter(polygon => 
                    polygon.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    (polygon.description && polygon.description.toLowerCase().includes(this.searchQuery.toLowerCase()))
                );
            }
            
            // Filter by category
            if (this.selectedCategory) {
                filtered = filtered.filter(polygon => polygon.category_id == this.selectedCategory);
            }
            
            this.filteredPolygons = filtered;
            this.sortPolygons();
        },
        sortPolygons() {
            this.filteredPolygons.sort((a, b) => {
                switch (this.sortBy) {
                    case 'name':
                        return a.name.localeCompare(b.name);
                    case 'created_at':
                        return new Date(b.created_at) - new Date(a.created_at);
                    case 'area':
                        return (b.area || 0) - (a.area || 0);
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
        generatePreviewPoints(polygon) {
            if (!polygon.coordinates || polygon.coordinates.length < 3) {
                return '10,10 50,10 50,30 10,30';
            }
            
            // Simple points generation for preview
            const points = polygon.coordinates.slice(0, 6); // Max 6 points for preview
            let pointsStr = '';
            
            for (let i = 0; i < points.length; i++) {
                const x = 10 + (i % 3) * 20;
                const y = 10 + Math.floor(i / 3) * 15;
                pointsStr += `${x},${y} `;
            }
            
            return pointsStr.trim();
        },
        viewOnMap(polygon) {
            // Navigate to map with polygon focused
            if (polygon.coordinates && polygon.coordinates.length > 0) {
                const center = this.calculatePolygonCenter(polygon.coordinates);
                this.$router.push({
                    name: 'map',
                    query: { 
                        lat: center.lat, 
                        lng: center.lng, 
                        zoom: 13,
                        polygon: polygon.id 
                    }
                });
            }
        },
        calculatePolygonCenter(coordinates) {
            let lat = 0;
            let lng = 0;
            
            coordinates.forEach(coord => {
                lat += coord[0];
                lng += coord[1];
            });
            
            return {
                lat: lat / coordinates.length,
                lng: lng / coordinates.length
            };
        },
        async deletePolygon(polygon) {
            const confirmed = await new Promise((resolve) => {
                if (window.confirm(`Are you sure you want to delete "${polygon.name}"?`)) {
                    resolve(true);
                } else {
                    resolve(false);
                }
            });
            
            if (confirmed) {
                try {
                    await GeoFeatureService.deleteFeature(polygon.id);
                    this.polygons = this.polygons.filter(p => p.id !== polygon.id);
                    this.filterPolygons();
                    toast.success('Polygon deleted successfully', 'Success');
                } catch (error) {
                    console.error('Error deleting polygon:', error);
                    toast.error('Error deleting polygon', 'Error');
                }
            }
        }
    }
}
</script>

<style scoped>
/* Reuse most styles from lines/Index.vue with polygon-specific modifications */
.polygons-container {
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

.polygons-list {
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

.polygons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
    padding: 1.5rem;
}

.polygon-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1rem;
    transition: all 0.2s;
}

.polygon-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.polygon-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.polygon-preview {
    width: 60px;
    height: 40px;
    background: #f9fafb;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e5e7eb;
}

.polygon-category {
    font-size: 0.75rem;
    background: #f3f4f6;
    color: #374151;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.polygon-content h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.polygon-content p {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0 0 0.75rem 0;
    line-height: 1.4;
}

.polygon-stats {
    display: flex;
    gap: 1rem;
}

.stat {
    font-size: 0.75rem;
    color: #9ca3af;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.polygon-actions {
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
.polygons-table {
    overflow-x: auto;
}

.polygons-table table {
    width: 100%;
    border-collapse: collapse;
}

.polygons-table th,
.polygons-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #f3f4f6;
}

.polygons-table th {
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.polygon-name {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.polygon-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
    border: 1px solid #e5e7eb;
}

.category-badge {
    background: #e0f2fe;
    color: #0369a1;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.area,
.points {
    font-size: 0.875rem;
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
:global(.dark) .polygon-content h3,
:global(.dark) .stat-number {
    color: #f9fafb;
}

:global(.dark) .header-content p,
:global(.dark) .polygon-content p,
:global(.dark) .stat-label {
    color: #d1d5db;
}

:global(.dark) .polygons-list,
:global(.dark) .stat-card,
:global(.dark) .polygon-card {
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

:global(.dark) .polygons-table th {
    background: #374151;
    color: #d1d5db;
}

:global(.dark) .polygons-table td {
    border-bottom-color: #374151;
}

:global(.dark) .polygon-category {
    background: #374151;
    color: #d1d5db;
}

:global(.dark) .polygon-actions {
    border-top-color: #374151;
}

:global(.dark) .polygon-preview {
    background: #374151;
    border-color: #4b5563;
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
    
    .polygons-grid {
        grid-template-columns: 1fr;
    }
}
</style>
