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
							<image class="swip-img" mode="widthFix" :src="item.pic.indexOf('http') != -1 ? item.pic : 'http://111.229.128.239:1003' + item.pic"></image>
						</swiper-item>
					</swiper>
				</view>

				<view class="index_menu">
					<view v-for="item in prefectureList" class="menu_box" :key="item.id" @click="toPrefecture(item.url)">
						<image class="menu_img" :src="item.src" mode="widthFix"></image>
						<view class="required-title">{{item.name}}</view>
						<view class="required-subtitle">{{item.subtitle}}</view>
					</view>
				</view>

				<!-- <view class="center_banner" @tap="toCard">
					<image class="center_img" src="/static/index/to_card.png" mode="widthFix"></image>
					<broadcast :list="indexRoll"></broadcast>
				</view> -->
				<view style="width: 80%; margin: auto; margin-bottom: 20px;">
					<xfl-select
							:list="prefectureList"
							:clearable="false"
							:showItemNum="5"
							:listShow="true"
							:isCanInput="true"
							:style_Container="'height: 50px; font-size: 16px;'"
							:placeholder = "'placeholder'"
							:initValue="'苹果'"
							:selectHideType="'hideAll'"
					>
					</xfl-select>
				</view>

				<view class="index_menu">
					<view v-for="item in requireList" class="menu_box" :key="item.id" @click="toPrefecture(item.url)">
						<image class="menu_img" :src="item.src" mode="widthFix"></image>
						<view>{{item.name}}</view>
					</view>
				</view>


				<view class="create-order-circle">
					<view class="create-order-inner" @click="toAddOrder()">
						下单
					</view>
				</view>
			</view>

			<footer-tabbar></footer-tabbar>
		</view>
	</view>
</template>

<script>
	import xflSelect from '../../components/xfl-select/xfl-select.vue';
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

				defaultPrefecture:0,


				prefectureList: [
					{
						id: 0,
						src: '/static/index/menu/menu_4.png',
						name: '废纸',
						subtitle:'杂纸，纯黄纸',
						url: ''
					},
					{
						id: 1,
						src: '/static/index/menu/menu_2.png',
						name: '塑料',
						subtitle:'塑料瓶，塑料杯',
						url: ''
					},
					{
						id: 2,
						src: '/static/index/menu/menu_1.png',
						name: '金属',
						subtitle:'废旧不锈钢',
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
			},
			toAddOrder(){
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

<style>
	page {
		background: #f5f5f5;
	}

	.content {
		/* #ifdef APP-PLUS */
		padding-bottom: 40rpx;
		/* #endif */
		/* #ifdef MP-WEIXIN */
		padding-bottom: 140rpx;
		/* #endif */
		padding-top: 100rpx;
	}
	

	.navbar .nav-item {
		display: flex;
		flex: 1;
		justify-content: center;
		padding-top: 30upx;
		font-size: 28upx;
		color: #fff;
	}


	.swiper_bg_2 {
		background: #fff;
		height: 140upx;
	}

	.scroll_top {
		border-radius: 20rpx;
		margin: 0 15rpx;
		overflow: hidden;
		margin-top: -240rpx;
	}

	.swiper {
		height: 240rpx;
	}

	.swip-img {
		width: 100%;
		height: 240rpx;
	}


	/* menu宫格 */
	.index_menu {
		display: flex;
		flex-direction: row;
		padding: 40rpx 15rpx 20rpx 15rpx;
		background: #fff;
		/* background: #4CD964; */
	}


	.required-subtitle{
		color: #1D2124;
		font-size:20rpx;
	}


	.menu_box {
		flex: 1;
		text-align: center;
		font-size: 30rpx;
		font-weight: bold;
	}

	.menu_img {
		width: 100rpx;
		height: 100rpx;
		padding-bottom: 18rpx;
	}


	.center_banner {
		padding: 10rpx 15rpx;
		background: #fff;
	}

	.center_img {
		width: 100%;
		height: 99rpx;
	}



	.yishi_img image {
		width: 200rpx;
		height: 200rpx;
	}



	.create-order-circle{
		position: relative;
		bottom: -200rpx;

		width: 300rpx;
		height: 300rpx;
		border-radius: 150rpx;
		margin: 0 auto;
		background-color: #7ef57e;
	}
	.create-order-inner{
		background-color: #09b609;
		width: 180rpx;
		height: 180rpx;
		border-radius: 90rpx;
		text-align: center;
		line-height: 180rpx;
		font-size: 50rpx;
		color: white;

		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		margin: auto  auto;

	}


</style>
