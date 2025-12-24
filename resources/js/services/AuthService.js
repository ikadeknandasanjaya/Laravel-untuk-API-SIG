import axios from 'axios';

const REMOTE_API_URL = 'https://gisapis.manpits.xyz/api';
const LOCAL_API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

class AuthService {
    constructor() {
        this.remoteToken = localStorage.getItem('remote_auth_token');
        this.localToken = localStorage.getItem('local_auth_token');
        this.remoteUser = JSON.parse(localStorage.getItem('remote_user') || 'null');
        this.localUser = JSON.parse(localStorage.getItem('local_user') || 'null');
        this.loginSystem = localStorage.getItem('login_system') || null;
        this.interceptorsSetup = false;
        this.setupAxiosInterceptors();
    }

    setupAxiosInterceptors() {
        if (this.interceptorsSetup) return;
        
        axios.interceptors.request.use(
            (config) => {
                // For remote API: RegionService handles token, don't override here
                if (config.url?.includes(REMOTE_API_URL)) {
                    // Skip - let RegionService set the Authorization header
                    return config;
                }
                
                // For local API: use interceptor
                if (config.url?.includes(LOCAL_API_URL) && this.localToken) {
                    config.headers.Authorization = `Bearer ${this.localToken}`;
                }
                return config;
            },
            (error) => Promise.reject(error)
        );

        axios.interceptors.response.use(
            (response) => response,
            (error) => {
                if (error.response?.status === 401) {
                    if (error.config?.url?.includes(REMOTE_API_URL)) {
                        this.logoutRemote();
                    } else if (error.config?.url?.includes(LOCAL_API_URL)) {
                        this.logoutLocal();
                    }
                }
                return Promise.reject(error);
            }
        );
        
        this.interceptorsSetup = true;
    }

    // LOGIN (WRAPPER - calls loginDual which attempts both local & remote)
    async login(credentials) {
        return this.loginDual(credentials);
    }

    // LOGIN (LOCAL + REMOTE DUAL - attempt both simultaneously)
    async loginDual(credentials) {
        try {
            console.log('🔐 Attempting DUAL LOGIN (local + remote)...');
            
            const response = await axios.post(`${LOCAL_API_URL}/login`, {
                email: credentials.email,
                password: credentials.password
            }, { timeout: 15000 });

            if (response.data?.access_token) {
                // Store LOCAL token (Sanctum)
                this.localToken = response.data.access_token;
                localStorage.setItem('local_auth_token', this.localToken);
                console.log('✅ LOCAL token saved (Sanctum):', this.localToken.substring(0, 20) + '...');
                
                // Store REMOTE token if available (JWT from Dosen API)
                if (response.data?.remote_token) {
                    this.remoteToken = response.data.remote_token;
                    localStorage.setItem('remote_auth_token', this.remoteToken);
                    console.log('✅ REMOTE token saved (JWT):', this.remoteToken.substring(0, 20) + '...');
                }
                
                this.loginSystem = 'local';
                localStorage.setItem('login_system', 'local');
                
                // Get user data from both systems
                await this.getLocalUser();
                if (response.data?.remote_success) {
                    await this.getRemoteUser();
                }
                
                return { 
                    success: true, 
                    system: 'dual',
                    localSuccess: true,
                    remoteSuccess: response.data?.remote_success || false,
                    message: response.data?.remote_success ? 'Login ke kedua sistem berhasil!' : 'Login lokal berhasil!' 
                };
            }
        } catch (error) {
            console.error('Dual login error:', error);
            const errorMsg = error.response?.data?.message || 'Login gagal';
            return { 
                success: false, 
                system: 'dual', 
                message: errorMsg
            };
        }
    }

    // REGISTER (WRAPPER - calls registerDual)
    async registerDual(credentials) {
        try {
            console.log('Registering user (dual system)...');
            console.log('Sending credentials:', {
                name: credentials.name,
                email: credentials.email,
                password: credentials.password ? '***' : 'empty',
                password_confirmation: credentials.password_confirmation ? '***' : 'empty'
            });
            
            const response = await axios.post(`${LOCAL_API_URL}/register`, {
                name: credentials.name,
                email: credentials.email,
                password: credentials.password,
                password_confirmation: credentials.password_confirmation
            }, { timeout: 15000 });

            if (response.data?.access_token) {
                // Store LOCAL token
                this.localToken = response.data.access_token;
                localStorage.setItem('local_auth_token', this.localToken);
                console.log('✅ LOCAL token saved:', this.localToken.substring(0, 20) + '...');
                
                // Note: Remote registration is done in backend
                // But token is obtained via login() method later
                console.log('Registered locally. Remote registration status:', response.data?.remote_success);

                this.loginSystem = 'local';
                localStorage.setItem('login_system', 'local');
                
                await this.getLocalUser();
                
                return { 
                    success: true, 
                    system: 'local', 
                    remoteSuccess: response.data?.remote_success || false,
                    message: 'Registrasi berhasil!' 
                };
            }
        } catch (error) {
            console.error('Register error full:', error.response?.data || error);
            const errorMsg = error.response?.data?.message || 
                           (error.response?.data?.errors ? Object.values(error.response.data.errors).flat().join(', ') : 'Registrasi gagal');
            return { 
                success: false, 
                system: 'local', 
                message: errorMsg
            };
        }
    }

