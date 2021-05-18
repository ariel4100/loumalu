 <template>
    <web-layout class="">
        <carousel :images="sliders" ></carousel>
        <section class="bg-dark">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 text-center py-5" v-for="(item,index) in imagenes" :key="index">
                        <img :src="item.image" :alt="item.title" class="img-fluid m-auto" style="min-height: 45px;">
                        <h5 class="text-white mt-3">{{ item.title }}</h5>
                    </div>
                </div>
            </div>
        </section>
        <template v-for="(item,index) in bloques.slice(0,2)">
            <template >
                <div class="  wow fadeIn " :data-wow-delay="'0.'+index+'s'">
                    <div class="row" v-if="index % 2 == 0">
                        <div class="col-md-6 p-0">
                            <img :src="item.image" alt="" class="img-fluid mx-auto h-100 w-100">  
                        </div>
                        <div class="col-md-6 p-0 p-4">
                            <h3 class="mb-3 text-center  ">{{ item.title }}</h3>
                            <p class="" v-html="item.text"></p>
                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="col-md-6 p-0 p-4">
                            <h3 class="mb-3 text-center  ">{{ item.title }}</h3>
                            <p class="" v-html="item.text"></p>
                        </div>
                        <div class="col-md-6 p-0">
                            <img :src="item.image" alt="" class="img-fluid mx-auto h-100 w-100">  
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