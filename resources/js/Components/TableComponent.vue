<template>
    <b-container fluid>
        <!-- User Interface controls -->
        <b-row class="justify-content-between mb-4" v-if="search !== false">
            <b-col lg="6" class="my-1">
                <b-form-group
                        label="Buscar:"
                        label-cols="2"
                        label-size="sm"
                        label-for="filterInput"
                        class="mb-0"
                >
                    <b-input-group size="sm">
                        <b-form-input
                                v-model="filter"
                                type="search"
                                id="filterInput"
                                placeholder=""
                        ></b-form-input>
                        <b-input-group-append>
                            <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </b-col>

<!--            <b-col lg="6" class="my-1">-->
<!--                <b-form-group-->
<!--                        label="Filter On"-->
<!--                        label-cols-sm="3"-->
<!--                        label-align-sm="right"-->
<!--                        label-size="sm"-->
<!--                        description="Leave all unchecked to filter on all data"-->
<!--                        class="mb-0">-->
<!--                    <b-form-checkbox-group v-model="filterOn" class="mt-1">-->
<!--                        <b-form-checkbox value="name">Name</b-form-checkbox>-->
<!--                        <b-form-checkbox value="age">Age</b-form-checkbox>-->
<!--                        <b-form-checkbox value="isActive">Active</b-form-checkbox>-->
<!--                    </b-form-checkbox-group>-->
<!--                </b-form-group>-->
<!--            </b-col>-->

            <b-col sm="2" md="3" class="my-1">
                <b-form-group
                        label="Mostrar"
                        label-cols-sm="7"
                        label-cols-md="7"
                        label-cols-lg="7"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="perPageSelect"
                        class="mb-0"
                >
                    <b-form-select
                            cols="2"
                            v-model="perPage"
                            id="perPageSelect"
                            size="sm"
                            :options="pageOptions"
                    ></b-form-select>
                </b-form-group>
            </b-col>

        </b-row>

        <!-- Main table element -->
        <b-table
                show-empty
                hover
                :busy="isBusy"
                responsive

                :items="items_map"
                :fields="fields_map"
                :current-page="currentPage"
                :per-page="perPage"
                :filter="filter"
                :filter-included-fields="filterOn"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :sort-direction="sortDirection"
                @filtered="onFiltered"
        >
            <template v-slot:empty="scope" >
                <div class="text-center">
                    No hay registros para mostrar
                </div>
            </template>
            <template v-slot:emptyfiltered="scope">
                <div class="text-center">
                    No hay registros para mostrar
                </div>
            </template>
            <template v-slot:table-busy>
                <div class="text-center text-danger my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Cargando...</strong>
                </div>
            </template>
            <template #cell(title)="row">
                {{ row.value.es || ''  }}

            </template>
            <template #cell(precio)="row">
                $ {{ row.item.precio | toCurrency }}
            </template>
            <template #cell(cantidad)="row">
                <slot name="cantidad" :row="row" :item="row.item"></slot>
            </template>
            <template #cell(stock)="row">
                <slot name="stock" :row="row" :item="row.item"></slot>
            </template>
            <template #cell(subtotal)="row">
                $ {{ (row.item.cantidad * row.item.precio) | toCurrency}}
            </template>
            <template #cell(total)="row">
                $ {{ row.item.total | toCurrency }}
            </template>
            <template #cell(total_iva)="row">
                $ {{ row.item.total_iva | toCurrency }}
            </template>

            <template #cell(actions)="row">
<!--                <b-button size="sm" @click="info(row.item, row.index, $event.target)" class="mr-1">-->
<!--                    Info modal-->
<!--                </b-button>-->
<!--                <b-button size="sm" @click="row.toggleDetails">-->
<!--                    {{ row.detailsShowing ? 'Hide' : 'Show' }} Details-->
<!--                </b-button>-->
                <slot name="action" :row="row" :item="row.item"></slot>
            </template>
            <template #cell(estado)="row">
                <slot name="estado" :row="row" :item="row.item"></slot>
            </template>

            <template #row-details="row">
                <b-card>
