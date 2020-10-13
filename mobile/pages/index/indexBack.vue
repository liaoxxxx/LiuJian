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
                <!--<view class="swiper_bg"></view>-->
                <view class="swiper_bg_2"></view>
                <!-- banner轮播 -->
                <view class="scroll_top">
                    <swiper class="swiper" indicator-dots="true" autoplay="true" circular="true">
                        <swiper-item class="swiper_item" v-for="(item,index) in indexBanner" :key="index">
                            <image class="swip-img" mode="widthFix"
                                   :src="item.pic.indexOf('http') != -1 ? item.pic : 'http://111.229.128.239:1003' + item.pic"></image>
                        </swiper-item>
                    </swiper>
                </view>

                <view class="index_menu">
                    <view v-for="item in prefectureList" class="menu_box" :key="item.id"
                          @click="toPrefecture(item.url)">
                        <image class="menu_img" :src="item.src" mode="widthFix"></image>
                        <view>{{item.name}}</view>
                    </view>
                </view>

                <view class="center_banner" @tap="toCard">
                    <image class="center_img" src="/static/index/to_card.png" mode="widthFix"></image>
                    <broadcast :list="indexRoll"></broadcast>
                </view>


                <view class="index_menu">
                    <view v-for="item in requireList" class="menu_box" :key="item.id"
                          @click="toPrefecture(item.url)">
                        <image class="menu_img" :src="item.src" mode="widthFix"></image>
                        <view>{{item.name}}</view>
                    </view>
                </view>
            </view>
        </view>
    </view>
</template>

<script>
    import {
        vuexData
    } from '@/common/commonMixin.js'
    import drawer from '@/components/drawer.vue'
    import tag from '@/components/tag.vue'
    import skeleton from '../../components/xinyi-skeleton/skeleton.vue'
    import {
        mapActions,
        mapGetters,
        mapMutations
    } from 'vuex'
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

                prefectureList: [
                	{
						id: 0,
						src: '/static/index/menu/menu_4.png',
						name: '品尝推荐',
						url: ''
					},
                    {
                        id: 1,
                        src: '/static/index/menu/menu_2.png',
                        name: '女士专区',
                        url: ''
                    },
                    {
                        id: 2,
                        src: '/static/index/menu/menu_1.png',
                        name: '新人专享',
                        url: '/pages/index/Special_Offer'
                    }
                ],



				requireList: [
					{
						id: 0,
						src: '/static/index/menu/menu_4.png',
						name: '拒绝掺水',
						url: ''
					},
					{
						id: 1,
						src: '/static/index/menu/menu_2.png',
						name: '拒绝掺杂',
						url: ''
					},
					{
						id: 2,
						src: '/static/index/menu/menu_1.png',
						name: '单次10KG以上',
						url: '/pages/index/Special_Offer'
					}
				],

                images: [{
                    img: '../../static/index/banner/banner.png'
                },
                    {
                        img: '../../static/index/banner/banner2.png'
                    }
                ]
            }
        },
        onLoad(options) {
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

        }
    }
</script>

