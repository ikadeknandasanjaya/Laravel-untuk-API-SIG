<template>
    <AppLayout>
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Markers Card -->
            <div class="stats-card">
                <div class="stats-header">
                    <h3>Markers</h3>
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="stats-number">{{ stats.markers || 0 }}</div>
            </div>

            <!-- Lines Card -->
            <div class="stats-card">
                <div class="stats-header">
                    <h3>Lines</h3>
                    <i class="fas fa-route"></i>
                </div>
                <div class="stats-number">{{ stats.lines || 0 }}</div>
            </div>

            <!-- Polygons Card -->
            <div class="stats-card">
                <div class="stats-header">
                    <h3>Polygons</h3>
                    <i class="fas fa-draw-polygon"></i>
                </div>
                <div class="stats-number">{{ stats.polygons || 0 }}</div>
            </div>

            <!-- Circles Card -->
            <div class="stats-card">
                <div class="stats-header">
                    <h3>Circles</h3>
                    <i class="fas fa-circle"></i>
                </div>
                <div class="stats-number">{{ stats.circles || 0 }}</div>
            </div>

            <!-- Marker Categories Card -->
            <div class="stats-card">
                <div class="stats-header">
                    <h3>Marker Categories</h3>
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stats-number">{{ stats.markerCategories || 0 }}</div>
            </div>

            <!-- Line Categories Card -->
            <div class="stats-card">
                <div class="stats-header">
                    <h3>Line Categories</h3>
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stats-number">{{ stats.lineCategories || 0 }}</div>
            </div>

            <!-- Polygon Categories Card -->
            <div class="stats-card">
                <div class="stats-header">
                    <h3>Polygon Categories</h3>
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stats-number">{{ stats.polygonCategories || 0 }}</div>
            </div>

            <!-- Circle Categories Card -->
            <div class="stats-card">
                <div class="stats-header">
                    <h3>Circle Categories</h3>
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stats-number">{{ stats.circleCategories || 0 }}</div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Total Spatial Data -->
            <div class="summary-card">
                <div class="summary-header">
                    <h3>Total Spatial Data</h3>
                </div>
                <div class="summary-content">
                    <div class="summary-item">
                        <span class="label">Markers:</span>
                        <span class="value">{{ stats.markers || 0 }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Lines:</span>
                        <span class="value">{{ stats.lines || 0 }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Polygons:</span>
                        <span class="value">{{ stats.polygons || 0 }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Circles:</span>
                        <span class="value">{{ stats.circles || 0 }}</span>
                    </div>
                    <div class="summary-total">
                        <span class="label">Total:</span>
                        <span class="value">{{ totalSpatialData }}</span>
                    </div>
                </div>
            </div>

            <!-- Total Categories Data -->
            <div class="summary-card">
                <div class="summary-header">
                    <h3>Total Categories Data</h3>
                </div>
                <div class="summary-content">
                    <div class="summary-item">
                        <span class="label">Marker Categories:</span>
                        <span class="value">{{ stats.markerCategories || 0 }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Line Categories:</span>
                        <span class="value">{{ stats.lineCategories || 0 }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Polygon Categories:</span>
                        <span class="value">{{ stats.polygonCategories || 0 }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Circle Categories:</span>
                        <span class="value">{{ stats.circleCategories || 0 }}</span>
                    </div>
                    <div class="summary-total">
                        <span class="label">Total:</span>
                        <span class="value">{{ totalCategoriesData }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3 class="section-title">Quick Actions</h3>
            <div class="actions-grid">
                <router-link to="/markers/create" class="action-card">
                    <i class="fas fa-plus"></i>
                    <span>Add Marker</span>
                </router-link>
                <router-link to="/lines/create" class="action-card">
                    <i class="fas fa-plus"></i>
                    <span>Add Line</span>
                </router-link>
                <router-link to="/polygons/create" class="action-card">
                    <i class="fas fa-plus"></i>
                    <span>Add Polygon</span>
                </router-link>
                <router-link to="/circles/create" class="action-card">
                    <i class="fas fa-plus"></i>
                    <span>Add Circle</span>
                </router-link>
                <router-link to="/map" class="action-card">
                    <i class="fas fa-map"></i>
                    <span>View Map</span>
                </router-link>
                <router-link to="/ruasjalan" class="action-card">
                    <i class="fas fa-road"></i>
                    <span>Street Data</span>
                </router-link>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../components/AppLayout.vue';
import { store } from '../store/index.js';

export default {
    name: 'Dashboard',
    components: {
        AppLayout
    },
    computed: {
        stats() {
            return store.stats;
        },
        totalSpatialData() {
            return (this.stats.markers || 0) + 
                   (this.stats.lines || 0) + 
                   (this.stats.polygons || 0) + 
                   (this.stats.circles || 0);
        },
        totalCategoriesData() {
            return (this.stats.markerCategories || 0) + 
                   (this.stats.lineCategories || 0) + 
                   (this.stats.polygonCategories || 0) + 
                   (this.stats.circleCategories || 0);
        }
    },
    async mounted() {
        try {
            await store.initializeStats();
        } catch (error) {
            console.error('Error initializing stats:', error);
            // Continue loading even if stats fail
        }
    }
}
</script>

<style scoped>
.stats-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    transition: all 0.2s;
}

.stats-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.stats-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.stats-header h3 {
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    margin: 0;
}

.stats-header i {
    font-size: 1.25rem;
    color: #9ca3af;
}

.stats-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #111827;
    line-height: 1;
}

.summary-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.summary-header {
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e5e7eb;
}

.summary-header h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.summary-content {
    space-y: 0.5rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
}

.summary-item .label {
    font-size: 0.875rem;
    color: #6b7280;
}

.summary-item .value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #111827;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    margin-top: 0.5rem;
    border-top: 1px solid #e5e7eb;
    font-weight: 600;
}

.summary-total .label {
    color: #374151;
}

.summary-total .value {
    color: #111827;
    font-size: 1rem;
}

.quick-actions {
    margin-top: 2rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 1rem;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.action-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    text-decoration: none;
    color: #374151;
    transition: all 0.2s;
}

.action-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
    color: #3b82f6;
}

.action-card i {
    font-size: 2rem;
    margin-bottom: 0.75rem;
    color: #6b7280;
    transition: color 0.2s;
}

.action-card:hover i {
    color: #3b82f6;
}

.action-card span {
    font-size: 0.875rem;
    font-weight: 500;
    text-align: center;
}

/* Dark mode styles */
:global(.dark) .stats-card,
:global(.dark) .summary-card,
:global(.dark) .action-card {
    background: #1f2937;
    border-color: #374151;
}

:global(.dark) .stats-header h3,
:global(.dark) .summary-item .label {
    color: #9ca3af;
}

:global(.dark) .stats-number,
:global(.dark) .summary-item .value,
:global(.dark) .summary-total .label,
:global(.dark) .summary-total .value,
:global(.dark) .section-title {
    color: #f9fafb;
}

:global(.dark) .summary-header {
    border-bottom-color: #374151;
}

:global(.dark) .summary-total {
    border-top-color: #374151;
}

:global(.dark) .action-card {
    color: #d1d5db;
}

:global(.dark) .action-card:hover {
    color: #60a5fa;
}

:global(.dark) .action-card i {
    color: #9ca3af;
}

:global(.dark) .action-card:hover i {
    color: #60a5fa;
}
</style>