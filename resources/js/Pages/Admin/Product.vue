<template>
    <app-layout>
        <template #header>
            Productos
        </template>
        <div class="card">
            <div class="card-header">
                <modal
                        title="Producto"

                        @ok="add()"
                        @hidden="reset()"
                >
                    <template #idioma="{ lang }">
                        <div class="row">
                            <div class="col-md-8 form-group">
                                <label for="">Titulo</label>
                                <input type="text" v-model="product.title" class="form-control">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="">Codigo</label>
                                <input type="text" v-model="product.cod" class="form-control">
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="">Marca</label>
                                <input type="text" v-model="product.marca" class="form-control">
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="">Stock</label>
                                <input type="number" min="0" v-model="product.stock" class="form-control">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="">Orden</label>
                                <input type="text" v-model="product.order" class="form-control">
                            </div>
<!--                            <div class="col-md-12 form-group">-->
<!--                                <label for="">Descripción</label>-->
<!--                                <jodit-vue v-model="product.text[lang]" :id="'Texto-'+lang"></jodit-vue>-->
<!--                            </div>   -->
                            <div class="col-md-12 form-group">
                                <label for="">Descripción</label>
                                <jodit-vue v-model="product.descripcion" :id="'Descripción-'+lang"></jodit-vue>
                            </div>

                        </div>
                    </template>
                    <template #default>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="">Precio</label>
                                <money v-model="product.price" class="form-control" v-bind="money"></money>

                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Familias</label>
<!--                                <multiselect v-model="familia_selected" :options="familias" placeholder="Familia" label="title" track-by="id"></multiselect>-->

                                <select v-model="product.family_id" id="" class="form-control">
                                    <option value="" disabled selected>Familia</option>
                                    <option :value="item.id" v-for="item in familias">
                                        {{ item.title }}
                                    </option>
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 d-flex align-items-end">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" v-model="product.featured" :true-value="1" :false-value="0" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1">Mostrar en la Sección Principal?</label>
                                </div>
                            </div>
<!--                            <div class="col-md-10 form-group">-->
<!--                                <label for="">Productos Relacionados</label>-->
<!--                                <select-multiple-->
<!--                                        :data="productos_detalle"-->
<!--                                        :model.sync="product.productos"-->
<!--                                ></select-multiple>-->
<!--                            </div>-->
                            <div class="col-md-2 form-group">
                                <label for="">Unidad</label>
                                <input type="number" min="0" v-model="product.unidad" class="form-control">
                            </div>
<!--                            <div class="form-group col-md-6 d-flex align-items-end">-->
<!--                                <div class="custom-control custom-switch">-->
<!--                                    <input type="checkbox" class="custom-control-input" v-model="product.stock" :true-value="1" :false-value="0" id="customSwitch1">-->
<!--                                    <label class="custom-control-label" for="customSwitch1">Tiene Stock?</label>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="form-group col-md-6">
                                <label>Archivo</label>
                                <image-custom :model.sync="product.file"></image-custom>
                            </div>
<!--                            <div class="col-md-12" >-->
<!--                                <label>Agregar fotos</label>-->
<!--                                <custom-gallery   label="" :model.sync="product.gallery" :link="0" class=""></custom-gallery>-->
<!--                            </div>-->
                        </div>
                    </template>
                </modal>
            </div>
            <div class="card-body">
<!--                <b-row>-->



<!--                    <b-col lg="6" class="my-1">-->
<!--                        <b-form-group-->
<!--                                label="Buscador"-->
<!--                                label-for="filter-input"-->
<!--                                label-cols-sm="3"-->
<!--                                label-align-sm="right"-->
<!--                                label-size="sm"-->
<!--                                class="mb-0"-->
<!--                        >-->
<!--                            <b-input-group size="sm">-->
<!--                                <b-form-input-->
<!--                                        id="filter-input"-->
<!--                                        v-model="filter"-->
<!--                                        type="search"-->
<!--                                        placeholder="Buscar..."-->
<!--                                ></b-form-input>-->

<!--                                <b-input-group-append>-->
<!--                                    <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>-->
<!--                                </b-input-group-append>-->
<!--                            </b-input-group>-->
<!--                        </b-form-group>-->
<!--                    </b-col>-->



