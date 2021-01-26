<template>
    <web-layout class="">
        <carousel :images="sliders" height="400px"></carousel>
        <buscador
        ></buscador>
        <div class="container my-5">
            <div class="d-md-flex d-none pb-4 justify-content-center align-items-center">
                <hr class="bg-dark m-0 w-100">
                <h5 class="m-0 text-nowrap px-3 font-weight-bold">Nuestros Productos Destacados</h5>
                <hr class="bg-dark m-0 w-100">
            </div>
            <div class="d-block d-md-none mb-4">
                <h5 class="m-0 font-weight-bold">Nuestros Productos Destacados</h5>
            </div>
            <div class="row">
                <template v-for="item in destacados_p">
                    <div class="col-md-3 mb-5">
                        <product-card :item="item" type="1"></product-card>
                    </div>
                </template>
            </div>
        </div>
        <div class="py-5 " style="background-color:rgba(190, 202, 214,0.4);">
            <div class="container ">
                <div class="d-md-flex d-none pb-4 justify-content-center align-items-center">
                    <hr class="bg-dark m-0 w-100">
                    <h5 class="m-0 text-nowrap px-3 font-weight-bold">Principales Categorías de Productos</h5>
                    <hr class="bg-dark m-0 w-100">
                </div>
                <div class="d-block d-md-none mb-4">
                    <h5 class="m-0 font-weight-bold">Principales Categorías de Productos</h5>
                </div>
                <div class="row">
                    <template v-for="item in destacados">
                        <div class="col-md-3 mb-4">
                            <product-card :item="item"></product-card>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <div class="container my-5">
            <div class="d-md-flex d-none pb-4 justify-content-center align-items-center">
                <hr class="bg-dark m-0 w-100">
                <h5 class="m-0 text-nowrap px-3 font-weight-bold">Distribuidores De Las Marcas Líderes</h5>
                <hr class="bg-dark m-0 w-100">
            </div>
            <div class="d-block d-md-none mb-4">
                <h5 class="m-0 font-weight-bold">Distribuidores De Las Marcas Líderes</h5>
            </div>
            <div class="" style="overflow: hidden;">
                <slick ref="slick" :options="slickOptions">
                    <div v-for="(item,key) in marcas" class="">
                        <div class="col my-3">
                            <img :src="item.image" :alt="item.title" class="img-fluid mx-auto">
                        </div>
                    </div>
                </slick>
            </div>
        </div>


    </web-layout>
</template>

<script>
    import Buscador from '@/Components/BuscadorComponent'
    import Carousel from '@/Components/CarouselComponent'
    import WebLayout from '@/Layouts/WebLayout'
    import ProductCard from '@/Components/ProductCardComponent'
    import Modal from '../../Components/ModalComponent'
    import SelectLevel from '@/Components/SelectLevelWebComponent'
    import Slick from 'vue-slick';

    export default {
        props: {
            sliders: Array,
            marcas: Array,
            destacados: Array,
            destacados_p: Array,
        },
        data(){
          return {
              filterByPadreId:'',
              slickOptions: {
                  infinite: true,
                  slidesToShow: 4,
                  slidesToScroll: 1,
                  arrows: true,
                  draggable: true,
                  autoplay: true,
                  autoplaySpeed: 3000,
                  // Any other options that can be got from plugin documentation
              },
          }
        },
        components: {
            Buscador,
            ProductCard,
            SelectLevel,
            WebLayout,
            Carousel,
            Slick,

        },
        methods: {
            buscar(){
                console.log(this.filterByPadreId)
                if (this.filterByPadreId != ''){
                    let seleccionado = this.$page.categorias.find((item)=>{
                        return  item.id == this.filterByPadreId
                    })
                    console.log(seleccionado)

                    location.href = route('familias',{ slug: seleccionado.slug})
                    // this.$inertia.post(route('adm.content.store'), data).then(() => {
                    //
                    // });
                }
            },
            next() {
                this.$refs.slick.next();
            },

            prev() {
                this.$refs.slick.prev();
            },
        },
    }
</script>