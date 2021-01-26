<template>
    <client-layout class="">
        <div class="container my-5">
            <b-table responsive  hover :items="pedidos" :fields="fields">
                <template  #cell(actions)="row">
                    <a class="btn btn-success"  @click="row.toggleDetails">
                        {{ row.detailsShowing ? 'OCULTAR' : 'VER' }}  DETALLE
                    </a>
                </template>
                <template #cell(estado)="row">
                    <span v-if="row.value.toLowerCase() == 'enviado'" class="border-warning d-block text-uppercase font-weight-bold text-warning px-4 py-2">
                         PENDIENTE
                    </span>
                    <span v-else class="border-success d-block text-uppercase font-weight-bold text-success px-4 py-2">
                        ENVIADO
                    </span>
                </template>
                <template #cell(total)="row">
                    $ {{ row.item.total | toCurrency }}
                </template>
                <template #cell(total_iva)="row">
                    $ {{ row.item.total_iva | toCurrency }}
                </template>
                <template #row-details="row">
                    <b-card>
<!--                        <b-list-group v-if="row.item.productos.length > 0">-->
<!--                            <b-list-group-item v-for="(value, key) in row.item.productos" :key="key">-->
<!--                                {{ value.cantidad }} x {{ value.producto }} <b>Cod:</b> {{ value.codigo }}-->
<!--                            </b-list-group-item>-->
<!--                        </b-list-group>-->
                        <template v-if="row.item.mensaje">
                            <h4 class="">Observaciones:</h4>
                            <p class="">
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
        </div>
    </client-layout>
</template>

<script>
    import Table from '@/Components/TableComponent'
    import Carousel from '@/Components/CarouselComponent'
    import ClientLayout from '@/Layouts/ClientLayout'
    import ImageFile from '../../Components/ImageComponent'
    import Modal from '../../Components/ModalComponent'
    export default {
        props: {
            sliders: Array,
            pedidos: Array,
            contenido: Object,
            descargas: Array,
        },
        data(){
            return {
                mostrar:['numero','fecha','estado','total','total_iva'],
                items:[
                    { numero: '25', fecha: '12-11-18', estado: 'pendiente', total: 'pendiente', total_iva: '$ 1.580,00'},
                    { numero: '25', fecha: '12-11-18', estado: 'pendiente', total: 'pendiente', total_iva: '$ 1.580,00'},
                    { numero: '25', fecha: '12-11-18', estado: 'enviado', total: 'pendiente', total_iva: '$ 1.580,00'},
                    { numero: '25', fecha: '12-11-18', estado: 'pendiente', total: 'pendiente', total_iva: '$ 1.580,00'},
                    { numero: '25', fecha: '12-11-18', estado: 'pendiente', total: 'pendiente', total_iva: '$ 1.580,00'},
                ],
                fields: [
                    { key: 'numero', label: 'NÚMERO', class: 'text-center'},
                    { key: 'fecha', label: 'FECHA', class: 'text-center'},
                    { key: 'estado', label: 'ESTADO', class: 'text-center'},
                    { key: 'total', label: 'TOTAL', class: 'text-center'},
                    { key: 'total_iva', label: 'TOTAL (IVA incluído) ', class: 'text-center'},
                    { key: 'actions', label: '',},
                ],
                category: {
                    id: '',
                    nombre: '',
                    autorizado: '',
                    clientes: [],
                    visto: 0,
                    image: '',
                },
            }
        },
        components: {
            Modal,
            ClientLayout,
            Carousel,
            'image-custom': ImageFile,
            'table-custom': Table,
        },
        methods: {
            add(){
                let data = new FormData()

                data.append('id', this.category.id)
                data.append('title', this.category.nombre || '')
                data.append('image', this.category.image || '')
                // data.append('hijo', this.category.hijo || '')
                this.$inertia.post(route('privada.descarga'), data).then(() => {
                    this.category = {
                        id: '',
                        nombre: '',
                        autorizado: '',
                        clientes: [],
                        visto: 0,
                        image: '',
                    }
                    // data.delete('image')
                    $('.modal').modal('hide');
                });
            },
        },
    }
</script>
