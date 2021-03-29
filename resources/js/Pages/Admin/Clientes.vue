<template>
    <app-layout>

        <template #header>
            Clientes
        </template>
        <div class="card">
            <div class="card-header">
                <modal
                        :lang="false"
                        title="Cliente"
                        title-button="Agregar Cliente"
                        @ok="add()"
                        @hidden="reset()"
                >
                    <template #default>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="">Nombre y Apellido</label>
                                <input type="text" v-model="user.name" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Apellido</label>
                                <input type="text" v-model="user.apellido" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Usuario</label>
                                <input type="text" v-model="user.username" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Email</label>
                                <input type="email" v-model="user.email" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <password-input
                                        placeholder=""
                                        label="Contraseña"
                                        v-model="user.password"
                                >
                                </password-input>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Teléfono</label>
                                <input type="text" class="form-control" v-model="user.telefono">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" v-model="user.fecha_nac">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">DNI</label>
                                <input type="text" class="form-control" v-model="user.dni">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Ciudad</label>
                                <input type="text" class="form-control" v-model="user.ciudad">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Domicilio</label>
                                <input type="text" class="form-control" v-model="user.domicilio">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Código Postal</label>
                                <input type="text" class="form-control" v-model="user.cp">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Descuento Cliente</label>
                                <div class="input-group mb-3">
                                    <input type="number" v-model="user.descuento" class="form-control" aria-describedby="basic-addon2">
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>
<!--                                <label for="">Descuento Cliente</label>-->
<!--                                <input type="number" class="form-control" v-model="user.descuento">-->
                            </div>
                        </div>
                    </template>
                </modal>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item,index) in items">
                        <td>{{ item.name || '' }}</td>
                        <td>{{ item.email }}</td>
                        <td>
                            <button @click="edit(item)" data-target="#user" class="btn btn-warning btn-circle" data-toggle="modal">
                                <i class="fas fa-exclamation-triangle"></i>
                            </button>
                            <button @click="del(item)" class="btn btn-danger btn-circle">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import PasswordInput from '@/Components/InputPassword'
    import AppLayout from '@/Layouts/AppLayout'
    import ImageFile from '@/Components/ImageComponent'
    import Modal from '@/Components/ModalComponent'

    export default {
        props: {
            items: Array,
            contenido: Object,
            section: '',
        },
        data(){
          return {

              user: {
                  id: '',
                  name:'',
                  apellido:'',
                  username:'',
                  email: '',
                  password: '',
                  dni: '',
                  teelfono: '',
                  ciudad: '',
                  domicilio: '',
                  cp: '',
                  fecha_nac: '',
                  descuento: '',
              },
          }
        },
        components: {
            PasswordInput,
            Modal,
            AppLayout,
            'image-custom': ImageFile,
        },
        methods: {

            reset(){
                this.user = {
                    id: '',
                    name:'',
                    apellido:'',
                    username:'',
                    email: '',
                    password: '',
                    dni: '',
                    teelfono: '',
                    ciudad: '',
                    domicilio: '',
                    cp: '',
                    fecha_nac: '',
                    descuento: '',
                };
            },
            add(){
                this.$inertia.post(route('adm.clientes.store'), this.user).then(() => {

                });
            },

            edit(item){
                console.log(item)
                this.user = JSON.parse(JSON.stringify(item))
                this.$root.$emit('bv::show::modal','modal-prevent-closing')
            },
            del(id){
                console.log(id)
                Swal.fire({
                    title: '¿Estas seguro de eliminar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        this.$inertia.delete(route('adm.clientes.destroy',id)) 
                    }
                })

            },
        },
    }
</script>