<style>
    page {
        background: #f5f5f5;
    }

    .content {
        /* #ifdef APP-PLUS */
        padding-bottom: 40 rpx;
        /* #endif */
        /* #ifdef MP-WEIXIN */
        padding-bottom: 140 rpx;
        /* #endif */
        padding-top: 100 rpx;
    }

    .shop {
        width: 180 rpx;
        height: 180 rpx;
        position: fixed;
        bottom: 10%;
        right: 0;
        z-index: 99;
    }

    .nav-top {
        background: linear-gradient(to right, #ff4b2d, #fd4341);
        z-index: 99;
        height: 170 rpx;
        top: 0;
    }

    /* 顶部选项卡 */
    .navbar {
        position: fixed;
        display: flex;
        z-index: 99;
        flex-direction: row;
        width: 100%;
        /* #ifdef APP-PLUS */
        top: 128 rpx;
        /* #endif */
        /* #ifdef MP-WEIXIN */
        top: 76 rpx;
        /* #endif */
        background: linear-gradient(to right, #ff4b2d, #fd4341);
        height: 80 rpx;
        /* border-bottom: 1px solid #000000; */
        width: 100%;
        padding-bottom: 50 rpx;
    }

    .navbar .nav-item {
        display: flex;
        flex: 1;
        justify-content: center;
        padding-top: 30 upx;
        font-size: 28 upx;
        color: #fff;
    }

    .active {
        color: #fff;
        font-size: 30 upx;
        font-weight: bold;
        background-size: 90%;
        border-bottom: 3px solid #fff;
    }


    /* banner轮播 */
    .swiper_bg {
        background: linear-gradient(to right, #ff4b2d, #fd4341);
        height: 280 upx;
    }

    .swiper_bg_2 {
        background: #fff;
        height: 140 upx;
    }

    .scroll_top {
        border-radius: 20 rpx;
        margin: 0 15 rpx;
        overflow: hidden;
        margin-top: -240 rpx;
    }

    .swiper {
        height: 240 rpx;
    }

    .swip-img {
        width: 100%;
        height: 240 rpx;
    }


    /* menu宫格 */
    .index_menu {
        display: flex;
        flex-direction: row;
        padding: 40 rpx 15 rpx 20 rpx 15 rpx;
        background: #fff;
        /* background: #4CD964; */
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


    /* 模块推荐区 */
    .modular {
        display: flex;
        flex-direction: row;
        padding: 15 rpx;
        background: #fff;
    }

    .modular_left {
        flex: 1;
        color: #ff6f2b;
        font-size: 36 rpx;
        font-weight: bold;
        background: #ffe3d6;
        margin-right: 10 rpx;
        border-radius: 20 rpx;
        padding: 30 rpx 15 rpx 30 rpx 15 rpx;
    }

    .zhe_btn {
        color: #fff;
        font-size: 18 rpx;
        text-align: center;
        border-radius: 50 rpx;
        padding: 8 rpx 12 rpx;
        margin-top: 10 rpx;
    }

    .yishi_img {
        margin-left: 38%;
        margin-top: -50 rpx;
        text-align: center;
    }

    .yishi_img image {
        width: 200 rpx;
        height: 200 rpx;
    }

    .red_color {
        color: #860b01;
        font-size: 24 rpx;
    }


    .modular_right {
        flex: 1;
        margin-left: 10 rpx;
        display: flex;
        flex-direction: column;
    }

    .modular_right_1 {
        flex: 1;
        background: #ffe2f9;
        border-radius: 20 rpx;
        margin-bottom: 15 rpx;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        padding: 30 rpx 15 rpx 30 rpx 15 rpx;
    }

    .modular_right_2 {
        flex: 1;
        margin-top: 15 rpx;
        background: #ffe8d9;
        border-radius: 20 rpx;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        padding: 30 rpx 15 rpx 30 rpx 15 rpx;
    }

    .jiu_1 {
        width: 120 rpx;
        height: 120 rpx;
    }

    .jiu_2 {
        width: 90 rpx;
        height: 90 rpx;
    }


    /* 啤酒专场 */
    .beer {
        background: #fff;
    }

    .welfare {
        background: #fff;
    }

    .beer_top {
        display: flex;
        flex-direction: row;
        align-items: center;
        padding: 20 rpx;
    }

    .small {
        width: 50 rpx;
        height: 50 rpx;
        margin-right: 6 rpx;
    }

    .beer_title {
        font-weight: bold;
    }

    .beer_main {
        display: flex;
        /* flex-direction: row; */
        white-space: nowrap;
    }


    .u-flex {
        display: flex;
        flex-direction: row;
    }

    .u-flex-1 {
        flex: 1;
        text-align: center;
    }

    .red_drink {
        width: 200 rpx;
    }

    .red_price {
        color: #fff;
        background: #fe0101;
        font-size: 20 rpx;
        padding: 4 rpx 8 rpx;
        /* margin: 0 auto; */
        border-radius: 40 rpx;
        position: absolute;
        margin-top: -30 rpx;
        left: 50%;
        transform: translateX(-50%);
    }

    .red_price_num {
        text-decoration: line-through;
        color: #fe0101;
        font-size: 20 rpx;
        margin-top: 4 rpx;
        padding-bottom: 20 rpx;
    }


    .scroll_block {
        height: 100 upx;
        width: 100%;
        overflow: hidden;
        background: #f5f5f5;
        z-index: 99;
        /* 手机版吸顶高度距离顶部130rpx */
        position: sticky;
        /* #ifdef APP-PLUS */
        top: 240 rpx;
        /* #endif */

        /* #ifdef H5 */
        top: 220 rpx;
        /* #endif */

        /* #ifdef MP-WEIXIN */
        top: 170 rpx;
        /* #endif */
    }

    /* white-space: nowrap;文本不会换行，文本会在在同一行上继续，直到遇到 <br> 标签为止。 */
    .scroll-view_H {
        white-space: nowrap;
        width: 100%;
        height: 115 upx;
        margin-left: 15 rpx;
    }

    /* 顶部切换栏 */
    .navbar_3 {
        display: flex;
        flex-direction: row;
        align-items: center;
        height: 100 rpx;
    }

    .nav-item_3 {
        height: 100 upx;
        line-height: 100 rpx;
        margin-right: 15 rpx;
        display: flex;
        align-items: center;
        font-size: 26 upx;
    }

    .first_tabar_3 {
        padding: 0 rpx 30 rpx;
        border-radius: 30 rpx;
        height: 52 rpx;
        line-height: 52 rpx;
        background: #eaeaea;
    }

    .active_3 {
        color: #fff;
        padding: 0 rpx 30 rpx;
        height: 52 rpx;
        line-height: 52 rpx;
        border-radius: 30 rpx;
        background: #fe0101;
    }


</style>
