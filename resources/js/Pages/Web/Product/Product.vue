<template>
    <web-layout class="">
        <buscador
        ></buscador>
        <div class="container my-5">
            <div class="row">
                <div class="col-md-6 mb-5">
                    <carousel :images="gallery"  producto="1"></carousel>
                </div>
                <div class="col-md-6 mb-5">
                    <h4 class="text-primario">
                        {{ producto.title }}
                    </h4>
                    <hr align="left" width="50" class="mt-1 bg-primario">

                    <div class="" v-html="producto.text"></div>
                    <a v-if="producto.file" :href="producto.file" download   class="btn btn-outline-secundario  btn-rounded">DESCARGAR FICHA <i class="fas fa-download"></i></a>
                    <a :href="route('contacto')" class="btn btn-secundario text-white btn-rounded">CONSULTAR</a>
                </div>
                <div class="col-md-6 bg-light mb-5 d-flex justify-content-center align-items-center" v-if="producto.video">
                    <div class="">
                        <p style="white-space: pre-line;">{{ producto.text_video }}</p>
                    </div>
                </div>
                <div class="col-md-6 mb-5" v-if="producto.video">
                    <iframe width="100%" height="300" :src="'https://www.youtube.com/embed/'+producto.video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="col-md-12">
                    <h4 class="text-primario">PRODUCTOS RELACIONADOS</h4>
                    <hr align="left" width="50" class="mt-1 bg-primario">
                    <div class="row">
                        <template v-for="item in productos">
                            <div class="col-md-3 mb-4">
                                <product-card :item="item" type="1"></product-card>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

    </web-layout>
</template>

<script>
    import Buscador from '@/Components/BuscadorComponent'
    import ProductCard from '@/Components/ProductCardComponent'
    import Carousel from '@/Components/CarouselComponent'
    import Tab from '@/Components/TabComponent'
    import WebLayout from '@/Layouts/WebLayout'
    import ImageFile from '@/Components/ImageComponent'
    import Modal from '@/Components/ModalComponent'
    export default {
        props: {
            subfamilia: Array,
            gallery: Array,
            productos: Array,
            producto: Object,
            familia: Object,
        },
        data(){
          return {
              text:'',
              slider: {
                  title: '',
                  text: '',
                  order: '',
                  image: '',
              },
          }
        },
        components: {
            Buscador,
            ProductCard,
            Modal,
            Tab,
            WebLayout,
            Carousel,
            'image-custom': ImageFile,
        },
        methods: {
            saveContent(){
                let data = {
                    id: this.contenido.id,
                    contenido: this.content,
                }
                // data.append('content', this.content || '')
                // data.append('id', this.contenido.id || '')
                this.$inertia.post(route('adm.content.store'), data).then(() => {

                });
            },
            addSlider() {
                let data = new FormData()
                data.append('id', this.slider.id || '')
                data.append('title', this.slider.title || '')
                data.append('text', this.slider.text || '')
                data.append('order', this.slider.order || '')
                data.append('section', this.contenido.section || '')
                data.append('image', this.slider.image || '')
                this.$inertia.post(route('adm.content.slider'), data).then(() => {
                    $('.modal').modal('hide');
                });
            },
            editSlider(item){
                console.log(item)
                this.slider = JSON.parse(JSON.stringify(item))
            },
            delSlider(id){
                Swal.fire({
                    title: '¿Estas seguro de eliminar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        this.$inertia.replace(route('adm.content.destroy.slider',{id: id})).then(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Se elimino correctamente',
                                showConfirmButton: false,
                                timer: 2000
                            })
                        })
                    }
                })

            },
            asset(img){
                if (img){
                    return 'storage/'+img
                }
                return '';
            },
        },
    }
</script>