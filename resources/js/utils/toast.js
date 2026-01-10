export const showToast = (type, title, message = '', duration = 3000) => {
    // Validasi: jangan kirim event jika title kosong atau hanya whitespace
    if (!title || !title.trim()) {
        console.warn('Toast tidak ditampilkan: title kosong', { type, title, message });
        return;
    }
    
    const event = new CustomEvent('show-toast', {
        detail: { type, title, message, duration }
    });
    window.dispatchEvent(event);
};

export default {
    success(title, message = '') {
        if (!title || !title.trim()) {
            console.warn('toast.success dipanggil dengan title kosong');
            return;
        }
        showToast('success', title, message, 3000);
    },
    error(title, message = '') {
        if (!title || !title.trim()) {
            console.warn('toast.error dipanggil dengan title kosong');
            return;
        }
        showToast('error', title, message, 4000);
    },
    warning(title, message = '') {
        if (!title || !title.trim()) {
            console.warn('toast.warning dipanggil dengan title kosong');
            return;
        }
        showToast('warning', title, message, 3500);
    },
    info(title, message = '') {
        if (!title || !title.trim()) {
            console.warn('toast.info dipanggil dengan title kosong');
            return;
        }
        showToast('info', title, message, 3000);
    }
};
