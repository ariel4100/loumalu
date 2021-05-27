<template>
    <div>
        <a v-if="type == 1" :href="item.ruta" class="p-0  nav-link">
            <div class="product-card border position-relative ">
                <div class="product-card__mask">
                    <div class="">
                        <!--                        <h5 class="my-2 text-left  text-white">{{ item.title }}</h5>-->
                        <i class="fas fa-search fa-2x"></i>
                        <!--                        <a :href="item.ruta" class="btn btn-white  btn-rounded text-secundario">VER MÁS</a>-->
                    </div>
                </div>
                <!-- <template v-if="item.imagef">
                    <img @error="replaceByDefault($event,null)" :src="item.imagef" :alt="item.title" class="d-block w-100 dsadadas">

                </template>
                 <template v-else>
                    <template v-if="item.image">
                        <img @error="replaceByDefault($event,null)" :src="item.image" :alt="item.title" class="d-block w-100 dsadadas">

                    </template>
                     
                </template> -->
                                    <img :src="item.image" :alt="item.title" class="img-fluid">

            </div>
            <div class="">
                <h5 class="mt-2 font-weight-bold text-dark">{{ item.title  }}</h5>
            </div>
        </a>
        <a v-if="type == 'blog'" :href="item.ruta" class="p-0  nav-link">
            <div class="product-card border position-relative">
                <div class="product-card__mask">
                    <div class="">
                        <!--                        <h5 class="my-2 text-left  text-white">{{ item.title }}</h5>-->
                        <i class="fas fa-search fa-2x"></i>
                        <!--                        <a :href="item.ruta" class="btn btn-white  btn-rounded text-secundario">VER MÁS</a>-->
                    </div>
                </div>
               
                    <img @error="replaceByDefault($event,null)" :src="item.image" :alt="item.title" class="img-fluid">
  
                </div>
            <div class="py-3">
                <h5 class="mt-1  font-weight-bold">{{ item.title   }}</h5>
            </div>
        </a>

        <a v-if="type == undefined" :href="item.ruta" class="p-0  nav-link">
            <div class="product-card border position-relative bg-white">
                <div class="product-card__mask">
                    <div class="">
<!--                        <h5 class="my-2 text-left  text-white">{{ item.title }}</h5>-->
                        <i class="fas fa-search fa-2x"></i>
<!--                        <a :href="item.ruta" class="btn btn-white  btn-rounded text-secundario">VER MÁS</a>-->
                    </div>
                </div>
                <template v-if="item.image">
                    <img @error="replaceByDefault($event,null)" :src="item.image" :alt="item.title" class="img-fluid">

                </template>
                  
            </div>
            <div class=" py-3">
                <h5 class="mt-2 font-weight-bold text-dark ">{{ item.title   }}</h5>

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
              replaceByDefault(e,item) {
                   if(item == null){
                    e.target.src = 'http://loumalu.osolelaravel.com/storage/uploads/logo/loumalu_140.png'
                  } 
            },
            imageExists(url, callback) {
                var img = new Image();
                img.onload = function() { callback(true); };
                img.onerror = function() { callback(false); };
                img.src = url;
            },
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