<template>
    <client-layout class="">
        <div class="container my-5">
            <table-custom
                    :items="productos"
                    :onlyShow="mostrar"
                    :fields="fields"
                    :search="false"
            >
                <template #action="{ item }">
                    <button @click="addToCart(item)" class="btn btn-success text-white" >
                        AGREGAR
                    </button>
                </template>
                <template #cantidad="{ item }">
                    <input type="number" class="form-control" v-model="item.cantidad">
                </template>
            </table-custom>

        </div>
    </client-layout>
</template>

<script>
    import Carousel from '@/Components/CarouselComponent'
    import ClientLayout from '@/Layouts/ClientLayout'
    import ImageFile from '../../Components/ImageComponent'
    import Modal from '../../Components/ModalComponent'
    import Table from '@/Components/TableComponent'
    import { mapState, mapActions } from 'vuex'

    export default {
        props: {
            sliders: Array,
            productos: Array,
            contenido: Object,
            descargas: Array,
        },
        data(){
          return {
              mostrar:['id','rubro','codigo','marca','producto','unidad','precio','cantidad','subtotal'],
              items:[
                  { rubro: ' 067 ', codigo: 'MI00517280C/1C', marca: 'FORD', producto: 'PISTONES CON PERNO TIK Y NOZUMI', unidad: '4', precio: '$ 1.500,00', cantidad: '2', subtotal: '$ 18.000,00'},
                  { rubro: ' 067 ', codigo: 'MI00517280C/1C', marca: 'FORD', producto: 'PISTONES CON PERNO TIK Y NOZUMI', unidad: '4', precio: '$ 1.500,00', cantidad: '2', subtotal: '$ 18.000,00'},
                  { rubro: ' 067 ', codigo: 'MI00517280C/1C', marca: 'FORD', producto: 'PISTONES CON PERNO TIK Y NOZUMI', unidad: '4', precio: '$ 1.500,00', cantidad: '2', subtotal: '$ 18.000,00'},
                  { rubro: ' 067 ', codigo: 'MI00517280C/1C', marca: 'FORD', producto: 'PISTONES CON PERNO TIK Y NOZUMI', unidad: '4', precio: '$ 1.500,00', cantidad: '2', subtotal: '$ 18.000,00'},
                  { rubro: ' 067 ', codigo: 'MI00517280C/1C', marca: 'FORD', producto: 'PISTONES CON PERNO TIK Y NOZUMI', unidad: '4', precio: '$ 1.500,00', cantidad: '2', subtotal: '$ 18.000,00'},

              ],
              fields: [
                  { key: 'rubro', label: 'RUBRO',},
                  { key: 'codigo', label: 'CÓDIGO',},
                  { key: 'marca', label: 'MARCA',},
                  { key: 'producto', label: 'PRODUCTO', class: 'text-secundario'},
                  { key: 'unidad', label: 'UNIDAD', class: 'text-center'},
                  { key: 'precio', label: 'PRECIO', class: 'text-center text-nowrap'},
                  { key: 'cantidad', label: 'CANTIDAD',},
                  { key: 'subtotal', label: 'SUBTOTAL', class: 'text-center text-nowrap'},
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
            ...mapActions('carrito', [
                'addToCart',
            ]),
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