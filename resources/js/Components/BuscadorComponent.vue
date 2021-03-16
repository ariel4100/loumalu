<template>
    <div class="" style="background-color: #F9F9F9">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-2 mb-md-0 mb-4">
                    <select v-model="marca"  class="form-control">
                        <option value="" selected disabled>Marca</option>
                        <option :value="item" v-for="item in marcas">
                            {{item}}
                        </option>
                    </select>
                </div>
                <div class="col-md-2 mb-md-0 mb-4">
                    <select v-model="familia" id="" class="form-control">
                        <option value="" selected disabled>Rubro</option>
                        <option :value="item.id" v-for="item in familias">
                            {{ item.title || ''}}
                        </option>
                    </select>
                </div>
<!--                <div class="col-md-2 mb-md-0 mb-4">-->
<!--                    <select name="" id="" class="form-control">-->
<!--                        <option value="" selected>Rubro</option>-->
<!--                    </select>-->
<!--                </div>-->
                <div class="col-md-3 mb-md-0 mb-4">
                    <input type="text" v-model="nombre" class="form-control" placeholder="Ingrese una descripción">
                </div>
                <div class="col-md-2 mb-md-0 mb-4 text-center text-md-left">
                    <a @click="buscar()" class="btn btn-secundario m-0">
                        <i class="fas fa-search mr-2"></i>
                        buscar
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['title'],
        data() {
            return {
                marca: '',
                familia: '',
                nombre: '',
                familias: [],
                marcas: [],
            }
        },
        mounted(){
            this.getData()
        },
        methods: {
            buscar(){
                let data = {}
                data['marca'] = this.marca
                data['familia'] = this.familia
                data['nombre'] = this.nombre
                console.log(data)
                this.$inertia.get(route('buscador.pro',data));
            },
            getData(){
                axios.get(route('buscador.global')).then((res)=>{
                    console.log(res)
                    this.marcas = res.data.marcas_global
                    this.familias = res.data.familias_global
                })
            }
        }
    }
</script>