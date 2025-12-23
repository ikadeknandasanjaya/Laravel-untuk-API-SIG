<template>
    <Layout>
        <div class="py-10">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Edit Ruas Jalan</h1>
                        <p class="mt-1 text-sm text-gray-500">Perbarui data ruas jalan dan simpan kembali ke API dosenmu.</p>
                    </div>
                    <router-link
                        to="/ruasjalan"
                        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                    >
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </router-link>
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

                <div v-if="loading" class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                    <p class="mt-2 text-sm text-gray-500">Memuat data...</p>
                </div>

                <div v-else class="overflow-hidden rounded-xl bg-white shadow border border-gray-100">
                    <form @submit.prevent="handleSubmit" class="px-6 py-6 space-y-6">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-location-dot text-primary-500 mr-2"></i>
                                Lokasi Administratif
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Desa ID</label>
                                    <input
                                        v-model.number="form.desa_id"
                                        type="number"
                                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required
                                    />
                                </div>
                            </div>
                        </div>

                        <div>
                            <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-road text-gray-600 mr-2"></i>
                                Data Ruas Jalan
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="kode_ruas" class="block text-xs font-medium text-gray-700 mb-1">Kode Ruas</label>
                                    <input
                                        id="kode_ruas"
                                        v-model="form.kode_ruas"
                                        type="text"
                                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required
                                    />
                                </div>
                                <div>
                                    <label for="nama_ruas" class="block text-xs font-medium text-gray-700 mb-1">Nama Ruas</label>
                                    <input
                                        id="nama_ruas"
                                        v-model="form.nama_ruas"
                                        type="text"
                                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required
                                    />
                                </div>
                                <div>
                                    <label for="panjang" class="block text-xs font-medium text-gray-700 mb-1">Panjang (meter)</label>
                                    <input
                                        id="panjang"
                                        v-model.number="form.panjang"
                                        type="number"
                                        step="0.01"
                                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required
                                    />
                                </div>
                                <div>
                                    <label for="lebar" class="block text-xs font-medium text-gray-700 mb-1">Lebar (meter)</label>
                                    <input
                                        id="lebar"
                                        v-model.number="form.lebar"
                                        type="number"
                                        step="0.01"
                                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required
                                    />
                                </div>
                            </div>
                        </div>

                        <div>
                            <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-list-ol text-emerald-600 mr-2"></i>
                                Klasifikasi Jalan (ID dari API)
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="eksisting_id" class="block text-xs font-medium text-gray-700 mb-1">Eksisting ID</label>
                                    <input
                                        id="eksisting_id"
                                        v-model.number="form.eksisting_id"
                                        type="number"
                                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required
                                    />
                                </div>
                                <div>
                                    <label for="jenisjalan_id" class="block text-xs font-medium text-gray-700 mb-1">Jenis Jalan ID</label>
                                    <input
                                        id="jenisjalan_id"
                                        v-model.number="form.jenisjalan_id"
                                        type="number"
                                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required
                                    />
                                </div>
                                <div>
                                    <label for="kondisi_id" class="block text-xs font-medium text-gray-700 mb-1">Kondisi Jalan ID</label>
                                    <input
                                        id="kondisi_id"
                                        v-model.number="form.kondisi_id"
                                        type="number"
                                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required
                                    />
                                </div>
                            </div>
                        </div>

                        <div>
                            <h2 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-draw-polygon text-purple-600 mr-2"></i>
                                Geometry (Encoded Polyline)
                            </h2>
                            <textarea
                                id="paths"
                                v-model="form.paths"
                                rows="3"
                                class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                required
                            ></textarea>
                        </div>

                        <div>
                            <label for="keterangan" class="block text-xs font-medium text-gray-700 mb-1">Keterangan (opsional)</label>
                            <textarea
                                id="keterangan"
                                v-model="form.keterangan"
                                rows="2"
                                class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            ></textarea>
                        </div>

                        <div class="pt-4 flex justify-end space-x-3">
                            <router-link
                                :to="`/ruasjalan/${$route.params.id}`"
                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                            >
                                <i class="fas fa-eye mr-2"></i>
                                Lihat
                            </router-link>
                            <button
                                type="submit"
                                :disabled="submitting"
                                class="inline-flex items-center rounded-md bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50"
                            >
                                <i class="fas fa-save mr-2"></i>
                                {{ submitting ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script>
import Layout from '../../components/Layout.vue';
import axios from 'axios';

export default {
    name: 'RuasJalanEdit',
    components: {
        Layout,
    },
    data() {
        return {
            form: {
                desa_id: '',
                kode_ruas: '',
                nama_ruas: '',
                panjang: '',
                lebar: '',
                eksisting_id: '',
                jenisjalan_id: '',
                kondisi_id: '',
                paths: '',
                keterangan: '',
            },
            loading: true,
            submitting: false,
            errors: [],
        };
    },
    async mounted() {
        await this.loadData();
    },
    methods: {
        async loadData() {
            try {
                const response = await axios.get(`/api/ruasjalan/${this.$route.params.id}`);
                const ruas = response.data?.ruasjalan || response.data;
                this.form = {
                    desa_id: ruas.desa_id || '',
                    kode_ruas: ruas.kode_ruas || '',
                    nama_ruas: ruas.nama_ruas || '',
                    panjang: ruas.panjang || '',
                    lebar: ruas.lebar || '',
                    eksisting_id: ruas.eksisting_id || '',
                    jenisjalan_id: ruas.jenisjalan_id || '',
                    kondisi_id: ruas.kondisi_id || '',
                    paths: ruas.paths || '',
                    keterangan: ruas.keterangan || '',
                };
            } catch (error) {
                this.errors = [error.response?.data?.message || 'Gagal memuat data ruas jalan'];
            } finally {
                this.loading = false;
            }
        },
        async handleSubmit() {
            this.submitting = true;
            this.errors = [];
            try {
                await axios.put(`/ruasjalan/${this.$route.params.id}`, this.form);
                this.$router.push({ path: '/ruasjalan', query: { success: 'Ruas jalan berhasil diupdate!' } });
            } catch (error) {
                if (error.response?.data?.errors) {
                    const errorData = error.response.data.errors;
                    this.errors = Object.values(errorData).flat();
                } else {
                    this.errors = [error.response?.data?.message || 'Gagal mengupdate ruas jalan'];
                }
            } finally {
                this.submitting = false;
            }
        },
    },
};
</script>