    // REGISTER LOCAL ONLY
    async registerLocal(credentials) {
        try {
            console.log('Registering user (local only)...');
            const response = await axios.post(`${LOCAL_API_URL}/register`, {
                name: credentials.name,
                email: credentials.email,
                password: credentials.password,
                password_confirmation: credentials.password_confirmation
            }, { timeout: 15000 });

            if (response.data?.access_token) {
                this.localToken = response.data.access_token;
                this.loginSystem = 'local';
                
                localStorage.setItem('local_auth_token', this.localToken);
                localStorage.setItem('login_system', 'local');
                
                await this.getLocalUser();
                
                return { success: true, system: 'local', message: 'Registrasi lokal berhasil!' };
            }
        } catch (error) {
            console.error('Local register error:', error);
            return { success: false, system: 'local', message: error.response?.data?.message || 'Registrasi lokal gagal' };
        }
    }

    // LOGIN REMOTE (API DOSEN)
    async loginRemote(credentials) {
        try {
            console.log('Logging in to REMOTE API...');
            const response = await axios.post(`${REMOTE_API_URL}/login`, {
                email: credentials.email,
                password: credentials.password
            }, { timeout: 15000 });

            if (response.data.meta?.code === 200) {
                this.remoteToken = response.data.meta.token;
                this.loginSystem = 'remote';
                
                const expiryTimestamp = Date.now() + (response.data.meta['token-expired'] * 1000);
                localStorage.setItem('remote_auth_token', this.remoteToken);
                localStorage.setItem('remote_token_expired', expiryTimestamp.toString());
                localStorage.setItem('login_system', 'remote');
                
                await this.getRemoteUser();
                
                return { success: true, system: 'remote', message: 'Login API berhasil!' };
            }
        } catch (error) {
            console.error('Remote login error:', error);
            return { success: false, system: 'remote', message: error.response?.data?.meta?.message || 'Login API gagal' };
        }
    }

    // LOGIN LOKAL
    async loginLocal(credentials) {
        try {
            console.log('Logging in to LOCAL system...');
            const response = await axios.post(`${LOCAL_API_URL}/login`, {
                email: credentials.email,
                password: credentials.password
            }, { timeout: 15000 });

            if (response.data?.access_token || response.data?.token) {
                // Store LOCAL token
                this.localToken = response.data.access_token || response.data.token;
                localStorage.setItem('local_auth_token', this.localToken);
                console.log('✅ LOCAL token saved:', this.localToken.substring(0, 20) + '...');
                
                // Store REMOTE token if available (from dual login)
                if (response.data?.remote_token) {
                    this.remoteToken = response.data.remote_token;
                    localStorage.setItem('remote_auth_token', this.remoteToken);
                    console.log('✅ REMOTE token saved:', this.remoteToken.substring(0, 20) + '...');
                }
                
                this.loginSystem = 'local';
                localStorage.setItem('login_system', 'local');
                
                await this.getLocalUser();
                
                // Also get remote user if remote login was successful
                if (response.data?.remote_success) {
                    await this.getRemoteUser();
                }
                
                return { 
                    success: true, 
                    system: 'local',
                    remoteSuccess: response.data?.remote_success || false,
                    message: 'Login berhasil!' 
                };
            }
        } catch (error) {
            console.error('Local login error:', error);
            return { success: false, system: 'local', message: error.response?.data?.message || 'Login gagal' };
        }
    }

    async getRemoteUser() {
        try {
            if (!this.remoteToken) {
                console.log('⚠️ getRemoteUser - no remoteToken');
                return;
            }
            console.log('📡 getRemoteUser - fetching from', REMOTE_API_URL);
            const response = await axios.get(`${REMOTE_API_URL}/user`, {
                headers: { Authorization: `Bearer ${this.remoteToken}` }
            });
            console.log('✅ getRemoteUser response:', response.data);
            // Handle response structure: data.user or data.data.user
            const userData = response.data.data?.user || response.data.user;
            if (userData) {
                this.remoteUser = userData;
                console.log('💾 remoteUser stored:', this.remoteUser);
                localStorage.setItem('remote_user', JSON.stringify(this.remoteUser));
            }
        } catch (error) {
            console.error('Get remote user error:', error);
        }
    }

    async getLocalUser() {
        try {
            if (!this.localToken) {
                console.log('⚠️ getLocalUser - no localToken');
                return;
            }
            console.log('📡 getLocalUser - fetching from', LOCAL_API_URL);
            const response = await axios.get(`${LOCAL_API_URL}/user`, {
                headers: { Authorization: `Bearer ${this.localToken}` }
            });
            console.log('✅ getLocalUser response:', response.data);
            // Handle response structure: data or data.user
            const userData = response.data.data || response.data.user || response.data;
            if (userData && (userData.name || userData.email)) {
                this.localUser = userData;
                console.log('💾 localUser stored:', this.localUser);
                localStorage.setItem('local_user', JSON.stringify(this.localUser));
            }
        } catch (error) {
            console.error('Get local user error:', error);
        }
    }

