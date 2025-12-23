<template>
    <AppLayout>
        <div class="lines-container">
            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1>Line Overview</h1>
                    <p>Manage all your map lines and polylines</p>
                </div>
                <div class="header-actions">
                    <router-link to="/lines/create" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        Add Line
                    </router-link>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="filters-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input 
                        type="text" 
                        placeholder="Search lines..." 
                        v-model="searchQuery"
                        @input="filterLines"
                    >
                </div>
                <div class="filter-controls">
                    <select v-model="selectedCategory" @change="filterLines" class="filter-select">
                        <option value="">All Categories</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    <select v-model="sortBy" @change="sortLines" class="filter-select">
                        <option value="name">Sort by Name</option>
                        <option value="created_at">Sort by Date</option>
                        <option value="length">Sort by Length</option>
                    </select>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-route"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ totalLines }}</div>
                        <div class="stat-label">Total Lines</div>
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
                        <i class="fas fa-ruler"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ totalLength.toFixed(1) }}</div>
                        <div class="stat-label">Total Length (km)</div>
                    </div>
                </div>
            </div>

            <!-- Lines List -->
            <div class="lines-list">
                <div class="list-header">
                    <h2>Lines ({{ filteredLines.length }})</h2>
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
                <div v-if="viewMode === 'grid'" class="lines-grid">
                    <div v-if="filteredLines.length === 0" class="empty-state">
                        <i class="fas fa-route"></i>
                        <h3>No lines found</h3>
                        <p>Start by creating your first line</p>
                        <router-link to="/lines/create" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            Add Line
                        </router-link>
                    </div>
                    <div 
                        v-for="line in filteredLines" 
                        :key="line.id" 
                        class="line-card"
                    >
                        <div class="line-header">
                            <div class="line-preview">
                                <svg width="60" height="40" viewBox="0 0 60 40">
                                    <path 
                                        :d="generatePreviewPath(line)" 
                                        :stroke="line.color || '#3b82f6'" 
                                        :stroke-width="line.width || 2"
                                        fill="none"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </div>
                            <div class="line-category">
                                {{ getCategoryName(line.category_id) }}
                            </div>
                        </div>
                        <div class="line-content">
                            <h3>{{ line.name }}</h3>
                            <p>{{ line.description || 'No description' }}</p>
                            <div class="line-stats">
                                <span class="stat">
                                    <i class="fas fa-ruler"></i>
                                    {{ (line.length || 0).toFixed(2) }} km
                                </span>
                                <span class="stat">
                                    <i class="fas fa-map-pin"></i>
                                    {{ (line.coordinates || []).length }} points
                                </span>
                            </div>
                        </div>
                        <div class="line-actions">
                            <button @click="viewOnMap(line)" class="action-btn view">
                                <i class="fas fa-eye"></i>
                            </button>
                            <router-link :to="`/lines/${line.id}/edit`" class="action-btn edit">
                                <i class="fas fa-edit"></i>
                            </router-link>
                            <button @click="deleteLine(line)" class="action-btn delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- List View -->
                <div v-if="viewMode === 'list'" class="lines-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Length</th>
                                <th>Points</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredLines.length === 0">
                                <td colspan="6" class="empty-row">
                                    <div class="empty-state">
                                        <i class="fas fa-route"></i>
                                        <span>No lines found</span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="line in filteredLines" :key="line.id">
                                <td>
                                    <div class="line-name">
                                        <div class="line-color" :style="{ backgroundColor: line.color || '#3b82f6' }"></div>
                                        {{ line.name }}
                                    </div>
                                </td>
                                <td>
                                    <span class="category-badge">
                                        {{ getCategoryName(line.category_id) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="length">
                                        {{ (line.length || 0).toFixed(2) }} km
                                    </span>
                                </td>
                                <td>
                                    <span class="points">
                                        {{ (line.coordinates || []).length }} points
                                    </span>
                                </td>
                                <td>
                                    <span class="date">
                                        {{ formatDate(line.created_at) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <button @click="viewOnMap(line)" class="action-btn view">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <router-link :to="`/lines/${line.id}/edit`" class="action-btn edit">
                                            <i class="fas fa-edit"></i>
                                        </router-link>
                                        <button @click="deleteLine(line)" class="action-btn delete">
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

export default {
    name: 'LinesIndex',
    components: {
        AppLayout
    },
    data() {
        return {
            lines: [],
            categories: [],
            filteredLines: [],
            searchQuery: '',
            selectedCategory: '',
            sortBy: 'name',
            viewMode: 'grid',
            loading: false
        }
    },
    computed: {
        totalLines() {
            return this.lines.length;
        },
        totalLength() {
            return this.lines.reduce((total, line) => total + (line.length || 0), 0);
        }
    },
    async mounted() {
        await this.loadData();
    },
    methods: {
        async loadData() {
            this.loading = true;
            try {
                // TODO: Replace with actual API calls
                // For now, using dummy data
                this.lines = [
                    {
                        id: 1,
                        name: 'Sample Route 1',
                        description: 'This is a sample route',
                        category_id: 1,
                        color: '#3b82f6',
                        width: 3,
                        length: 5.2,
                        coordinates: [
                            [-8.4095, 115.1889],
                            [-8.4105, 115.1899],
                            [-8.4115, 115.1909]
                        ],
                        created_at: '2024-01-15T10:30:00Z'
                    }
                ];
                
                this.categories = [
                    { id: 1, name: 'Main Road' },
                    { id: 2, name: 'Highway' },
                    { id: 3, name: 'Local Road' },
                    { id: 4, name: 'Trail' }
                ];
                
                this.filteredLines = [...this.lines];
            } catch (error) {
                console.error('Error loading lines:', error);
            } finally {
                this.loading = false;
            }
        },
        filterLines() {
            let filtered = [...this.lines];
            
            // Filter by search query
            if (this.searchQuery) {
                filtered = filtered.filter(line => 
                    line.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    (line.description && line.description.toLowerCase().includes(this.searchQuery.toLowerCase()))
                );
            }
            
            // Filter by category
            if (this.selectedCategory) {
                filtered = filtered.filter(line => line.category_id == this.selectedCategory);
            }
            
            this.filteredLines = filtered;
            this.sortLines();
        },
        sortLines() {
            this.filteredLines.sort((a, b) => {
                switch (this.sortBy) {
                    case 'name':
                        return a.name.localeCompare(b.name);
                    case 'created_at':
                        return new Date(b.created_at) - new Date(a.created_at);
                    case 'length':
                        return (b.length || 0) - (a.length || 0);
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
        generatePreviewPath(line) {
            if (!line.coordinates || line.coordinates.length < 2) {
                return 'M 10,20 Q 30,10 50,20';
            }
            
            // Simple path generation for preview
            const points = line.coordinates.slice(0, 4); // Max 4 points for preview
            let path = `M ${10},${20}`;
            
            for (let i = 1; i < points.length; i++) {
                const x = 10 + (i * 15);
                const y = 20 + (Math.sin(i) * 8);
                path += ` L ${x},${y}`;
            }
            
            return path;
        },
        viewOnMap(line) {
            // Navigate to map with line focused
            if (line.coordinates && line.coordinates.length > 0) {
                const center = line.coordinates[Math.floor(line.coordinates.length / 2)];
                this.$router.push({
                    name: 'map',
                    query: { 
                        lat: center[0], 
                        lng: center[1], 
                        zoom: 13,
                        line: line.id 
                    }
                });
            }
        },
        async deleteLine(line) {
            if (confirm(`Are you sure you want to delete "${line.name}"?`)) {
                try {
                    // TODO: Implement actual delete API call
                    this.lines = this.lines.filter(l => l.id !== line.id);
                    this.filterLines();
                    alert('Line deleted successfully');
                } catch (error) {
                    console.error('Error deleting line:', error);
                    alert('Error deleting line');
                }
            }
        }
    }
}
</script>

<style scoped>
/* Reuse most styles from markers/Index.vue with line-specific modifications */
.lines-container {
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

.lines-list {
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

.lines-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
    padding: 1.5rem;
}

.line-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1rem;
    transition: all 0.2s;
}

.line-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.line-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.line-preview {
    width: 60px;
    height: 40px;
    background: #f9fafb;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e5e7eb;
}

.line-category {
    font-size: 0.75rem;
    background: #f3f4f6;
    color: #374151;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.line-content h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.line-content p {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0 0 0.75rem 0;
    line-height: 1.4;
}

.line-stats {
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

.line-actions {
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
.lines-table {
    overflow-x: auto;
}

.lines-table table {
    width: 100%;
    border-collapse: collapse;
}

.lines-table th,
.lines-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #f3f4f6;
}

.lines-table th {
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.line-name {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.line-color {
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

.length,
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
:global(.dark) .line-content h3,
:global(.dark) .stat-number {
    color: #f9fafb;
}

:global(.dark) .header-content p,
:global(.dark) .line-content p,
:global(.dark) .stat-label {
    color: #d1d5db;
}

:global(.dark) .lines-list,
:global(.dark) .stat-card,
:global(.dark) .line-card {
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

:global(.dark) .lines-table th {
    background: #374151;
    color: #d1d5db;
}

:global(.dark) .lines-table td {
    border-bottom-color: #374151;
}

:global(.dark) .line-category {
    background: #374151;
    color: #d1d5db;
}

:global(.dark) .line-actions {
    border-top-color: #374151;
}

:global(.dark) .line-preview {
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
    
    .lines-grid {
        grid-template-columns: 1fr;
    }
}
</style>
