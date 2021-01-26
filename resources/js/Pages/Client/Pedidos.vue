<template>
    <client-layout class="">
        <div class="" style="background-color: #F9F9F9">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-md-2 mb-md-0 mb-4">
                        <select v-model="marca"  class="form-control">
                            <option value="" selected disabled>Marca</option>
                            <option :value="item" v-for="item in $page.marcas_global">
                                {{item}}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-md-0 mb-4">
                        <select v-model="familia" id="" class="form-control">
                            <option value="" selected>Rubro</option>
                            <option :value="item.nombre" v-for="item in $page.familias_global">
                                {{ item.nombre || ''}}
                            </option>
                        </select>
                    </div>
                    <!--                <div class="col-md-2 mb-md-0 mb-4">-->
                    <!--                    <select name="" id="" class="form-control">-->
                    <!--                        <option value="" selected>Rubro</option>-->
                    <!--                    </select>-->
                    <!--                </div>-->
                    <div class="col-md-3 mb-md-0 mb-4">
                        <input type="text" v-model="nombre" class="form-control" placeholder="Ingrese una descripción">
                    </div>
                    <div class="col-md-2 mb-md-0 mb-4 text-center text-md-left">
                        <a   class="btn btn-secundario m-0">
                            <i class="fas fa-search mr-2"></i>
                            buscar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container my-5">
            <table-custom
                    :items="filteredItems"
                    :onlyShow="mostrar"
                    :fields="fields"
                    :search="false"
            >
                <template #action="{ item }">
                    <button @click="addToCart(item)" class="btn btn-success text-white" >
                        AGREGAR
                    </button>
                </template>
                <template #stock="{ item }">
                    <div class="p-4" :id="item.id">
                        <button class="btn btn-dark" @click="verificarStock(item)" type="button">
                            <i class="fas fa-traffic-light text-white"></i>
                        </button>
                    </div>
                </template>
                <template #cantidad="{ item }">
                    <input type="number" class="form-control"  min="0" :step="item.unidad" v-model="item.cantidad">
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
              marca: '',
              familia: '',
              nombre: '',
              mostrar:['id','rubro','codigo','marca','producto','unidad','stock','precio','cantidad','subtotal'],
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
                  { key: 'stock', label: 'STOCK', class: 'text-center'},
                  { key: 'precio', label: 'PRECIO', class: 'text-center text-nowrap'},
                  { key: 'cantidad', label: 'CANTIDAD',},
                  { key: 'subtotal', label: 'SUBTOTAL', class: 'text-center text-nowrap'},
                  { key: 'actions', label: '',},
              ],
              filteredItems: [],
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
        created(){
          this.filteredItems = this.productos
        },
        components: {
            Modal,
            ClientLayout,
            Carousel,
            'image-custom': ImageFile,
            'table-custom': Table,

        },
        // trigger filter on either input
        watch: {
            marca: function () {

                this.filteredItems = this.filterItems()
            },
            nombre: function () {

                this.filteredItems = this.filterItems()
            },
            familia: function () {

                this.filteredItems = this.filterItems()
            }
        },
        methods: {
            ...mapActions('carrito', [
                'addToCart',
            ]),
            verificarStock(data){
                console.log(data)
                let alertifyObject = alertify.notify('Comprobando stock', 'warning');
                alertifyObject.setContent('Comprobando stock <i class="fas fa-spinner fa-lg fa-spin"></i>');
                setTimeout(function(){
                    if(data.stock > 0){
                        alertify.success('Stock disponible');
                        $('#'+data.id).addClass('bg-success');
                    }
                    if(data.stock == 0 || data.stock == undefined){
                        alertify.success('Stock disponible');
                        $('#'+data.id).addClass('bg-danger');
                    }
                },4700)
            },
            filterItems: function() {
                return this.productos.filter(item => {
                    return (
                        (item.marca.toLowerCase().indexOf(this.marca.toLowerCase()) > -1)
                    );
                }).filter(item => {
                    return (item.rubro.toLowerCase().indexOf(this.familia.toLowerCase()) > -1)
                }).filter(item => {
                    return (item.producto.toLowerCase().indexOf(this.nombre.toLowerCase()) > -1)
                })
            },
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