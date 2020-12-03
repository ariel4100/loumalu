<template>
    <web-layout class="">
        <buscador
        ></buscador>
        <div class="container my-5">
            <div class="row">
                <template v-for="item in familias">
                    <div class="col-md-3 mb-5">
                        <product-card :item="item" ></product-card>
                    </div>
                </template>
            </div>
        </div>

    </web-layout>
</template>

<script>
    import Buscador from '@/Components/BuscadorComponent'
    import Carousel from '@/Components/CarouselComponent'
    import Tab from '@/Components/TabComponent'
    import WebLayout from '@/Layouts/WebLayout'
    import ProductCard from '@/Components/ProductCardComponent'
    import Modal from '@/Components/ModalComponent'
    import SelectLevel from '@/Components/SelectLevelWebComponent'

    export default {
        props: {
            familias: Array,
            familia: Object,
            subfamilia: Object,
        },
        data(){
          return {
              filterByPadreId:'',
          }
        },
        components: {
            Buscador,
            ProductCard,
            SelectLevel,
            WebLayout,
            Carousel,
            Tab,
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
            elimLastSelected(){

            },

        },
    }
</script>