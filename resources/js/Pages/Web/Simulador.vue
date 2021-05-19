<template>
    <web-layout class="">
        <section class="bg-primario py-3">
            <div class="container">
                <h6 class="text-white text-italic m-0"><em>Simulador</em></h6>
            </div>
        </section>
        <div class="container my-5">
            <div class="accordion" id="accordionExample">
                <div class=""  v-for="(item,index) in textos" :key="index">
                    <div class="card-header bg-white py-2" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn shadow-none px-0 btn-block text-left text-dark hover-link" style="padding: 0px !important" type="button" data-toggle="collapse" :data-target="'#collapseOne'+index" aria-expanded="true" aria-controls="collapseOne">
                         {{ item.title }}
                        </button>
                    </h2>
                    </div>

                    <div :id="'collapseOne'+index" class="collapse" :class="index == 0 ? 'show' : ''" style="padding: 0px !important" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="" v-html="item.text"></div>
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
            textos: Array,
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