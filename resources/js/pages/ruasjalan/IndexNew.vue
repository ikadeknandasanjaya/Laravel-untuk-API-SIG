<template>
    <AppLayout>
        <div class="ruas-jalan-index">
            <div class="page-header">
                <div class="header-content">
                    <h1>Ruas Jalan</h1>
                    <p>Kelola data ruas jalan di sistem GIS</p>
                </div>
                <router-link to="/ruasjalan/create" class="add-btn">
                    <i class="fas fa-plus"></i>
                    Tambah Ruas Jalan
                </router-link>
            </div>

            <!-- Filters -->
            <div class="filters-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Cari ruas jalan..."
                        @input="filterData"
                    />
                </div>
                
                <div class="filter-controls">
                    <select v-model="filterKondisi" @change="filterData">
                        <option value="">Semua Kondisi</option>
                        <option
                            v-for="kondisi in kondisiList"
                            :key="kondisi.id"
                            :value="kondisi.id"
                        >
                            {{ kondisi.kondisi }}
                        </option>
                    </select>
                    
                    <select v-model="filterJenis" @change="filterData">
                        <option value="">Semua Jenis</option>
                        <option
                            v-for="jenis in jenisJalanList"
                            :key="jenis.id"
                            :value="jenis.id"
                        >
                            {{ jenis.jenisjalan }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-road"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ totalRuasJalan }}</h3>
                        <p>Total Ruas Jalan</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon good">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ ruasJalanBaik }}</h3>
                        <p>Kondisi Baik</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ ruasJalanRusak }}</h3>
                        <p>Perlu Perbaikan</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-ruler"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ totalPanjang.toFixed(2) }} km</h3>
                        <p>Total Panjang</p>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-container">
                <div v-if="loading" class="loading-state">
                    <div class="loading-spinner"></div>
                    <p>Memuat data ruas jalan...</p>
                </div>
                
                <div v-else-if="filteredData.length === 0" class="empty-state">
                    <i class="fas fa-road"></i>
                    <h3>Tidak ada data ruas jalan</h3>
                    <p>{{ searchQuery ? 'Tidak ditemukan hasil pencarian' : 'Belum ada ruas jalan yang ditambahkan' }}</p>
                    <router-link to="/ruasjalan/create" class="add-btn-empty">
                        <i class="fas fa-plus"></i>
                        Tambah Ruas Jalan Pertama
                    </router-link>
                </div>
                
                <div v-else class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Kode Ruas</th>
                                <th>Nama Ruas</th>
                                <th>Lokasi</th>
                                <th>Panjang</th>
                                <th>Lebar</th>
                                <th>Kondisi</th>
                                <th>Jenis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="ruas in paginatedData" :key="ruas.id">
                                <td>
                                    <span class="kode-ruas">{{ ruas.kode_ruas }}</span>
                                </td>
                                <td>
                                    <div class="nama-ruas">
                                        <strong>{{ ruas.nama_ruas }}</strong>
                                        <small v-if="ruas.keterangan">{{ ruas.keterangan }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="lokasi">Desa {{ getDesaName(ruas.desa_id) }}</span>
                                </td>
                                <td>
                                    <span class="panjang">{{ ruas.panjang }} m</span>
                                </td>
                                <td>
                                    <span class="lebar">{{ ruas.lebar }} m</span>
                                </td>
                                <td>
                                    <span :class="['kondisi-badge', getKondisiClass(ruas.kondisi_id)]">
                                        {{ getKondisiName(ruas.kondisi_id) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="jenis">{{ getJenisName(ruas.jenisjalan_id) }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <router-link
                                            :to="`/ruasjalan/${ruas.id}`"
                                            class="action-btn view"
                                            title="Lihat Detail"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </router-link>
                                        <router-link
                                            :to="`/ruasjalan/${ruas.id}/edit`"
                                            class="action-btn edit"
                                            title="Edit"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </router-link>
                                        <button
                                            @click="confirmDelete(ruas)"
                                            class="action-btn delete"
                                            title="Hapus"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="totalPages > 1" class="pagination">
                    <button
                        @click="currentPage = 1"
                        :disabled="currentPage === 1"
                        class="pagination-btn"
                    >
                        <i class="fas fa-angle-double-left"></i>
                    </button>
                    <button
                        @click="currentPage--"
                        :disabled="currentPage === 1"
                        class="pagination-btn"
                    >
                        <i class="fas fa-angle-left"></i>
                    </button>
                    
                    <span class="pagination-info">
                        Halaman {{ currentPage }} dari {{ totalPages }}
                    </span>
                    
                    <button
                        @click="currentPage++"
                        :disabled="currentPage === totalPages"
                        class="pagination-btn"
                    >
                        <i class="fas fa-angle-right"></i>
                    </button>
                    <button
                        @click="currentPage = totalPages"
                        :disabled="currentPage === totalPages"
                        class="pagination-btn"
                    >
                        <i class="fas fa-angle-double-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../../components/AppLayout.vue';
import RuasJalanService from '../../services/RuasJalanService.js';
import RegionService from '../../services/RegionService.js';
import toast from '../../utils/toast.js';

export default {
    name: 'RuasJalanIndex',
    components: {
        AppLayout
    },
    data() {
        return {
            ruasJalanList: [],
            filteredData: [],
            
            // Master data
            kondisiList: [],
            jenisJalanList: [],
            eksistingList: [],
            regionData: null,
            
            // Filters
            searchQuery: '',
            filterKondisi: '',
            filterJenis: '',
            
            // Pagination
            currentPage: 1,
            itemsPerPage: 10,
            
            // Loading
            loading: false
        }
    },
    computed: {
        totalRuasJalan() {
            return this.ruasJalanList.length;
        },
        ruasJalanBaik() {
            return this.ruasJalanList.filter(ruas => {
                const kondisi = this.kondisiList.find(k => k.id === ruas.kondisi_id);
                return kondisi?.kondisi?.toLowerCase() === 'baik';
            }).length;
        },
        ruasJalanRusak() {
            return this.ruasJalanList.filter(ruas => {
                const kondisi = this.kondisiList.find(k => k.id === ruas.kondisi_id);
                return kondisi?.kondisi?.toLowerCase() === 'rusak';
            }).length;
        },
        totalPanjang() {
            return this.ruasJalanList.reduce((total, ruas) => total + (parseFloat(ruas.panjang) || 0), 0) / 1000; // Convert to km
        },
        totalPages() {
            return Math.ceil(this.filteredData.length / this.itemsPerPage);
        },
        paginatedData() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredData.slice(start, end);
        }
    },
    async mounted() {
        await this.loadData();
    },
    methods: {
        async loadData() {
            this.loading = true;
            
            try {
                // Load ruas jalan data
                const ruasJalanResult = await RuasJalanService.getAllRuasJalan();
                if (ruasJalanResult.success) {
                    this.ruasJalanList = ruasJalanResult.data.ruasjalan || [];
                    this.filteredData = [...this.ruasJalanList];
                }
                
                // Load master data
                const [kondisiResult, jenisResult, eksistingResult, regionResult] = await Promise.all([
                    RuasJalanService.getMasterKondisi(),
                    RuasJalanService.getMasterJenisJalan(),
                    RuasJalanService.getMasterEksisting(),
                    RegionService.getAllRegions()
                ]);
                
                if (kondisiResult.success) {
                    this.kondisiList = kondisiResult.data;
                }
                
                if (jenisResult.success) {
                    this.jenisJalanList = jenisResult.data;
                }
                
                if (eksistingResult.success) {
                    this.eksistingList = eksistingResult.data;
                }
                
                if (regionResult.success) {
                    this.regionData = regionResult.data;
                }
                
            } catch (error) {
                console.error('Error loading data:', error);
                toast.error('Gagal memuat data ruas jalan.', 'Error');
            } finally {
                this.loading = false;
            }
        },

        filterData() {
            let filtered = [...this.ruasJalanList];
            
            // Filter by search query
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(ruas => 
                    ruas.nama_ruas.toLowerCase().includes(query) ||
                    ruas.kode_ruas.toLowerCase().includes(query) ||
                    (ruas.keterangan && ruas.keterangan.toLowerCase().includes(query))
                );
            }
            
            // Filter by kondisi
            if (this.filterKondisi) {
                filtered = filtered.filter(ruas => ruas.kondisi_id == this.filterKondisi);
            }
            
            // Filter by jenis
            if (this.filterJenis) {
                filtered = filtered.filter(ruas => ruas.jenisjalan_id == this.filterJenis);
            }
            
            this.filteredData = filtered;
            this.currentPage = 1; // Reset to first page
        },

        getKondisiName(kondisiId) {
            const kondisi = this.kondisiList.find(k => k.id === kondisiId);
            return kondisi?.kondisi || 'Unknown';
        },

        getKondisiClass(kondisiId) {
            const kondisi = this.kondisiList.find(k => k.id === kondisiId);
            const kondisiName = kondisi?.kondisi?.toLowerCase();
            
            if (kondisiName === 'baik') return 'good';
            if (kondisiName === 'rusak') return 'bad';
            return 'medium';
        },

        getJenisName(jenisId) {
            const jenis = this.jenisJalanList.find(j => j.id === jenisId);
            return jenis?.jenisjalan || 'Unknown';
        },

        getDesaName(desaId) {
            if (!this.regionData) return 'Unknown';
            const desa = this.regionData.desa.find(d => d.id === desaId);
            return desa?.desa || 'Unknown';
        },

        async confirmDelete(ruas) {
            if (confirm(`Apakah Anda yakin ingin menghapus ruas jalan "${ruas.nama_ruas}"?`)) {
                await this.deleteRuasJalan(ruas.id);
            }
        },

        async deleteRuasJalan(id) {
            try {
                const result = await RuasJalanService.deleteRuasJalan(id);
                
                if (result.success) {
                    toast.success('Ruas jalan berhasil dihapus!', 'Sukses');
                    await this.loadData(); // Reload data
                } else {
                    toast.error(result.message, 'Error');
                }
            } catch (error) {
                console.error('Error deleting ruas jalan:', error);
                toast.error('Gagal menghapus ruas jalan.', 'Error');
            }
        }
    }
}
</script>

<style scoped>
.ruas-jalan-index {
    max-width: 1400px;
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

.add-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s;
}

.add-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
}

.filters-section {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
    flex: 1;
    min-width: 300px;
}

.search-box i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
}

