<template>
    <web-layout class="">
        <div class="bg-primario">
            <div class="container">
                <h6 class="section-title">
                    <em>{{ t('Solicitud de presupuesto') }}</em>
                </h6>
            </div>
        </div>

        <form @submit.prevent="enviar" class="container my-5">
            <div v-if="$page.flash.success" class="alert alert-success">
                {{ $page.flash.success }}
            </div>
            <div v-if="$page.flash.error" class="alert alert-danger">
                {{ $page.flash.error }}
            </div>
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div v-if="step == 1" class="row">
                        
                        <div class="col-md-12 mb-5">
                    
                            <img :src="$page.appUrl+'/imagenes/pre1.png'" alt="" class="img-fluid mx-auto">
                        </div>
                        <div class="col-md-6 mb-4">
                             <section class="p-3" :style="mail.variante == '1' ? 'background-color: #00548B;' : 'background-color: #F6F6F6;'">
                                <div class="d-flex align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" v-model="mail.variante" id="exampleRadios1" value="1">
                                        <label class="form-check-label" for="exampleRadios1">
                                        </label>
                                    </div>
                                    <img :src="$page.appUrl+'/imagenes/presu-1.png'" alt="" class="img-fluid">
                                    <p class="pl-md-3 m-0" :class="mail.variante == '1' ? 'text-white' : ''">
                                        Mts2 necesarios 
                                        de los revestimientos
                                        veneciano
                                    </p>
                                </div>
                             <div class="mt-3" v-if="mail.variante == '1'">
                                    <input type="text" v-model="mail.radio" class="form-control" placeholder="Radio">
                                </div>
                            </section>
                        </div>
                        <div class="col-md-6 mb-4">
                             <section class="p-3" :style="mail.variante == '2' ? 'background-color: #00548B;' : 'background-color: #F6F6F6;'">
                                 <div class="d-flex align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" v-model="mail.variante" id="exampleRadios1" value="2">
                                        <label class="form-check-label" for="exampleRadios1">
                                        </label>
                                    </div>
                                    <img :src="$page.appUrl+'/imagenes/presu-2.png'" alt="" class="img-fluid">
                                    <p class="pl-md-3 m-0" :class="mail.variante == '2' ? 'text-white' : ''">
                                        Mts2 necesarios 
                                        de los revestimientos
                                        veneciano
                                    </p>
                                 </div>
                            <div class="row" v-if="mail.variante == '2'">
                                <div class="col-md-6 mt-3">
                                    <input type="text" v-model="mail.altura_min" class="form-control" placeholder="Altura Min.">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <input type="text" v-model="mail.altura_max" class="form-control" placeholder="Altura Max.">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <input type="text" v-model="mail.ancho" class="form-control" placeholder="Ancho">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <input type="text" v-model="mail.largo" class="form-control" placeholder="Largo">
                                </div>
                               
                            </div>
                            </section>
                        </div>
                        <div class="col-md-6 mb-4">
                            <input type="text" class="form-control" v-model="mail.nombre" required placeholder="Ingresar nombre *">
                        </div>
                        <div class="col-md-6 mb-4">
                            <input type="email" class="form-control" v-model="mail.email" required placeholder="Ingrese su correo electrónico *">
                        </div>
                        <div class="col-md-6 mb-4">
                            <input type="text" class="form-control" v-model="mail.telefono" required placeholder="Ingrese teléfono *">
                        </div>
                        <div class="col-md-6 mb-4">
                            <input type="text" class="form-control" v-model="mail.empresa" required placeholder="Empresa">
                        </div>
                         <div class="col-md-12 mt-3">
                            <select v-model="mail.revestimiento" id="" class="form-control"  >
                                <option value="0" disabled selected>Tipo de revestimiento</option>
                            </select>
                        </div>
                        <div class="col-md-12 text-right mt-4">
                            <a @click="next()" class="btn btn-primario   text-white">siguiente <i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                    <div v-if="step == 2" class="row">
                        <div class="col-md-12 mb-5">
                            <img :src="$page.appUrl+'/imagenes/pre2.png'" alt="" class="img-fluid mx-auto">
                        </div>
                        <div class="col-md-12 mb-4">
                            <textarea v-model="mail.mensaje" class="form-control" cols="30" rows="10" placeholder="Escriba su mensaje..."></textarea>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" @change="onFileChange" id="customFileLang" lang="es">
                                <label class="custom-file-label" for="customFileLang" v-if="file == 0">Seleccionar Archivo</label>
                                <label class="custom-file-label" for="customFileLang" v-else>{{ file.name }}</label>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex justify-content-between align-items-center mt-4">
                            <a @click="prev()" class="btn btn-white border text-color  ">VOLVER</a>
                            <a @click="enviar()" v-if="loading == 0" class="btn btn-primario   text-white">ENVIAR <i class="fas fa-chevron-right"></i></a>
                            <a v-if="loading == 1" class="btn btn-primario   text-white">ENVIANDO <i class="fas fa-spinner fa-spin "></i></a>
                        </div>
                    </div>

                </div>
            </div>

        </form>

    </web-layout>
</template>

<script>
    import InputImage from '@/Components/CarouselComponent'
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
        data() {
            return {
                text: '',
                step: 1,
                mail: {
                    nombre: '',
                    apellido: '',
                    variante: '1',
                    ancho: '',
                    largo: '',
                    altura_min: '',
                    altura_max: '',
                    radio: '',
                    revestimiento: '0',
                    email: '',
                    asunto: '',
                    telefono: '',
                    mensaje: '',
                },
                asuntos: [
                    'Venta',
                    'Distribuidor',
                    'Servicios/Mantemiento',
                    'Proveedores',
                    'Educativa',
                    'Ente estatal',
                    'Otros',
                ],
                files: 0,
                file: 0,
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
                let file = e.target.files[0];
                this.file = file

            },
            enviar() {

                if (this.mail.nombre == '' || this.mail.mensaje == '' || this.mail.email == '' || this.mail.telefono == '') {
                    return false;
                }
                this.loading = true;
                let form = new FormData()

                // if (this.files.length > 0) {
                //     this.files.forEach(function (file, index) {
                //         if (file && file instanceof File) {
                //             form.append('attached[' + index + ']', file);
                //         }
                //         if (typeof file === 'string' || file instanceof String) {
                //             form.append('attached[' + index + ']', file);
                //         }
                //         if (typeof file === 'object' || file instanceof Object) {
                //             form.append('attached[' + index + ']', file.path);
                //         }
                //     })
                // }

                form.append('datos', JSON.stringify(this.mail));
                form.append('attached',this.file);

                this.$inertia.post(route('mail.presupuesto'), form).then((res) => {
                    // if (res.data.status == 'success'){
                    //     this.$bvToast.toast('¡Gracias por Suscribirte!', {
                    //         title: `FABRIMATICA S.A.`,
                    //         variant: 'success',
                    //         solid: true
                    //     })
                    // }
                    console.log(res)
                    this.mail = {
                        nombre: '',
                        apellido: '',
                        email: '',
                        asunto: '',
                        telefono: '',
                        mensaje: '',
                    },
                        this.file = 0
                        this.step = 1
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

            prev(){
                this.step = 1
            },
            next(){
                if (this.mail.nombre == '' || this.mail.email == '' || this.mail.telefono == '') {
                    return false;
                }
                this.step = 2

            }
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