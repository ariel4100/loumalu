<template>
    <header>
        <div class="bg-white">
            <div class="container">
                <div class="d-flex justify-content-md-between">
                    <div class="d-flex align-items-center">
                        <a :href="item.link" target="_blank" class="px-2  text-dark" v-for="item in $page.redes">
                            <i class="" :class="item.type"></i>
                        </a>
                    </div>
                    <div class="d-flex align-items-center py-2">
                         
                        <a :href="route('privada.home')" class="border bg-primario text-white  text-nowrap py-1 mr-3 nav-link text-uppercase"  data-toggle="modal" data-target="#login" >
                            Descargas
                        </a>
                        <a :href="route('simulador')" class="border text-dark  text-nowrap py-1 nav-link text-uppercase"   >
                            simulá tu ambiente
                        </a>
                             


                        <template v-if="$page.auth.check == false">
                            <li class="nav-item dropdown d-none d-md-block">
                                <a  class="    text-nowrap py-1 nav-link text-uppercase"  data-toggle="dropdown">
                                    Zona Privada
                                </a>
                                <form class="dropdown-menu dropdown-menu-right login-menu " style="min-width: 15rem !important; background-color:#0270B9;">
                                    <login-component
                                            class=""
                                    ></login-component>
                                </form>
                            </li>
                            <li class="nav-item dropdown d-block d-md-none">
                                <a  class="border   text-nowrap py-1 nav-link text-uppercase" data-toggle="modal" data-target="#login" >
                                    Zona Privada
                                </a>
<!--                                <a class="nav-link py-md-4 text-white text-uppercase bg-terciario" data-toggle="modal" data-target="#login">-->
<!--                                    <i class="fas fa-user mr-2  text-white"></i> {{ t('Ingresar') }}-->
<!--                                </a>-->
                            </li>
                        </template>
                        <template v-if="$page.auth.check == true">
                            <div class="dropdown">
                                <a class="nav-link text-dark texx dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Bienvenido, {{ $page.auth.user.username.toUpperCase() || 'CLIENTE'}}  
                                    <!-- <i class="fas fa-user mr-2  text-dark"></i> -->
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" :href="route('privada.home')">Descargas</a>
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

            }
        },
        methods: {

        }
    }
</script>