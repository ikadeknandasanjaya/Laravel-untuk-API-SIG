// Toast utility functions
export const toast = {
    success(message, title = '', options = {}) {
        this.show({
            type: 'success',
            title,
            message,
            ...options
        });
    },
    
    error(message, title = '', options = {}) {
        this.show({
            type: 'error',
            title,
            message,
            duration: 6000, // Error messages stay longer
            ...options
        });
    },
    
    warning(message, title = '', options = {}) {
        this.show({
            type: 'warning',
            title,
            message,
            ...options
        });
    },
    
    info(message, title = '', options = {}) {
        this.show({
            type: 'info',
            title,
            message,
            ...options
        });
    },
    
    show(options) {
        const event = new CustomEvent('show-toast', {
            detail: options
        });
        window.dispatchEvent(event);
    }
};

// Make it available globally
if (typeof window !== 'undefined') {
    window.$toast = toast;
}

export default toast;
