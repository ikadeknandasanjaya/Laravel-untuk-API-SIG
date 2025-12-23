<template>
    <Layout>
        <div class="py-10">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Detail Ruas Jalan</h1>
                        <p class="mt-1 text-sm text-gray-500">
                            Data berikut berasal dari endpoint <code class="text-[10px] bg-gray-100 px-1 py-0.5 rounded">/ruasjalan/{id}</code>.
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <router-link
                            to="/ruasjalan"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </router-link>
                        <router-link
                            :to="`/ruasjalan/${$route.params.id}/edit`"
                            class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700"
                        >
                            <i class="fas fa-pen mr-2"></i>
                            Edit
                        </router-link>
                    </div>
                </div>

                <div v-if="loading" class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                    <p class="mt-2 text-sm text-gray-500">Memuat data...</p>
                </div>

                <div v-else-if="ruasJalan" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="overflow-hidden rounded-xl bg-white shadow border border-gray-100">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h2 class="text-sm font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-road text-primary-500 mr-2"></i>
                                    Informasi Utama
                                </h2>
                            </div>
                            <div class="px-6 py-4 text-sm text-gray-700">
                                <dl class="divide-y divide-gray-100">
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-gray-500">Kode Ruas</dt>
                                        <dd class="col-span-2 font-mono text-gray-900">{{ ruasJalan.kode_ruas || '-' }}</dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-gray-500">Nama Ruas</dt>
                                        <dd class="col-span-2 font-medium text-gray-900">{{ ruasJalan.nama_ruas || '-' }}</dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-gray-500">Desa ID</dt>
                                        <dd class="col-span-2 text-gray-900">{{ ruasJalan.desa_id || '-' }}</dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-gray-500">Panjang</dt>
                                        <dd class="col-span-2 text-gray-900">
                                            {{ ruasJalan.panjang ? new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(ruasJalan.panjang) + ' m' : '-' }}
                                        </dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-gray-500">Lebar</dt>
                                        <dd class="col-span-2 text-gray-900">
                                            {{ ruasJalan.lebar ? new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(ruasJalan.lebar) + ' m' : '-' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div class="overflow-hidden rounded-xl bg-white shadow border border-gray-100">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h2 class="text-sm font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-list-ol text-emerald-500 mr-2"></i>
                                    Klasifikasi & Keterangan
                                </h2>
                            </div>
                            <div class="px-6 py-4 text-sm text-gray-700">
                                <dl class="divide-y divide-gray-100">
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-gray-500">Eksisting ID</dt>
                                        <dd class="col-span-2 text-gray-900">{{ ruasJalan.eksisting_id || '-' }}</dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-gray-500">Kondisi ID</dt>
                                        <dd class="col-span-2 text-gray-900">{{ ruasJalan.kondisi_id || '-' }}</dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-gray-500">Jenis Jalan ID</dt>
                                        <dd class="col-span-2 text-gray-900">{{ ruasJalan.jenisjalan_id || '-' }}</dd>
                                    </div>
                                    <div class="py-3 grid grid-cols-3 gap-4">
                                        <dt class="text-gray-500">Keterangan</dt>
                                        <dd class="col-span-2 text-gray-900">{{ ruasJalan.keterangan || '-' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="overflow-hidden rounded-xl bg-white shadow border border-gray-100">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h2 class="text-sm font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-draw-polygon text-purple-500 mr-2"></i>
                                    Geometry (Paths)
                                </h2>
                            </div>
                            <div class="px-6 py-4 text-xs text-gray-700">
                                <p class="mb-2 text-[11px] text-gray-500">
                                    Nilai mentah dari field <code class="bg-gray-100 px-1 rounded">paths</code> yang dikirim/diterima dari API.
                                </p>
                                <pre class="whitespace-pre-wrap break-all bg-gray-50 rounded-md p-3 border border-gray-100 text-[11px]">{{ ruasJalan.paths || '-' }}</pre>
                            </div>
                        </div>

                        <div class="overflow-hidden rounded-xl bg-white shadow border border-gray-100">
                            <div class="px-6 py-4">
                                <h2 class="text-sm font-semibold text-gray-800 mb-2">Aksi Cepat</h2>
                                <div class="space-y-2 text-xs text-gray-600">
                                    <p>- Klik <span class="font-semibold">Edit</span> untuk memperbarui data ini.</p>
                                    <button
                                        @click="handleDelete"
                                        class="inline-flex items-center rounded-md bg-red-50 border border-red-300 px-3 py-1.5 text-[11px] font-medium text-red-700 hover:bg-red-100"
                                    >
                                        <i class="fas fa-trash mr-1"></i>
                                        Hapus Ruas Jalan
                                    </button>
                                </div>
                            </div>
                        </div>
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
    name: 'RuasJalanShow',
    components: {
        Layout,
    },
    data() {
        return {
            ruasJalan: null,
            loading: true,
        };
    },
    async mounted() {
        await this.loadData();
    },
    methods: {
        async loadData() {
            try {
                const response = await axios.get(`/api/ruasjalan/${this.$route.params.id}`);
                this.ruasJalan = response.data?.ruasjalan || response.data;
            } catch (error) {
                console.error('Error loading ruas jalan:', error);
            } finally {
                this.loading = false;
            }
        },
        async handleDelete() {
            if (!confirm('Yakin ingin menghapus ruas jalan ini?')) return;
            try {
                await axios.delete(`/ruasjalan/${this.$route.params.id}`);
                this.$router.push({ path: '/ruasjalan', query: { success: 'Ruas jalan berhasil dihapus!' } });
            } catch (error) {
                alert(error.response?.data?.message || 'Gagal menghapus ruas jalan');
            }
        },
    },
};
</script>

