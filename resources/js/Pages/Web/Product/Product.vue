<template>
    <web-layout class="">
      
        <div class="bg-primario">
            <div class="container">
                <h5 class="section-title  text-white">
                    <i class="fas fa-home"></i>
                    <a :href="route('familias')" class=" text-white">
                        <em>{{ t('Productos') }}</em>
                    </a>
                    <a v-if="familia" :href="route('productos',{ slug: familia.slug })" class=" text-white">
                        <em>{{ (familia ? '| '+familia.title : '') }}</em>
                    </a>
                    <em>{{ (producto ? '| '+producto.title : '') }}</em>
                </h5>
            </div>
        </div>
        <div class="container my-5">
            <div class="row">
                <div class="col-md-3 ">
                    <sidenav
                            :familia-id="familia.id"
                            :producto-id="producto.id"
                            :familias="familias"
                    ></sidenav>
                </div>
                <div class="col-md-9 ">
                    <div class="row">
                        <div class="col-md-6 mb-5 border justify-content-center align-items-center d-flex">
                            <!-- <img :src="'uploads/imagenes/'+producto.code+'.jpg'" alt="" class="img-fluid"> -->
                            <!-- <template v-if="producto.file">
                                <img @error="replaceByDefault($event, null)" :src="producto.file"   class="w-100 d-block">

                            </template>
                            <template v-else>
                                <img @error="replaceByDefault($event,producto)" :src="$page.appUrl+'/uploads/imagenes/'+producto.code+'-001.jpg'"   class="w-100 d-block 2">
                            </template> -->
                           <carousel :images="gallery"  producto="1" arrows="1"></carousel>
                        </div>
                        <div class="col-md-6 mb-5">
                            <h4 class=" font-weight-bold">
                                {{ producto.title }}
                            </h4>
                            <div class="" v-html="producto.text"></div>
                            <a v-if="producto.file" :href="producto.file" download   class="btn btn-outline-primario">DESCARGAR FICHA  </a>
                            <a :href="route('presupuesto')" class="btn btn-primario mx-0 text-white">Solicitud de presupuesto</a>
                        </div>
                        <div class="col-md-12" v-if="medidas.length > 0">
                             <h4 class="text-secundario font-weight-bold">
                                Medidas
                            </h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold">Codigo</th>
                                         <th class="font-weight-bold">Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item,index) in medidas" :key="index">
                                        <td>{{ item.code }}</td>
                                     
                                        <td>{{ item.title }}</td>
                                    </tr>
 
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4 mb-5 d-flex justify-content-center align-items-center" v-if="producto.video" style="background-color:#F6F6F6;">
                            <div class="">
                                <p style="white-space: pre-line;">{{ producto.text_video }}</p>
                            </div>
                        </div>
                        <div class="col-md-8 mb-5" v-if="producto.video">
                            <iframe width="100%" height="300" :src="'https://www.youtube.com/embed/'+producto.video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                       <div class="col-md-12" v-if="productos.length > 0">
                           <h5 class="text-secundario">Productos relacionados</h5>
                           <hr   class="mt-1 bg-secundario">
                           <div class="row">
                               <template v-for="item in productos">
                                   <div class="col-md-4 col-sm-6 col-lg-4 mb-4">
                                       <product-card :item="item" type="1"></product-card>

                                   </div>
                               </template>
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
    import Sidenav from '@/Components/SidenavComponent'
    import WebLayout from '@/Layouts/WebLayout'
    import ImageFile from '@/Components/ImageComponent'
    import ProductCard from '@/Components/ProductCardComponent'
    import Buscador from '@/Components/BuscadorComponent'

    export default {
        props: {
            gallery: Array,
            medidas: Array,
            familias: Array,
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
            ProductCard,
            Sidenav,
            WebLayout,
            Carousel,
            Buscador,
            'image-custom': ImageFile,
        },
        methods: {
              replaceByDefault(e,item) {
                 let imageUrl = this.$page.appUrl+'/uploads/imagenes/'+item.code+'-001.JPG';
                  if(item == null){
                    e.target.src = 'http://osolelaravel.com/intertrade/storage/uploads/logo/Grupo%20429.png'
                  }else{
                    this.imageExists(imageUrl, (exists) => {
                        // console.log(['ssssssssssssssssssss',exists])
                        if(exists){
                            e.target.src = this.$page.appUrl+'/uploads/imagenes/'+item.code+'-001.JPG'
                        }else{
                            e.target.src = 'http://osolelaravel.com/intertrade/storage/uploads/logo/Grupo%20429.png'
                        }
                        // console.log('RESULT: url=' + imageUrl + ', exists=' + exists);
                    });
                      
                  }
            },
            imageExists(url, callback) {
                var img = new Image();
                img.onload = function() { callback(true); };
                img.onerror = function() { callback(false); };
                img.src = url;
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