<template>
    <web-layout class="">
        <div class="" v-html="contenido.title"></div>
        <div class="container my-5">
            <div v-if="$page.flash.success" class="alert alert-success">
                {{ $page.flash.success }}
            </div>
            <div v-if="$page.flash.error" class="alert alert-danger">
                {{ $page.flash.error }}
            </div>
          
            <div class="row">
                <div class="col-md-4">
                      <h3 class="text-primario font-weight-bold  ">
                        {{ t('Contacto')}}
                    </h3>
                    <hr class="bg-primario  ">

                    <p>
                        {{ t('Para mayor información, no dude en contactarse mediante el formulario o a través de nuestras vías de comunicación.')}}
                    </p>

                    <ul class="list-group">
                        <li class="list-group-item pl-0 border-0 d-flex align-items-center">
                            <i class="fas fa-map-marker-alt fa-lg text-primario mt-2 mr-2"></i>
                            <div class="">
                                <!-- <h6 class="text-primario  font-weight-bold">{{ t('Dirección')}}</h6> -->

                                <template v-for="item in $page.direcciones.slice(0,1)">
                                    <a :href="item.link" target="_blank" class="text-muted hover-color" style="white-space: pre-line;">{{ item.address }}</a>
                                </template>

                            </div>
                        </li>
                        

                        <li class="list-group-item pl-0 border-0 d-flex align-items-center">
                            <i class="fab fa-whatsapp fa-lg  mr-2" style="color: #0DC143"></i>
                            <div class="d-flex flex-column">
                                <!-- <h6 class="text-secundario  font-weight-bold mb-0 mt-1">{{ t('Whats App comercial')}}</h6> -->
                                <template v-for="item in $page.telefonos">
                                    <template v-if="item.type == 'wha'">
                                        <a :href="'https://wa.me/'+item.numero" class="text-muted">
                                            {{ item.numero_visible }}
                                        </a>
                                    </template>
                                </template>
                            </div>
                        </li>
                        <li class="list-group-item pl-0 border-0 d-flex align-items-center">
                            <i class="fas fa-phone-volume fa-lg text-primario  mr-2"></i>
                            <div class="d-flex flex-column">
                                <!-- <h6 class="text-secundario  font-weight-bold">{{ t('Llámenos al')}} </h6> -->
                                <template v-for="item in $page.telefonos">
                                    <template v-if="item.type == 'tel'">
                                        <a :href="'tel:'+item.numero" class="text-muted">
                                            {{ item.numero_visible }}
                                        </a>
                                    </template>
                                </template>
                            </div>
                        </li>
                        <li class="list-group-item pl-0 border-0 d-flex align-items-center">
                            <i class="fas fa-envelope fa-lg text-primario mr-2"></i>
                            <div class="">
                                <!-- <h6 class="text-primario  font-weight-bold">E-mail</h6> -->
                                <template v-for="item in $page.emails.slice(0,3)">
                                    <a :href="'mailto:'+item.email" target="_blank" class="text-muted hover-color">{{ item.email }}</a> <br>    
                                </template>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <input type="text" class="form-control" v-model="mail.nombre" :placeholder="t('Ingrese su nombre')">
                        </div>
                        <div class="col-md-4 form-group">
                            <input type="text" class="form-control" v-model="mail.apellido" :placeholder="t('Ingrese su apellido')">
                        </div>
                        <div class="col-md-4 form-group">
                            <input type="text" class="form-control" v-model="mail.empresa" :placeholder="t('Empresa representada')">
                        </div>
                        <div class="col-md-12 form-group">
                            <input type="email" class="form-control" v-model="mail.email" :placeholder="t('Correo electrónico')">
                        </div>
                        <div class="col-md-12 form-group">
                            <textarea name="" class="form-control" v-model="mail.mensaje" :placeholder="t('Mensaje')" cols="30" rows="10"></textarea>
                        </div>

                        <div class="col-md-12 text-right">
                            <button v-if="loading == true" type="button" class="btn btn-primario  px-5 text-white">Enviando <i class="fas fa-sync fa-spin"></i></button>
                            <button v-if="loading == 0" @click="enviar()" class="btn btn-primario px-5  text-white">{{ t('Enviar') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </web-layout>
</template>

<script>
    import Carousel from '@/Components/CarouselComponent'
    import WebLayout from '@/Layouts/WebLayout'
    import ImageFile from '../../Components/ImageComponent'
    import Modal from '../../Components/ModalComponent'
    export default {
        props: {
            sliders: Array,
            bloques: Array,
            contenido: Object,
        },
        data(){
          return {
              text:'',
              mail: {
                  nombre: '',
                  apellido: '',
                  email: '',
                  empresa: '',
                  telefono: '',
                  mensaje: '',
              },
              asuntos:[
                'Venta',
                'Distribuidor',
                'Servicios/Mantemiento',
                'Proveedores',
                'Educativa',
                'Ente estatal',
                'Otros',
              ],
              files: 0,
              loading: 0,
          }
        },
        components: {
            Modal,
            WebLayout,
            Carousel,
            'image-custom': ImageFile,
        },
        methods: {
            onFileChange(e) {
                let file = e.target.files;
                this.files = file
                // console.log(file)
            },
            enviar(){
                if(this.mail.nombre == '' || this.mail.mensaje == '' || this.mail.email == ''  ){
                    return false;
                }
                this.loading = true;
                let form = new FormData()

                if (this.files.length > 0) {
                    this.files.forEach(function(file, index){
                        if (file && file instanceof File) {
                            form.append('attached['+index+']', file);
                        }
                        if (typeof file === 'string' || file instanceof String) {
                            form.append('attached['+index+']', file);
                        }
                        if (typeof file === 'object' || file instanceof Object) {
                            form.append('attached['+index+']', file.path);
                        }
                    })
                }

                form.append('datos', JSON.stringify(this.mail));

                    this.$inertia.post(route('mail.contacto'), form).then(() => {
                        // if (res.data.status == 'success'){
                        //     this.$bvToast.toast('¡Gracias por Suscribirte!', {
                        //         title: `FABRIMATICA S.A.`,
                        //         variant: 'success',
                        //         solid: true
                        //     })
                        // }

                        this.mail = {
                            nombre: '',
                            apellido: '',
                            email: '',
                            asunto: '',
                            telefono: '',
                            mensaje: '',
                        },
                            this.files = 0
                            form.delete('attached')
                        this.loading = false;
                    });


                // axios.post(route('mail.newsletter'), { email: this.email}).then((res) => {
                //     // console.log(res)
                //     this.email = ''
                //     if (res.data.status == 'success'){
                //         this.$bvToast.toast('¡Gracias por Suscribirte!', {
                //             title: `FABRIMATICA S.A.`,
                //             variant: 'success',
                //             solid: true
                //         })
                //     }
                //     this.email = ''
                // });
                // data.append('content', this.content || '')
                // data.append('id', this.contenido.id || '')

            },

        },
    }
</script>
<style>

    .file-input {
        position: relative;
    }

    .file-input > [type='file'] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        z-index: 10;
        cursor: pointer;
    }

    .file-input > .button {
        display: inline-block;
        cursor: pointer;
        background: #eee;
        padding: 8px 16px;
        border-radius: 2px;
        margin-right: 8px;
    }

    .file-input:hover > .button {
        background: dodgerblue;
        color: white;
    }


</style>