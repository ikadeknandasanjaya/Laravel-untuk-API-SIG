<template>
    <div class="toast-container">
        <Toast
            v-for="toast in toasts"
            :key="toast.id"
            :type="toast.type"
            :title="toast.title"
            :message="toast.message"
            :duration="toast.duration"
            @close="removeToast(toast.id)"
        />
    </div>
</template>

<script>
import Toast from './Toast.vue';

export default {
    name: 'ToastContainer',
    components: {
        Toast
    },
    data() {
        return {
            toasts: []
        };
    },
    mounted() {
        // Global event listener untuk menambah toast
        window.addEventListener('show-toast', this.addToast);
    },
    beforeUnmount() {
        window.removeEventListener('show-toast', this.addToast);
    },
    methods: {
        addToast(event) {
            // Validasi: jangan tampilkan toast jika title kosong atau hanya whitespace
            const title = event.detail.title?.trim();
            if (!title) {
                console.warn('Toast ignored: empty title');
                return;
            }

            const toast = {
                id: Date.now(),
                type: event.detail.type || 'info',
                title: title,
                message: event.detail.message?.trim() || '',
                duration: event.detail.duration || 3000
            };
            this.toasts.push(toast);
        },
        removeToast(id) {
            this.toasts = this.toasts.filter(toast => toast.id !== id);
        }
    }
};
</script>

<style scoped>
.toast-container {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 9999;
    pointer-events: none;
}

.toast-container :deep(.toast) {
    pointer-events: all;
    margin-bottom: 10px;
}
</style>