<!--                    <b-col sm="5" md="6" class="my-1">-->
<!--                        <b-form-group-->
<!--                                label="Paginar"-->
<!--                                label-for="per-page-select"-->
<!--                                label-cols-sm="6"-->
<!--                                label-cols-md="4"-->
<!--                                label-cols-lg="3"-->
<!--                                label-align-sm="right"-->
<!--                                label-size="sm"-->
<!--                                class="mb-0"-->
<!--                        >-->
<!--                            <b-form-select-->
<!--                                    id="per-page-select"-->
<!--                                    v-model="perPage"-->
<!--                                    :options="pageOptions"-->
<!--                                    size="sm"-->
<!--                            ></b-form-select>-->
<!--                        </b-form-group>-->
<!--                    </b-col>-->

<!--                </b-row>-->
<!--                &lt;!&ndash; Main table element &ndash;&gt;-->
<!--                <b-table-->
<!--                        :items="productos"-->
<!--                        :fields="fields"-->
<!--                        :current-page="currentPage"-->
<!--                        :per-page="perPage"-->
<!--                        :filter="filter"-->
<!--                        :filter-included-fields="filterOn"-->
<!--                        :sort-by.sync="sortBy"-->
<!--                        :sort-desc.sync="sortDesc"-->
<!--                        :sort-direction="sortDirection"-->
<!--                        stacked="md"-->
<!--                        show-empty-->
<!--                        small-->
<!--                        @filtered="onFiltered"-->
<!--                >-->


<!--                    <template #cell(actions)="row">-->
<!--                        <button @click="edit(item)" data-target="#category" class="btn btn-warning btn-circle" data-toggle="modal">-->
<!--                            <i class="far fa-edit"></i>-->
<!--                        </button>-->
<!--                    </template>-->

<!--                </b-table>-->
<!--                <b-row>-->
<!--                    <b-col   md="12" class="my-1">-->
<!--                        <b-pagination-->
<!--                                v-model="currentPage"-->
<!--                                :total-rows="totalRows"-->
<!--                                :per-page="perPage"-->
<!--                                align="fill"-->
<!--                                size="sm"-->
<!--                                class="my-0"-->
<!--                        ></b-pagination>-->
<!--                    </b-col>-->
<!--                </b-row>-->
                <table class="table" v-if="0">
                    <thead>
                    <tr>
                        <th scope="col">Codigo</th>
                        <th scope="col">Titulo</th>
                        <th scope="col">Rubro</th>
                        <th scope="col">Marca</th>
                        <th scope="col">Orden</th>
                        <th scope="col">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item,index) in productos">
                        <td>{{ item.cod || ''}}</td>
                        <td>{{ item.title || '' }}</td>
                        <td>{{ item.name_family || ''}}</td>
                        <td>{{ item.marca || ''}}</td>
                        <td>{{ item.order || ''}}</td>
                        <td>
                            <button @click="edit(item)" data-target="#category" class="btn btn-warning btn-circle" data-toggle="modal">
                                <i class="far fa-edit"></i>
                            </button>
