<template>
    <app-layout>
        <template #header>
            Productos
        </template>
        <div class="card">
            <div class="card-header">
                <modal
                        title="Producto"
                        title-button="Agregar Producto"
                        @ok="add()"
                        @hidden="reset()"
                >
                    <template #idioma="{ lang }">
                        <div class="row">
                            <div class="col-md-8 form-group">
                                <label for="">Titulo</label>
                                <input type="text" v-model="product.title" class="form-control">
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Codigo</label>
                                <input type="text" v-model="product.cod" class="form-control">
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Orden</label>
                                <input type="text" v-model="product.order" class="form-control">
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">Texto</label>
                                <jodit-vue v-model="product.text[lang]" :id="'Texto-'+lang"></jodit-vue>
                            </div>

                        </div>
                    </template>
                    <template #default>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="">Precio</label>
                                <money v-model="product.price" class="form-control" v-bind="money"></money>

                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Familias</label>
<!--                                <multiselect v-model="familia_selected" :options="familias" placeholder="Familia" label="title" track-by="id"></multiselect>-->

                                <select v-model="product.family_id" id="" class="form-control">
                                    <option value="" disabled selected>Familia</option>
                                    <option :value="item.id" v-for="item in familias">
                                        {{ item.title }}
                                    </option>
                                </select>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-12 form-group">
                                <label for="">Productos Relacionados</label>
                                <select-multiple
                                        :data="productos"
                                        :model.sync="product.productos"
                                ></select-multiple>
                            </div>
                            <div class="form-group col-md-6 d-flex align-items-end">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" v-model="product.featured" :true-value="1" :false-value="0" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1">Mostrar en la Sección Principal?</label>
                                </div>
                            </div>
                            <div class="form-group col-md-6 d-flex align-items-end">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" v-model="product.stock" :true-value="1" :false-value="0" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1">Tiene Stock?</label>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Archivo</label>
                                <image-custom :model.sync="product.file"></image-custom>
                            </div>
                            <div class="col-md-12" >
                                <label>Agregar fotos</label>
                                <custom-gallery   label="" :model.sync="product.gallery" :link="0" class=""></custom-gallery>
                            </div>
                        </div>
                    </template>
                </modal>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Titulo</th>
                        <th scope="col">Orden</th>
                        <th scope="col">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item,index) in productos">
                        <td>{{ item.title || '' }}</td>
                        <td>{{ item.order }}</td>
                        <td>
                            <button @click="edit(item)" data-target="#category" class="btn btn-warning btn-circle" data-toggle="modal">
                                <i class="far fa-edit"></i>
                            </button>
                            <button @click="duplicate(item)" class="btn btn-info btn-circle">
                                <i class="far fa-clone"></i>
                            </button>
                            <button @click="del(item)" class="btn btn-danger btn-circle">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import SelectMultiple from '@/Components/SelectMultipleComponent'
    import AppLayout from '@/Layouts/AppLayout'
    import ImageFile from '@/Components/ImageComponent'
    import Modal from '@/Components/ModalComponent'
    import CustomGallery from '../../components/CustomGalleryComponent';

    export default {
        props: {
            familias: Array,
            subfamilias: Array,
            productos: Array,
            contenido: Object,
            section: '',
        },
        data(){
          return {
              familia_selected_id: '',
              product: {
                  id: '',
                  cod: '',
                  title: '',
                  price: '',
                  stock: 1,
                  featured: 0,
                  text: {},
                  family_id: '',
                  productos: [],
                  gallery: [],
                  file: '',
                  order: '',
              },
          }
        },
        components: {
            SelectMultiple,
            Modal,
            AppLayout,
            'custom-gallery': CustomGallery,
            'image-custom': ImageFile,
        },
        // watch:{
        //     familia_selected(){
        //         this.product.family_id = ''
        //     }
        // },
        computed:{
            subfamilias_filter(){
                let result = this.subfamilias.filter((item) => {
                    return item.padre_id ==  this.familia_selected.id
                })
                return result;
            }
        },
        methods: {

            reset(){
                this.product = {
                    id: '',
                    title: {},
                    description: {},
                    text: { es: ''},
                    text_video: {},
                    family_id: '',
                    video: '',
                    productos: [],
                    banner: '',
                    gallery: [],
                    file: '',
                    order: '',
                    featured: 0,
                };
            },
            add(){
                let data = new FormData()

                if (this.product.gallery) {
                    let self = this
                    Object.keys(this.product.gallery).forEach(function(key){

                        let file = self.product.gallery[key]
                        if (file && file instanceof File) {
                            data.append('gallery['+key+']', file);
                        }
                        if (typeof file === 'string' || file instanceof String) {
                            data.append('gallery['+key+']', file);
                        }
                        if (typeof file === 'object' || file instanceof Object) {
                            // console.log( file.image)
                            data.append('gallery['+key+']', file);

                            // self.formData.append('images['+key+'][title]', file.title);
                        }
                    })
                }


                data.append('id', this.product.id)
                data.append('cod', this.product.cod || '')
                data.append('title', this.product.title || '')
                data.append('text', JSON.stringify(this.product.text) || '')
                data.append('productos', JSON.stringify(this.product.productos || []))
                data.append('family_id', this.product.family_id || '')
                data.append('archivo', this.product.file || '')
                data.append('order', this.product.order || '')
                data.append('featured', this.product.featured || '')

                this.$inertia.post(route('adm.productos.store'), data).then(() => {

                });
            },



            duplicate(id){
                Swal.fire({
                    title: '¿Estas seguro de duplicar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        this.$inertia.get(route('adm.productos.show',{id: id})).then(() => {
                            // Swal.fire({
                            //     icon: 'success',
                            //     title: 'Se elimino correctamente',
                            //     showConfirmButton: false,
                            //     timer: 2000
                            // })

                        })
                    }
                })

            },
            edit(item){
                console.log(item)
                // this.familia_selected = item.family || {}
                this.product = JSON.parse(JSON.stringify(item))
                this.$root.$emit('bv::show::modal','modal-prevent-closing')
            },
            del(id){
                Swal.fire({
                    title: '¿Estas seguro de eliminar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        this.$inertia.delete(route('adm.productos.destroy',{id: id})).then(() => {
                            // Swal.fire({
                            //     icon: 'success',
                            //     title: 'Se elimino correctamente',
                            //     showConfirmButton: false,
                            //     timer: 2000
                            // })
                            $('.modal').modal('hide');
                        })
                    }
                })

            },
        },
    }
</script>