<template>
    <client-layout class="">
        <div class="container my-5">
            <div v-if="$page.flash.success" class="alert alert-success">
                {{ $page.flash.success }}
            </div>
            <div v-if="$page.flash.error" class="alert alert-danger">
                {{ $page.flash.error }}
            </div>

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
                        <label for="">Código del producto <span class="text-danger">*</span></label>
                        <input type="text" v-model="item.codigo" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="">Descripción</label>
                        <input type="text" v-model="item.descripcion" class="form-control">
                    </div>
                    <div class="col-md-3 col-10 mb-4">
                        <label for="">Cantidad <span class="text-danger">*</span></label>
                        <input type="number" v-model="item.cantidad" min="1" class="form-control" required>
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
                    <div class="p-2 w-100 text-muted border">Código del producto </div>
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
                            <label>NÚMERO/S FACTURA/S</label>
                            <input type="text"   v-model="reclamo.factura" class="form-control" placeholder="Factura">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label>NOMBRE TRANSPORTE <span class="text-danger">*</span></label>
                            <input type="text" required v-model="reclamo.transporte" class="form-control" placeholder="Transporte">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label>FECHA/S FACTURA/S</label>
                            <input type="date"   v-model="reclamo.fecha" class="form-control" placeholder="Fecha">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label>NÚMERO GUÍA <span class="text-danger">*</span></label>
                            <input type="text" required v-model="reclamo.guia" class="form-control" placeholder="Guía">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="form-group">
                        <label for="">MOTIVO DE LA DEVOLUCIÓN <span class="text-danger">*</span></label>
                        <textarea v-model="reclamo.motivo"  required cols="30" rows="5" class="form-control" placeholder="Escriba su mensaje..."></textarea>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <label for="">AUTORIZÓ</label>
                    <input type="text" required v-model="reclamo.autorizo" class="form-control" placeholder="Empresa representada">
                </div>
                <div class="col-md-3 mb-4">
                    <label for="">TIPO DE ENVIO</label>
                    <select v-model="reclamo.controlo" class="form-control">
                        <option  value="A Domicilio">A domicilio</option>
                        <option value="Retiro Terminal">Retiro terminal</option>
                    </select>
                    <!-- <input type="text" required v-model="reclamo.controlo" class="form-control" placeholder="Empresa representada"> -->
                </div>
                <div class="col-md-4 mb-4">
                    <label for="">ARJUNTAR FOTO DE LA GUÍA</label>
                    <input type="file" @change="onFileChange($event)"  class="">
                </div>
                <div class="col-md-12 text-right mb-4">

                    <button v-if="loading == true" type="button" class="btn btn-secundario  text-white">Enviando <i class="fas fa-sync fa-spin"></i></button>
                    <button v-if="loading == 0" type="submit" class="btn btn-secundario  text-white">{{ t('Enviar') }}</button>

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
              loading: 0,
              foto_guia: '',
              reclamo: {
                  factura: '',
                  transporte: '',
                  guia: '',
                  fecha: '',
                  autorizo: '',
                  controlo: '',
                  motivo: '',
                  usuario: this.$page.auth.user.username,
                  nombre: this.$page.auth.user.name,
                  email: this.$page.auth.user.email,

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
            onFileChange(e){

                let file = e.target.files[0];

                this.foto_guia = file
            },
            send(){
                let data = new FormData()
                this.loading = true;
                console.log([this.reclamo,this.items]);



                data.append('datos', JSON.stringify(this.reclamo) || '')
                data.append('items', JSON.stringify(this.items) || '')
                data.append('foto', this.foto_guia || '')
                // data.append('hijo', this.category.hijo || '')
                this.$inertia.post(route('privada.reclamos.mail'), data).then(() => {
                    this.loading = false;
                    this.items = [];
                    this.reclamo = {
                        factura: '',
                        transporte: '',
                        guia: '',
                        fecha: '',
                        autorizo: '',
                        controlo: '',
                        motivo: '',
                        usuario: this.$page.auth.user.username,
                        nombre: this.$page.auth.user.name,
                        email: this.$page.auth.user.email,

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