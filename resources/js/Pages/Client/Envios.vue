<template>
    <client-layout class="">
        <div class="container my-5">
            <table-custom
                  :items="items"
                  :onlyShow="mostrar"
                  :fields="fields"
                  :search="false"
            ></table-custom>
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
              mostrar:['pedido','fecha','transporte','guia'],
              items:[
                  { pedido: '1001-2569', fecha: '12-11-18', transporte: 'La Sevillanita', guia: '10458'},
                  { pedido: '1001-2569', fecha: '12-11-18', transporte: 'La Sevillanita', guia: '10458'},
                  { pedido: '1001-2569', fecha: '12-11-18', transporte: 'La Sevillanita', guia: '10458'},
                  { pedido: '1001-2569', fecha: '12-11-18', transporte: 'La Sevillanita', guia: '10458'},
                  { pedido: '1001-2569', fecha: '12-11-18', transporte: 'La Sevillanita', guia: '10458'},

              ],
              fields: [
                  { key: 'pedido', label: ' PEDIDO WEB',},
                  { key: 'fecha', label: 'FECHA',},
                  { key: 'transporte', label: 'TRANSPORTE',},
                  { key: 'guia', label: 'GUÍA DE TRANPOSTE',},
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