<!--                    <b-list-group v-if="row.item.productos.length > 0">-->
<!--                        <b-list-group-item v-for="(value, key) in row.item.productos" :key="key">-->
<!--                            {{ value.cantidad }} x {{ value.producto }} <b>Cod:</b> {{ value.codigo }}-->
<!--                        </b-list-group-item>-->
<!--                    </b-list-group>-->

                    <template v-if="row.item.mensaje">
                        <h4 class="font-weight-bold">Observaciones:</h4>
                        <p class="mb-3">
                            {{ row.item.mensaje }}
                        </p>
                    </template>
                    <table class="table table-bordered" v-if="row.item.productos.length > 0">
                        <thead>
                        <tr>
                            <th scope="col">Codigo</th>
                            <th scope="col">Producto</th>
                            <th scope="col" class="text-center">Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="(value, key) in row.item.productos" :key="key">
                            <td>   {{ value.producto_codigo }}</td>
                            <td>   {{ value.producto_nombre }}</td>
                            <td class="text-center">   {{ value.cantidad }}</td>
                        </tr>
                        </tbody>
                    </table>
                </b-card>
            </template>
        </b-table>
        <b-row class="justify-content-center" v-if="paginate !== false">
            <b-col md="6" class="my-1" v-if="items_map.length > 50">

                <b-pagination
                        v-model="currentPage"
                        :total-rows="totalRows"
                        :per-page="perPage"
                        align="fill"
                        size="sm"
                        class="my-0"
                ></b-pagination>
            </b-col>
        </b-row>

        <!-- Info modal -->
        <b-modal :id="infoModal.id" :title="infoModal.title" ok-only @hide="resetInfoModal">
            <pre>{{ infoModal.content }}</pre>
        </b-modal>
    </b-container>
</template>

<script>
    export default {
        props:['items','fields','onlyShow','search','paginate','filtrar'],
        data() {
            return {
                isBusy: false,
                totalRows: 1,
                currentPage: 1,
                perPage: 250,
                pageOptions: [10, 20, 50, { value: 100000, text: "Todos" }],
                sortBy: '',
                sortDesc: false,
                sortDirection: 'asc',
                filter: null,
                filterOn: ['title','order'],
                infoModal: {
                    id: 'info-modal',
                    title: '',
                    content: ''
                }
            }
        },
        computed: {
            fields_map(){
                let campos = []
              if (this.fields){
                  return this.fields
              }else{
                  campos = [
                      { key: 'title', label: 'Titulo', sortable: true, sortDirection: 'desc' },
                      { key: 'order', label: 'Orden', sortable: true, class: 'text-center' },
                      { key: 'actions', label: 'Acciones' }
                  ]
              }

                return campos
            },
            items_map(){

                let result = this.items.map((item)=>{
                    // console.log(item)
                    if(this.onlyShow){
                        let mostrar = {}
                            this.onlyShow.forEach((e)=>{
                            // console.log(e)
                                mostrar[e] = item[e]
                        })
                        // console.log(['acaaaaaaaaaaa',mostrar])

                        return mostrar
                    }else{
                        // console.log('entra')
                        return item
                        // return {
                        //     'title': item.title.es || ' - ',
                        //     'order': item.order || 'aa',
                        //     'featured': item.featured || 0,
                        // }
                    }
                })

                return result
            },
            sortOptions() {
                // Create an options list from our fields
                // return this.fields
                //     .filter(f => f.sortable)
                //     .map(f => {
                //         return { text: f.label, value: f.key }
                //     })
            }
        },
        created(){
            if (this.paginate == false){
                this.perPage = 10000
            }
            if(this.filtrar){
                this.filterOn = this.filtrar
            }
        },
        mounted() {
            // Set the initial number of items
            this.totalRows = this.items.length

        },
        methods: {
            info(item, index, button) {
                this.infoModal.title = `Row index: ${index}`
                this.infoModal.content = JSON.stringify(item, null, 2)
                this.$root.$emit('bv::show::modal', this.infoModal.id, button)
            },
            resetInfoModal() {
                this.infoModal.title = ''
                this.infoModal.content = ''
            },
            onFiltered(filteredItems) {
                // Trigger pagination to update the number of buttons/pages due to filtering
                this.totalRows = filteredItems.length
                this.currentPage = 1
            }
        }
    }
</script>