.search-box input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
}

.search-box input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-controls {
    display: flex;
    gap: 1rem;
}

.filter-controls select {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    min-width: 150px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: #3b82f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.stat-icon.good {
    background: #10b981;
}

.stat-icon.warning {
    background: #f59e0b;
}

.stat-icon.info {
    background: #8b5cf6;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.stat-content p {
    color: #6b7280;
    margin: 0;
    font-size: 0.875rem;
}

.table-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.loading-state,
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
}

.loading-state .loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f4f6;
    border-top: 4px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

.empty-state i {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin: 0 0 0.5rem;
}

.empty-state p {
    color: #6b7280;
    margin: 0 0 2rem;
}

.add-btn-empty {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s;
}

.table-wrapper {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #f3f4f6;
}

.data-table th {
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.kode-ruas {
    font-family: monospace;
    background: #f3f4f6;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
}

.nama-ruas strong {
    display: block;
    color: #111827;
}

.nama-ruas small {
    color: #6b7280;
    font-size: 0.75rem;
}

.kondisi-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.kondisi-badge.good {
    background: #d1fae5;
    color: #065f46;
}

.kondisi-badge.medium {
    background: #fef3c7;
    color: #92400e;
}

.kondisi-badge.bad {
    background: #fee2e2;
    color: #991b1b;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    font-size: 0.875rem;
}

.action-btn.view {
    background: #dbeafe;
    color: #1d4ed8;
}

.action-btn.edit {
    background: #fef3c7;
    color: #d97706;
}

.action-btn.delete {
    background: #fee2e2;
    color: #dc2626;
}

.action-btn:hover {
    transform: scale(1.1);
}

.pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    padding: 1.5rem;
    border-top: 1px solid #f3f4f6;
}

.pagination-btn {
    width: 40px;
    height: 40px;
    border: 1px solid #d1d5db;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.pagination-btn:hover:not(:disabled) {
    background: #f9fafb;
    border-color: #9ca3af;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-info {
    font-size: 0.875rem;
    color: #6b7280;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filters-section {
        flex-direction: column;
    }
    
    .filter-controls {
        flex-direction: column;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>
