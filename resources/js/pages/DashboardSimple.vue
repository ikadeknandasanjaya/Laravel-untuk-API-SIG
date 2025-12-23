<template>
    <AppLayout>
        <div class="dashboard-simple">
            <div class="welcome-section">
                <h1>🎉 Selamat Datang di Dashboard!</h1>
                <p>Login berhasil! Anda sekarang berada di dashboard GIS Bali.</p>
                
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <span>Dashboard berhasil dimuat</span>
                </div>
                
                <div class="quick-actions">
                    <h3>Menu Utama:</h3>
                    <div class="action-grid">
                        <router-link to="/ruasjalan" class="action-card">
                            <i class="fas fa-road"></i>
                            <span>Ruas Jalan</span>
                        </router-link>
                        
                        <router-link to="/markers" class="action-card">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Markers</span>
                        </router-link>
                        
                        <router-link to="/lines" class="action-card">
                            <i class="fas fa-route"></i>
                            <span>Lines</span>
                        </router-link>
                        
                        <router-link to="/polygons" class="action-card">
                            <i class="fas fa-draw-polygon"></i>
                            <span>Polygons</span>
                        </router-link>
                    </div>
                </div>
                
                <div class="debug-info">
                    <h4>Debug Info:</h4>
                    <ul>
                        <li>Current Route: {{ $route.path }}</li>
                        <li>User Authenticated: {{ isAuthenticated ? 'Yes' : 'No' }}</li>
                        <li>Current Time: {{ currentTime }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../components/AppLayout.vue';
import AuthService from '../services/AuthService.js';

export default {
    name: 'DashboardSimple',
    components: {
        AppLayout
    },
    data() {
        return {
            currentTime: new Date().toLocaleString()
        }
    },
    computed: {
        isAuthenticated() {
            return AuthService.isAuthenticated();
        }
    },
    mounted() {
        console.log('Dashboard Simple mounted successfully!');
        console.log('User authenticated:', this.isAuthenticated);
        console.log('Current route:', this.$route.path);
    }
}
</script>

<style scoped>
.dashboard-simple {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

.welcome-section {
    text-align: center;
}

.welcome-section h1 {
    font-size: 2.5rem;
    color: #10b981;
    margin-bottom: 1rem;
}

.welcome-section p {
    font-size: 1.25rem;
    color: #6b7280;
    margin-bottom: 2rem;
}

.success-message {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #d1fae5;
    color: #065f46;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    margin-bottom: 3rem;
}

.success-message i {
    font-size: 1.25rem;
}

.quick-actions {
    margin-bottom: 3rem;
}

.quick-actions h3 {
    color: #374151;
    margin-bottom: 1.5rem;
}

.action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    max-width: 600px;
    margin: 0 auto;
}

.action-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    padding: 1.5rem;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    text-decoration: none;
    color: #374151;
    transition: all 0.2s;
}

.action-card:hover {
    border-color: #3b82f6;
    background: #f8fafc;
    transform: translateY(-2px);
}

.action-card i {
    font-size: 2rem;
    color: #3b82f6;
}

.action-card span {
    font-weight: 600;
}

.debug-info {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: left;
    max-width: 400px;
    margin: 0 auto;
}

.debug-info h4 {
    color: #374151;
    margin-bottom: 1rem;
}

.debug-info ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.debug-info li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #e5e7eb;
    color: #6b7280;
}

.debug-info li:last-child {
    border-bottom: none;
}

@media (max-width: 640px) {
    .dashboard-simple {
        padding: 1rem;
    }
    
    .welcome-section h1 {
        font-size: 2rem;
    }
    
    .action-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
