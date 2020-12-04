<template>
    <client-layout class="">
        <div class="container my-5">
            <table class="table table-hover table-responsive-sm">
                <thead>
                <tr style="border-bottom: 3px solid #007cc2;">
                    <th scope="col">RUBRO</th>
                    <th class="text-center">CÓDIGO</th>
                    <th scope="col">MARCA</th>
                    <th scope="col">PRODUCTO</th>
                    <th class="text-center">UNIDAD</th>
                    <th class="text-center">PRECIO</th>
                    <th class="text-center">CANTIDAD</th>
                    <th class="text-center">SUBTOTAL</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in 3">
                    <td>{{ item.title || '067' }}</td>
                    <td class="text-center">{{ item.title || 'MI00517280C/1C' }}</td>
                    <td>{{ item.title || 'FORD' }}</td>
                    <td>{{ item.title || 'PISTONES CON PERNOTIK Y NOZUMI' }}</td>
                    <td>{{ item.title || '4' }}</td>
                    <td>{{ item.title || '$ 1.500,00' }}</td>
                    <td>
                        <select name="" id="" class="form-control">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>{{ item.title || '$ 18.000,00' }}</td>
                    <td class="text-center">
                        <a :href="item.file" download class="">
                            <i class="fas fa-download text-primario"></i>
                        </a>
                        <a :href="item.file" target="_blank" class="">
                            <i class="fas fa-eye text-primario ml-2"></i>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
            <a href="#descarga" data-toggle="modal"  class="btn btn-terciario text-white btn-rounded">CARGAR NUEVO</a>

            <!-- Modal -->
            <div class="modal fade" id="descarga" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar Archivo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="">Nombre de Documento</label>
                                    <input type="text" v-model="category.nombre" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Seleccionar Archivo</label>
                                    <image-custom :model.sync="category.image"></image-custom>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="add()" class="btn btn-primario text-white btn-rounded">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </client-layout>
</template>

<script>
    import Carousel from '@/Components/CarouselComponent'
    import ClientLayout from '@/Layouts/ClientLayout'
    import ImageFile from '../../Components/ImageComponent'
    import Modal from '../../Components/ModalComponent'
    export default {
        props: {
            sliders: Array,
            bloques: Array,
            contenido: Object,
            descargas: Array,
        },
        data(){
          return {
              text:'',
              category: {
                  id: '',
                  nombre: '',
                  autorizado: '',
                  clientes: [],
                  visto: 0,
                  image: '',
              },
          }
        },
        components: {
            Modal,
            ClientLayout,
            Carousel,
            'image-custom': ImageFile,
        },
        methods: {
            add(){
                let data = new FormData()

                data.append('id', this.category.id)
                data.append('title', this.category.nombre || '')
                data.append('image', this.category.image || '')
                // data.append('hijo', this.category.hijo || '')
                this.$inertia.post(route('privada.descarga'), data).then(() => {
                    this.category = {
                        id: '',
                        nombre: '',
                        autorizado: '',
                        clientes: [],
                        visto: 0,
                        image: '',
                    }
                    // data.delete('image')
                    $('.modal').modal('hide');
                });
            },
        },
    }
</script>