import axios from 'axios';

const API_BASE_URL = 'https://gisapis.manpits.xyz/api';

class RuasJalanService {
    
    // Get remote token directly from localStorage (always fresh)
    getRemoteToken() {
        return localStorage.getItem('remote_auth_token');
    }
    
    async getAllRuasJalan() {
        try {
            const remoteToken = this.getRemoteToken();
            const headers = remoteToken ? { Authorization: `Bearer ${remoteToken}` } : {};
            
            const response = await axios.get(`${API_BASE_URL}/ruasjalan`, { headers });
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    data: {
                        user: response.data.user,
                        ruasjalan: response.data.ruasjalan
                    }
                };
            } else {
                throw new Error('Failed to fetch ruas jalan');
            }
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || error.message || 'Failed to fetch ruas jalan'
            };
        }
    }

    async getRuasJalanById(id) {
        try {
            const remoteToken = this.getRemoteToken();
            const headers = remoteToken ? { Authorization: `Bearer ${remoteToken}` } : {};
            
            console.log('Getting ruas jalan ID:', id);
            console.log('Token:', remoteToken ? 'Present' : 'Missing');
            
            const response = await axios.get(`${API_BASE_URL}/ruasjalan/${id}`, { headers });
            
            console.log('Response:', response.data);
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    data: {
                        user: response.data.user,
                        ruasjalan: response.data.ruasjalan
                    }
                };
            } else {
                throw new Error('Failed to fetch ruas jalan');
            }
        } catch (error) {
            console.error('Error in getRuasJalanById:', error);
            return {
                success: false,
                message: error.response?.data?.message || error.message || 'Failed to fetch ruas jalan'
            };
        }
    }

    async createRuasJalan(ruasJalanData) {
        try {
            const remoteToken = this.getRemoteToken();
            const headers = remoteToken ? { Authorization: `Bearer ${remoteToken}` } : {};
            
            // Ensure keterangan is never null or empty
            const payload = {
                paths: ruasJalanData.paths,
                desa_id: ruasJalanData.desa_id,
                kode_ruas: ruasJalanData.kode_ruas,
                nama_ruas: ruasJalanData.nama_ruas,
                panjang: ruasJalanData.panjang,
                lebar: ruasJalanData.lebar,
                eksisting_id: ruasJalanData.eksisting_id,
                kondisi_id: ruasJalanData.kondisi_id,
                jenisjalan_id: ruasJalanData.jenisjalan_id,
                keterangan: ruasJalanData.keterangan || '-'
            };
            
            console.log('POST to Dosen API:', `${API_BASE_URL}/ruasjalan`);
            console.log('Payload:', payload);
            console.log('Headers:', headers);
            
            const response = await axios.post(`${API_BASE_URL}/ruasjalan`, payload, { headers });
            
            if (response.data.code === 200 || response.status === 201) {
                return {
                    success: true,
                    data: response.data,
                    message: 'Ruas jalan berhasil ditambahkan'
                };
            } else {
                throw new Error('Failed to create ruas jalan');
            }
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || error.message || 'Failed to create ruas jalan'
            };
        }
    }

    async updateRuasJalan(id, ruasJalanData) {
        try {
            const remoteToken = this.getRemoteToken();
            const headers = remoteToken ? { Authorization: `Bearer ${remoteToken}` } : {};
            
            const response = await axios.put(`${API_BASE_URL}/ruasjalan/${id}`, {
                paths: ruasJalanData.paths,
                desa_id: ruasJalanData.desa_id,
                kode_ruas: ruasJalanData.kode_ruas,
                nama_ruas: ruasJalanData.nama_ruas,
                panjang: ruasJalanData.panjang,
                lebar: ruasJalanData.lebar,
                eksisting_id: ruasJalanData.eksisting_id,
                kondisi_id: ruasJalanData.kondisi_id,
                jenisjalan_id: ruasJalanData.jenisjalan_id,
                keterangan: ruasJalanData.keterangan
            }, { headers });
            
            if (response.data.code === 200 || response.status === 200) {
                return {
                    success: true,
                    data: response.data,
                    message: 'Ruas jalan berhasil diupdate'
                };
            } else {
                throw new Error('Failed to update ruas jalan');
            }
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || error.message || 'Failed to update ruas jalan'
            };
        }
    }

    async deleteRuasJalan(id) {
        try {
            const remoteToken = this.getRemoteToken();
            const headers = remoteToken ? { Authorization: `Bearer ${remoteToken}` } : {};
            
            const response = await axios.delete(`${API_BASE_URL}/ruasjalan/${id}`, { headers });
            
            if (response.status === 200 || response.status === 204) {
                return {
                    success: true,
                    message: 'Ruas jalan berhasil dihapus'
                };
            } else {
                throw new Error('Failed to delete ruas jalan');
            }
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || error.message || 'Failed to delete ruas jalan'
            };
        }
    }

    // Master data methods
    async getMasterEksisting() {
        try {
            const remoteToken = this.getRemoteToken();
            const headers = remoteToken ? { Authorization: `Bearer ${remoteToken}` } : {};
            
            const response = await axios.get(`${API_BASE_URL}/meksisting`, { headers });
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    data: response.data.eksisting
                };
            } else {
                throw new Error('Failed to fetch master eksisting');
            }
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || error.message || 'Failed to fetch master eksisting'
            };
        }
    }

    async getMasterJenisJalan() {
        try {
            const remoteToken = this.getRemoteToken();
            const headers = remoteToken ? { Authorization: `Bearer ${remoteToken}` } : {};
            
            const response = await axios.get(`${API_BASE_URL}/mjenisjalan`, { headers });
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    data: response.data.eksisting // Note: API returns 'eksisting' field for jenis jalan
                };
            } else {
                throw new Error('Failed to fetch master jenis jalan');
            }
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || error.message || 'Failed to fetch master jenis jalan'
            };
        }
    }

    async getMasterKondisi() {
        try {
            const remoteToken = this.getRemoteToken();
            const headers = remoteToken ? { Authorization: `Bearer ${remoteToken}` } : {};
            
            const response = await axios.get(`${API_BASE_URL}/mkondisi`, { headers });
            
            if (response.data.code === 200) {
                return {
                    success: true,
                    data: response.data.eksisting // Note: API returns 'eksisting' field for kondisi
                };
            } else {
                throw new Error('Failed to fetch master kondisi');
            }
        } catch (error) {
            return {
                success: false,
                message: error.response?.data?.message || error.message || 'Failed to fetch master kondisi'
            };
        }
    }

    // Utility method to encode polyline (you might need a polyline encoding library)
    encodePolyline(coordinates) {
        // This is a placeholder - you should use a proper polyline encoding library
        // like @mapbox/polyline or similar
        return JSON.stringify(coordinates);
    }

    // Utility method to decode polyline
    decodePolyline(encodedPath) {
        try {
            // Handle different path formats from API
            if (encodedPath.startsWith('[') && encodedPath.endsWith(']')) {
                // JSON array format
                return JSON.parse(encodedPath);
            } else if (encodedPath === 'click_map') {
                // Special case for click_map
                return null;
            } else {
                // Encoded polyline format - you might need a decoding library
                return encodedPath;
            }
        } catch (error) {
            console.error('Error decoding polyline:', error);
            return null;
        }
    }
}

export default new RuasJalanService();
