<template>
    <Layout>
        <div class="py-10">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Tambah Ruas Jalan</h1>
                        <p class="mt-1 text-sm text-gray-500">
                            Data akan dikirim langsung ke endpoint <code class="text-[10px] bg-gray-100 px-1 py-0.5 rounded">/ruasjalan</code> milik API dosenmu.
                        </p>
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

                <div class="overflow-hidden rounded-xl bg-white shadow border border-gray-100">
                    <form @submit.prevent="handleSubmit" class="px-6 py-6 space-y-6">
                        <!-- Lokasi Administratif -->
                        <div>
                            <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-location-dot text-primary-500 mr-2"></i>
                                Lokasi Administratif
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="provinsi_id" class="block text-xs font-medium text-gray-700 mb-1">Provinsi</label>
                                    <select
                                        id="provinsi_id"
                                        v-model="form.provinsi_id"
                                        @change="onProvinsiChange"
                                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    >
                                        <option value="">Pilih Provinsi</option>
                                        <option v-for="prov in regions" :key="prov.id" :value="prov.id">
                                            {{ prov.provinsi || `Provinsi #${prov.id}` }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="kabupaten_id" class="block text-xs font-medium text-gray-700 mb-1">Kabupaten</label>
                                    <select
                                        id="kabupaten_id"
                                        v-model="form.kabupaten_id"
                                        @change="loadKecamatan"
                                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    >
                                        <option value="">Pilih kabupaten</option>
                                        <option v-for="kab in kabupatenList" :key="kab.id" :value="kab.id">
                                            {{ kab.value || kab.kabupaten }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="kecamatan_id" class="block text-xs font-medium text-gray-700 mb-1">Kecamatan</label>
                                    <select
                                        id="kecamatan_id"
                                        v-model="form.kecamatan_id"
                                        @change="loadDesa"
                                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    >
                                        <option value="">Pilih kecamatan</option>
                                        <option v-for="kec in kecamatanList" :key="kec.id" :value="kec.id">
                                            {{ kec.value || kec.kecamatan }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="desa_id" class="block text-xs font-medium text-gray-700 mb-1">Desa</label>
                                    <select
                                        id="desa_id"
                                        v-model="form.desa_id"
                                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        required
                                    >
                                        <option value="">Pilih desa</option>
                                        <option v-for="desa in desaList" :key="desa.id" :value="desa.id">
                                            {{ desa.value || desa.desa }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Data Ruas Jalan -->
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

                        <!-- Klasifikasi -->
                        <div>
                            <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-list-ol text-emerald-600 mr-2"></i>
                                Klasifikasi Jalan (Isi ID dari API)
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

                        <!-- Geometry -->
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
                                placeholder="Contoh: l`is@asr~T@UHiAZyCD_D atau hasil encode polyline dari map"
                                required
                            ></textarea>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan" class="block text-xs font-medium text-gray-700 mb-1">Keterangan (opsional)</label>
                            <textarea
                                id="keterangan"
                                v-model="form.keterangan"
                                rows="2"
                                class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                placeholder="Contoh: Jalan menuju objek wisata, jalan desa, dll."
                            ></textarea>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button
                                type="submit"
                                :disabled="loading"
                                class="inline-flex items-center rounded-md bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50"
                            >
                                <i class="fas fa-save mr-2"></i>
                                {{ loading ? 'Menyimpan...' : 'Simpan Ruas Jalan' }}
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
import RegionService from '../../services/RegionService.js';

export default {
    name: 'RuasJalanCreate',
    components: {
        Layout,
    },
    data() {
        return {
            form: {
                provinsi_id: '',
                kabupaten_id: '',
                kecamatan_id: '',
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
            regions: [],
            kabupatenList: [],
            kecamatanList: [],
            desaList: [],
            loading: false,
            errors: [],
        };
    },
    async mounted() {
        await this.loadRegions();
    },
    methods: {
        async loadRegions() {
            try {
                const result = await RegionService.getAllRegions();
                if (result.success) {
                    this.regions = result.data.provinsi || [];
                    console.log('Loaded provinsi:', this.regions);
                } else {
                    console.error('Failed to load regions:', result.message);
                }
            } catch (error) {
                console.error('Error loading regions:', error);
            }
        },
        onProvinsiChange() {
            console.log('🔥 onProvinsiChange called!');
            console.log('form.provinsi_id:', this.form.provinsi_id);
            console.log('type:', typeof this.form.provinsi_id);
            this.loadKabupaten();
        },
        async loadKabupaten() {
            if (!this.form.provinsi_id) return;
            this.kabupatenList = [];
            this.kecamatanList = [];
            this.desaList = [];
            try {
                const result = await RegionService.getKabupatenByProvinsiId(this.form.provinsi_id);
                console.log('loadKabupaten result:', result);
                console.log('result.success:', result.success);
                console.log('result.data:', result.data);
                if (result.success) {
                    this.kabupatenList = result.data || [];
                    console.log('Loaded kabupaten:', this.kabupatenList);
                } else {
                    console.error('Failed to load kabupaten:', result.message);
                }
            } catch (error) {
                console.error('Error loading kabupaten:', error);
            }
        },
        async loadKecamatan() {
            if (!this.form.kabupaten_id) return;
            this.kecamatanList = [];
            this.desaList = [];
            try {
                const result = await RegionService.getKecamatanByKabupatenId(this.form.kabupaten_id);
                if (result.success) {
                    this.kecamatanList = result.data || [];
                    console.log('Loaded kecamatan:', this.kecamatanList);
                } else {
                    console.error('Failed to load kecamatan:', result.message);
                }
            } catch (error) {
                console.error('Error loading kecamatan:', error);
            }
        },
        async loadDesa() {
            if (!this.form.kecamatan_id) return;
            this.desaList = [];
            try {
                const result = await RegionService.getDesaByKecamatanId(this.form.kecamatan_id);
                if (result.success) {
                    this.desaList = result.data || [];
                    console.log('Loaded desa:', this.desaList);
                } else {
                    console.error('Failed to load desa:', result.message);
                }
            } catch (error) {
                console.error('Error loading desa:', error);
            }
        },
        async handleSubmit() {
            this.loading = true;
            this.errors = [];
            try {
                await axios.post('/ruasjalan', this.form);
                this.$router.push({ path: '/ruasjalan', query: { success: 'Ruas jalan berhasil ditambahkan!' } });
            } catch (error) {
                if (error.response?.data?.errors) {
                    const errorData = error.response.data.errors;
                    this.errors = Object.values(errorData).flat();
                } else {
                    this.errors = [error.response?.data?.message || 'Gagal menambah ruas jalan'];
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

