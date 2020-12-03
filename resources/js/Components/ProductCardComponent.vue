<template>
    <div>
        <a v-if="type == 1" :href="item.ruta" class="p-0  nav-link">
            <div class="product-card position-relative border">
                <div class="product-card__mask">
                    <div class="">
                        <!--                        <h5 class="my-2 text-left  text-white">{{ item.title }}</h5>-->
                        <i class="fas fa-search fa-2x"></i>
                        <!--                        <a :href="item.ruta" class="btn btn-white  btn-rounded text-secundario">VER MÁS</a>-->
                    </div>
                </div>
                <img :src="item.image" alt="" class="img-fluid">
            </div>
            <div class="">
                <h6 class="mt-2 mb-0 text-secundario">{{ item.code || 'MI00517280C/1C' }}</h6>
                <h6 class="mt-1 text-secundario">{{ item.marca || 'FORD' }}</h6>
                <h5 class="mt-2 font-weight-bold text-primario">{{ item.title || 'PISTONES CON PERNO TIK Y NOZUMI' }}</h5>
            </div>
        </a>
        <a v-if="type == 'blog'" :href="item.ruta" class="p-0  nav-link">
            <div class="product-card position-relative">
                <div class="product-card__mask">
                    <div class="">
                        <!--                        <h5 class="my-2 text-left  text-white">{{ item.title }}</h5>-->
                        <i class="fas fa-search fa-2x"></i>
                        <!--                        <a :href="item.ruta" class="btn btn-white  btn-rounded text-secundario">VER MÁS</a>-->
                    </div>
                </div>
                <img :src="item.image" :alt="item.title" class="img-fluid">
            </div>
            <div class="p-3">
                <h6 class="mt-2 mb-0 text-uppercase text-secundario">{{ item.categoria  || 'OTROS' }}</h6>
                <h5 class="mt-1 text-primario font-weight-bold">{{ item.title|| 'Titulo' }}</h5>
                <p class="text-color" v-html="item.text.substr(0,100)"></p>
            </div>
        </a>

        <a v-if="type == undefined" :href="item.ruta" class="p-0 border nav-link">
            <div class="product-card position-relative">
                <div class="product-card__mask">
                    <div class="">
<!--                        <h5 class="my-2 text-left  text-white">{{ item.title }}</h5>-->
                        <i class="fas fa-search fa-2x"></i>
<!--                        <a :href="item.ruta" class="btn btn-white  btn-rounded text-secundario">VER MÁS</a>-->
                    </div>
                </div>
                <img :src="item.image" alt="" class="img-fluid">
            </div>
            <div class="border p-3">
                <h5 class="my-2 text-primario text-center">{{ item.title }}</h5>
                <template v-if="subfamilia">
                    <div v-html="item.description" class="text-color"></div>
                </template>
                <template v-else>
                    <div v-html="item.text" class="text-color"></div>
                </template>
            </div>
        </a>
    </div>
</template>

<script>
    export default {
        props:['item','type','data'],
        data() {
            return {
                show: false,
                name: '',
                nameState: null,
                submittedNames: [],
                items: [],
            }
        },
        created() {
            this.items = this.data || []
        },
        watch: {
            bloques: function () {
                this.$emit('update:model', this.bloques || [])
            },
        },
        methods: {
            checkFormValidity() {
                const valid = this.$refs.form.checkValidity()
                this.nameState = valid
                return valid
            },
            resetModal() {
                this.$emit('show')
            },
            hiddenModal() {
                this.$emit('hidden')
            },

            handleOk(bvModalEvt) {
                this.$emit('ok')
                // Prevent modal from closing
                // bvModalEvt.preventDefault()
                // Trigger submit handler
                // this.handleSubmit()
            },
            handleSubmit() {
                // Exit when the form isn't valid
                if (!this.checkFormValidity()) {
                    return
                }
                // Push the name to submitted names
                this.submittedNames.push(this.name)
                // Hide the modal manually
                this.$nextTick(() => {
                    this.$bvModal.hide('modal-prevent-closing')
                })
            }
        }
    }
</script>
<style lang="scss" scoped>
</style>