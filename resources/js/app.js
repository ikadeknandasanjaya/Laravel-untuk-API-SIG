import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import routes from './routes';
import './utils/toast.js'; // Import toast utility
import AuthService from './services/AuthService.js';

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Normal Navigation guard with proper auth check
router.beforeEach((to, from, next) => {
    try {
        console.log('Navigation guard:', { to: to.path, from: from.path });
        
        // Check if route requires auth
        if (to.matched.some(record => record.meta.requiresAuth)) {
            const isAuth = AuthService.isAuthenticated();
            const isExpired = AuthService.isTokenExpired();
            
            console.log('Protected route check:', { isAuth, isExpired });
            
            if (isAuth && !isExpired) {
                console.log('Navigation guard: Access granted to protected route');
                next();
            } else {
                console.log('Navigation guard: Not authenticated, redirecting to login');
                // Clear invalid token
                if (isAuth && isExpired) {
                    AuthService.logout();
                }
                // Redirect to login with return url
                next({
                    name: 'login',
                    query: { redirect: to.fullPath }
                });
            }
        } else {
            // For public routes (login, register, welcome)
            console.log('Navigation guard: Public route, access granted');
            next();
        }
    } catch (error) {
        console.error('Navigation guard error:', error);
        next();
    }
});

const app = createApp(App);
app.use(router);
app.mount('#app');
