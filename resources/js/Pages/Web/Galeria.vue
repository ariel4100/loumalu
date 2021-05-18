<template>
    <web-layout class="">
        <div class="bg-primario">
            <div class="container">
                <h6 class="section-title">
                    <em>{{ t('Galeria') }}</em>
                </h6>
            </div>
        </div>
         <div class="" style="overflow: hidden;">
            <slick ref="slick" :options="slickOptions2" class="slider-for">
                <div v-for="(item,key) in imagenes" class="">
                    <div class="col my-3 ">
                        <div class="position-relative">
                            <img :src="item.image" :alt="item.title" class="img-fluid mx-auto ">
                            <div class="d-flex position-absolute" style="bottom: 0px; right: 0px;">
                                <span class="p-3 bg-dark" @click="prev()">
                                    <i class="fas fa-arrow-left fa-lg text-white"></i>
                                </span>
                                <span class="p-3 bg-primario" @click="next()">
                                    <i class="fas fa-arrow-right fa-lg text-white"></i>
                                </span>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </slick>
        </div>
         <div class="" style="overflow: hidden;">
            <slick ref="slick" :options="slickOptions" class="slider-nav">
                <div v-for="(item,key) in imagenes" class="">
                    <div class="col my-3">
                        <img :src="item.image" :alt="item.title" class="img-fluid mx-auto">
                    </div>
                </div>
            </slick>
        </div>
    </web-layout>
</template>

<script>
    import Carousel from '@/Components/CarouselComponent'
    import TimeLine from '@/Components/TimeLineCarouselComponent'
    import WebLayout from '@/Layouts/WebLayout'
    import ImageFile from '../../Components/ImageComponent'
    import Modal from '../../Components/ModalComponent'
    import Slick from 'vue-slick';
    export default {
        props: {
            sliders: Array,
            bloques: Array,
            imagenes: Array,
            descargas: Array,
            contenido: Object,
        },
        data(){
          return {
              text:'',
              slickOptions2: {
                   slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    fade: true,
                    asNavFor: '.slider-nav'
                  // Any other options that can be got from plugin documentation
              },
              slickOptions: {
                  slidesToShow: 8,
                    slidesToScroll: 1,
                    asNavFor: '.slider-for',
                    dots: false,
                    arrows: false,
                    centerMode: true,
                    focusOnSelect: true
                  // Any other options that can be got from plugin documentation
              },
          }
        },
        components: {
            TimeLine,
            Modal,
            WebLayout,
            Carousel,
            'image-custom': ImageFile,
            Slick,
        },
        methods: {
             next() {
               
                this.$refs.slick.next();
            },

            prev() {
                this.$refs.slick.prev();
            },
            
            reInit() {
                // Helpful if you have to deal with v-for to update dynamic lists
                this.$nextTick(() => {
                    this.$refs.slick.reSlick();
                });
            },

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