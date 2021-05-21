<template>
    <web-layout class="">
        <carousel :images="sliders" ></carousel>
        <section class="bg-primario">
            <div class="container">
                <div class="row  py-5" v-for="(item,index) in textos" :key="index">
                    <div class="col-md-3" >
                        <h5 class=" text-white  ">{{ item.title }}</h5>
                    </div>
                    <div class="col-md-6" >
                        
                        <div class="text-white pl-3" style="border-left: 3px solid white;" v-html="item.text"></div>
                    </div>
                    <div class="col-md-3 text-center " >
                        <a href="" class="btn btn-outline-white">
                            simulá tu ambiente <i class="fas fa-long-arrow-alt-right fa-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        
        <div class=" " style="background-color:rgba(190, 202, 214,0.4);" v-if="destacados.length > 0">
            <div class="container-fluid ">
                <div class="row">
                    <template v-for="item in destacados">
                        <div class="col-md-6   p-0">
                            <div class="position-relative">
                                <img :src="item.image" :alt="item.title" class="img-fluid w-100">
                                <div class="position-absolute" style="bottom: 0px">
                                    <a :href="item.ruta" class="px-4 px-md-0 pb-4 pl-md-5 pb-md-5">
                                        <h2 class="text-white mb-n4">{{ item.title }}</h2>
                                        <img :src="$page.appUrl+'/imagenes/flecha.png'" style="width: 370px;" alt="" class="img-fluid">

                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <div class="container my-5" v-if="destacados_p.length > 0">
            <div class="">
                <h5 class="pb-3 text-uppercase   font-weight-bold">Nuestros Productos</h5>
            </div>
        
            <div class="row my-4">
                <template v-for="item in destacados_p">
                    <div class="col-md-3 mb-5">
                        <product-card :item="item" type="1"></product-card>
                    </div>
                </template>
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
            textos: Array,
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