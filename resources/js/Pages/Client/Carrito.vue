<template>
    <client-layout class="">
        <div v-if="carrito.length == 0" class="container my-5">
            <h5 class="text-center">El carrito esta vacio.</h5>
        </div>
        <div v-else class="container my-5">
            <table-custom
                    :items="carrito"
                    :onlyShow="mostrar"
                    :fields="fields"
                    :search="false"
                    :paginate="false"
            >
                <template #action="{ row }">
                    <i @click="elimItemCart(row.index)" class="far fa-times-circle fa-lg"></i>
                </template>
                <template #cantidad="{ item }">
                    <input @change="updateItemCart(item)" type="number"  min="0" :step="item.unidad" class="form-control" v-model="item.cantidad">
                </template>
            </table-custom>
            <div class="row my-4">
                <div class="col-md-12">
                    <label for="">Observaciones</label>
                    <textarea v-model="mensage" class="form-control" cols="30" rows="4" placeholder="Escriba aquí su texto de nota de pedido correspondiente"></textarea>
                </div>
                <div class="col-md-7 col-lg-5 offset-md-5 offset-lg-7 my-4 ">
                    <div class="d-flex justify-content-between align-items-center border-bottom">
                        <div class="">
                            <h5 class="semibold text-color">Subtotal</h5>
                            <h5 class="semibold text-color" v-if="parseInt(subTotalWithDiscountWeb())">Descuento Web ({{ descuento_general}}%)</h5>
                            <h5 class="semibold text-color" v-if="parseInt(subTotalWithDiscountClient())">Descuento Cliente ({{ descuento_cliente}}%)</h5>
                            <h5 class="semibold text-color">IVA (21%)</h5>
                            <h5 class="semibold mt-4">Total</h5>
                        </div>
                        <div class="">
                            <h5 class="semibold text-color">$ {{ subTotal() | toCurrency }}</h5>
                            <h5 class="semibold text-color" v-if="parseInt(subTotalWithDiscountWeb())">- $ {{ subTotalWithDiscountWeb() | toCurrency }}</h5>
                            <h5 class="semibold text-color" v-if="parseInt(subTotalWithDiscountClient())">- $ {{ subTotalWithDiscountClient() | toCurrency }}</h5>
                            <h5 class="semibold text-color"> $ {{ subTotalWithIVA() | toCurrency }}</h5>
                            <h4 class="semibold mt-4">$ {{ total() | toCurrency }}</h4>
                        </div>
                    </div>
                    <div class="text-right">
                        <p  class="text-right mb-3">
                            <small>Precio final, IVA incluido</small>
                        </p>
                        <a :href="route('privada.home')" class="btn btn-outline-success">CONTINUAR PEDIDO</a>
                        <a v-if="spinner == 0" @click="finalizarCampra()" class="btn btn-success">FINALIZAR COMPRA</a>
                        <a v-if="spinner == 1" class="btn btn-success">
                            <b-spinner small ></b-spinner>
                            PROCENSANDO COMPRA
                        </a>
                        <template v-if="spinner == 2">
                            <a v-if="spinner == 2" class="btn btn-danger">
                                ERROR AL PROCESAR PEDIDO
                            </a>
                            <p class="">Intente más tarde</p>
                        </template>

                    </div>
                </div>
            </div>

        </div>
    </client-layout>
</template>

