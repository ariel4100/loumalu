<template>
    <web-layout class="">
        <section class="bg-primario py-3">
            <div class="container">
                <h6 class="text-white text-italic m-0"><em>Simulador</em></h6>
            </div>
        </section>
        <section class="container my-5">
            <div class="row">
                <div class="col-md-1 text-center">
                    <div class="mb-3 border-bottom" v-for="(item,index) in familias" :key="index">
                        <div @click="showItem(item)" class="">
                            <img :src="$page.appUrl+'/imagenes/simulador.png'" alt="" class="img-fluid mx-auto">
                            <h6 class="my-2 text-uppercase">
                                {{ item.title }}
                            </h6>

                        </div>
                    </div>
                    <div class="mb-3  border-bottom"  >
                        <div @click="restart()" class="">
                            <img :src="$page.appUrl+'/imagenes/camera.png'" alt="" class="img-fluid mx-auto">
                           <h6 class="my-2">
                                DETALLE
                            </h6>
                        </div>
                    </div>
                    <div class="pb-3 border-bottom"  >
                        <div @click="restart()" class="">
                            <img :src="$page.appUrl+'/imagenes/bin.png'" alt="" class="img-fluid mx-auto">
                         
                        </div>
                    </div>
                </div>
                <div class="col-md-2 text-center" style="overflow-y: auto; max-height: 600px">
                    <div class="mb-3" v-for="(item,index) in productos" :key="index">
                        <div @click="showSimulador(item)" class="">
                            <img :src="item.image" alt="" class="img-fluid mx-auto">
                            <h6 class="my-2">
                                {{ item.title }}
                            </h6>

                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <img :src="simulador" alt="" class="img-fluid w-100">
                </div>
            </div>
        </section>
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
            familias: Array,
            productos_f: Array,
            textos: Array,
            descargas: Array,
            producto_f: Object,
        },
        data(){
          return {
              simulador:'',
              productos: []
          }
        },
        components: {
            TimeLine,
            Modal,
            WebLayout,
            Carousel,
            'image-custom': ImageFile,
        },
        created(){
            this.getFirst()
        },
        methods: {

            restart(){
                location.reload()
            },
            getFirst(){
                this.productos = this.productos_f
                this.simulador = this.$page.appUrl+'/storage/'+this.producto_f.banner
            },
            showItem(item){

                this.productos = item.productos
            },
            showSimulador(item){

                this.simulador = item.image_simulador
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