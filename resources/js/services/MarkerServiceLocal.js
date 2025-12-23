// MarkerService.js - Using Local Backend (Supabase)
// This service wraps GeoFeatureService for backward compatibility
import GeoFeatureService from './GeoFeatureService';

class MarkerService {
    /**
     * Get all markers
     */
    async getAllMarkers() {
        try {
            const response = await GeoFeatureService.getMarkers();
            // Transform to match expected format
            return {
                success: response.success,
                data: response.data || []
            };
        } catch (error) {
            console.error('Error fetching markers:', error);
            return {
                success: false,
                message: error.response?.data?.message || 'Failed to fetch markers'
            };
        }
    }

    /**
     * Get marker by ID
     */
    async getMarkerById(id) {
        try {
            const response = await GeoFeatureService.getFeatureById(id);
            return {
                success: response.success,
                data: response.data
            };
        } catch (error) {
            console.error('Error fetching marker:', error);
            return {
                success: false,
                message: error.response?.data?.message || 'Failed to fetch marker'
            };
        }
    }

    /**
     * Create a new marker
     * @param {Object} markerData - Marker data containing nama, deskripsi, kategori_id, latitude, longitude
     */
    async createMarker(markerData) {
        try {
            const response = await GeoFeatureService.createMarker({
                nama: markerData.nama,
                deskripsi: markerData.deskripsi,
                kategori_id: markerData.kategori_id,
                latitude: markerData.latitude,
                longitude: markerData.longitude,
                properties: markerData.properties || {}
            });
            
            return {
                success: response.success,
                data: response.data,
                message: response.message
            };
        } catch (error) {
            console.error('Error creating marker:', error);
            return {
                success: false,
                message: error.response?.data?.message || 'Failed to create marker'
            };
        }
    }

    /**
     * Update a marker
     */
    async updateMarker(id, markerData) {
        try {
            const updateData = {
                nama: markerData.nama,
                deskripsi: markerData.deskripsi,
                kategori_id: markerData.kategori_id,
                coordinates: {
                    lat: markerData.latitude,
                    lng: markerData.longitude
                },
                properties: markerData.properties || {}
            };
            
            const response = await GeoFeatureService.updateFeature(id, updateData);
            
            return {
                success: response.success,
                data: response.data,
                message: response.message
            };
        } catch (error) {
            console.error('Error updating marker:', error);
            return {
                success: false,
                message: error.response?.data?.message || 'Failed to update marker'
            };
        }
    }

    /**
     * Delete a marker
     */
    async deleteMarker(id) {
        try {
            const response = await GeoFeatureService.deleteFeature(id);
            
            return {
                success: response.success,
                message: response.message
            };
        } catch (error) {
            console.error('Error deleting marker:', error);
            return {
                success: false,
                message: error.response?.data?.message || 'Failed to delete marker'
            };
        }
    }
}

export default new MarkerService();
