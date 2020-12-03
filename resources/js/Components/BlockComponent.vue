<template>
    <div>
        <b-tabs card>
            <b-tab v-for="(lang,key) in Object.keys($page.idioma)" :key="'dyn-tab-' + key" :title="$page.idioma[lang].name ">
                <div class="text-center">
                    <button type="button" @click="addBlock(lang)" class="btn btn-info mb-4">{{ btnTitle || 'Agregar Bloque' }}</button>
                </div>
                <template v-if="only == undefined">
                    <div class="row border p-3 mb-4" v-for="(item,key) in bloques">
                        <div class="col-md-6">
                            <div class="col-md-12 form-group">
                                <label for="">Titulo</label>
                                <input type="text" v-model="item.title[lang]" class="form-control">
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">Texto</label>
                                <jodit-vue v-model="item.text[lang]" :id="'Texto-'+lang"></jodit-vue>
                                <!--                            <textarea v-model="item.text[lang]" class="form-control"  cols="30" rows="3"></textarea>-->
                            </div>
                            <slot name="idioma" :item="item" :lang="lang"></slot>
                        </div>
                        <div class="col-md-6">
                            <image-custom :model.sync="item.image"></image-custom>
                        </div>
                    </div>
                </template>
                <template v-if="only == 'text'">
                    <div class="row border p-3 mb-4">
                        <div  v-for="(item,key) in bloques" class="col-md-4 mb-4">
                            <div class="col-md-12 form-group">
                                <label for="">Titulo</label>
                                <input type="text" v-model="item.title[lang]" class="form-control">
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">Texto</label>
                                <jodit-vue v-model="item.text[lang]" :id="'Texto-'+lang"></jodit-vue>
                                <!-- <textarea v-model="item.text[lang]" class="form-control"  cols="30" rows="3"></textarea>-->
                            </div>
                        </div>
                    </div>
                </template>
                <template v-if="only == 'image'">
                    <div class="row border p-3 mb-4">
                        <div  v-for="(item,key) in bloques" class="col-md-3 mb-4">
                            <div class="form-group">
                                <label for="">Titulo</label>
                                <input type="text" v-model="item.title[lang]" class="form-control">
                            </div>
<!--                            <div class="form-group" >-->
<!--                                <label for="">Texto</label>-->
<!--                                <textarea v-model="item.text[lang]" class="form-control"  cols="30" rows="2"></textarea>-->
<!--&lt;!&ndash;                                <input type="text" v-model="item.text[lang]" class="form-control">&ndash;&gt;-->
<!--                            </div>-->
                            <div class="">
                                <image-custom :model.sync="item.image"></image-custom>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-if="only == 'video'">
                    <div class="row border p-3 mb-4">
                        <div  v-for="(item,key) in bloques" class="col-md-6 mb-4">
                            <div class="col-md-12 form-group">
                                <label for="">Codigo de youtube - <small>https://www.youtube.com/watch?v=<b>HFOslNedk38</b></small></label>
                                <input type="text" v-model="item.title[lang]" class="form-control mb-3">
                                <iframe width="100%" height="100%" :src="'https://www.youtube.com/embed/'+item.title[lang]" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">Texto</label>
                                <jodit-vue v-model="item.text[lang]" :id="'Texto-'+lang"></jodit-vue>
                                <!-- <textarea v-model="item.text[lang]" class="form-control"  cols="30" rows="3"></textarea>-->
                            </div>
                        </div>
                    </div>
                </template>
            </b-tab>
            <slot></slot>
        </b-tabs>
    </div>
</template>

<script>
    import ImageFile from './ImageComponent'

    export default {
        components: {
            'image-custom': ImageFile,
        },
        props:['model','only','btnTitle','type'],
        data() {
            return {
                show: false,
                name: '',
                nameState: null,
                submittedNames: [],
                bloques: [],
            }
        },
        created() {
            this.bloques = this.model || []
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

            addBlock() {
                let objeto = new Object();
                console.log(Object.keys(this.$page.idioma))
                Object.keys(this.$page.idioma).map((item)=>{
                    objeto[item] = ''
                    return objeto
                })
                console.log(objeto)
                this.bloques.push({
                    title: JSON.parse(JSON.stringify(objeto)),
                    text: JSON.parse(JSON.stringify(objeto)),
                    type: this.type || '',
                    image:'',
                })
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