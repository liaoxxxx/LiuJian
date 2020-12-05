<template>
    <view id="content" class="content">

        <view v-if="loading" class="pt-4">
            <skeleton banner :row="2" animate :loading="loading" style="margin-top:24rpx;">
                <view>
                </view>
            </skeleton>
            <skeleton banner :row="0" animate :loading="loading" style="margin-top:24rpx;">
                <view>
                </view>
            </skeleton>
            <skeleton banner :row="10" animate :loading="loading" style="margin-top:24rpx;">
                <view>
                </view>
            </skeleton>
        </view>

        <view class="list" v-if="!loading">
            <view class="text_1" v-show="tabCurrentIndex===0">
				<view class="header-bg" style="height: 400rpx;background-color: #55ea55;overflow: hidden">
					<img src="../../static/img/header-bg.jpg" alt="">
				</view>

                <view class="stat-panel" >
                    <view class="stat-panel-item" v-for="item in statList">
                        <text class="stat-item-title">累积积分</text>
                        <view class="stat-item-content">
                            <text class="stat-item-counter">{{item.count}}</text>
                            <br>
                            <text class="stat-item-unit">{{item.unit}}</text>
                        </view>
                    </view>
                </view>

                <view class="current-city" >
                    <text class="left">当前城市</text>
                    <text class="right">{{currentCity}}<u-icon name="map" color="#2979ff" size="28"></u-icon></text>
                </view>
                <u-notice-bar mode="vertical" type="success" :is-circular="false" :list="noticeList"></u-notice-bar>

                <!-- banner轮播 -->
                <view class="scroll_top">
                    <swiper class="swiper" indicator-dots="true" autoplay="true" circular="true">
                        <swiper-item class="swiper_item" v-for="(item,index) in indexBanner" :key="index">
                            <image class="swip-img" mode="widthFix"
                                   :src="item.pic.indexOf('http') != -1 ? item.pic : 'http://111.229.128.239:1003' + item.pic"></image>
                        </swiper-item>
                    </swiper>
                </view>

                <view class="prefecture-block" >
                    <view class=" prefecture-item" v-for="(item,index) in prefectureList" :key="item.id"
                          @click="toPrefecture(item.url)"
                          v-bind:style="{  background: item.backgroundColor}">
                        <view class="left" >
                            <view class="grid-text">{{item.name}}</view>
                            <view class="grid-text">{{item.subtitle}}</view>
                        </view>
                        <view class="right">
                            <u-icon name="photo" :size="46"></u-icon>
                        </view>
                    </view>
                </view>


                <view class="contact-block" >
                    <view   class=" contact-item" v-for="(item,index) in prefectureList" >
                            <view class="left">{{item.title}}</view>
                            <view class="right">{{currentCity}}<u-icon name="map" color="#2979ff" size="28"></u-icon></view>
                        </view>
                </view>


                <view class="create-order-circle">
                    <view class="create-order-inner" @click="toAddOrder()" >
                        <text class="left">{{item.title}}</text>
                        <text class="right">{{currentCity}}<u-icon name="map" color="#2979ff" size="28"></u-icon></text>
                    </view>
                </view>
                <br>
            </view>

            <footer-tabbar></footer-tabbar>
        </view>
    </view>
</template>

