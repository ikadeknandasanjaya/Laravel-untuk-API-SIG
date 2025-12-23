<template>
    <teleport to="body">
        <div class="toast-container">
            <transition-group name="toast" tag="div">
                <div
                    v-for="toast in toasts"
                    :key="toast.id"
                    :class="[
                        'toast',
                        `toast-${toast.type}`,
                        { 'toast-dismissible': toast.dismissible }
                    ]"
                >
                    <div class="toast-icon">
                        <i :class="getIcon(toast.type)"></i>
                    </div>
                    <div class="toast-content">
                        <div class="toast-title" v-if="toast.title">{{ toast.title }}</div>
                        <div class="toast-message">{{ toast.message }}</div>
                    </div>
                    <button
                        v-if="toast.dismissible"
                        @click="removeToast(toast.id)"
                        class="toast-close"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </transition-group>
        </div>
    </teleport>
</template>

<script>
export default {
    name: 'Toast',
    data() {
        return {
            toasts: []
        }
    },
    mounted() {
        // Listen for global toast events
        window.addEventListener('show-toast', this.handleToastEvent);
    },
    beforeUnmount() {
        window.removeEventListener('show-toast', this.handleToastEvent);
    },
    methods: {
        handleToastEvent(event) {
            this.addToast(event.detail);
        },
        addToast(options) {
            const toast = {
                id: Date.now() + Math.random(),
                type: options.type || 'info',
                title: options.title || '',
                message: options.message || '',
                duration: options.duration || 4000,
                dismissible: options.dismissible !== false
            };
            
            this.toasts.push(toast);
            
            if (toast.duration > 0) {
                setTimeout(() => {
                    this.removeToast(toast.id);
                }, toast.duration);
            }
        },
        removeToast(id) {
            const index = this.toasts.findIndex(toast => toast.id === id);
            if (index > -1) {
                this.toasts.splice(index, 1);
            }
        },
        getIcon(type) {
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            return icons[type] || icons.info;
        }
    }
}
</script>

<style scoped>
.toast-container {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    max-width: 400px;
}

.toast {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-left: 4px solid;
    min-width: 300px;
    transition: all 0.3s ease;
}

.toast-success {
    border-left-color: #10b981;
    background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
}

.toast-error {
    border-left-color: #ef4444;
    background: linear-gradient(135deg, #fef2f2 0%, #fef2f2 100%);
}

.toast-warning {
    border-left-color: #f59e0b;
    background: linear-gradient(135deg, #fffbeb 0%, #fefce8 100%);
}

.toast-info {
    border-left-color: #3b82f6;
    background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
}

.toast-icon {
    flex-shrink: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.toast-success .toast-icon {
    color: #10b981;
}

.toast-error .toast-icon {
    color: #ef4444;
}

.toast-warning .toast-icon {
    color: #f59e0b;
}

.toast-info .toast-icon {
    color: #3b82f6;
}

.toast-content {
    flex: 1;
}

.toast-title {
    font-weight: 600;
    font-size: 0.875rem;
    color: #111827;
    margin-bottom: 0.25rem;
}

.toast-message {
    font-size: 0.875rem;
    color: #6b7280;
    line-height: 1.4;
}

.toast-close {
    flex-shrink: 0;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    border-radius: 4px;
    transition: all 0.2s;
}

.toast-close:hover {
    background: rgba(0, 0, 0, 0.1);
    color: #6b7280;
}

/* Animations */
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(100%) scale(0.9);
}

.toast-leave-to {
    opacity: 0;
    transform: translateX(100%) scale(0.9);
}

.toast-move {
    transition: transform 0.3s ease;
}

/* Dark mode */
:global(.dark) .toast {
    background: #1f2937;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

:global(.dark) .toast-success {
    background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
}

:global(.dark) .toast-error {
    background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%);
}

:global(.dark) .toast-warning {
    background: linear-gradient(135deg, #78350f 0%, #92400e 100%);
}

:global(.dark) .toast-info {
    background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
}

:global(.dark) .toast-title {
    color: #f9fafb;
}

:global(.dark) .toast-message {
    color: #d1d5db;
}

:global(.dark) .toast-close {
    color: #9ca3af;
}

:global(.dark) .toast-close:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #d1d5db;
}

@media (max-width: 640px) {
    .toast-container {
        top: 0.5rem;
        right: 0.5rem;
        left: 0.5rem;
        max-width: none;
    }
    
    .toast {
        min-width: auto;
    }
}
</style>
