<template>
    <app-layout>
        <h1 class="h1">Videos</h1>
        <div class="card">
            <div class="card-header">
                <button @click="edit({})" data-target="#video" class="btn btn-primary" data-toggle="modal">
                    Agregar
                </button>
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
                    <tr v-for="(item,index) in videos">
                        <td>{{ item.title }}</td>
                        <td>{{ item.order }}</td>
                        <td>
                            <button @click="edit(item)" data-target="#video" class="btn btn-warning btn-circle" data-toggle="modal">
                                <i class="fas fa-exclamation-triangle"></i>
                            </button>
                            <button @click="del(item)" class="btn btn-danger btn-circle">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <!-- Modal -->
                <div class="modal fade" id="video" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-dark text-white">
                                <h5 class="modal-title" id="exampleModalLabel">categoria</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form class="row">
                                    <div class="form-group col-md-8">
                                        <label>Titulo</label>
                                        <input type="text" v-model="video.title" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Orden</label>
                                        <input type="text" v-model="video.order" class="form-control">
                                    </div>
<!--                                    <div class="form-group col-md-12">-->
<!--                                        <label>Texto</label>-->
<!--                                        <textarea name="" id="" cols="30" rows="3" class="form-control" v-model="video.text"></textarea>-->
<!--                                    </div>-->
                                    <div class="col-md-12">
                                        <button type="button" @click="addVideo" class="btn btn-info text-center">Agregar video youtube</button>
                                        <div class="row">
                                            <div v-for="(item,index) in video.video" class="form-group col-md-4">
                                                <div class="d-flex">
                                                    <input type="text" v-model="item.code" class="form-control" placeholder="Codigo">
                                                    <a @click="delVideo(index)" class="btn btn-danger text-center text-white">X</a>
                                                </div>
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe class="embed-responsive-item" :src="'https://www.youtube.com/embed/'+item.code" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button @click="add" type="button" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '../../Layouts/AppLayout'
    import ImageFile from '../../Components/ImageComponent'
    export default {
        props: {
            videos: Array,
            contenido: Object,
        },
        data(){
          return {

              video: {
                  title: '',
                  text: '',
                  order: '',
                  video: [],
              },
          }
        },
        components: {
            AppLayout,
            'image-custom': ImageFile,
        },
        methods: {


            add(){
                this.$inertia.post(route('adm.videos.store'), this.video).then(() => {
                    $('.modal').modal('hide');
                });
            },

            edit(item){
                console.log(item)
                let data = JSON.parse(JSON.stringify(item))
                this.video.id = data.id || ''
                this.video.title = data.title || ''
                this.video.text = data.text || ''
                this.video.order = data.order || ''
                this.video.video = data.video || []
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
                        this.$inertia.delete(route('adm.videos.destroy',{id: id})).then(() => {
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

            addVideo(){
                this.video.video.push({
                    code: ''
                })
            },

            delVideo(index){
                this.video.video.splice(1,index)
            }
        },
    }
</script>