<template>
    <app-layout>
        <template #header>
            Envios
        </template>
        <div class="card">
            <div class="card-header">
                <modal
                        title="Envios"
                        title-button="Agregar envios"
                        @ok="add()"
                        @hidden="reset()"
                >
                    <template #idioma="{ lang }">
                        <div class="row">
                            <div class="col-md-7 form-group">
                                <label for="">Nro de Pedido</label>
                                <input type="text" v-model="category.pedido" class="form-control">
                            </div>
                            <div class="col-md-5 form-group">
                                <label for="">Fecha</label>
                                <input type="date" v-model="category.fecha" class="form-control"  >
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Transporte</label>
                                <input type="text" v-model="category.transporte" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Guia de Transporte</label>
                                <input type="text" v-model="category.guia" class="form-control">
                            </div>
                            <!-- <div class="form-group col-md-6 d-flex align-items-end">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" v-model="category.featured" :true-value="1" :false-value="0" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1">Mostrar en la Sección Principal?</label>
                                </div>
                            </div> -->
<!--                            <div class="col-md-12 form-group" v-if="category.padre_id != null">-->
<!--                                <label for="">Texto</label>-->
<!--                                <jodit-vue v-model="category.text[lang]" :id="'text-'+lang"></jodit-vue>-->

<!--                                <textarea v-model="category.text[lang]" class="form-control"  cols="30" rows="3"></textarea>-->
<!--                            </div>-->
                        </div>
                    </template>
 
                </modal>
            </div>
            <div class="card-body">
                <b-table striped hover :items="envios" :fields="fields" >
                    <template #cell(actions)="row">
                        <button @click="edit(row.item)" data-target="#category" class="btn btn-warning btn-circle" data-toggle="modal">
                            <i class="far fa-edit"></i>
                        </button>

                       <!-- <button @click="del(row.item)" class="btn btn-danger btn-circle">
                           <i class="fas fa-trash"></i>
                       </button> -->

                    </template>
                </b-table>

  

            </div>
        </div>
    </app-layout>
</template>

<script>
    import SelectLevel from '@/Components/SelectLevelComponent'
    import AppLayout from '@/Layouts/AppLayout'
    import ImageFile from '@/Components/ImageComponent'
    import Modal from '@/Components/ModalComponent'
    import Table from '@/Components/TableComponent'
     import { mapState, mapActions } from 'vuex'


    export default {
        props: {
            envios: Array,
            contenido: Object,
            section: '',
        },
        data(){
          return {
              selectedCat: '',
              fields: [
                  {
                      key: 'id',
                      label: 'Nro',
                  },
                  {
                      key: 'nro_pedido',
                      label: 'Nro Pedido',
                      class: 'text-center'
                  },
                  {
                      key: 'fecha',
                      label: 'Fecha',
                  },
                  {
                      key: 'transporte',
                      label: 'Transporte',
                  },
                  {
                      key: 'guia',
                      label: 'Guia',
                  },
                  {
                      key: 'actions',
                      label: 'Acciones',
                  },
              ],
              // selectedValue: [],
              category: {
                  id: '',
                  pedido: '',
                  fecha: '',
                  trasporte: '',
                  guia: '',
                  order: '',
              },
          }
        },
        components: {
            SelectLevel,
            Modal,
            AppLayout,
            'image-custom': ImageFile,
            'custom-table': Table,
        },
        mounted(){
            console.log(this.categorias)
        },
        computed:{
            familias_principales(){
                // let selects = this.selectedValue.push({
                //     value: this.selectedCat,
                //     id: this.selectedCat.id,
                // })

                let result = this.categorias.filter((item)=>{
                    if (item.padre_id == null){
                        return item
                    }
                })

                return result
            },
 
        },
        methods: {
  ...mapActions('admin', [
                'eliminar',
            ]),
            reset(){
                this.category = {
                    id: '',
                    pedido: '',
                    fecha: '',
                    transporte: '',
                    guia: '',
 
                };
            },
            add(){
                let data = new FormData()

                data.append('id', this.category.id)
                data.append('pedido', this.category.pedido)
                data.append('fecha', this.category.fecha || '')
                data.append('transporte', this.category.transporte || '')
                data.append('guia', this.category.guia || '')
                this.$inertia.post(route('adm.envios.store'), data).then(() => {

                });
            },

            edit(item){
                // console.log(item)
                this.category.id =   item.id
                this.category.pedido =   item.nro_pedido
                this.category.fecha =   item.fecha
                this.category.transporte =   item.transporte
                this.category.guia =   item.guia
                this.$root.$emit('bv::show::modal','modal-prevent-closing')
            },
            del(id){
                this.eliminar(route('adm.envios.destroy', {id: id} ))
            },
        },
    }
</script>