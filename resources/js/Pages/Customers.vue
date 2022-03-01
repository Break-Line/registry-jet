<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manage Customers
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert" v-if="flash.success">
                      <div class="flex">
                        <div>
                          <p class="text-sm">{{ flash.success }}</p>
                        </div>
                      </div>
                    </div>
                    <button @click="customerModal.mode = 'save'" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Create New Customer</button>
                    <button v-if="user.data.role[0] == 'admin'" @click="customerModal.mode = 'importCustomers'" class="float-right bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Import New Customer(s)</button>
                    <table class="table-fixed w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 w-1/12">ID</th>
                                <th class="px-4 py-2 w-3/12">Email</th>
                                <th class="px-4 py-2 w-2/12">Ragione sociale</th>
                                <th class="px-4 py-2 w-2/12">Città</th>
                                <!--<th class="px-4 py-2">Indirizzo</th>
                                <th class="px-4 py-2">CAP</th>-->
                                <!--<th class="px-4 py-2 w-1/12">Provincia</th>
                                <th class="px-4 py-2 w-1/12">Regione</th>-->
                                <th class="px-4 py-2 w-4/12">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in customers.data" :key="row.id">
                                <td class="border px-4 py-2">{{ row.id }}</td>
                                <td class="border px-4 py-2">{{ row.email }}</td>
                                <td class="border px-4 py-2">{{ row.business_name }}</td>
                                <td class="border px-4 py-2">{{ row.city }}</td>
                                <!--<td class="border px-4 py-2">{{ row.address }}</td>
                                <td class="border px-4 py-2">{{ row.postal_code }}</td>
                                <td class="border px-4 py-2">{{ row.province }}</td>
                                <td class="border px-4 py-2">{{ row.region }}</td>-->
                                <td class="border px-4 py-2">
                                    <button @click="view(row)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mx-1 rounded">View</button>
                                    <button @click="edit(row)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mx-1 rounded">Edit</button>
                                    <button @click="deleteRow(row)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 mx-1 rounded">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination class="mt-6" :links="customers.links" />

                    <!-- importCustomers modal -->
                    <DialogModal :show="customerModal.mode === 'importCustomers'" @close="resetModal()">
                        <template #title>
                            Import customers
                        </template>
                        <template #content>
                            <form @submit.prevent="importCustomers" ref="importForm">
                                <input type="file" @input="form.import.selectedFile = $event.target.files[0]" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                                <div v-if="needFormErrors">
                                    <span v-for="error in errors" :key="error" class="text-red-500 py-4">{{ error }}</span>
                                </div>
                                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                {{ form.progress.percentage }}%
                                </progress>
                            </form>
                        </template>

                        <template #footer>
                            <button @click="$refs.importForm.requestSubmit()" :disabled="isLoading" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Confirm</button>
                        </template>

                    </DialogModal>

                    <!-- view modal -->
                    <DialogModal :show="customerModal.mode === 'view' && customerModal.data !== null" @close="resetModal()">
                        <template #title>
                            Customer: {{ customerModal.data.email }}
                        </template>
                        <template #content>
                            <div>
                                <table class="table-fixed w-full">
                                    <tbody>
                                        <tr v-for="(row, index) in customerModal.data" :key="index">
                                            <strong>
                                                <td class="border px-4 py-2 w-1/3">{{ index }}</td>
                                            </strong>
                                            <td class="border px-4 py-2 w-2/3">{{ row }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </template>

                        <template #footer>
                            <button @click="edit(customerModal.data)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</button>
                            <button @click="deleteRow(customerModal.data)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                        </template>
                    </DialogModal>

                    <!-- edit modal -->
                    <DialogModal :show="customerModal.mode === 'edit' && customerModal.data !== null" @close="resetModal()">
                        <template #title>
                            Edit customer: {{ customerModal.data.email }}
                        </template>
                        <template #content>
                            <div>
                                <form>
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="">
                                            <div class="mb-4">
                                                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" placeholder="example@example.com" v-model="form.email">
                                                <div v-if="needFormErrors" class="text-red-500">{{ errors.email }}</div>
                                            </div>
                                            <div class="mb-4">
                                                <label for="business_name" class="block text-gray-700 text-sm font-bold mb-2">Ragione sociale:</label>
                                                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="business_name" v-model="form.business_name" placeholder="Meta Platforms Inc."></textarea>
                                                <div v-if="needFormErrors" class="text-red-500">{{ errors.business_name }}</div>
                                            </div>
                                            <div class="mb-4">
                                                <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Indirizzo:</label>
                                                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="address" v-model="form.address" placeholder="Via Roma, 10"></textarea>
                                                <div v-if="needFormErrors" class="text-red-500">{{ errors.address }}</div>
                                            </div>
                                            <div class="mb-4">
                                                <label for="postal_code" class="block text-gray-700 text-sm font-bold mb-2">Codice postale:</label>
                                                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="postal_code" v-model="form.postal_code" placeholder="09123"></textarea>
                                                <div v-if="needFormErrors" class="text-red-500">{{ errors.postal_code }}</div>
                                            </div>
                                            <div class="mb-4">
                                                <label for="city" class="block text-gray-700 text-sm font-bold mb-2">Città:</label>
                                                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="city" v-model="form.city" placeholder="Cagliari"></textarea>
                                                <div v-if="needFormErrors" class="text-red-500">{{ errors.city }}</div>
                                            </div>
                                            <div class="mb-4">
                                                <label for="province" class="block text-gray-700 text-sm font-bold mb-2">Provincia:</label>
                                                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="province" v-model="form.province" placeholder="Cagliari"></textarea>
                                                <div v-if="needFormErrors" class="text-red-500">{{ errors.province }}</div>
                                            </div>
                                            <div class="mb-4">
                                                <label for="region" class="block text-gray-700 text-sm font-bold mb-2">Regione:</label>
                                                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="region" v-model="form.region" placeholder="Sardegna"></textarea>
                                                <div v-if="needFormErrors" class="text-red-500">{{ errors.region }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </template>

                        <template #footer>
                            <button @click="update(form)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Confirm</button>
                        </template>
                    </DialogModal>

                    <!-- save modal -->
                    <DialogModal :show="customerModal.mode === 'save'" @close="resetModal()">
                        <template #title>
                            Nuovo cliente
                        </template>
                        
                        <template #content>
                            <form>
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="mb-4">
                                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                                        <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" placeholder="example@example.com" v-model="form.email">
                                        <div v-if="needFormErrors" class="text-red-500">{{ errors.email }}</div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="business_name" class="block text-gray-700 text-sm font-bold mb-2">Ragione sociale:</label>
                                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="business_name" v-model="form.business_name" placeholder="Meta Platforms Inc."></textarea>
                                        <div v-if="needFormErrors" class="text-red-500">{{ errors.business_name }}</div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Indirizzo:</label>
                                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="address" v-model="form.address" placeholder="Via Roma, 10"></textarea>
                                        <div v-if="needFormErrors" class="text-red-500">{{ errors.address }}</div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="postal_code" class="block text-gray-700 text-sm font-bold mb-2">Codice postale:</label>
                                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="postal_code" v-model="form.postal_code" placeholder="09123"></textarea>
                                        <div v-if="needFormErrors" class="text-red-500">{{ errors.postal_code }}</div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="city" class="block text-gray-700 text-sm font-bold mb-2">Città:</label>
                                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="city" v-model="form.city" placeholder="Cagliari"></textarea>
                                        <div v-if="needFormErrors" class="text-red-500">{{ errors.city }}</div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="province" class="block text-gray-700 text-sm font-bold mb-2">Provincia:</label>
                                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="province" v-model="form.province" placeholder="Cagliari"></textarea>
                                        <div v-if="needFormErrors" class="text-red-500">{{ errors.province }}</div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="region" class="block text-gray-700 text-sm font-bold mb-2">Regione:</label>
                                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="region" v-model="form.region" placeholder="Sardegna"></textarea>
                                        <div v-if="needFormErrors" class="text-red-500">{{ errors.region }}</div>
                                    </div>
                                </div>
                            </form>
                        </template>

                        <template #footer>
                            <button @click="save(form)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Confirm</button>
                        </template>
                    </DialogModal>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Pagination from '@/Components/Pagination'
    import DialogModal from '@/Jetstream/DialogModal'
    import { useForm } from '@inertiajs/inertia-vue3'
    import { InertiaProgress } from '@inertiajs/progress'

    export default {
        components: {
            AppLayout,
            Pagination,
            DialogModal,
            useForm,
            InertiaProgress
        },
        props: ['customers', 'flash', 'errors', 'user'],
        setup() {
            InertiaProgress.init();
        },
        data() {
            return this.initialState();
        },
        methods: {
            initialState: function () {
                return {
                    customerModal: {
                        "mode": null,
                        "data": null
                    },
                    form: {
                        email: null,
                        business_name: null,
                        address: null,
                        postal_code: null,
                        city: null,
                        province: null,
                        region: null,
                        import: useForm({
                            selectedFile: null
                        })
                    },
                    needFormErrors: false,
                    isLoading: false
                }
            },
            importCustomers: function () {
                this.form.import.post('/customers/importXlsx', {
                    onStart: () => {
                        this.isLoading = true;
                    },
                    onSuccess: () => {
                        return Promise.all([
                            this.resetModal()
                        ]);
                    },
                    onError: () => {
                        return Promise.all([
                            this.needFormErrors = true
                        ]);
                    },
                    onFinish: () => {
                        return Promise.all([
                            this.isLoading = false
                        ]);
                    }
                });
            },
            resetModal: function () {
                this.customerModal.mode = this.initialState().customerModal;
                this.needFormErrors = false;
                this.form = this.initialState().form;
            },
            view: function (data) {
                this.customerModal.data = data;
                this.customerModal.mode = "view";
            },
            save: function (data) {
                this.$inertia.post('/customers', data, {
                    onSuccess: () => {
                        return Promise.all([
                            this.resetModal(),
                        ]);
                    },
                    onError: () => {
                        return Promise.all([
                            this.needFormErrors = true
                        ]);
                    }
                });
                this.editMode = false;
            },
            edit: function (data) {
                this.customerModal.data = data;
                this.customerModal.mode = "edit";
                this.form = this.customerModal.data;
            },
            update: function (data) {
                data._method = 'PUT';
                this.$inertia.post('/customers/' + data.id, data, {
                    onSuccess: () => {
                        return Promise.all([
                            this.resetModal(),
                        ]);
                    },
                    onError: () => {
                        return Promise.all([
                            this.needFormErrors = true
                        ]);
                    }
                });
            },
            deleteRow: function (data) {
                if (!confirm('Sei sicuro di voler rimuovere l\'elemento?')) return;
                data._method = 'DELETE';
                this.$inertia.post('/customers/' + data.id, data)
            }
        }
    }
</script>