<script>
    import {vuexData} from '@/common/commonMixin.js'
    import drawer from '@/components/drawer.vue'
    import tag from '@/components/tag.vue'
    import skeleton from '../../components/xinyi-skeleton/skeleton.vue'
    import {mapActions, mapGetters, mapMutations} from 'vuex'
    import goodsItem from '@/components/goods_item.vue'
    import broadcast from '@/components/broadcast.vue'
    import NavBar from '@/components/navBar.vue'
    import specialList from '@/components/special_card.vue'
    import navTab from '@/components/nav_tab.vue'

    export default {
        components: {
            NavBar,
            navTab,
            specialList,
            broadcast,
            goodsItem,
            drawer,
            tag,
            skeleton
        },
        mixins: [vuexData],
        data() {
            return {
                tabCurrentIndex: 0,
                loading: true,
                CurrentIndex: 0,
                isLoading: 1,
                scrollH: 0,
                page: 1,
                proId: '',
                tempDetail: {},
                windowHeight: 0,
                // 吸顶高度
                statusHeight: "0",
                showTab: true,

                defaultPrefecture: 0,

                currentCity:"南宁",

                prefectureList: [
                    {
                        id: 0,
                        src: '/static/index/menu/menu_4.png',
                        name: '废纸',
                        subtitle: '杂纸，纯黄纸',
                        url: '',
                        remainder:0,
                        backgroundColor:"#ffc728"
                    },
                    {
                        id: 1,
                        src: '/static/index/menu/menu_2.png',
                        name: '塑料',
                        subtitle: '塑料瓶，塑料杯',
                        url: '',
                        remainder:1,
                        backgroundColor:"#289bff"
                    },
                    {
                        id: 2,
                        src: '/static/index/menu/menu_1.png',
                        name: '金属',
                        subtitle: '废旧不锈钢',
                        url: '/pages/index/Special_Offer',
                        remainder:0,
                        backgroundColor:"#a9a9fa"
                    },
                    {
                        id: 3,
                        src: '/static/index/menu/menu_1.png',
                        name: '其他废品',
                        subtitle: '家电，家具，衣物，玻璃',
                        url: '/pages/index/Special_Offer',
                        remainder:1,
                        backgroundColor:"#84ff58"
                    }
                ],

                statList: [
                    {
                        id: 0,
                        name: '累计积分',
                        url: '',
                        count:10,
                        unit:'分'
                    },
                    {
                        id: 1,
                        name: '累计回收',
                        url: '',
                        count:15,
                        unit:'kg'

                    },
                    {
                        id: 2,
                        name: '累计收益',
                        url: '/pages/index/Special_Offer',
                        count:12,
                        unit:'元'
                    }
                ],


                contactList: [
                    {title: '全国服务热线', tel: '400-0055-888'},
                    {title: '区域服务热线', tel: '0775-0055-888'}
                ],

                noticeList:[
                    "上线运营啦",
                    "上线运营啦",
                    "上线运营啦",
                    "上线运营啦",
                ],

                requireList: [
                    {
                        id: 0,
                        src: '/static/index/menu/menu_4.png',
                        name: '拒绝掺水',
                        url: '',
                        backgroundColor:"#ffc728"
                    },
                    {
                        id: 1,
                        src: '/static/index/menu/menu_2.png',
                        name: '拒绝掺杂',
                        url: '',
                        backgroundColor:"#ffc728"
                    },
                    {
                        id: 2,
                        src: '/static/index/menu/menu_1.png',
                        name: '单次10KG以上',
                        url: '/pages/index/Special_Offer',
                        backgroundColor:"#ffc728"
                    }
                ],



            }
        },
        onLoad(options) {
            console.log("------------------------");
            console.log(this.$u.config.v);
            console.log("------------------------");
            // 页面显示是默认选中第一个
            this.tabCurrentIndex = 0;
            this.CurrentIndex = 0;
            // 选项卡吸顶获取窗口高度
            let res = uni.getSystemInfoSync();
            this.statusHeight = `${res.windowTop}rpx`;
        },
        methods: {
            ...mapActions(['getIndex']),
            ...mapMutations(['getFirstInfo']),
            //顶部tab点击
            tabClick(index) {
                this.tabCurrentIndex = index;
            },
            toCard() {
                uni.navigateTo({
                    url: '/pages/index/get_coupon'
                })
            },
            checkPro(id) {
                if (id == this.proId) {
                    this.proId = ''
                } else {
                    this.proId = id
                }
            },

            imgErr(e) {
                this.getFirstInfo({
                    msg: 'imgErr',
                    index: e
                })
            },
            goDetail(id) {
                uni.navigateTo({
                    url: `/pages/index/goods_info?id=${id}`
                })
            },

            toPrefecture(url) {
                if (url) {
                    uni.navigateTo({
                        url
                    })
                } else {
                    uni.showModal({
                        title: '提示',
                        content: '我还没想好这里干嘛。。。'
                    })
                }
            },

            // 有规格 加入购物车
            async proAddCart() {
                if (this.proId == '') {
                    uni.showToast({
                        icon: '',
                        title: '请选择商品规格～～'
                    })
                } else {
                    let result = await this.$api.addCart({
                        productId: this.selectedPro.product_id,
                        uniqueId: this.selectedPro.unique,
                        cartNum: 1,
                        new: 0
                    })
                    this.checkRes(result, '宝贝在购物车里等着您了～～')
                    this.$refs.drawer.hide()
                }
            },
            async addCart(id) {
                let goodsDetail = await this.$api.getProductDetail(id)
                if (goodsDetail.status != 200) {
                    uni.showModal({
                        showCancel: false,
                        title: '提示',
                        content: '数据错误请稍后重试。。。'
                    })
                    return
                }
                this.tempDetail = goodsDetail.data
                let result
                let {
                    productValue
                } = goodsDetail.data
                let productArr = Object.values(productValue)
                if (productArr.length > 0) {
                    this.$refs.drawer.show()
                    this.showTab = false
                } else {
                    result = await this.$api.addCart({
                        productId: id,
                        cartNum: 1,
                        new: 0
                    })
                    this.checkRes(result, '宝贝在购物车里等着您了～～')
                }
                // let result = await this.$api.addCart({productId: id, cartNum: 1, uniqueId, new: 0})


            },
            // 跳转到搜索页
            toSearch() {
                uni.navigateTo({
                    url: '/pages/index/search'
                })
            },
            getElSize(id) {
                return new Promise((res, rej) => {
                    uni.createSelectorQuery().in(this).select(id).boundingClientRect(data => {
                        res(data);
                    }).exec();
                })
            },
            toAddOrder() {
                uni.navigateTo({
                    url: '/pages/order/addOrder'
                })
            }

        },

        computed: {
            ...mapGetters(['indexBanner', 'indexTopMenus', 'indexRoll', 'indexBastInfo', 'indexFirstInfo']),
            firstList() {
                let arr
                if (this.indexFirstInfo.firstList) {
                    arr = this.indexFirstInfo.firstList.filter((item, index) => {
                        return index <= 2
                    })
                }
                return arr
            },
            // 后期可优化  规格
            tempPro() {
                if (this.tempDetail.productValue) {
                    console.log(Object.values(this.tempDetail.productValue))
                    return Object.values(this.tempDetail.productValue)
                }
                return []
            },
            selectedPro() {
                if (this.tempPro.length > 0 && this.proId != '') {
                    return this.tempPro.filter(item => item.unique == this.proId)[0]
                }
                return ''
            }
        },
        async onPageScroll(e) {
            if (this.isLoading == 2 || this.isLoading == 0) {
                return
            }
            if (this.scrollH == 0) {
                let data = await this.getElSize('#content')
                let {
                    height
                } = data
                this.scrollH = height
            }
            let {
                scrollTop
            } = e
            if (this.scrollH - (scrollTop + this.windowHeight) < 20) {
                this.page++
                this.scrollH = 0
                this.isLoading = 0
                let goods = await this.$api.getProductList(`?page=${this.page}&limit=${10}&type=0`)
                this.getGoodsList(goods)
                if (goods.data.length == 0) {
                    setTimeout(() => {
                        this.isLoading = 2
                    })
                } else {
                    setTimeout(() => {
                        this.isLoading = 1
                    })
                }

            }
        },
        async mounted() {

            let {
                windowHeight
            } = uni.getSystemInfoSync()
            this.windowHeight = windowHeight
            let data = await this.$api.getIndex()
            let goods = await this.$api.getProductList(`?page=${this.page}&limit=${10}&type=0`)

            this.getGoodsList(goods)
            this.getIndex(data)
            this.loading = false
            setTimeout(() => {
                console.log(this.activity)
            }, 2000)
            // let userCenter = await this.$api.getUserCenter()
            // let cart = await this.$api.getCartList()
            // this.getUserCenter(userCenter)
            // let hot = await this.$api.getHot(`?page=${this.page}&limit=${10}`)
        }
    }
