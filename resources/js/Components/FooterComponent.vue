<template>
    <!-- Footer -->
    <footer class="page-footer font-small  " style="background-color: #161616;">

        <!-- Footer Links -->
        <div class=" " >
            <div class="container text-center text-md-left">
                <!-- Grid row -->
                <div class="row pt-5">
                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-3 mx-auto mb-4">
                        <!-- Content -->
                        <h5 class="font-weight-bold">NEWSLETTER</h5>
                        <P>
                            Manténgase informado de todas nuestras nuevas colecciones, noticias y eventos especiales a través de nuestra newsletter y nuestros perfiles en redes sociales.
                        </P>
                        <!-- <div class="d-flex align-items-center">
                            <input v-model="email" type="text" class="form-control" placeholder="Ingrese su correo electrónico*">
                            <i class="fas fa-paper-plane fa-lg p-2 border"></i>
                        </div> -->
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <input v-model="email" type="text" class="form-control" id="inlineFormInputGroupUsername" placeholder="Ingrese su correo electrónico">
                            <div @click="enviar()" class="input-group-text">
                                <i class="fas fa-paper-plane fa-lg  "></i>
                            </div>
                            </div>
                        </div>
                        <!-- <img :src="$page.footer" alt="" class="img-fluid "> -->

                    </div>
                    <div class="col-md-2 col-lg-2  mx-auto mb-4">
                        <!-- Content -->
                        <h5 class="font-weight-bold">PRODUCTOS</h5>
                         <p v-for="item in $page.familias_global" class="mb-1">
                            <a :href="route('productos',item.slug)" class="fz-14 hover-link">{{  t(item.title) }}</a>
                        </p>
                        <!-- <img :src="$page.footer" alt="" class="img-fluid "> -->

                    </div>
                    <div class="col-md-2 col-lg-2  mx-auto mb-4">
                        <!-- Content -->
                        <h5 class="font-weight-bold">LOU MALU</h5>
                            <p v-for="item in menu" class="mb-1">
                                    <a :href="route(item.route)" class="fz-14 hover-link">{{  t(item.nombre) }}</a>
                                </p>

                        <!-- <img :src="$page.footer" alt="" class="img-fluid "> -->

                    </div>
                    <!-- Grid column -->
                    <div class="col-md-4 col-lg-4 mx-auto mb-4">
                        <h5 class="font-weight-bold">CONTACTANOS</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-center justify-content-md-start flex-md-row flex-column text-white">
                                    <ul class="list-group mb-4 mb-md-0 mr-md-3 text-white">
                                        <li class="list-group-item bg-transparent border-0 fz-15 d-flex align-items-center px-0 py-1 mb-2 flex-md-row flex-column">
                                            <i class="fas fa-map-marker-alt fa-lg text-white mr-md-3 mt-2 mr-2"></i>
                                            <div class="">
                                                <h6 class="font-weight-bold">Dirección</h6>
                                                <a :href="item.link" v-for="item in $page.direcciones.slice(0,1)" target="_blank" class="text-white  hover-link" style="white-space: pre-line;">{{ item.address }}</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group mb-4 mb-md-0 text-white">
                                    <li class="list-group-item bg-transparent border-0 fz-15 d-flex align-items-center px-0 py-1 mb-2 flex-md-row flex-column">
                                        <i class="fas fa-phone-volume text-white fa-lg mr-md-3 mb-2"></i>
                                        <div class="d-flex flex-column">
                                                <h6 class="font-weight-bold">Llámenos al </h6>

                                            <template v-for="item in $page.telefonos">
                                                <template v-if="item.type == 'tel'">
                                                    <a :href="'tel:'+item.numero" class="text-white hover-link">
                                                        {{ item.numero_visible }}
                                                    </a>
                                                </template>
                                            </template>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6  ">
                                <ul class="list-group mb-4 mb-md-0 text-white">
                                    <li class="list-group-item bg-transparent border-0 d-flex fz-15 px-0 py-1 mb-2 flex-md-row flex-column">
                                        <i class="fas fa-envelope fa-lg text-white mr-md-3 mt-2"></i>
                                        <div class="">
                                                <h6 class="font-weight-bold">Escríbanos a </h6>

                                            <a :href="'mailto:'+item.email" class="text-white hover-link" v-for="item in $page.emails.slice(0,3)">
                                                {{ item.email }}
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                      
                        
                            
                    </div>
                  
                    <!-- Grid column -->

                   
                    <div class="col-md-12 col-lg-12 d-flex justify-content-between align-items-center py-3" style="border-top: 2px solid white;">
                        <div class="">
                        <img :src="$page.footer" alt="" class="img-fluid ">

                        </div>
                        <div class="d-flex justify-content-center justify-content-md-start flex-md-row flex-column text-white">
                            <ul class="list-group mb-4 mb-md-0 text-white">
                                <li class="list-group-item bg-transparent border-0 fz-15 d-flex align-items-center px-0 py-1 mb-2 flex-md-row  ">
                                    <a :href="item.link" target="_blank" class="mx-2 py-1 text-white" style="border: 2px solid;border-radius: 50%;height: 35px;width: 35px;display: flex;justify-content: center;align-items: center;" v-for="item in $page.redes">
                                        <i class="" :class="item.type"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Grid column -->

                </div>
                <!-- Grid row -->
            </div>
        </div>
        <!-- Footer Links -->
    </footer>
    <!-- Footer -->
</template>

<script>
    export default {
        props:['title','familias_global'],
        data() {
            return {
                email: ''
            }
        },
        methods: {
            enviar(){
                if(this.email == ''){
                    return false
                }
                axios.post(route('mail.newsletter'), { email: this.email}).then((res) => {
                   // console.log(res)
                    this.email = ''
                    if (res.data.status == 'success'){
                        this.$bvToast.toast('¡Gracias por Suscribirte!', {
                            title: `EMONA S.R.L.`,
                            variant: 'success',
                            solid: true
                        })
                    }
                    this.email = ''
                });
            }
        }
    }
</script>
<style>
    .input-newsletter{
        background-color: transparent;
        color: white;
        border-radius: 5rem
    }
</style>