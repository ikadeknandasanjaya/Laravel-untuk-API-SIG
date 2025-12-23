import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

class GeoFeatureService {
    constructor() {
        this.apiClient = axios.create({
            baseURL: API_BASE_URL,
            headers: {
                'Content-Type': 'application/json'
            }
        });

        // Add request interceptor for authentication
        this.apiClient.interceptors.request.use(
            (config) => {
                const token = localStorage.getItem('auth_token');
                if (token) {
                    config.headers.Authorization = `Bearer ${token}`;
                }
                return config;
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

    /**
     * Get all geo features with optional type filter
     * @param {string} type - Optional: 'marker', 'polygon', 'polyline', 'circle'
     */
    async getAllFeatures(type = null) {
        try {
            const params = type ? { type } : {};
            const response = await this.apiClient.get('/geo-features', { params });
            return response.data;
        } catch (error) {
            console.error('Error fetching geo features:', error);
            throw error;
        }
    }

    /**
     * Get markers only
     */
    async getMarkers() {
        try {
            const response = await this.apiClient.get('/geo-features/markers');
            return response.data;
        } catch (error) {
            console.error('Error fetching markers:', error);
            throw error;
        }
    }

    /**
     * Get polygons only
     */
    async getPolygons() {
        try {
            const response = await this.apiClient.get('/geo-features/polygons');
            return response.data;
        } catch (error) {
            console.error('Error fetching polygons:', error);
            throw error;
        }
    }

    /**
     * Get polylines only
     */
    async getPolylines() {
        try {
            const response = await this.apiClient.get('/geo-features/polylines');
            return response.data;
        } catch (error) {
            console.error('Error fetching polylines:', error);
            throw error;
        }
    }

    /**
     * Get circles only
     */
    async getCircles() {
        try {
            const response = await this.apiClient.get('/geo-features/circles');
            return response.data;
        } catch (error) {
            console.error('Error fetching circles:', error);
            throw error;
        }
    }

    /**
     * Get a single geo feature by ID
     */
    async getFeatureById(id) {
        try {
            const response = await this.apiClient.get(`/geo-features/${id}`);
            return response.data;
        } catch (error) {
            console.error('Error fetching geo feature:', error);
            throw error;
        }
    }

    /**
     * Create a new geo feature
     * @param {Object} featureData - Feature data
     * @param {string} featureData.nama - Name
     * @param {string} featureData.deskripsi - Description
     * @param {number} featureData.kategori_id - Category ID
     * @param {string} featureData.geometry_type - 'marker', 'polygon', 'polyline', 'circle'
     * @param {Array|Object} featureData.coordinates - Coordinates data
     * @param {Object} featureData.properties - Additional properties (color, radius, etc)
     */
    async createFeature(featureData) {
        try {
            const response = await this.apiClient.post('/geo-features', featureData);
            return response.data;
        } catch (error) {
            console.error('Error creating geo feature:', error);
            throw error;
        }
    }

    /**
     * Create a marker
     */
    async createMarker(markerData) {
        return this.createFeature({
            ...markerData,
            geometry_type: 'marker',
            coordinates: {
                lat: markerData.latitude,
                lng: markerData.longitude
            }
        });
    }

    /**
     * Create a polygon
     */
    async createPolygon(polygonData) {
        return this.createFeature({
            ...polygonData,
            geometry_type: 'polygon'
        });
    }

    /**
     * Create a polyline
     */
    async createPolyline(polylineData) {
        return this.createFeature({
            ...polylineData,
            geometry_type: 'polyline'
        });
    }

    /**
     * Create a circle
     */
    async createCircle(circleData) {
        return this.createFeature({
            ...circleData,
            geometry_type: 'circle',
            coordinates: {
                lat: circleData.latitude,
                lng: circleData.longitude
            },
            properties: {
                radius: circleData.radius,
                ...(circleData.properties || {})
            }
        });
    }

    /**
     * Update a geo feature
     */
    async updateFeature(id, featureData) {
        try {
            const response = await this.apiClient.put(`/geo-features/${id}`, featureData);
            return response.data;
        } catch (error) {
            console.error('Error updating geo feature:', error);
            throw error;
        }
    }

    /**
     * Delete a geo feature
     */
    async deleteFeature(id) {
        try {
            const response = await this.apiClient.delete(`/geo-features/${id}`);
            return response.data;
        } catch (error) {
            console.error('Error deleting geo feature:', error);
            throw error;
        }
    }
}

export default new GeoFeatureService();
