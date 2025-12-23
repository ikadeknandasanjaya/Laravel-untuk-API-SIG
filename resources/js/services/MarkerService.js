import axios from 'axios';

const API_BASE_URL = 'https://gisapis.manpits.xyz/api';

class MarkerService {
    constructor() {
        this.setupInterceptors();
    }

    setupInterceptors() {
        axios.interceptors.request.use(
            (config) => {
                const token = localStorage.getItem('auth_token');
                if (token && config.url?.includes(API_BASE_URL)) {
                    config.headers.Authorization = `Bearer ${token}`;
                }
                return config;
            },
            (error) => Promise.reject(error)
        );
    }

    // Get all markers (wisata)
    async getAllMarkers() {
        try {
            const response = await axios.get(`${API_BASE_URL}/wisata`);
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    data: response.data.wisata || []
                };
            }
            
            throw new Error(response.data.status || 'Failed to fetch markers');
        } catch (error) {
            console.error('Get all markers error:', error);
            return {
                success: false,
                message: error.response?.data?.status || error.message || 'Failed to fetch markers'
            };
        }
    }

    // Get marker by ID
    async getMarkerById(id) {
        try {
            const response = await axios.get(`${API_BASE_URL}/wisata/${id}`);
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    data: response.data.wisata
                };
            }
            
            throw new Error(response.data.status || 'Failed to fetch marker');
        } catch (error) {
            console.error('Get marker by ID error:', error);
            return {
                success: false,
                message: error.response?.data?.status || error.message || 'Failed to fetch marker'
            };
        }
    }

    // Create new marker
    async createMarker(markerData) {
        try {
            const response = await axios.post(`${API_BASE_URL}/wisata`, markerData);
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    data: response.data.wisata,
                    message: response.data.status
                };
            }
            
            throw new Error(response.data.status || 'Failed to create marker');
        } catch (error) {
            console.error('Create marker error:', error);
            return {
                success: false,
                message: error.response?.data?.status || error.message || 'Failed to create marker'
            };
        }
    }

    // Update marker
    async updateMarker(id, markerData) {
        try {
            const response = await axios.put(`${API_BASE_URL}/wisata/${id}`, markerData);
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    data: response.data.wisata,
                    message: response.data.status
                };
            }
            
            throw new Error(response.data.status || 'Failed to update marker');
        } catch (error) {
            console.error('Update marker error:', error);
            return {
                success: false,
                message: error.response?.data?.status || error.message || 'Failed to update marker'
            };
        }
    }

    // Delete marker
    async deleteMarker(id) {
        try {
            const response = await axios.delete(`${API_BASE_URL}/wisata/${id}`);
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    message: response.data.status
                };
            }
            
            throw new Error(response.data.status || 'Failed to delete marker');
        } catch (error) {
            console.error('Delete marker error:', error);
            return {
                success: false,
                message: error.response?.data?.status || error.message || 'Failed to delete marker'
            };
        }
    }

    // Get all region data (provinsi, kabupaten, kecamatan, desa)
    async getAllRegion() {
        try {
            const response = await axios.get(`${API_BASE_URL}/mregion`);
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    data: {
                        provinsi: response.data.provinsi || [],
                        kabupaten: response.data.kabupaten || [],
                        kecamatan: response.data.kecamatan || [],
                        desa: response.data.desa || []
                    }
                };
            }
            
            throw new Error(response.data.status || 'Failed to fetch region data');
        } catch (error) {
            console.error('Get all region error:', error);
            return {
                success: false,
                message: error.response?.data?.status || error.message || 'Failed to fetch region data'
            };
        }
    }

    // Get desa by kecamatan ID
    async getDesaByKecamatanId(kecamatanId) {
        try {
            const response = await axios.get(`${API_BASE_URL}/desa/${kecamatanId}`);
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    data: response.data.desa || []
                };
            }
            
            throw new Error(response.data.status || 'Failed to fetch desa');
        } catch (error) {
            console.error('Get desa error:', error);
            return {
                success: false,
                message: error.response?.data?.status || error.message || 'Failed to fetch desa'
            };
        }
    }
}

export default new MarkerService();
