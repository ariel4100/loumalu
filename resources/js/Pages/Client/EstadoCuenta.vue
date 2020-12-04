<template>
    <client-layout class="">
        <div class="container my-5">
            <table-custom
                    :items="items"
                    :onlyShow="mostrar"
                    :fields="fields"
                    :search="false"
            >
                <template #default>

                </template>
            </table-custom>
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
            bloques: Array,
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
                    { key: 'numero', label: 'NÚMERO',},
                    { key: 'fecha', label: 'FECHA',},
                    { key: 'estado', label: 'ESTADO',},
                    { key: 'total', label: 'TOTAL',},
                    { key: 'total_iva', label: 'TOTAL (IVA incluído) ',},
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
