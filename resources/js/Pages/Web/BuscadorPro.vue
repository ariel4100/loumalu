<template>
    <web-layout class="">

        <buscador
        ></buscador>

        <div class="container my-5">

            <template v-if="productos.length  == 0">
                <h4 class="my-5">No hay Resultados</h4>
            </template>
            <div class="row mt-5">
                <template v-for="item in productos">
                    <div class="col-md-3 col-sm-6 col-lg-3 mb-4">
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
            productos: Array,
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