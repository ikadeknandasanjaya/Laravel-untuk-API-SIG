/**
 * API Configuration
 * Central configuration for all API endpoints
 */

// Local Laravel Backend
export const LOCAL_API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8001/api';

// Remote API (Dosen)
export const REMOTE_API_URL = 'https://gisapis.manpits.xyz/api';

// API Timeouts (in milliseconds)
export const API_TIMEOUT = {
    DEFAULT: 10000,
    UPLOAD: 30000,
    LONG: 60000
};

export default {
    LOCAL_API_URL,
    REMOTE_API_URL,
    API_TIMEOUT
};