</script>

<style lang="scss" scoped>
    page {
        background-color: $u-bg-color;
    }

    .content {
        /* #ifdef APP-PLUS */
        padding-bottom: 40 rpx;
        /* #endif */
        /* #ifdef MP-WEIXIN */
        padding-bottom: 140 rpx;
        /* #endif */
        padding-top: 20 rpx;
    }




    .stat-panel{
        background-color: #e7e5e5;
        height: 160rpx;
        overflow: hidden;
        font-size: 24rpx;
        margin: 0 20rpx;
        position: relative;
        top:-50rpx;
        border-radius: 10rpx;
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        padding: 20rpx;
    }
    .stat-panel-item{

    }

    .stat-item-content .stat-item-counter{
        color: #1aad19;
        font-size: 42rpx;
        text-align: center;
        position: relative;
        right: -30rpx;
    }
    .stat-item-content .stat-item-unit{
        color: #1aad19;
        position: relative;
        right: -50rpx;
    }

    .current-city{
        width: 90%;
        margin:0 auto;
        overflow: hidden;
    }
    .left{
        float: left;
    }
    .right{
        float: right;
    }






    .navbar .nav-item {
        display: flex;
        flex: 1;
        justify-content: center;
        padding-top: 30 upx;
        font-size: 28 upx;
        color: #fff;
    }


    .swiper_bg_2 {
        background: #fff;
        height: 140 upx;
    }

    .scroll_top {
        border-radius: 20 rpx;
        margin: 0 15 rpx;
        overflow: hidden;
        margin-top: -120 rpx;
    }

    .swiper {
        height: 240 rpx;
    }

    .swip-img {
        width: 100%;
        height: 240 rpx;
    }




    .prefecture-block{
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        flex-direction: row;
        align-items:center;

    }

    .prefecture-item{
        margin-top: 10rpx;
        height: 30vw;
        width: 48%  ;
        border-radius: 10rpx;
        background-color: darkgray;
    }



    /* menu宫格 */
    .index_menu {
        display: flex;
        flex-direction: row;
        padding: 40 rpx 15 rpx 20 rpx 15 rpx;
        background: #fff;
        /* background: #4CD964; */
    }


    .required-subtitle {
        color: #1D2124;
        font-size: 20 rpx;
    }


    .menu_box {
        flex: 1;
        text-align: center;
        font-size: 30 rpx;
        font-weight: bold;
    }

    .menu_img {
        width: 100 rpx;
        height: 100 rpx;
        padding-bottom: 18 rpx;
    }


    .center_banner {
        padding: 10 rpx 15 rpx;
        background: #fff;
    }

    .center_img {
        width: 100%;
        height: 99 rpx;
    }


    .yishi_img image {
        width: 200 rpx;
        height: 200 rpx;
    }


    .create-order-circle {
        position: relative;
        bottom: -200 rpx;

        width: 300 rpx;
        height: 300 rpx;
        border-radius: 150 rpx;
        margin: 0 auto;
        background-color: #7ef57e;
    }

    .create-order-inner {
        background-color: #09b609;
        width: 180 rpx;
        height: 180 rpx;
        border-radius: 90 rpx;
        text-align: center;
        line-height: 180 rpx;
        font-size: 50 rpx;
        color: white;

        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto auto;

    }


</style>
