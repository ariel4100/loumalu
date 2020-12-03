 <template>
    <web-layout class="">
        <carousel :images="sliders" height="400px"></carousel>
        <template v-for="(item,index) in bloques">
            <template v-if="index == 0">
                <div class="container wow fadeIn py-4" :data-wow-delay="'0.'+index+'s'">
                    <div class="row">
                        <div class="col-md-7">
                            <h3 class="mb-3 text-secundario">{{ item.title }}</h3>
                            <p class="" v-html="item.text"></p>
                        </div>
                        <div class="col-md-5">
                            <div class="row mb-4" v-for="(item,index) in bloques.slice(1)">
                                <div class="col-md-2">
                                    <img :src="item.image" alt="" class="img-fluid mx-auto">
                                </div>
                                <div class="col-md-9 pl-md-0 mt-4 mt-md-0 text-center text-md-left">
                                    <h6 class="mb-2 font-weight-bold text-primario">{{ item.title }}</h6>
                                    <p class="" v-html="item.text"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </template>

    </web-layout>
</template>

<script>
    import Carousel from '@/Components/CarouselComponent'
    import TimeLine from '@/Components/TimeLineCarouselComponent'
    import WebLayout from '@/Layouts/WebLayout'
    import ImageFile from '../../Components/ImageComponent'
    import Modal from '../../Components/ModalComponent'
    import Slick from 'vue-slick';
    export default {
        props: {
            sliders: Array,
            bloques: Array,
            timelines: Array,
            imagenes: Array,
            contenido: Object,
        },
        data(){
          return {
              text:'',
              slider: {
                  title: '',
                  text: '',
                  order: '',
                  image: '',
              },
              slickOptions: {
                  infinite: true,
                  slidesToShow: 4,
                  slidesToScroll: 1,
                  arrows: false,
                  draggable: false,
                  // Any other options that can be got from plugin documentation
              },
          }
        },
        components: {
            TimeLine,
            Slick,
            Modal,
            WebLayout,
            Carousel,
            'image-custom': ImageFile,
        },
        methods: {

            next() {
                this.$refs.slick.next();
            },

            prev() {
                this.$refs.slick.prev();
            },
        },
    }
</script>