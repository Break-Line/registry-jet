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
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert" v-if="$page.props.flash.success">
                      <div class="flex">
                        <div>
                          <p class="text-sm">{{ $page.props.flash.success }}</p>
                        </div>
                      </div>
                    </div>
                    <button @click="openModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Create New Customer</button>
                    <table class="table-fixed w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 w-20">ID</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Ragione sociale</th>
                                <th class="px-4 py-2">Indirizzo</th>
                                <th class="px-4 py-2">CAP</th>
                                <th class="px-4 py-2">Città</th>
                                <th class="px-4 py-2">Provincia</th>
                                <th class="px-4 py-2">Regione</th>
                                <th class="px-4 py-2">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in data" :key="row.id">
                                <td class="border px-4 py-2">{{ row.id }}</td>
                                <td class="border px-4 py-2">{{ row.email }}</td>
                                <td class="border px-4 py-2">{{ row.business_name }}</td>
                                <td class="border px-4 py-2">{{ row.address }}</td>
                                <td class="border px-4 py-2">{{ row.postal_code }}</td>
                                <td class="border px-4 py-2">{{ row.city }}</td>
                                <td class="border px-4 py-2">{{ row.province }}</td>
                                <td class="border px-4 py-2">{{ row.region }}</td>
                                <td class="border px-4 py-2">
                                    <button @click="edit(row)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Modifica</button>
                                    <button @click="deleteRow(row)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Elimina</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpen">
                      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        
                        <div class="fixed inset-0 transition-opacity">
                          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <!-- This element is to trick the browser into centering the modal contents. -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                          <form>
                          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="">
                                  <div class="mb-4">
                                      <label for="edit-email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                                      <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit-email" placeholder="example@example.com" v-model="form.email">
                                      <div v-if="$page.props.flash.error" class="text-red-500">{{ $page.props.flash.error }}</div>
                                  </div>
                                  <div class="mb-4">
                                      <label for="edit-business_name" class="block text-gray-700 text-sm font-bold mb-2">Ragione sociale:</label>
                                      <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit-business_name" v-model="form.business_name" placeholder="Meta Platforms Inc."></textarea>
                                      <div v-if="$page.props.flash.error" class="text-red-500">{{ $page.props.flash.error }}</div>
                                  </div>
                                  <div class="mb-4">
                                      <label for="edit-address" class="block text-gray-700 text-sm font-bold mb-2">Indirizzo:</label>
                                      <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit-address" v-model="form.address" placeholder="Via Roma, 10"></textarea>
                                      <div v-if="$page.props.flash.error" class="text-red-500">{{ $page.props.flash.error }}</div>
                                  </div>
                                  <div class="mb-4">
                                      <label for="edit-postal_code" class="block text-gray-700 text-sm font-bold mb-2">Codice postale:</label>
                                      <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit-postal_code" v-model="form.postal_code" placeholder="09123"></textarea>
                                      <div v-if="$page.props.flash.error" class="text-red-500">{{ $page.props.flash.error }}</div>
                                  </div>
                                  <div class="mb-4">
                                      <label for="edit-city" class="block text-gray-700 text-sm font-bold mb-2">Città:</label>
                                      <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit-city" v-model="form.city" placeholder="Cagliari"></textarea>
                                      <div v-if="$page.props.flash.error" class="text-red-500">{{ $page.props.flash.error }}</div>
                                  </div>
                                  <div class="mb-4">
                                      <label for="edit-province" class="block text-gray-700 text-sm font-bold mb-2">Provincia:</label>
                                      <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit-province" v-model="form.province" placeholder="Cagliari"></textarea>
                                      <div v-if="$page.props.flash.error" class="text-red-500">{{ $page.props.flash.error }}</div>
                                  </div>
                                  <div class="mb-4">
                                      <label for="edit-region" class="block text-gray-700 text-sm font-bold mb-2">Regione:</label>
                                      <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit-region" v-model="form.region" placeholder="Sardegna"></textarea>
                                      <div v-if="$page.props.flash.error" class="text-red-500">{{ $page.props.flash.error }}</div>
                                  </div>
                            </div>
                          </div>
                          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                              <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" v-show="!editMode" @click="save(form)">
                                Salva
                              </button>
                            </span>
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                              <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" v-show="editMode" @click="update(form)">
                                Modifica
                              </button>
                            </span>
                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                              <button @click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Cancella
                              </button>
                            </span>
                          </div>
                          </form>
                          
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
    import AppLayout from './../Layouts/AppLayout'
    export default {
        components: {
            AppLayout
        },
        props: ['data', 'errors'],
        data() {
            return {
                editMode: false,
                isOpen: false,
                form: {
                    email: null,
                    business_name: null,
                    address: null,
                    postal_code: null,
                    city: null,
                    province: null,
                    region: null,
                },
            }
        },
        methods: {
            openModal: function () {
                this.isOpen = true;
            },
            closeModal: function () {
                this.isOpen = false;
                this.reset();
                this.editMode=false;
            },
            reset: function () {
                this.form = {
                    email: null,
                    business_name: null,
                    address: null,
                    postal_code: null,
                    city: null,
                    province: null,
                    region: null,
                }
            },
            save: function (data) {
                this.$inertia.post('/customers', data)
                this.reset();
                this.closeModal();
                this.editMode = false;
            },
            edit: function (data) {
                this.form = Object.assign({}, data);
                this.editMode = true;
                this.openModal();
            },
            update: function (data) {
                data._method = 'PUT';
                this.$inertia.post('/customers/' + data.id, data)
                this.reset();
                this.closeModal();
            },
            deleteRow: function (data) {
                if (!confirm('Sei sicuro di voler rimuovere l\'elemento?')) return;
                data._method = 'DELETE';
                this.$inertia.post('/customers/' + data.id, data)
                this.reset();
                this.closeModal();
            }
        }
    }
</script>