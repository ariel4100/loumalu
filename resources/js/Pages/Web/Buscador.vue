<template>
    <web-layout class="">
        <carousel :images="sliders"></carousel>
        <div class="container my-5">
            <h4 class="text-primario">Buscador</h4>
            <div class="row">
                <div class="col-md-4">
                    <input type="search" v-model="search" class="form-control" placeholder="Buscar...">
                </div>
                <div class="col-md-4">
                    <a @click="buscar()" class="btn btn-secundario   mt-md-0 text-nowrap">Buscar</a>
                </div>
            </div>
            <template v-if="productos.length  == 0">
                <h4 class="my-5">No hay Resultados</h4>
            </template>
            <div class="row mt-5">
                <template v-for="item in productos">
                    <div class="col-md-3 mb-4">
                        <a :href="item.ruta" class="p-0 border nav-link product-card position-relative">
                            <div class="product-card__mask ">
                                <div class="">
                                    <h5 class="my-2 text-left  text-white">{{ item.title }}</h5>
                                    <template v-if="subfamilia">
                                        <div v-html="item.description" class=" text-left text-white"></div>
                                    </template>
                                    <template v-else>
                                        <div v-html="item.text" class=" text-left text-white"></div>
                                    </template>
                                    <a :href="item.ruta" class="btn btn-white  btn-rounded text-secundario">VER MÁS</a>
                                </div>
                            </div>
                            <img :src="item.image" alt="" class="img-fluid">
                            <div class="texto p-3">
                                <h5 class="my-2 text-secundario">{{ item.title }}</h5>
                                <template v-if="subfamilia">
                                    <div v-html="item.description" class="text-color"></div>
                                </template>
                                <template v-else>
                                    <div v-html="item.text" class="text-color"></div>
                                </template>
                            </div>
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </web-layout>
</template>

<script>
    import Carousel from '@/Components/CarouselComponent'
    import WebLayout from '@/Layouts/WebLayout'
    import ImageFile from '../../Components/ImageComponent'
    import Modal from '../../Components/ModalComponent'
    import SelectLevel from '@/Components/SelectLevelWebComponent'
    import Slick from 'vue-slick';

    export default {
        props: {
            productos: Array,
        },
        data(){
          return {
              search:'',

          }
        },
        components: {
            WebLayout,
        },
        methods: {
            buscar(){
                console.log(this.search)

                    this.$inertia.get(route('buscador',{ name: this.search})).then(() => {

                    });

            },

        },
    }
</script>