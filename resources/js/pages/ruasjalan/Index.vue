<template>
    <Layout>
        <div class="py-10">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Data Ruas Jalan</h1>
                        <p class="mt-1 text-sm text-gray-500">Daftar ruas jalan yang disimpan di API dosenmu.</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <router-link
                            to="/dashboard"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                        >
                            <i class="fas fa-gauge-high mr-2"></i>
                            Dashboard
                        </router-link>
                        <router-link
                            to="/ruasjalan/create"
                            class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Ruas Jalan
                        </router-link>
                    </div>
                </div>

                <div v-if="successMessage" class="mb-4 rounded-md bg-green-50 border border-green-200 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ successMessage }}</p>
                        </div>
                    </div>
                </div>

                <div v-if="errors.length > 0" class="mb-4 rounded-md bg-red-50 border border-red-200 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li v-for="error in errors" :key="error">{{ error }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-xl bg-white shadow border border-gray-100">
                    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-list mr-2 text-primary-500"></i>
                            Tabel Ruas Jalan
                        </h2>
                        <span class="text-xs text-gray-400">Total: {{ ruasJalan.length }} data</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Ruas</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Desa ID</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Panjang (m)</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Lebar (m)</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Eksisting ID</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Kondisi ID</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Jalan ID</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                <tr v-for="ruas in ruasJalan" :key="ruas.id" class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-mono text-xs text-gray-800">{{ ruas.kode_ruas || '-' }}</td>
                                    <td class="px-4 py-3 text-gray-900">
                                        <div class="font-medium">{{ ruas.nama_ruas || '-' }}</div>
                                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ ruas.keterangan || '' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ ruas.desa_id || '-' }}</td>
                                    <td class="px-4 py-3 text-right text-gray-700">
                                        {{ ruas.panjang ? new Intl.NumberFormat('id-ID').format(ruas.panjang) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-700">
                                        {{ ruas.lebar ? new Intl.NumberFormat('id-ID').format(ruas.lebar) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-700">{{ ruas.eksisting_id || '-' }}</td>
                                    <td class="px-4 py-3 text-center text-gray-700">{{ ruas.kondisi_id || '-' }}</td>
                                    <td class="px-4 py-3 text-center text-gray-700">{{ ruas.jenisjalan_id || '-' }}</td>
                                    <td class="px-4 py-3 text-right text-xs">
                                        <div class="flex items-center justify-end space-x-2">
                                            <router-link
                                                :to="`/ruasjalan/${ruas.id}`"
                                                class="inline-flex items-center rounded-md border border-gray-200 px-2 py-1 text-[11px] font-medium text-gray-700 hover:bg-gray-50"
                                            >
                                                <i class="fas fa-eye mr-1"></i>
                                                Lihat
                                            </router-link>
                                            <router-link
                                                :to="`/ruasjalan/${ruas.id}/edit`"
                                                class="inline-flex items-center rounded-md border border-amber-300 bg-amber-50 px-2 py-1 text-[11px] font-medium text-amber-800 hover:bg-amber-100"
                                            >
                                                <i class="fas fa-pen mr-1"></i>
                                                Edit
                                            </router-link>
                                            <button
                                                @click="deleteRuas(ruas.id)"
                                                class="inline-flex items-center rounded-md border border-red-300 bg-red-50 px-2 py-1 text-[11px] font-medium text-red-700 hover:bg-red-100"
                                            >
                                                <i class="fas fa-trash mr-1"></i>
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="ruasJalan.length === 0">
                                    <td colspan="9" class="px-4 py-6 text-center text-sm text-gray-500">
                                        Belum ada data ruas jalan. Coba tambah data baru.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script>
import Layout from '../../components/Layout.vue';
import axios from 'axios';

export default {
    name: 'RuasJalanIndex',
    components: {
        Layout,
    },
    data() {
        return {
            ruasJalan: [],
            successMessage: '',
            errors: [],
        };
    },
    async mounted() {
        await this.loadData();
        if (this.$route.query.success) {
            this.successMessage = this.$route.query.success;
        }
    },
    methods: {
        async loadData() {
            try {
                const response = await axios.get('/api/ruasjalan');
                this.ruasJalan = response.data?.ruasjalan || [];
            } catch (error) {
                this.errors = [error.response?.data?.message || 'Gagal memuat data ruas jalan'];
            }
        },
        async deleteRuas(id) {
            if (!confirm('Yakin ingin menghapus ruas jalan ini?')) return;
            try {
                await axios.delete(`/ruasjalan/${id}`);
                this.successMessage = 'Ruas jalan berhasil dihapus';
                await this.loadData();
            } catch (error) {
                this.errors = [error.response?.data?.message || 'Gagal menghapus ruas jalan'];
            }
        },
    },
};
</script>

