// Simple reactive store for managing global state
import { reactive } from 'vue';

export const store = reactive({
    // Spatial data counts
    stats: {
        markers: 0,
        lines: 0,
        polygons: 0,
        circles: 0,
        markerCategories: 0,
        lineCategories: 0,
        polygonCategories: 0,
        circleCategories: 0
    },
    
    // Data arrays (for future use)
    data: {
        markers: [],
        lines: [],
        polygons: [],
        circles: []
    },
    
    // Methods to update stats
    updateStats(type, count) {
        if (this.stats.hasOwnProperty(type)) {
            this.stats[type] = count;
        }
    },
    
    incrementStat(type) {
        if (this.stats.hasOwnProperty(type)) {
            this.stats[type]++;
        }
    },
    
    decrementStat(type) {
        if (this.stats.hasOwnProperty(type) && this.stats[type] > 0) {
            this.stats[type]--;
        }
    },
    
    // Methods to manage data
    addItem(type, item) {
        if (this.data.hasOwnProperty(type)) {
            this.data[type].push(item);
            this.incrementStat(type);
        }
    },
    
    removeItem(type, id) {
        if (this.data.hasOwnProperty(type)) {
            const index = this.data[type].findIndex(item => item.id === id);
            if (index > -1) {
                this.data[type].splice(index, 1);
                this.decrementStat(type);
            }
        }
    },
    
    // Initialize stats (call this on app startup)
    async initializeStats() {
        try {
            // TODO: Replace with actual API calls
            // For now, load from localStorage or set defaults
            const savedStats = localStorage.getItem('gis-stats');
            if (savedStats) {
                const parsed = JSON.parse(savedStats);
                Object.assign(this.stats, parsed);
            } else {
                // Set default categories count
                this.stats.markerCategories = 6;
                this.stats.lineCategories = 6;
                this.stats.polygonCategories = 5;
                this.stats.circleCategories = 5;
            }
        } catch (error) {
            console.error('Error initializing stats:', error);
        }
    },
    
    // Save stats to localStorage
    saveStats() {
        try {
            localStorage.setItem('gis-stats', JSON.stringify(this.stats));
        } catch (error) {
            console.error('Error saving stats:', error);
        }
    }
});

// Auto-save stats when they change
let saveTimeout;
const originalUpdateStats = store.updateStats;
const originalIncrementStat = store.incrementStat;
const originalDecrementStat = store.decrementStat;

store.updateStats = function(type, count) {
    originalUpdateStats.call(this, type, count);
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => this.saveStats(), 1000);
};

store.incrementStat = function(type) {
    originalIncrementStat.call(this, type);
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => this.saveStats(), 1000);
};

store.decrementStat = function(type) {
    originalDecrementStat.call(this, type);
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => this.saveStats(), 1000);
};

export default store;
