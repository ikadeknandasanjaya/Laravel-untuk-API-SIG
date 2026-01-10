<template>
    <AppLayout>
        <div class="create-customan">
            <div class="page-header">
                <h1>Tambah Customan</h1>
                <p>Tambahkan data customan baru ke sistem GIS</p>
                <router-link to="/customan" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </router-link>
            </div>

            <div class="form-container">
                <!-- <form @submit.prevent="" class="customan-form">
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Lokasi Administratif
                        </h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="provinsi_id">Provinsi</label>
                                <select id="provinsi_id" v-model="form.provinsi_id" required>
                                    <option value="">Pilih provinsi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form> -->
            </div>

            
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../../components/AppLayout.vue';

export default {
    name: 'CreateCustoman',
    components: {
        AppLayout
    },
   
}
</script>


<style scoped>
    .create-ruas-jalan {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        gap: 1rem;
    }
    
    .header-content h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 0.5rem;
    }
    
    .header-content p {
        color: #6b7280;
        margin: 0;
    }
    
    .back-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: #f9fafb;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        color: #374151;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .back-btn:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }
    
    .form-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .ruas-jalan-form {
        padding: 2rem;
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .form-section:last-child {
        margin-bottom: 0;
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f3f4f6;
    }
    
    .section-title i {
        color: #3b82f6;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    
    .form-group label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-group input:disabled,
    .form-group select:disabled,
    .form-group textarea:disabled {
        background: #f9fafb;
        cursor: not-allowed;
    }
    
    .map-container {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .map {
        height: 400px;
        width: 100%;
    }
    
    .map-instructions {
        padding: 1rem;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
    }
    
    .map-instructions p {
        margin: 0;
        font-size: 0.875rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .map-instructions i {
        color: #3b82f6;
    }
    
    .instruction-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
        color: #374151;
    }
    
    .instruction-item:last-child {
        margin-bottom: 0;
    }
    
    .instruction-item i {
        color: #3b82f6;
        margin-top: 0.125rem;
        flex-shrink: 0;
    }
    
    .instruction-item strong {
        color: #1f2937;
    }
    
    .path-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-top: 1rem;
        padding: 0.75rem;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 6px;
        font-size: 0.75rem;
    }
    
    .path-info i {
        color: #3b82f6;
    }
    
    .path-info code {
        background: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-family: 'Monaco', 'Courier New', monospace;
        color: #1e40af;
    }
    
    .form-actions {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
    }
    
    .submit-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .submit-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
    }
    
    .submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    
    .loading-spinner {
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Leaflet Draw Control Styling */
    .leaflet-draw-toolbar {
        margin-top: 10px !important;
    }
    
    .leaflet-draw-toolbar a {
        background-color: white !important;
        border: 2px solid rgba(0,0,0,0.2) !important;
        width: 34px !important;
        height: 34px !important;
        line-height: 32px !important;
    }
    
    .leaflet-draw-toolbar a:hover {
        background-color: #f4f4f4 !important;
        border-color: #3b82f6 !important;
    }
    
    .leaflet-draw-draw-polyline {
        background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiMzYjgyZjYiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cGF0aCBkPSJNNCAyMGg0TDIwIDhsLTQtNEw0IDIweiIvPjxwYXRoIGQ9Ik0xMy41IDcuNWwyLjUgMi41Ii8+PC9zdmc+') !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        background-size: 20px 20px !important;
    }
    
    .leaflet-draw-edit-edit {
        background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM0YjU1NjMiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cGF0aCBkPSJNMTcgM2E0IDQgMCAwIDEgNCA0djJhNCA0IDAgMCAxLTQgNEg3YTQgNCAwIDAgMS00LTRWN2E0IDQgMCAwIDEgNC00eiIvPjxwYXRoIGQ9Ik0xMiA4djgiLz48cGF0aCBkPSJNOCAxMmg4Ii8+PC9zdmc+') !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        background-size: 18px 18px !important;
    }
    
    .leaflet-draw-edit-remove {
        background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiNlZjQ0NDQiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cG9seWxpbmUgcG9pbnRzPSIzIDYgNSA2IDIxIDYiLz48cGF0aCBkPSJNMTkgNnYxNGEyIDIgMCAwIDEtMiAySDdhMiAyIDAgMCAxLTItMlY2bTE0IDBWNGEyIDIgMCAwIDAtMi0ySDlhMiAyIDAgMCAwLTIgMnYyIi8+PC9zdmc+') !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        background-size: 18px 18px !important;
    }
    
    .leaflet-draw-actions {
        left: 34px !important;
    }
    
    .leaflet-draw-actions a {
        background-color: white !important;
        color: #333 !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        padding: 0 10px !important;
        height: 28px !important;
        line-height: 28px !important;
    }
    
    /* COMMENTED OUT: Line Style Selector CSS tidak diperlukan
    .line-style-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 0.75rem;
    }
    
    .line-style-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem 0.75rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .line-style-option:hover,
    .line-style-option.active {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    
    .style-preview {
        font-size: 1.5rem;
        font-weight: bold;
        color: #6b7280;
        margin-bottom: 0.5rem;
        letter-spacing: 2px;
    }
    
    .line-style-option.active .style-preview {
        color: #3b82f6;
    }
    
    .line-style-option span {
        font-size: 0.75rem;
        color: #6b7280;
    }
    
    .line-style-option.active span {
        color: #3b82f6;
        font-weight: 600;
    }
    
    .color-selector {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .color-option {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .color-option:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .color-option.active {
        border-color: #1f2937;
        transform: scale(1.15);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .width-selector {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    
    .width-option {
        flex: 1;
        padding: 1rem 0.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 50px;
    }
    
    .width-option:hover,
    .width-option.active {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    
    .width-preview {
        width: 100%;
        max-width: 40px;
        border-radius: 2px;
        transition: all 0.2s;
    }
    */
    
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .ruas-jalan-form {
            padding: 1.5rem;
        }
        
        /* COMMENTED OUT: Line style responsive CSS
        .line-style-selector {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .width-selector {
            flex-wrap: wrap;
        }
        */
    }
    </style>