<template>
    <web-layout class="">
        <carousel :images="sliders"></carousel>
        <div class="container my-5">
            <div class="row">
                <template v-for="(item,index) in bloques">
                    <div class="col-md-4 wow fadeIn mb-5" :data-wow-delay="'0.'+index+'s'">
                        <div class="">
                            <h3 class="text-secundario">{{ item.title }}</h3>
                            <p class="" v-html="item.text"></p>
                        </div>
                    </div>
                </template>
            </div>
            <div class="row">
                <div class="col-md-3 mb-5" v-for="(item,index) in descargas">
                    <div class="">
                        <img :src="item.image" :alt="item.title" class="img-fluid border">
                        <div class="">
                            <h5 class="text-primario font-weight-bold mt-2">{{ item.title }}</h5>
                            <div class="d-flex">
                                <a :href="item.file" target="_blank" class="text-secundario">
                                    <i class="fas fa-eye fa-lg"></i>
                                </a>
                                <a :href="item.file" download class="text-secundario ml-3">
                                    <i class="fas fa-download fa-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </web-layout>
</template>

<script>
    import Carousel from '@/Components/CarouselComponent'
    import TimeLine from '@/Components/TimeLineCarouselComponent'
    import WebLayout from '@/Layouts/WebLayout'
    import ImageFile from '../../Components/ImageComponent'
    import Modal from '../../Components/ModalComponent'
    export default {
        props: {
            sliders: Array,
            bloques: Array,
            descargas: Array,
            contenido: Object,
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
            TimeLine,
            Modal,
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