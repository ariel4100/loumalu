<template>
    <app-layout>
        <template #header>
            {{ $page.contenido.section }}
        </template>
        <template v-if="$page.contenido.section == 'carrito'">
            <form @submit.prevent="save()" class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header bg1 py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold  ">Configuración de parametros de Compra</h6>
                        </div>
                        <div class="card-body">
<!--                            <div class="form-group">-->
<!--                                <label for="iva">IVA</label>-->
<!--                                <input id="iva" v-model="texto.iva" type="text" class="form-control" required>-->
<!--                            </div>-->
                            <div class="form-group">
                                <label for="min_reparto">Descuento Web</label>
                                <input id="min_reparto" v-model="texto.descuento_general" type="number" class="form-control">
                            </div>
                            <div class="form-group mt-3">
                                <label for="Texto2">Texto</label>
                                <!-- <textarea class="form-control" id="Texto" v-model="texto.texto" cols="30" rows="10"></textarea> -->
                                <jodit-vue v-model="texto.texto"  id="Texto2"></jodit-vue>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-lg-12 d-sm-flex align-items-center justify-content-end">
                    <button type="submit"  class=" btn btn-primary shadow-sm">
                        <!--<i class="fas fa-save fa-sm text-white-50"></i>-->
                        Guardar
                    </button>
                </div>

            </form>
        </template>
        <template v-else-if="$page.contenido.section == 'pop-up'">
            <form @submit.prevent="savePopup()" class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header bg1 py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold  ">Configuración de Pop-Up</h6>
                        </div>
                        <div class="card-body">
