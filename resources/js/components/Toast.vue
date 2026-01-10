<template>
    <transition name="toast">
        <div v-if="visible" :class="['toast', `toast-${type}`]" @click="close">
            <div class="toast-icon">
                <i v-if="type === 'success'" class="fas fa-check-circle"></i>
                <i v-else-if="type === 'error'" class="fas fa-times-circle"></i>
                <i v-else-if="type === 'warning'" class="fas fa-exclamation-triangle"></i>
                <i v-else class="fas fa-info-circle"></i>
            </div>
            <div class="toast-content">
                <div v-if="title" class="toast-title">{{ title }}</div>
                <div v-if="message" class="toast-message">{{ message }}</div>
            </div>
            <button @click.stop="close" class="toast-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'Toast',
    props: {
        type: {
            type: String,
            default: 'info',
            validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
        },
        title: {
            type: String,
            required: true
        },
        message: {
            type: String,
            default: ''
        },
        duration: {
            type: Number,
            default: 3000
        },
        autoClose: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            visible: false,
            timer: null
        };
    },
    mounted() {
        // Jangan show toast jika title kosong
        if (!this.title || !this.title.trim()) {
            console.warn('Toast component mounted with empty title - auto closing');
            this.$emit('close');
            return;
        }
        this.show();
    },
    methods: {
        show() {
            this.visible = true;
            if (this.autoClose && this.duration > 0) {
                this.timer = setTimeout(() => {
                    this.close();
                }, this.duration);
            }
        },
        close() {
            this.visible = false;
            if (this.timer) {
                clearTimeout(this.timer);
            }
            setTimeout(() => {
                this.$emit('close');
            }, 300);
        }
    },
    beforeUnmount() {
        if (this.timer) {
            clearTimeout(this.timer);
        }
    }
};
</script>

<style scoped>
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    min-width: 300px;
    max-width: 500px;
    background: white;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: flex-start;
    gap: 12px;
    z-index: 9999;
    cursor: pointer;
    border-left: 4px solid;
}

.toast-success {
    border-left-color: #10b981;
}

.toast-error {
    border-left-color: #ef4444;
}

.toast-warning {
    border-left-color: #f59e0b;
}

.toast-info {
    border-left-color: #3b82f6;
}

.toast-icon {
    font-size: 24px;
    flex-shrink: 0;
    margin-top: 2px;
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
    font-size: 14px;
    color: #1f2937;
    margin-bottom: 4px;
}

.toast-message {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.4;
}

.toast-close {
    flex-shrink: 0;
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s;
    font-size: 14px;
}

.toast-close:hover {
    background: #f3f4f6;
    color: #6b7280;
}

/* Toast Animation */
.toast-enter-active {
    animation: toast-in 0.3s ease-out;
}

.toast-leave-active {
    animation: toast-out 0.3s ease-in;
}

@keyframes toast-in {
    0% {
        opacity: 0;
        transform: translateX(100%) translateY(-20px);
    }
    100% {
        opacity: 1;
        transform: translateX(0) translateY(0);
    }
}

@keyframes toast-out {
    0% {
        opacity: 1;
        transform: translateX(0) translateY(0);
    }
    100% {
        opacity: 0;
        transform: translateX(100%) translateY(-20px);
    }
}

@media (max-width: 640px) {
    .toast {
        top: 10px;
        right: 10px;
        left: 10px;
        min-width: auto;
    }
}
</style>
