<template>
    <!-- Footer -->
    <footer class="page-footer font-small bg-secundario">

        <!-- Footer Links -->
        <div class=" " :style="'background-image: url('+$page.appUrl+'/imagenes/imagen_footer.jpg)'" style=" background-size: cover; background-repeat: no-repeat; background-position: top;">
            <div class="container text-center text-md-left">
                <!-- Grid row -->
                <div class="row pt-5">
                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-3 mx-auto mb-4">
                        <!-- Content -->
                        <img :src="$page.footer" alt="" class="img-fluid ">

                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-2 col-lg-2 mx-auto mb-4">
                        <ul class="list-group mb-4 mb-md-0 text-white">
                            <li class="list-group-item bg-transparent border-0 fz-15 d-flex align-items-center px-0 py-1 mb-2 flex-md-row flex-column">
                                <i class="fas fa-phone-volume text-secundario fa-lg mr-md-3 mb-2"></i>
                                <div class="d-flex flex-column">
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
                    <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mb-4">
                        <ul class="list-group mb-4 mb-md-0 text-white">
                            <li class="list-group-item bg-transparent border-0 d-flex fz-15 px-0 py-1 mb-2 flex-md-row flex-column">
                                <i class="fas fa-envelope fa-lg text-secundario mr-md-3 mt-2"></i>
                                <div class="">
                                    <a :href="'mailto:'+item.email" class="text-white hover-link" v-for="item in $page.emails.slice(0,3)">
                                        {{ item.email }}
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-2 col-lg-2 mx-auto mb-md-0 mb-4">
                        <div class="d-flex justify-content-center justify-content-md-start flex-md-row flex-column text-white">
                            <ul class="list-group mb-4 mb-md-0 mr-md-3 text-white">
                                <li class="list-group-item bg-transparent border-0 fz-15 d-flex align-items-center px-0 py-1 mb-2 flex-md-row flex-column">
                                    <i class="fas fa-map-marker-alt fa-lg text-secundario mr-md-3 mt-2 mr-2"></i>
                                    <div class="">
                                        <a :href="item.link" v-for="item in $page.direcciones.slice(0,1)" target="_blank" class="text-white  hover-link" style="white-space: pre-line;">{{ item.address }}</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-2 col-lg-2 mx-auto mb-md-0 mb-4">
                        <div class="d-flex justify-content-center justify-content-md-start flex-md-row flex-column text-white">
                            <ul class="list-group mb-4 mb-md-0 text-white">
                                <li class="list-group-item bg-transparent border-0 fz-15 d-flex align-items-center px-0 py-1 mb-2 flex-md-row flex-column">
                                    <a :href="item.link" target="_blank" class="px-2 py-1 text-white" v-for="item in $page.redes">
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
        props:['title'],
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