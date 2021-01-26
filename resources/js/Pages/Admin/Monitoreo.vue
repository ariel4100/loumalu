<template>
    <app-layout>
        <template #header>
            Monitoreo
        </template>
        <div class="card">
            <div class="card-body">
                <custom-table
                        :items="items"
                        :fields="fields"
                        :filtrar="['fecha','cliente','ip']"
                >


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
            items: Array,
        },
        data(){
          return {
              fields: [
                  { key: 'fecha', label: 'FECHA', class: 'text-center'},
                  { key: 'cliente', label: 'CLIENTE', class: 'text-center'},
                  { key: 'ip', label: 'IP', class: 'text-center'},
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