<script>
    import Carousel from '@/Components/CarouselComponent'
    import ClientLayout from '@/Layouts/ClientLayout'
    import ImageFile from '../../Components/ImageComponent'
    import Modal from '../../Components/ModalComponent'
    import Table from '@/Components/TableComponent'
    import { mapState, mapActions } from 'vuex'

    export default {
        props: {
            sliders: Array,
            bloques: Array,
            contenido: Object,
            descargas: Array,
            descuento_general: Number,
            descuento_cliente: Number,
        },
        data(){
            return {
                mensage: '',
                spinner: 0,
                error: 0,
                mostrar:['id','rubro','codigo','marca','producto','unidad','precio','cantidad','subtotal'],
                items:[
                    { rubro: ' 067 ', codigo: 'MI00517280C/1C', marca: 'FORD', producto: 'PISTONES CON PERNO TIK Y NOZUMI', unidad: '4', precio: '$ 1.500,00', cantidad: '2', subtotal: '$ 18.000,00'},
                    { rubro: ' 067 ', codigo: 'MI00517280C/1C', marca: 'FORD', producto: 'PISTONES CON PERNO TIK Y NOZUMI', unidad: '4', precio: '$ 1.500,00', cantidad: '2', subtotal: '$ 18.000,00'},
                    { rubro: ' 067 ', codigo: 'MI00517280C/1C', marca: 'FORD', producto: 'PISTONES CON PERNO TIK Y NOZUMI', unidad: '4', precio: '$ 1.500,00', cantidad: '2', subtotal: '$ 18.000,00'},
                    { rubro: ' 067 ', codigo: 'MI00517280C/1C', marca: 'FORD', producto: 'PISTONES CON PERNO TIK Y NOZUMI', unidad: '4', precio: '$ 1.500,00', cantidad: '2', subtotal: '$ 18.000,00'},
                    { rubro: ' 067 ', codigo: 'MI00517280C/1C', marca: 'FORD', producto: 'PISTONES CON PERNO TIK Y NOZUMI', unidad: '4', precio: '$ 1.500,00', cantidad: '2', subtotal: '$ 18.000,00'},

                ],
                fields: [
                    { key: 'rubro', label: 'RUBRO',},
                    { key: 'codigo', label: 'CÓDIGO',},
                    { key: 'marca', label: 'MARCA',},
                    { key: 'producto', label: 'PRODUCTO', class: 'text-secundario'},
                    { key: 'precio', label: 'PRECIO', class: 'text-center text-nowrap'},
                    { key: 'cantidad', label: 'CANTIDAD',},
                    { key: 'subtotal', label: 'SUBTOTAL', class: 'text-center text-nowrap'},
                    { key: 'actions', label: '',},
                ],
            }
        },
        computed: {
            ...mapState({
                carrito: state =>  state.carrito.carrito,
            }),
        },
        components: {
            Modal,
            ClientLayout,
            Carousel,
            'image-custom': ImageFile,
            'table-custom': Table,

        },
        methods: {
            ...mapActions('carrito', [
                'updateItemCart',
                'elimItemCart',
            ]),
            finalizarCampra(){
                let data = {}
                data['carrito'] = this.carrito
                data['mensage'] = this.mensage || ''
                data['total'] = this.total().toFixed(2)
                data['subtotal'] = this.subTotal().toFixed(2)
                data['subtotaliva'] = this.subTotalWithIVA().toFixed(2)
                alertify.dialog('confirm')
                    .setHeader('Confirmar Pedido')
                    .set({
                    transition:'zoom',
                    movable:false,
                    message: '<div class="text-center"><i class="far fa-question-circle text-info fa-6x"></i></div>' +
                        '<h4 class="text-center mt-3">¿Estas seguro de finalizar la compra?</h4>',
                    labels: {ok:'Confirmar', cancel:'Cancelar'},
                    onok: (closeEvent) => {
                        this.spinner = 1
                        axios.post(route('privada.finalizar.compra',data)).then((res) => {
                            console.log(res)
                            if(res.data == 1){
                                setTimeout(()=>{
                                    this.spinner = 0
                                    alertify.alert()
                                        .setHeader('Pedido Realizado')
                                        .setContent('<div class="text-center"><i class="far fa-check-circle text-success fa-6x"></i></div>' +
                                            '<h2 class="text-center mt-3"> Su pedido fue procesado correctamente. </h1>'
                                        ).show();
                                    this.$store.state.carrito.carrito = []
                                    localStorage.removeItem('carrito_intertrade')
                                    setTimeout(()=>{
                                        location.href = route('privada.estado.cuenta')
                                    },3000)


                                },3000)
                            }else{
                                this.spinner = 2
                            }
                        }).catch(error => {
                            console.log(error.response)
                            this.spinner = 2
                        });

                    },
                }).show();


            },
            total() {
                return this.subTotalWithIVA() + this.subTotal() - this.subTotalWithDiscountWeb() - this.subTotalWithDiscountClient()
            },
            subTotalWithIVA() {
                let precio = this.subTotal()
                precio = ((precio * parseFloat(21)) / 100)
                return precio
            },
            subTotalWithDiscountWeb() {
                let precio = this.subTotal()
                precio = ((precio * parseFloat(this.descuento_general)) / 100)
                return precio
            },
            subTotalWithDiscountClient() {
                let precio = this.subTotal()
                precio = ((precio * parseFloat(this.descuento_cliente)) / 100)
                return precio
            },

            // subTotalDiscount(amount, percent) {
            //     return amount-((amount*percent)/100)
            // },
            subTotal(){
                let subtotal = 0;
                this.carrito.forEach((producto, key)=>{
                    // console.log(this.subTotalDiscount((producto.producto * producto.qty),producto.discount))
                    subtotal += producto.precio * producto.cantidad
                })
                // subtotal = this.formatter.format(subtotal)
                return subtotal
            },

        },
    }
</script>