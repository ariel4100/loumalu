<template>
    <client-layout class="">
         <section class="bg-primario py-3">
            <div class="container">
                <h6 class="text-white text-italic m-0"><em>Descargas</em></h6>
            </div>
        </section>
        <div class="container wow fadeIn py-4" :data-wow-delay="'0.2s'">
            <div class="row">
                <div class="col-md-3 mt-4" v-for="(item,index) in descargas">
                    <img :src="item.image" alt="" class="img-fluid">
                    <!-- <div class=" " style="min-height: 75px">
                        <hr width="60" class="mt-1 bg-primario">
                        <h6 class="mt-4 pl-2 font-weight-bold mt-md-3 text-primario" v-html="item.title"></h6>
                    </div> -->
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mt-4 pl-2 font-weight-bold mt-md-3 text-primario" v-html="item.title"></h6>
                     
                        <a :href="item.file" download   class="m-1">
                            <i class="fas fa-file-download text-primario   p-2"></i>
                            <!-- <i class="fas fa-download  text-white bg-primary p-2 rounded-circle"></i> -->
                        </a>
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
            descargas: Array,
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
                  { key: 'rubro', label: 'RUBRO', class: 'px-0'},
                  { key: 'codigo', label: 'CÓDIGO',},
                  { key: 'marca', label: 'MARCA', class: 'px-0'},
                  { key: 'producto', label: 'PRODUCTO', class: 'text-secundario ' },
                  { key: 'unidad', label: 'UNIDAD', class: 'text-center px-0'},
                  { key: 'stock', label: 'STOCK', class: 'text-center '},
                  { key: 'precio', label: 'PRECIO', class: 'text-center text-nowrap px-0'},
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
<style scoped>
 
.table th, .table td {
    padding: 0.15rem;
    vertical-align: center;
    border-top: 1px solid #dee2e6;
}
</style>