<!--                            <div class="form-group">-->
<!--                                <label for="iva">IVA</label>-->
<!--                                <input id="iva" v-model="texto.iva" type="text" class="form-control" required>-->
<!--                            </div>-->
                            <div class="form-check form-switch">
                                <input class="form-check-input" v-model="popup.estado" :false-value="0" :true-value="1" type="checkbox" id="flexSwitchCheckDefault">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Habilitar?</label>
                            </div>
                            <div class="form-group mt-3">
                                <label for="Texto">Texto</label>
                                <!-- <textarea class="form-control" id="Texto" v-model="popup.texto" cols="30" rows="10"></textarea> -->
                                 <jodit-vue v-model="popup.texto"  id="Texto"></jodit-vue>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-lg-12 d-sm-flex align-items-center justify-content-end">
                    <button type="submit"  class=" btn btn-primary shadow-sm">
                        <!--<i class="fas fa-save fa-sm text-white-50"></i>-->
                        Guardar
                    </button>
                </div>

            </form>
        </template>
        <div v-else class="card" >
            <section v-if="['inicio','empresa'].includes($page.contenido.section)">

                <div class="card-header">
                    <modal
                            title="Slider"
                            title-button="Agregar Slider"
                            @ok="addSlider()"
                            @hidden="reset()"
                    >
                        <template #idioma="{ lang }">
                            <div class="row">
                                <div class="col-md-10 form-group">
                                    <label for="">Titulo</label>
                                    <input type="text" v-model="slider.title[lang]" class="form-control">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="">Orden</label>
                                    <input type="text" v-model="slider.order" class="form-control">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="">Texto</label>

                                    <textarea v-model="slider.text[lang]" class="form-control"  cols="30" rows="3"></textarea>
                                </div>
                            </div>
                        </template>
                        <template #default>
                            <div class="form-group col-md-12">
                                <label>Imagen</label>
                                <image-custom :model.sync="slider.image"></image-custom>
                            </div>
                        </template>
                    </modal>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Imagen</th>
                            <th scope="col">Titulo</th>
                            <th scope="col">Orden</th>
                            <th scope="col">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item,index) in sliders">
                            <td>
                                <img :src="item.image" alt="" class="img-fluid" width="100px">
                            </td>
                            <td>{{ item.title.es }}</td>
                            <td>{{ item.order }}</td>
                            <td>
                                <button @click="editSlider(item)" class="btn btn-warning btn-circle">
                                    <i class="far fa-edit"></i>
                                </button>
                                <button @click="delSlider(item.id)" class="btn btn-danger btn-circle">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <div class="card-body">
                <block v-if="[ 'empresa','descargas'].includes($page.contenido.section)"
                       :model.sync="bloques"
                ></block>
                <block v-if="['empresa','inicio','asistencia'].includes($page.contenido.section)" :model.sync="timelines"
                       btn-title="Agregar Textos"
                       only="text"
                       type="texto"
                ></block>
                <block v-if="['empresa', 'galeria'].includes($page.contenido.section)"
                       :model.sync="images"
                       btn-title="Agregar Imagen"
                       only="image"
                       type="img"
                ></block>
            </div>
            <div class="">
                <button type="button" class="btn btn-info" @click="saveContent">Guardar cambios</button>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '../../Layouts/AppLayout'
    import ImageFile from '../../Components/ImageComponent'
    import Modal from '../../Components/ModalComponent'
    import Block from '../../Components/BlockComponent'
    import SelectMultiple from '@/Components/SelectMultipleComponent'

    export default {
        props: {
            sliders: Array,
            bloques: Array,
            imagenes: Array,
            images: Array,
            timelines: Array,
            contenido: Object,
            productos: Array,
            videos: Array,
        },
        data(){
          return {
              loader: 1,
              content: this.contenido.data || {},
              slider: {
                  title: {},
                  text: {},
                  order: '',
                  image: '',
              },
              texto: {
                  descuento_general: '',
                  texto: '',
                  section: '',
              },
              popup: {
                  estado: '',
                  texto: '',
                  popup: '',
                  section: '',
              },
          }
        },
        components: {

            Modal,
            Block,
            AppLayout,
            SelectMultiple,
            'image-custom': ImageFile,
        },
        created(){
            this.loader = 0
          setTimeout(() => {
              this.loader = 1
          },3000)
            if(this.contenido.data){
                this.texto = this.contenido.data
                this.popup = this.contenido.data
            }
        },
        methods: {
            savePopup() {
                 let formData = new FormData()
                let self = this
 
 
                formData.append('id', this.contenido.id)
                formData.append('section', this.contenido.section || '')
                formData.append('contenido', JSON.stringify(this.popup))
                // data.append('content', this.content || '')
                // data.append('id', this.contenido.id || '')
                // this.$inertia.post(route('adm.content.store'), formData).then(() => {

                // });
                 
              
                this.$inertia.post(route('adm.content.popup'),formData)

                //         axios.post(route('adm.carrito',this.texto)).then(res => {
                //     // this.loader = false
                //     console.log(res)
                //     alertify.notify('Registro guardado correctamente', 'success', 1, function(){
                //         // location.reload()
                //     });
                // });
            },
            save() {
                this.texto.section = this.$page.contenido.section;

                this.$inertia.post(route('adm.carrito',this.texto))

                //         axios.post(route('adm.carrito',this.texto)).then(res => {
                //     // this.loader = false
                //     console.log(res)
                //     alertify.notify('Registro guardado correctamente', 'success', 1, function(){
                //         // location.reload()
                //     });
                // });
            },
            saveContent(){
                let formData = new FormData()
                let self = this
                //concateno los bloques junto con el timelina xq van en la misma tabla
                let all_bloques = this.bloques.concat(this.timelines).concat(this.images)
                if (all_bloques) {
                    let self = this
                    Object.keys(all_bloques).forEach(function(key){
                        let file = all_bloques[key]
                        // console.log(file)
                        if (file && file instanceof File) {
                            formData.append('bloques['+key+']', file);
                        }
                        if (typeof file === 'string' || file instanceof String) {
                            formData.append('bloques['+key+']', file);
                            // formData.append('images['+key+'][title]', JSON.stringify(file.title));
                            // formData.append('images['+key+'][text]', JSON.stringify(file.text));
                        }
                        if (typeof file === 'object' || file instanceof Object) {
                            formData.append('bloques['+key+'][image]', file.image);
                            formData.append('bloques['+key+'][id]', file.id || '');
                            formData.append('bloques['+key+'][title]', JSON.stringify(file.title));
                            formData.append('bloques['+key+'][text]', JSON.stringify(file.text));
                            formData.append('bloques['+key+'][type]', file.type || '');
                            if (file.productos && file.productos.length > 0){
                                formData.append('bloques['+key+'][productos]', JSON.stringify(file.productos));
                            }
                        }
                    })
                }
                if (this.imagenes) {
                    Object.keys(this.imagenes).forEach(function(key){
                        let file = self.imagenes[key]
                        // console.log(file)
                        if (file && file instanceof File) {
                            formData.append('imagenes['+key+']', file);
                        }
                        if (typeof file === 'string' || file instanceof String) {
                            formData.append('imagenes['+key+']', file);
                            // formData.append('images['+key+'][title]', JSON.stringify(file.title));
                            // formData.append('images['+key+'][text]', JSON.stringify(file.text));
                        }
                        if (typeof file === 'object' || file instanceof Object) {
                            formData.append('imagenes['+key+'][image]', file);
                        }
                    })
                }
                formData.append('id', this.contenido.id)
                formData.append('section', this.contenido.section || '')
                // data.append('content', this.content || '')
                // data.append('id', this.contenido.id || '')
                this.$inertia.post(route('adm.content.store'), formData).then(() => {

                });
            },
            reset(){
                this.slider = {
                    id: '',
                    title: {},
                    text: {},
                    order: '',
                    image: '',
                };
            },
            addSlider() {
                let data = new FormData()
                data.append('id', this.slider.id || '')
                data.append('title', JSON.stringify(this.slider.title) || '')
                data.append('text', JSON.stringify(this.slider.text) || '')
                data.append('order', this.slider.order || '')
                data.append('section', this.contenido.section || '')
                data.append('image', this.slider.image || '')
                this.$inertia.post(route('adm.content.slider'), data).then((res) => {
                    console.log(res)
                    // if (res.data.hasOwnProperty('status')) {
                    //     if (res.data.status == 'error'){
                    //         return false
                    //     }
                    // }
                    this.$nextTick(() => {
                        this.$bvModal.hide('modal-prevent-closing')
                    })
                });
            },
            editSlider(item){
                item.title.length == 0 ? item.title = {} : item.title
                item.text.length == 0 ? item.text = {} : item.text
                console.log(item)
                this.slider = JSON.parse(JSON.stringify(item))
                this.$root.$emit('bv::show::modal','modal-prevent-closing')
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
        },
    }
</script>