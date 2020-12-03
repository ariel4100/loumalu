<template>
    <header>
        <div class="bg-secundario">
            <div class="container">
                <div class="d-flex justify-content-md-between  text-white">

                </div>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-0">
            <div class="container">
                <a class="navbar-brand" :href="route('home')">
                    <img :src="$page.header" alt="" class="img-fluid" style="max-width: 300px;">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto text-center text-start">
                        <li class="nav-item" >
                            <a class="nav-link fw-medium text-uppercase py-md-4" :class="'home' == $page.currentRouteName ? 'activo' : ''" :href="route('home')">Pedidos</a>
                        </li>
                        <li class="nav-item dropdown">
<!--                            <form method="POST" @submit.prevent="logout">-->
<!--                                <button type="submit" class="nav-link py-md-4 px-4 text-white bg-primario">-->
<!--                                    SALIR-->
<!--                                </button>-->
<!--                            </form>-->
                            <a class="nav-link py-md-4 px-4 text-white bg-primario text-uppercase" @click="logout">
                                {{ t('Salir') }}
                            </a>
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
            logout() {
                axios.post(route('logout').url()).then(response => {
                    window.location = route('home');
                })
            },
        }
    }
</script>