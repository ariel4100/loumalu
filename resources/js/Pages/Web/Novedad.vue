<template>
    <web-layout class="">
        <div class="container my-5">
            <div class="d-flex my-2">
                <a :href="route('novedades')" class="text-color text-italic text-lowercase">
                    <i class="fas fa-home"></i>
                    Noticias /
                </a>
                <a href="" v-if="novedad.title" class="text-color text-italic text-lowercase ml-1">
                     {{ novedad.title }}
                </a>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <carousel :images="sliders" arrows="1"></carousel>
                    <h5 class="text-uppercase mt-3 text-secundario"> {{ categoria.title || 'Otro' }}</h5>
                    <h3 class="font-weight-bold mb-3 text-primario">{{ novedad.title }}</h3>
                    <div class="" v-html="novedad.text"></div>
                </div>
                <div class="col-md-3" >
                    <h3 class="text-secundario font-weight-bold">{{ t('Categorias') }}</h3>
                    <a :href="item.category_news" class="d-flex text-color justify-content-between align-items-center font-weight-bold py-1" v-for="item in categorias">
                        {{ item.title }}
                    </a>
                </div>
            </div>
        </div>
    </web-layout>
</template>

<script>
    import Carousel from '@/Components/CarouselComponent'
    import WebLayout from '@/Layouts/WebLayout'
    import ImageFile from '../../Components/ImageComponent'
    import Modal from '../../Components/ModalComponent'
    export default {
        props: {
            sliders: Array,
            categorias: Array,
            categoria: Object,
            novedad: Object,
        },
        data(){
            return {
                text:'',
                category_id: 1,
                slider: {
                    title: '',
                    text: '',
                    order: '',
                    image: '',
                },
            }
        },
        components: {
            Modal,
            WebLayout,
            Carousel,
            'image-custom': ImageFile,
        },
        methods: {
            filterByCategory(item){
                this.category_id = item.id
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