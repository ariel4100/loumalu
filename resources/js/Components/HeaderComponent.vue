<template>
    <header class="" :class="['home','empresa'].includes($page.currentRouteName) ? 'fixed-top' : ''" >
        <div class=" ">
            <div class="container" v-if="['home','empresa'].includes($page.currentRouteName)">
                <div class="d-flex justify-content-md-end  text-white">
                    <div class="d-flex align-items-center py-2">
                            <li class="nav-item dropdown d-block d-md-none">
                                <a  class="border text-white text-nowrap py-1 nav-link text-uppercase"  data-toggle="modal" data-target="#login" >
                                    simulá tu ambiente
                                </a>
                            </li>


                        <template v-if="$page.auth.check == false">
                            <li class="nav-item dropdown d-none d-md-block">
                                <a  class="border text-white text-nowrap py-1 nav-link text-uppercase"  data-toggle="dropdown">
                                    Zona de clientes
                                </a>
                                <form class="dropdown-menu dropdown-menu-right login-menu bg1" style="min-width: 15rem !important;">
                                    <login-component
                                            class=""
                                    ></login-component>
                                </form>
                            </li>
                            <li class="nav-item dropdown d-block d-md-none">
                                <a  class="border text-white text-nowrap py-1 nav-link text-uppercase" data-toggle="modal" data-target="#login" >
                                    Zona de clientes
                                </a>
<!--                                <a class="nav-link py-md-4 text-white text-uppercase bg-terciario" data-toggle="modal" data-target="#login">-->
<!--                                    <i class="fas fa-user mr-2  text-white"></i> {{ t('Ingresar') }}-->
<!--                                </a>-->
                            </li>
                        </template>
                        <template v-if="$page.auth.check == true">
                            <div class="dropdown">
                                <a class="nav-link text-white texx dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $page.auth.user.username.toUpperCase() || 'CLIENTE'}}  <i class="fas fa-user mr-2  text-dark"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" :href="route('privada.home')">Pedidos</a>
                                    <a class="dropdown-item" @click="logout()">Salir</a>
                                </div>
                            </div>

                        </template>

                    </div>
                </div>
            </div>
            <div v-else class="container"  >
                <div class="d-flex justify-content-md-end   ">
                    <div class="d-flex align-items-center py-2">
                         
                        <a  class="border text-dark  text-nowrap py-1 nav-link text-uppercase"  data-toggle="modal" data-target="#login" >
                            simulá tu ambiente
                        </a>
                             


                        <template v-if="$page.auth.check == false">
                            <li class="nav-item dropdown d-none d-md-block">
                                <a  class="    text-nowrap py-1 nav-link text-uppercase"  data-toggle="dropdown">
                                    Zona de clientes
                                </a>
                                <form class="dropdown-menu dropdown-menu-right login-menu bg1" style="min-width: 15rem !important;">
                                    <login-component
                                            class=""
                                    ></login-component>
                                </form>
                            </li>
                            <li class="nav-item dropdown d-block d-md-none">
                                <a  class="border   text-nowrap py-1 nav-link text-uppercase" data-toggle="modal" data-target="#login" >
                                    Zona de clientes
                                </a>
<!--                                <a class="nav-link py-md-4 text-white text-uppercase bg-terciario" data-toggle="modal" data-target="#login">-->
<!--                                    <i class="fas fa-user mr-2  text-white"></i> {{ t('Ingresar') }}-->
<!--                                </a>-->
                            </li>
                        </template>
                        <template v-if="$page.auth.check == true">
                            <div class="dropdown">
                                <a class="nav-link text-white texx dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $page.auth.user.username.toUpperCase() || 'CLIENTE'}}  <i class="fas fa-user mr-2  text-dark"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" :href="route('privada.home')">Pedidos</a>
                                    <a class="dropdown-item" @click="logout()">Salir</a>
                                </div>
                            </div>

                        </template>

                    </div>
                </div>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-0">
            <div class="container">
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent" >
                    <ul class="navbar-nav m-auto text-center text-start">
                        <li class="nav-item" v-for="item in menu.slice(0,5)" v-if="item.mostrar == 1">
                            <a class="nav-link  fw-medium text-uppercase py-md-4" :class="['home','empresa'].includes($page.currentRouteName) ? 'text-white' : 'text-dark'" :href="route(item.route)">{{ t(item.nombre) }}</a>
                            <hr v-if="item.url.split(',').includes($page.currentRouteName)" width="20px" class="pt-1 m-auto bg-dark">
                        </li>
                    </ul>
                    <a class="navbar-brand m-0" :href="route('home')">
                        <img :src="$page.header" alt="" class="img-fluid" style="max-width: 300px;">
                    </a>
                    <ul class="navbar-nav m-auto text-center text-start">
                        <li class="nav-item" v-for="item in menu.slice(5)" v-if="item.mostrar == 1">
                            <a class="nav-link fw-medium text-uppercase py-md-4" :class="['home','empresa'].includes($page.currentRouteName) ? 'text-white' : 'text-dark'" :href="route(item.route)">{{ t(item.nombre) }}</a>
                            <hr v-if="item.url.split(',').includes($page.currentRouteName)" width="20px" class="pt-1 m-auto bg-dark">
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Modal Login-->
        <div class="modal fade" id="login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase" id="exampleModalLabel">{{ t('Ingresar') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <login-component
                                class=""
                        ></login-component>
                    </div>
                </div>
            </div>
        </div>

    </header>
</template>

<script>
    import Login from '@/Components/LoginComponent'
    export default {
        components: {
          'login-component': Login
        },
        props:['title'],
        data() {
            return {
                nombre: '',
            }
        },
        methods: {
            buscar(){
                let data = {}
                data['marca'] = ''
                data['familia'] = ''
                data['nombre'] = this.nombre
                console.log(data)
                this.$inertia.get(route('buscador.pro',data));

            }
        }
    }
</script>