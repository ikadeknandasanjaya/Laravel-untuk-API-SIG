import axios from 'axios';
import { LOCAL_API_URL } from '../config/api.js';

const API_URL = LOCAL_API_URL;

class CategoryService {
    constructor(type) {
        this.type = type; // 'marker', 'line', 'polygon', 'circle'
        this.endpoint = `${API_URL}/categories/${type}`;
    }

    async getAll() {
        try {
            console.log(`📡 Fetching ${this.type} categories...`);
            const response = await axios.get(this.endpoint);
            console.log(`✅ ${this.type} categories fetched:`, response.data);
            return response.data.data || response.data || [];
        } catch (error) {
            console.error(`Error fetching ${this.type} categories:`, error);
            throw error;
        }
    }

    async create(data) {
        try {
            console.log(`📝 Creating ${this.type} category:`, data);
            const response = await axios.post(this.endpoint, data);
            console.log(`✅ ${this.type} category created:`, response.data);
            return response.data.data || response.data;
        } catch (error) {
            console.error(`Error creating ${this.type} category:`, error);
            throw error;
        }
    }

    async update(id, data) {
        try {
            console.log(`📝 Updating ${this.type} category ${id}:`, data);
            const response = await axios.put(`${this.endpoint}/${id}`, data);
            console.log(`✅ ${this.type} category updated:`, response.data);
            return response.data.data || response.data;
        } catch (error) {
            console.error(`Error updating ${this.type} category:`, error);
            throw error;
        }
    }

    async delete(id) {
        try {
            console.log(`🗑️ Deleting ${this.type} category ${id}...`);
            const response = await axios.delete(`${this.endpoint}/${id}`);
            console.log(`✅ ${this.type} category deleted`);
            return response.data;
        } catch (error) {
            console.error(`Error deleting ${this.type} category:`, error);
            throw error;
        }
    }
}

export default CategoryService;
