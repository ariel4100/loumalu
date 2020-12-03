<template>
    <app-layout>
        <template #header>
            Industrias
        </template>
        <div class="card">
            <div class="card-header">
                <modal
                        title="Nueva Industria"
                        title-button="Agregar Industria"
                        @ok="add()"
                        @hidden="reset()"
                >
                    <template #idioma="{ lang }">
                        <div class="row">
                            <div class="col-md-10 form-group">
                                <label for="">Titulo</label>
                                <input type="text" v-model="category.title[lang]" class="form-control">
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Orden</label>
                                <input type="text" v-model="category.order" class="form-control">
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">Descripción</label>
                                <jodit-vue v-model="category.description[lang]" :id="'Descripción-'+lang"></jodit-vue>

<!--                                <textarea v-model="category.description[lang]" class="form-control"  cols="30" rows="3"></textarea>-->
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">Texto</label>
                                <jodit-vue v-model="category.text[lang]" :id="'text-'+lang"></jodit-vue>

<!--                                <textarea v-model="category.text[lang]" class="form-control"  cols="30" rows="3"></textarea>-->
                            </div>
                        </div>
                    </template>
                    <template #default>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Imagen Principal</label>
                                <image-custom :model.sync="category.image"></image-custom>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Imagen Banner</label>
                                <image-custom :model.sync="category.image_2"></image-custom>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Imagen de Texto</label>
                                <image-custom :model.sync="category.image_3"></image-custom>
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
                    <tr v-for="(item,index) in categorias">
                        <td>{{ item.title.es || '' }}</td>
                        <td>{{ item.order }}</td>
                        <td>
                            <button @click="edit(item)" data-target="#category" class="btn btn-warning btn-circle" data-toggle="modal">
                                <i class="fas fa-exclamation-triangle"></i>
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
    import AppLayout from '../../Layouts/AppLayout'
    import ImageFile from '../../Components/ImageComponent'
    import Modal from '../../Components/ModalComponent'

    export default {
        props: {
            categorias: Array,
            contenido: Object,
            section: '',
        },
        data(){
          return {

              category: {
                  id: '',
                  title: {},
                  description: {},
                  text: {},
                  image: '',
                  image_2: '',
                  image_3: '',
                  order: '',
              },
          }
        },
        components: {
            Modal,
            AppLayout,
            'image-custom': ImageFile,
        },
        methods: {

            reset(){
                this.category = {
                    id: '',
                    title: {},
                    description: {},
                    text: {},
                    image: '',
                    image_2: '',
                    image_3: '',
                    order: '',
                };
            },
            add(){
                let data = new FormData()
                data.append('id', this.category.id)
                data.append('title', JSON.stringify(this.category.title) || '')
                data.append('text', JSON.stringify(this.category.text) || '')
                data.append('description', JSON.stringify(this.category.description) || '')
                data.append('image', this.category.image || '')
                data.append('image_2', this.category.image_2 || '')
                data.append('image_3', this.category.image_3 || '')
                data.append('section', this.section || '')
                data.append('order', this.category.order || '')
                this.$inertia.post(route('adm.categorias.store'), data).then(() => {

                });
            },

            edit(item){
                console.log(item)
                this.category = JSON.parse(JSON.stringify(item))
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
                        this.$inertia.delete(route('adm.categorias.destroy',{id: id})).then(() => {
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