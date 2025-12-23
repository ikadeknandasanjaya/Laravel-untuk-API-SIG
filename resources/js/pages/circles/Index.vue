<template>
    <AppLayout>
        <div class="circles-container">
            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1>Circle Overview</h1>
                    <p>Manage all your map circles and circular areas</p>
                </div>
                <div class="header-actions">
                    <router-link to="/circles/create" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        Add Circle
                    </router-link>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="filters-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input 
                        type="text" 
                        placeholder="Search circles..." 
                        v-model="searchQuery"
                        @input="filterCircles"
                    >
                </div>
                <div class="filter-controls">
                    <select v-model="selectedCategory" @change="filterCircles" class="filter-select">
                        <option value="">All Categories</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    <select v-model="sortBy" @change="sortCircles" class="filter-select">
                        <option value="name">Sort by Name</option>
                        <option value="created_at">Sort by Date</option>
                        <option value="radius">Sort by Radius</option>
                    </select>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ totalCircles }}</div>
                        <div class="stat-label">Total Circles</div>
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
                        <div class="stat-number">{{ averageRadius.toFixed(1) }}</div>
                        <div class="stat-label">Avg Radius (km)</div>
                    </div>
                </div>
            </div>

            <!-- Circles List -->
            <div class="circles-list">
                <div class="list-header">
                    <h2>Circles ({{ filteredCircles.length }})</h2>
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
                <div v-if="viewMode === 'grid'" class="circles-grid">
                    <div v-if="filteredCircles.length === 0" class="empty-state">
                        <i class="fas fa-circle"></i>
                        <h3>No circles found</h3>
                        <p>Start by creating your first circle</p>
                        <router-link to="/circles/create" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            Add Circle
                        </router-link>
                    </div>
                    <div 
                        v-for="circle in filteredCircles" 
                        :key="circle.id" 
                        class="circle-card"
                    >
                        <div class="circle-header">
                            <div class="circle-preview">
                                <svg width="60" height="40" viewBox="0 0 60 40">
                                    <circle 
                                        cx="30" 
                                        cy="20" 
                                        :r="Math.min(15, Math.max(5, (circle.radius || 1) * 2))"
                                        :fill="circle.fillColor || '#3b82f6'" 
                                        :stroke="circle.strokeColor || '#1e40af'"
                                        :stroke-width="circle.strokeWidth || 2"
                                        :fill-opacity="circle.fillOpacity || 0.3"
                                    />
                                </svg>
                            </div>
                            <div class="circle-category">
                                {{ getCategoryName(circle.category_id) }}
                            </div>
                        </div>
                        <div class="circle-content">
                            <h3>{{ circle.name }}</h3>
                            <p>{{ circle.description || 'No description' }}</p>
                            <div class="circle-stats">
                                <span class="stat">
                                    <i class="fas fa-expand-arrows-alt"></i>
                                    {{ (circle.radius || 0).toFixed(2) }} km
                                </span>
                                <span class="stat">
                                    <i class="fas fa-map-pin"></i>
                                    {{ circle.latitude?.toFixed(4) || 0 }}, {{ circle.longitude?.toFixed(4) || 0 }}
                                </span>
                            </div>
                        </div>
                        <div class="circle-actions">
                            <button @click="viewOnMap(circle)" class="action-btn view">
                                <i class="fas fa-eye"></i>
                            </button>
                            <router-link :to="`/circles/${circle.id}/edit`" class="action-btn edit">
                                <i class="fas fa-edit"></i>
                            </router-link>
                            <button @click="deleteCircle(circle)" class="action-btn delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- List View -->
                <div v-if="viewMode === 'list'" class="circles-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Center</th>
                                <th>Radius</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredCircles.length === 0">
                                <td colspan="6" class="empty-row">
                                    <div class="empty-state">
                                        <i class="fas fa-circle"></i>
                                        <span>No circles found</span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="circle in filteredCircles" :key="circle.id">
                                <td>
                                    <div class="circle-name">
                                        <div class="circle-color" :style="{ backgroundColor: circle.fillColor || '#3b82f6' }"></div>
                                        {{ circle.name }}
                                    </div>
                                </td>
                                <td>
                                    <span class="category-badge">
                                        {{ getCategoryName(circle.category_id) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="center">
                                        {{ circle.latitude?.toFixed(4) || 0 }}, {{ circle.longitude?.toFixed(4) || 0 }}
                                    </span>
                                </td>
                                <td>
                                    <span class="radius">
                                        {{ (circle.radius || 0).toFixed(2) }} km
                                    </span>
                                </td>
                                <td>
                                    <span class="date">
                                        {{ formatDate(circle.created_at) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <button @click="viewOnMap(circle)" class="action-btn view">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <router-link :to="`/circles/${circle.id}/edit`" class="action-btn edit">
                                            <i class="fas fa-edit"></i>
                                        </router-link>
                                        <button @click="deleteCircle(circle)" class="action-btn delete">
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
    name: 'CirclesIndex',
    components: {
        AppLayout
    },
    data() {
        return {
            circles: [],
            categories: [],
            filteredCircles: [],
            searchQuery: '',
            selectedCategory: '',
            sortBy: 'name',
            viewMode: 'grid',
            loading: false
        }
    },
    computed: {
        totalCircles() {
            return this.circles.length;
        },
        averageRadius() {
            if (this.circles.length === 0) return 0;
            const totalRadius = this.circles.reduce((total, circle) => total + (circle.radius || 0), 0);
            return totalRadius / this.circles.length;
        }
    },
    async mounted() {
        await this.loadData();
    },
    methods: {
        async loadData() {
            this.loading = true;
            try {
                const result = await GeoFeatureService.getCircles();
                if (result.success) {
                    this.circles = result.data.map(circle => ({
                        id: circle.id,
                        name: circle.nama,
                        description: circle.deskripsi || '',
                        category_id: circle.kategori_id,
                        latitude: circle.coordinates?.[1] || 0,
                        longitude: circle.coordinates?.[0] || 0,
                        radius: (circle.properties?.radius || 0) / 1000, // Convert to km
                        fillColor: circle.properties?.color || '#3b82f6',
                        strokeColor: circle.properties?.color || '#3b82f6',
                        strokeWidth: 2,
                        fillOpacity: circle.properties?.opacity || 0.5,
                        created_at: circle.created_at
                    }));
                } else {
                    this.circles = [];
                }
                
                this.categories = [
                    { id: 1, name: 'Buffer Zone' },
                    { id: 2, name: 'Service Area' },
                    { id: 3, name: 'Impact Zone' },
                    { id: 4, name: 'Coverage Area' },
                    { id: 5, name: 'Restricted Area' }
                ];
                
                this.filteredCircles = [...this.circles];
            } catch (error) {
                console.error('Error loading circles:', error);
                toast.error('Failed to load circles', 'Error');
                this.circles = [];
                this.filteredCircles = [];
            } finally {
                this.loading = false;
            }
        },
        filterCircles() {
            let filtered = [...this.circles];
            
            // Filter by search query
            if (this.searchQuery) {
                filtered = filtered.filter(circle => 
                    circle.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    (circle.description && circle.description.toLowerCase().includes(this.searchQuery.toLowerCase()))
                );
            }
            
            // Filter by category
            if (this.selectedCategory) {
                filtered = filtered.filter(circle => circle.category_id == this.selectedCategory);
            }
            
            this.filteredCircles = filtered;
            this.sortCircles();
        },
        sortCircles() {
            this.filteredCircles.sort((a, b) => {
                switch (this.sortBy) {
                    case 'name':
                        return a.name.localeCompare(b.name);
                    case 'created_at':
                        return new Date(b.created_at) - new Date(a.created_at);
                    case 'radius':
                        return (b.radius || 0) - (a.radius || 0);
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
        viewOnMap(circle) {
            // Navigate to map with circle focused
            this.$router.push({
                name: 'map',
                query: { 
                    lat: circle.latitude, 
                    lng: circle.longitude, 
                    zoom: 13,
                    circle: circle.id 
                }
            });
        },
        async deleteCircle(circle) {
            const confirmed = await new Promise((resolve) => {
                if (window.confirm(`Are you sure you want to delete "${circle.name}"?`)) {
                    resolve(true);
                } else {
                    resolve(false);
                }
            });
            
            if (confirmed) {
                try {
                    await GeoFeatureService.deleteFeature(circle.id);
                    this.circles = this.circles.filter(c => c.id !== circle.id);
                    this.filterCircles();
                    toast.success('Circle deleted successfully', 'Success');
                } catch (error) {
                    console.error('Error deleting circle:', error);
                    toast.error('Error deleting circle', 'Error');
                }
            }
        }
    }
}
</script>

<style scoped>
/* Reuse most styles from polygons/Index.vue with circle-specific modifications */
.circles-container {
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

.circles-list {
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

.circles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
    padding: 1.5rem;
}

.circle-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1rem;
    transition: all 0.2s;
}

.circle-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.circle-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.circle-preview {
    width: 60px;
    height: 40px;
    background: #f9fafb;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e5e7eb;
}

.circle-category {
    font-size: 0.75rem;
    background: #f3f4f6;
    color: #374151;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.circle-content h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.circle-content p {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0 0 0.75rem 0;
    line-height: 1.4;
}

.circle-stats {
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

.circle-actions {
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
.circles-table {
    overflow-x: auto;
}

.circles-table table {
    width: 100%;
    border-collapse: collapse;
}

.circles-table th,
.circles-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #f3f4f6;
}

.circles-table th {
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.circle-name {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.circle-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
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

.center,
.radius {
    font-size: 0.875rem;
    color: #6b7280;
    font-family: monospace;
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
:global(.dark) .circle-content h3,
:global(.dark) .stat-number {
    color: #f9fafb;
}

:global(.dark) .header-content p,
:global(.dark) .circle-content p,
:global(.dark) .stat-label {
    color: #d1d5db;
}

:global(.dark) .circles-list,
:global(.dark) .stat-card,
:global(.dark) .circle-card {
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

:global(.dark) .circles-table th {
    background: #374151;
    color: #d1d5db;
}

:global(.dark) .circles-table td {
    border-bottom-color: #374151;
}

:global(.dark) .circle-category {
    background: #374151;
    color: #d1d5db;
}

:global(.dark) .circle-actions {
    border-top-color: #374151;
}

:global(.dark) .circle-preview {
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
    
    .circles-grid {
        grid-template-columns: 1fr;
    }
}
</style>
