<template>
    <client-layout class="">
        <div class="container my-5">
            <template v-for="(item,index) in bloques">
                <template v-if="index == 0">
                    <div class="container wow fadeIn py-4" :data-wow-delay="'0.'+index+'s'">
                        <div class="row">
                            <div class="col-md-10">
                                <h3 class="mb-3 text-secundario">{{ item.title }}</h3>
                                <p class="" v-html="item.text"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
            
            <template v-for="(item,index) in items">
                <div class="row py-3 border-bottom">
                    <div class="col-md-4 mb-4">
                        <label for="">Código del producto</label>
                        <input type="text" v-model="item.codigo" class="form-control">
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="">Descripción</label>
                        <input type="text" v-model="item.descripcion" class="form-control">
                    </div>
                    <div class="col-md-3 col-10 mb-4">
                        <label for="">Cantidad</label>
                        <input type="number" v-model="item.cantidad" min="1" class="form-control">
                    </div>
                    <div class="col-md-1 col-2 mb-3 d-flex align-items-center justify-content-end">
                        <a @click="elimItem(index)" class="">
                            <i class="far fa-times-circle fa-lg"></i>
                        </a>
                    </div>
                </div>
            </template>
            <div class="row  pt-4" style="background-color: rgba(110,111,113,5%);">
                <div class="col-md-4 d-flex align-items-center mb-4">
                    <div class="p-2 w-100 text-muted border">Código del producto</div>
                </div>
                <div class="col-md-4 d-flex align-items-center mb-4">
                    <div class="p-2 w-100 text-muted border">Descripción</div>
                </div>
                <div class="col-md-1 d-flex align-items-center mb-4">
                    <div class="p-2 w-100 text-muted border">1</div>
                </div>
                <div class="col-md-3 d-flex align-items-center mb-4">
                    <a @click="addItem()" class="btn btn-block btn-outline-dark p-2 m-0">AGREGAR NUEVO ITEM</a>
                </div>
            </div>

            <form @submit.prevent="send()" class="row mt-5">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="">NÚMERO/S FACTURA/S</label>
                            <input type="text" required v-model="reclamo.factura" class="form-control" placeholder="Factura">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="">NOMBRE TRANSPORTE</label>
                            <input type="text" required v-model="reclamo.transporte" class="form-control" placeholder="Transporte">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="">FECHA/S FACTURA/S</label>
                            <input type="text" required v-model="reclamo.fecha" class="form-control" placeholder="Fecha">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="">NÚMERO GUÍA</label>
                            <input type="text" required v-model="reclamo.guia" class="form-control" placeholder="Guía">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="form-group">
                        <label for="">MOTIVO DE LA DEVOLUCIÓN</label>
                        <textarea v-model="reclamo.motivo"  cols="30" rows="5" class="form-control" placeholder="Escriba su mensaje..."></textarea>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <label for="">AUTORIZÓ</label>
                    <input type="text" required v-model="reclamo.autorizo" class="form-control" placeholder="Empresa representada">
                </div>
                <div class="col-md-3 mb-4">
                    <label for="">CONTROLÓ</label>
                    <input type="text" required v-model="reclamo.controlo" class="form-control" placeholder="Empresa representada">
                </div>
                <div class="col-md-4 mb-4">
                    <label for="">ARJUNTAR FOTO DE LA GUÍA</label>
                    <input type="file"  class="">
                </div>
                <div class="col-md-12 text-right mb-4">
                    <button type="submit" class="btn btn-secundario px-5">enviar</button>
                </div>
            </form>
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
              items:[],
              reclamo: {
                  factura: '',
                  transporte: '',
                  guia: '',
                  fecha: '',
                  autorizo: '',
                  controlo: '',
                  motivo: '',

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
            send(){
                let data = new FormData()

                console.log([this.reclamo,this.items]);
                return false;
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

                });
            },
            elimItem(index){
                this.items.splice(index,1)
            },
            addItem(){
                this.items.push({
                    codigo: '',
                    descripcion: '',
                    cantidad: 1,
                })
            }
        },
    }
</script>