<!--                            <button @click="duplicate(item)" class="btn btn-info btn-circle">-->
<!--                                <i class="far fa-clone"></i>-->
<!--                            </button>-->
<!--                            <button @click="del(item)" class="btn btn-danger btn-circle">-->
<!--                                <i class="fas fa-trash"></i>-->
<!--                            </button>-->
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="overflow-auto">


                    <b-table
                            id="my-table"
                            :items="productos"
                            :fields="fields"
                            :per-page="perPage"
                            :current-page="currentPage"
                            small
                    >
                        <template #cell(actions)="row">

                            <button @click="edit(row.item)" data-target="#category" class="btn btn-warning btn-circle" data-toggle="modal">
                                <i class="far fa-edit"></i>
                            </button>
                        </template>
                    </b-table>
                    <b-pagination
                            v-model="currentPage"
                            :total-rows="rows"
                            :per-page="perPage"
                            aria-controls="my-table"
                    ></b-pagination>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import SelectMultiple from '@/Components/SelectMultipleComponent'
    import AppLayout from '@/Layouts/AppLayout'
    import ImageFile from '@/Components/ImageComponent'
    import Modal from '@/Components/ModalComponent'
    import CustomGallery from '../../components/CustomGalleryComponent';

    export default {
        props: {
            familias: Array,
            subfamilias: Array,
            productos: Array,
            productos_detalle: Array,
            contenido: Object,
            section: '',
        },
        data(){
          return {
              fields: [
                  { key: 'title', label: 'Titulo', sortable: false, sortDirection: 'desc' },
                  { key: 'cod', label: 'Codigo', sortable: false, class: 'text-center' },
                  { key: 'name_family', label: 'Rubro', sortable: false, class: 'text-center' },
                  { key: 'marca', label: 'Marca', sortable: false, class: 'text-center' },

                  { key: 'actions', label: 'Acciones' }
              ],
              perPage: 500,
              currentPage: 1,
              totalRows: 1,

              pageOptions: [10, 50, 100, { value: 1000000, text: "Mostrar todo" }],
              sortBy: '',
              sortDesc: false,
              sortDirection: 'asc',
              filter: null,
              filterOn: [],
              familia_selected_id: '',
              product: {
                  id: '',
                  cod: '',
                  title: '',
                  descripcion: '',
                  price: '',
                  stock: 1,
                  unidad: 1,
                  featured: 0,
                  text: {},
                  family_id: '',
                  productos: [],
                  gallery: [],
                  file: '',
                  order: '',
              },
          }
        },
        components: {
            SelectMultiple,
            Modal,
            AppLayout,
            'custom-gallery': CustomGallery,
            'image-custom': ImageFile,
        },
        // watch:{
        //     familia_selected(){
        //         this.product.family_id = ''
        //     }
        // },
        computed:{
            subfamilias_filter(){
                let result = this.subfamilias.filter((item) => {
                    return item.padre_id ==  this.familia_selected.id
                })
                return result;
            },
            rows() {
                return this.productos.length
            }
        },
        methods: {

            reset(){
                this.product = {
                    id: '',
                    title: {},
                    description: {},
                    text: { es: ''},
                    text_video: {},
                    family_id: '',
                    video: '',
                    productos: [],
                    banner: '',
                    gallery: [],
                    file: '',
                    order: '',
                    featured: 0,
                };
            },
            add(){
                let data = new FormData()

                if (this.product.gallery) {
                    let self = this
                    Object.keys(this.product.gallery).forEach(function(key){

                        let file = self.product.gallery[key]
                        if (file && file instanceof File) {
                            data.append('gallery['+key+']', file);
                        }
                        if (typeof file === 'string' || file instanceof String) {
                            data.append('gallery['+key+']', file);
                        }
                        if (typeof file === 'object' || file instanceof Object) {
                            // console.log( file.image)
                            data.append('gallery['+key+']', file);

                            // self.formData.append('images['+key+'][title]', file.title);
                        }
                    })
                }


                data.append('id', this.product.id)
                data.append('id_inter', this.product.id_inter)
                data.append('cod', this.product.cod || '')
                data.append('title', this.product.title || '')
                data.append('descripcion', this.product.descripcion || '')
                data.append('text', JSON.stringify(this.product.text) || '')
                data.append('productos', JSON.stringify(this.product.productos || []))
                data.append('family_id', this.product.family_id || '')
                data.append('archivo', this.product.file || '')
                data.append('order', this.product.order || '')
                data.append('price', this.product.price || '')
                data.append('marca', this.product.marca || '')
                data.append('unidad', this.product.unidad || '')
                data.append('clasificacion', this.product.clasificacion || '')
                data.append('featured', this.product.featured || '')
                data.append('stock', this.product.stock || '')

                this.$inertia.post(route('adm.productos.store'), data).then(() => {

                });
            },



            duplicate(id){
                Swal.fire({
                    title: '¿Estas seguro de duplicar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        this.$inertia.get(route('adm.productos.show',{id: id})).then(() => {
                            // Swal.fire({
                            //     icon: 'success',
                            //     title: 'Se elimino correctamente',
                            //     showConfirmButton: false,
                            //     timer: 2000
                            // })

                        })
                    }
                })

            },
            edit(item){
                console.log(item)
                // this.familia_selected = item.family || {}
                this.product = JSON.parse(JSON.stringify(item))
                this.$root.$emit('bv::show::modal','modal-prevent-closing')
            },
            del(id){
                Swal.fire({
                    title: '¿Estas seguro de eliminar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        this.$inertia.delete(route('adm.productos.destroy',{id: id})).then(() => {
                            // Swal.fire({
                            //     icon: 'success',
                            //     title: 'Se elimino correctamente',
                            //     showConfirmButton: false,
                            //     timer: 2000
                            // })
                            $('.modal').modal('hide');
                        })
                    }
                })

            },
        },
    }
</script>