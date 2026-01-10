<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Circle Categories</h1>
                    <p class="mt-1 text-sm text-gray-500">Kelola circle categories</p>
                </div>
                <button @click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Kategori
                </button>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                <p class="mt-4 text-gray-600">Memuat kategori...</p>
            </div>

            <!-- Categories Table -->
            <div v-else class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="categories.length === 0">
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada kategori ditemukan
                            </td>
                        </tr>
                        <tr v-for="(item, index) in categories" :key="item.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ item.name || '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ item.description || '-' }}</td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <button @click="editCategory(item)" class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                                <button @click="deleteCategory(item.id)" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal for Create/Edit -->
            <div v-if="showModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">{{ editingId ? 'Edit Kategori' : 'Tambah Kategori' }}</h2>
                    <form @submit.prevent="saveCategory" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input v-model="form.name" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea v-model="form.description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                        <div class="flex gap-3 justify-end">
                            <button @click="closeModal" type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                                {{ editingId ? 'Perbarui' : 'Buat' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '../../components/AppLayout.vue';
import toast from '../../utils/toast.js';
import CategoryService from '../../services/CategoryService.js';

export default {
    name: 'CircleCategories',
    components: {
        AppLayout,
    },
    data() {
        return {
            categories: [],
            loading: false,
            showModal: false,
            editingId: null,
            form: {
                name: '',
                description: '',
            },
            categoryService: new CategoryService('circle'),
        };
    },
    mounted() {
        this.loadCategories();
    },
    methods: {
        async loadCategories() {
            this.loading = true;
            try {
                const data = await this.categoryService.getAll();
                this.categories = Array.isArray(data) ? data : [];
                console.log('Categories loaded:', this.categories);
            } catch (error) {
                console.error('Error loading categories:', error);
                toast.error('Failed to load categories', 'Error');
                this.categories = [];
            } finally {
                this.loading = false;
            }
        },
        openCreateModal() {
            this.editingId = null;
            this.form = { name: '', description: '' };
            this.showModal = true;
        },
        editCategory(item) {
            this.editingId = item.id;
            this.form = { name: item.name, description: item.description };
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.editingId = null;
            this.form = { name: '', description: '' };
        },
        async saveCategory() {
            try {
                if (!this.form.name.trim()) {
                    toast.error('Name is required', 'Validation Error');
                    return;
                }

                if (this.editingId) {
                    // Update existing
                    await this.categoryService.update(this.editingId, this.form);
                    toast.success('Category updated!', 'Success');
                } else {
                    // Create new
                    await this.categoryService.create(this.form);
                    toast.success('Category created!', 'Success');
                }
                
                this.closeModal();
                // Reload categories after save
                await this.loadCategories();
            } catch (error) {
                console.error('Error saving category:', error);
                toast.error('Failed to save category', 'Error');
            }
        },
        async deleteCategory(id) {
            if (!confirm('Are you sure you want to delete this category?')) {
                return;
            }

            try {
                await this.categoryService.delete(id);
                toast.success('Category deleted!', 'Success');
                // Reload categories after delete
                await this.loadCategories();
            } catch (error) {
                console.error('Error deleting category:', error);
                toast.error('Failed to delete category', 'Error');
            }
        },
    },
};
</script>
