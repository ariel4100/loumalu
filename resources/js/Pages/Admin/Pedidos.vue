<template>
    <app-layout>
        <template #header>
            Pedidos
        </template>
        <div class="card">
            <div class="card-body">
                <custom-table
                        :items="pedidos"
                        :fields="fields"
                        :filtrar="['fecha','cliente','total','total_iva','estado']"

                >
                    <template #estado="{ row }">
                        <div class="btn-group">
                            <button type="button" class="btn text-uppercase" :class="row.value.toLowerCase() == 'pendiente' ? 'btn-warning' : 'btn-success'" >   {{ row.item.estado }}</button>
                            <button type="button" class="btn dropdown-toggle dropdown-toggle-split" :class="row.value.toLowerCase() == 'pendiente' ? 'btn-warning' : 'btn-success'"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" @click="cambiarEstado('Pendiente',row.item.id)">Pendiente</a>
                                <a class="dropdown-item" @click="cambiarEstado('Enviado',row.item.id)">Enviado</a>
                            </div>
                        </div>
                    </template>
                    <template #action="{ row }">
                        <button type="button" class="btn btn-info text-white"  @click="row.toggleDetails">
                            {{ row.detailsShowing ? 'OCULTAR' : 'VER' }}  DETALLE
                        </button>
                    </template>

                </custom-table>

            </div>
        </div>
    </app-layout>
</template>

<script>
    import SelectLevel from '@/Components/SelectLevelComponent'
    import AppLayout from '@/Layouts/AppLayout'
    import ImageFile from '@/Components/ImageComponent'
    import Modal from '@/Components/ModalComponent'
    import Table from '@/Components/TableComponent'
    import { mapState, mapActions } from 'vuex'


    export default {
        props: {
            pedidos: Array,
        },
        data(){
          return {
              fields: [
                  { key: 'id', label: 'Nro Pedido', class: 'text-center'},
                  { key: 'fecha', label: 'FECHA', class: 'text-center'},
                  { key: 'cliente', label: 'CLIENTE', class: 'text-center'},
                  { key: 'total', label: 'TOTAL', class: 'text-center'},
                  { key: 'total_iva', label: 'TOTAL (IVA incluído) ', class: 'text-center'},
                  { key: 'estado', label: 'ESTADO', class: 'text-center'},
                  { key: 'actions', label: '',},
              ],

          }
        },
        components: {
            AppLayout,
            'image-custom': ImageFile,
            'custom-table': Table,
        },

        computed:{

        },
        methods: {
            ...mapActions('admin', [
                'eliminar',
            ]),

            cambiarEstado(estado,id){
                let data = {}
                data['estado'] = estado
                data['id'] = id
                this.$inertia.post(route('adm.pedidos.store'), data, {
                    preserveState: true,
                    preserveScroll: true,
                })
            },

            del(id){
                this.eliminar(route('adm.pedidos.destroy',{id: id}))
            },
        },
    }
</script>