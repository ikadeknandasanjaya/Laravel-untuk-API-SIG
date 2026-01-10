import axios from 'axios';
import { REMOTE_API_URL } from '../config/api.js';

class RegionService {
    
    // Get remote token directly from localStorage (always fresh)
    getRemoteToken() {
        return localStorage.getItem('remote_auth_token');
    }
    
    async getAllRegions() {
        try {
            const remoteToken = this.getRemoteToken();
            
            if (!remoteToken) {
                console.warn('No remote token - API dosen not available. Using local data only.');
                // Return empty data structure - aplikasi tetap bisa jalan dengan data lokal
                return {
                    success: true,
                    data: {
                        provinsi: [],
                        kabupaten: [],
                        kecamatan: [],
                        desa: []
                    },
                    warning: 'API dosen tidak tersedia. Login dengan akun yang ter-register di API dosen untuk menggunakan fitur ini.'
                };
            }
            
            console.log('RegionService: getAllRegions with token:', remoteToken?.substring(0, 20) + '...');
            
            // Call /mregion endpoint yang return semua data (provinsi, kabupaten, kecamatan, desa)
            const headers = { Authorization: `Bearer ${remoteToken}` };
            const response = await axios.get(`${REMOTE_API_URL}/mregion`, { headers });
            
            if (response.data.status === 'success') {
                return {
                    success: true,
                    data: {
                        provinsi: response.data.provinsi || [],
                        kabupaten: response.data.kabupaten || [],
                        kecamatan: response.data.kecamatan || [],
                        desa: response.data.desa || []
                    }
                };
            } else {
                throw new Error('Failed to fetch regions');
            }
        } catch (error) {
            console.error('getAllRegions error:', error.message);
            // Return empty data dengan warning - aplikasi tetap bisa digunakan
            return {
                success: false,
                data: {
                    provinsi: [],
                    kabupaten: [],
                    kecamatan: [],
                    desa: []
                },
                message: 'API dosen tidak tersedia. Gunakan data lokal.',
                error: error.message
            };
        }
    }

    async getProvinsiById(provinsiId) {
        try {
            const remoteToken = this.getRemoteToken();
            const headers = remoteToken ? { Authorization: `Bearer ${remoteToken}` } : {};
            
            const response = await axios.get(`${REMOTE_API_URL}/provinsi/${provinsiId}`, { headers });
            
            if (response.data.status === 'success') {
                return {
                    success: true,
                    data: response.data.provinsi || []
                };
            } else {
                throw new Error('Failed to fetch provinsi');
            }
        } catch (error) {
            return {
                success: false,
                message: error.message || 'Failed to fetch provinsi'
            };
        }
    }

    async getKabupatenByProvinsiId(provinsiId) {
        try {
            const remoteToken = this.getRemoteToken();
            const headers = remoteToken ? { Authorization: `Bearer ${remoteToken}` } : {};
            
            const response = await axios.get(`${REMOTE_API_URL}/kabupaten/${provinsiId}`, { headers });
            
            if (response.data.status === 'success') {
                return {
                    success: true,
                    data: response.data.kabupaten || []
                };
            } else {
                throw new Error('Failed to fetch kabupaten');
            }
        } catch (error) {
            return {
                success: false,
                message: error.message || 'Failed to fetch kabupaten'
            };
        }
    }

    async getKecamatanByKabupatenId(kabupatenId) {
        try {
            const remoteToken = this.getRemoteToken();
            const headers = remoteToken ? { Authorization: `Bearer ${remoteToken}` } : {};
            
            const response = await axios.get(`${REMOTE_API_URL}/kecamatan/${kabupatenId}`, { headers });
            
            if (response.data.status === 'success') {
                return {
                    success: true,
                    data: response.data.kecamatan || []
                };
            } else {
                throw new Error('Failed to fetch kecamatan');
            }
        } catch (error) {
            return {
                success: false,
                message: error.message || 'Failed to fetch kecamatan'
            };
        }
    }

    async getDesaByKecamatanId(kecamatanId) {
        try {
            const remoteToken = this.getRemoteToken();
            const headers = remoteToken ? { Authorization: `Bearer ${remoteToken}` } : {};
            
            const response = await axios.get(`${REMOTE_API_URL}/desa/${kecamatanId}`, { headers });
            
            if (response.data.status === 'success') {
                return {
                    success: true,
                    data: response.data.desa || []
                };
            } else {
                throw new Error('Failed to fetch desa');
            }
        } catch (error) {
            return {
                success: false,
                message: error.message || 'Failed to fetch desa'
            };
        }
    }

    async getKecamatanByDesaId(desaId) {
        try {
            const response = await axios.get(`${API_BASE_URL}/kecamatanbydesaid/${desaId}`);
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    data: {
                        desa: response.data.desa,
                        kecamatan: response.data.kecamatan,
                        kabupaten: response.data.kabupaten,
                        provinsi: response.data.provinsi
                    }
                };
            } else {
                throw new Error('Failed to fetch region by desa');
            }
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || error.message || 'Failed to fetch region by desa'
            };
        }
    }

    async getKabupatenByKecamatanId(kecamatanId) {
        try {
            const response = await axios.get(`${API_BASE_URL}/kabupatenbykecamatanid/${kecamatanId}`);
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    data: {
                        kecamatan: response.data.kecamatan,
                        kabupaten: response.data.kabupaten,
                        provinsi: response.data.provinsi
                    }
                };
            } else {
                throw new Error('Failed to fetch region by kecamatan');
            }
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || error.message || 'Failed to fetch region by kecamatan'
            };
        }
    }

    async getProvinsiByKabupatenId(kabupatenId) {
        try {
            const response = await axios.get(`${API_BASE_URL}/provinsibykabupatenid/${kabupatenId}`);
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    data: {
                        kabupaten: response.data.kabupaten,
                        provinsi: response.data.provinsi
                    }
                };
            } else {
                throw new Error('Failed to fetch region by kabupaten');
            }
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || error.message || 'Failed to fetch region by kabupaten'
            };
        }
    }
}

export default new RegionService();
