<template>
    <client-layout class="">
        <div class="" style="background-color: #F9F9F9">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-md-2 mb-md-0 mb-4">
                        <select v-model="marca"  class="form-control">
                            <option value="" selected >Marca</option>
                            <option :value="item" v-for="(item,index) in marcas" :key="index">
                                {{item}}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-md-0 mb-4">
                        <select v-model="familia" id="" class="form-control">
                            <option value="" selected>Rubro</option>
                            <option :value="item.id" v-for="(item,index) in familias" :key="index">
                                {{ item.title || ''}}
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
                        <a @click="Buscar()"  class="btn btn-secundario m-0">
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

        <!-- Modal -->
<div class="modal fade show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
      <div class="modal-body">
        <div class=" " v-html="modal.texto">
            
        </div>
        <!-- <ul class="list-unstyled">

            <li>Pasos:
                <ul>
                <li>Iniciar sessión en la web.</li>
                <li>Inscribirse en los cursos.</li>
                <li>Espera la notificación ó mail que vamos a enviarte.</li>
                <li>Listo, la solicitud estara en proceso.</li>
                <li>NOTA: en caso de cambiar el curso, puede modificar el curso hasta el 27 de Febrero.</li>
                </ul>
            </li>

        </ul> -->
      </div>

    </div>
  </div>
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
            modal: Object,
            contenido: Object,
            descargas: Array,
        },
        data(){
          return {
              marcas: [],
              familias: [],
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
          this.getData()
        },
       mounted(){
            setTimeout(()=>{
                if(this.modal.estado == 1){
                    $('#exampleModal').modal('show'); 
                }
            },2000)
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
            // marca: function () {

            //     this.filteredItems = this.filterItems()
            // },
            // nombre: function () {

            //     this.filteredItems = this.filterItems()
            // },
            // familia: function () {

            //     this.filteredItems = this.filterItems()
            // }
        },
        methods: {
            ...mapActions('carrito', [
                'addToCart',
            ]),
             getData(){
                axios.get(route('buscador.global')).then((res)=>{
                    console.log(res)
                    this.marcas = res.data.marcas_global
                    this.familias = res.data.familias_global
                })
            },
            Buscar(){
                let data = {}
                data['marca'] = this.marca
                data['familia'] = this.familia
                data['nombre'] = this.nombre
                console.log(['acaaaa',data,route('buscador.pro2',data)])
                axios.get(route('buscador.pro2',data)).then((res) => {
                    console.log(res)
                    this.filteredItems = res.data.productos
                });
            },
            verificarStock(data){
                // console.log(data)
                let alertifyObject = alertify.notify('Comprobando stock', 'warning');
                alertifyObject.setContent('Comprobando stock <i class="fas fa-spinner fa-lg fa-spin"></i>');
                setTimeout(function(){
                    if(data.stock == 0 || data.stock == undefined){
                        alertify.error('Stock no disponible');
                        $('#'+data.id).addClass('bg-danger');
                    }
                    if(data.stock == 1 || data.stock == 2){
                        alertify.success('Stock inferior o igual a cantidad crítica');
                        $('#'+data.id).addClass('bg-warning');
                    }
                    if(data.stock > 2){
                        alertify.success('Stock disponible');
                        $('#'+data.id).addClass('bg-success');
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