    async refreshUserData() {
        try {
            console.log('🔄 AuthService.refreshUserData() - localToken:', !!this.localToken, 'remoteToken:', !!this.remoteToken);
            // Try to get fresh user data from API
            if (this.localToken) {
                console.log('📡 Fetching local user data...');
                await this.getLocalUser();
            }
            if (this.remoteToken) {
                console.log('📡 Fetching remote user data...');
                await this.getRemoteUser();
            }
            console.log('✅ refreshUserData complete - user:', this.getCurrentUser());
        } catch (error) {
            console.error('Refresh user data error:', error);
        }
    }

    async logoutRemote() {
        try {
            if (this.remoteToken) {
                await axios.post(`${REMOTE_API_URL}/logout`, {}, {
                    headers: { Authorization: `Bearer ${this.remoteToken}` }
                });
            }
        } catch (error) {
            console.error('Remote logout error:', error);
        } finally {
            this.remoteToken = null;
            this.remoteUser = null;
            localStorage.removeItem('remote_auth_token');
            localStorage.removeItem('remote_user');
            localStorage.removeItem('remote_token_expired');
            if (this.loginSystem === 'remote') {
                this.loginSystem = null;
                localStorage.removeItem('login_system');
            }
        }
    }

    async logoutLocal() {
        try {
            if (this.localToken) {
                await axios.post(`${LOCAL_API_URL}/logout`, {}, {
                    headers: { Authorization: `Bearer ${this.localToken}` }
                });
            }
        } catch (error) {
            console.error('Local logout error:', error);
        } finally {
            this.localToken = null;
            this.localUser = null;
            localStorage.removeItem('local_auth_token');
            localStorage.removeItem('local_user');
            if (this.loginSystem === 'local') {
                this.loginSystem = null;
                localStorage.removeItem('login_system');
            }
        }
    }

    async logout() {
        await this.logoutRemote();
        await this.logoutLocal();
    }

    isAuthenticated() {
        return !!this.remoteToken || !!this.localToken;
    }

    isRemoteAuthenticated() {
        return !!this.remoteToken && !this.isRemoteTokenExpired();
    }

    isLocalAuthenticated() {
        return !!this.localToken;
    }

    getCurrentSystem() {
        return this.loginSystem;
    }

    getCurrentUser() {
        if (this.loginSystem === 'remote' && this.remoteUser) {
            return this.remoteUser;
        } else if (this.loginSystem === 'local' && this.localUser) {
            return this.localUser;
        }
        return this.remoteUser || this.localUser;
    }

    getRemoteToken() {
        return this.remoteToken;
    }

    getLocalToken() {
        return this.localToken;
    }

    isRemoteTokenExpired() {
        if (!this.remoteToken) return true;
        const tokenExpired = localStorage.getItem('remote_token_expired');
        if (!tokenExpired) return true;
        try {
            return Date.now() > parseInt(tokenExpired);
        } catch {
            return true;
        }
    }

    isTokenExpired() {
        // Check if current system's token is expired
        if (this.loginSystem === 'remote') {
            return this.isRemoteTokenExpired();
        }
        // Local tokens don't have expiry (Sanctum tokens are stateless)
        return false;
    }

    // DEBUG: Log current token status
    logTokenStatus() {
        console.log('📋 TOKEN STATUS:');
        console.log('  Local Token:', this.localToken ? '✅ ' + this.localToken.substring(0, 20) + '...' : '❌ None');
        console.log('  Remote Token:', this.remoteToken ? '✅ ' + this.remoteToken.substring(0, 20) + '...' : '❌ None');
        console.log('  Local User:', this.localUser ? '✅ ' + (this.localUser.email || this.localUser.name) : '❌ None');
        console.log('  Remote User:', this.remoteUser ? '✅ ' + (this.remoteUser.email || this.remoteUser.name) : '❌ None');
        console.log('  Current System:', this.loginSystem || 'None');
        console.log('  Remote Token Expired:', this.isRemoteTokenExpired() ? 'Yes' : 'No');
        console.log('  Is Authenticated:', this.isAuthenticated() ? 'Yes' : 'No');
    }

    // DEBUG: Show localStorage
    logStorageStatus() {
        console.log('💾 LOCALSTORAGE:');
        console.log('  local_auth_token:', localStorage.getItem('local_auth_token') ? '✅' : '❌');
        console.log('  remote_auth_token:', localStorage.getItem('remote_auth_token') ? '✅' : '❌');
        console.log('  local_user:', localStorage.getItem('local_user') ? '✅' : '❌');
        console.log('  remote_user:', localStorage.getItem('remote_user') ? '✅' : '❌');
        console.log('  login_system:', localStorage.getItem('login_system') || 'None');
        console.log('  remote_token_expired:', localStorage.getItem('remote_token_expired') || 'None');
    }
}

export default new AuthService();
