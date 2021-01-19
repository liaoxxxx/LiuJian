<template>
	<view id="page1" @touchmove.stop.prevent="moveHandle" class="flex flex-column border-box w-100 page">
		<!-- 头部导航 -->
		<!-- #ifdef APP-PLUS -->
		<nav-tab id="navTab" maxPadding :activeIndex="tabCurrentIndex" @handleTap="tabClick" :list="navList" :fixed="0"
		 bgColor="#ff4b2d">
		</nav-tab>
		<!-- #endif -->

		<!-- #ifndef APP-PLUS -->
		<nav-bar id="navBar" bgColor="#ff4b2d" @toSearch="toSearch" notFocus showSearch placeholder="请输入搜索内容">

		</nav-bar>
		<nav-tab id="navTab" maxPadding :activeIndex="tabCurrentIndex" @handleTap="tabClick" :list="navList" :fixed="80"
		 bgColor="#ff4b2d">
		</nav-tab>
		<!-- #endif -->

		<!-- #ifdef APP-PLUS -->
		<view class="flex">
			<!-- #endif -->
			<!-- #ifndef APP-PLUS -->
			<view class="flex position-absolute w-100" style="top: 160rpx">
				<!-- #endif -->
				<!-- 左侧导航 -->
				<scroll-view scroll-y="true" class="flex-2 font-md " :style="leftStyle">
					<view class="flex flex-column" style="background-color: rgba(252, 252, 252, .9);">
						<view v-for="item in childrenList" @tap="changeCur(item.id)" :key="item.id" class="flex  align-center py-1 border-bottom pr-2"
						 :class="current == item.id ? 'bg-white text-danger' : ''">
							<view class=" mr-1" :class="current == item.id ? 'activ_line' : 'line'"></view>
							<view class="mx-2 flex justify-center flex-1">
								<text>{{item.cate_name}}</text>
							</view>
						</view>
					</view>
					<!-- #ifndef APP-PLUS -->
					<view class="" style="height: 200rpx;"></view>
					<!-- #endif -->
				</scroll-view>
				<!-- 右侧产品 -->
				<view class="flex position-relative flex-column flex-5">
					<view class="flex pl" id="sort">
						<view class="flex py-4 px-1" style="width: 100%; background-color: rgba(252, 252, 252, .9);" @tap.stop="checkSort(item.id)" v-for="item in sortList">
							<text class="font" :class="{'text-admin': item.id == sortId}" >{{item.name}}</text>
							<text class="iconfont text-grey font" :class="{'text-admin': item.id == sortId}" >&#xe700;</text>
						</view>
					</view>
					<scroll-view @scrolltolower="scrolltolower(getGoods)" class="position-absolute" scroll-y="true" :style="rightStyle">

						<view v-if="loading">
							<skeleton :row="20" animate :loading="loading" style="margin-top:24rpx;">
							</skeleton>
						</view>

						<view v-else-if="goodsList.length > 0"  class="flex flex-column">
							<goods-item :size="160" :showLable="false" :showInfo="false" @goDetail="goDetail(item.id)" @addCart="addCart(item.id)" v-for="item in goodsList"
							 :key="item.id" :name="item.store_name" :src="item.image" :price="item.price">
							</goods-item>
							<loading-module notDataText="我是有底线的～～" :isLoading="isLoading"></loading-module>
						</view>

						<view v-else class="flex align-center flex-column justify-center">
							<image src="/static/search/no_record2.svg" style="height: 400rpx; width: 400rpx;" mode=""></image>
							<text class="text-grey font-weight-bolder ">该品类暂无商品，敬请期待</text>
						</view>

						<!-- 站位 -->
						<!-- #ifndef APP-PLUS -->
						<view class="" style="height: 200rpx;"></view>
						<!-- #endif -->
					</scroll-view>
				</view>
			</view>

			<!-- #ifndef APP-PLUS -->
			<footer-tabbar></footer-tabbar>
			<!-- #endif -->

			<drawer fixed ref="drawer" type="bottom">
				<view class=" w-100 borer-box position-absolute flex py-2 flex-column px-2 bottom-0 bg-white">
					<view v-if="tempDetail.storeInfo" class="flex border-bottom py-1">
						<image class="mr-2" style="height: 220rpx; width: 220rpx;" :src="tempDetail.storeInfo.image"></image>
						<view class="flex flex-column mt-3">
							<view class="text-danger align-end flex mb-1">
								<text class="font-sm">¥</text>
								<text class="font-md font-weight-bolder">{{proId == '' ? tempDetail.storeInfo.price : selectedPro.price}}</text>
							</view>
							<view class="font text-grey mb-1">库存{{ proId == '' ? tempDetail.storeInfo.stock : selectedPro.stock }}件</view>
							<view class="font">{{ proId == '' ? '请选择套餐类型' : '已选择： ' + selectedPro.suk}}</view>
						</view>
					</view>
					<view class="flex flex-column mb-2">
						<halving-line bg-color="#fff" content="规格选择"></halving-line>
						<view class="flex flex-wrap">
							<view class="mr-1 mb-1 " v-for="item in tempPro" :key="item.unique">
								<tag @handleTap="checkPro(item.unique)" :content="item.suk" :size="30" :color="proId == item.unique ? '#ececec' : '#333'"
								 bold :bgColor="proId == item.unique ? '#e52' : '#eee'" rounded="radius"> </tag>
							</view>
						</view>
					</view>
					<view class="mb-2">
						<my-btn content="确认" color="#fefeee" @handleTap="proAddCart" bold bgColor="#e52"></my-btn>
					</view>
				</view>
			</drawer>
		</view>
</template>

<script>
	import navTab from '@/components/nav_tab.vue'
	import navBar from '@/components/navBar.vue'
	import skeleton from '@/components/xinyi-skeleton/skeleton.vue'
	import tag from '@/components/tag.vue'
	import drawer from '@/components/drawer.vue'
	import goodsItem from '@/components/goods_item.vue'
	import {page} from '@/common/commonMixin.js'
	export default {
		components: {
			navBar,
			navTab,
			goodsItem,
			skeleton,
			drawer,
			tag
		},
		mixins: [page],
		data() {
			return {
				tabCurrentIndex: 0,
				draIsShow: false,
				loading: true,
				category: [],
				current: 0,
				proId: '',
				navList: [],
				sortHeight: 0,
				tempDetail: {},
				goodsList: [],
				sortId: 0,
				windowHeight: 0,
				isRise: true,
				tapHeight: 0,
				sortList: [{
						name: '推荐排序',
						id: 0,
						type: 'dfault'
					},
					{
						name: '销量排序',
						id: 1,
						type: 'salesOrder'
					},
					{
						name: '价格排序',
						id: 2,
						type: 'priceOrder'
					}
				]
			}
		},
		async onLoad(options) {
			// 页面显示是默认选中第一个
			this.tabCurrentIndex = 0
			// 请求tap数据
			uni.showLoading({
				title: '努力加载中...'
			})
			let res = await this.$api.getCategory()
		
			if (res.status == 200) {
				let {
					data
				} = res
				this.navList = data.map(item => {
					item.name = item.cate_name
					return item
				})
				// 初始化子类菜单
				this.current = data[0].children[0].id ? data[0].children[0].id : -1
			}
			uni.hideLoading()
			this.$nextTick(async () => {
				// 请求商品列表
				this.loading = true
				await this.getGoods()
				this.loading = false
				let {
					windowHeight
				} = uni.getSystemInfoSync()
				console.log(windowHeight)
				this.windowHeight = windowHeight
			})
		},
		async onReady() {
			this.$nextTick(async () => {
				let navTab = await this.getElSize('#navTab')
				let sort = await this.getElSize('#sort')
				let page = await this.getElSize('#page1')
				console.log(page)
				let {
					height
				} = navTab
				let {
					height: sortHeight
				} = sort
				this.tapHeight = height
				this.sortHeight = sortHeight
			})
		},
		computed: {
			selectedPro() {
				if (this.tempPro.length > 0 && this.proId != '') {
					return this.tempPro.filter(item => item.unique == this.proId)[0]
				}
				return ''
			},
			// 后期可优化  规格
			tempPro() {
				if (this.tempDetail.productValue) {
					console.log(this.tempDetail.productValue)
					return Object.values(this.tempDetail.productValue)
				}
				return []
			},
			leftStyle() {
				let tabHeight = this.tapHeight
				// #ifndef APP-PLUS
				let height = this.windowHeight - uni.upx2px(140) - tabHeight
				return `height: ${height}px`
				// #endif
				// #ifdef APP-PLUS
				let height = this.windowHeight - tabHeight - uni.upx2px(60)
				return `height: ${height}px`
				// #endif
			},
			rightStyle() {
				let tabHeight = this.tapHeight
				let sortHeight = this.sortHeight
				// #ifndef APP-PLUS
				let height = this.windowHeight - uni.upx2px(140) - tabHeight - sortHeight
				let top = sortHeight
				return `height: ${height}px; top: ${sortHeight}px`
				// #endif
				// #ifdef APP-PLUS
				let height = this.windowHeight - tabHeight - uni.upx2px(60) - sortHeight
				let top = sortHeight
				return `height: ${height}px; top: ${sortHeight}px`
				// #endif
			},
			childrenList() {
				if (this.navList.length == 0) {
					this.current = -1
					return []
				}
				let obj = this.navList.filter(item => item.id == this.cid)[0]
				if (obj.children.length > 0) {
					this.current = obj.children[0].id
					return obj.children
				}
				this.current = -1
				return [{
					name: '全部',
					id: -1
				}]
			},
			cid() {
				let index = this.tabCurrentIndex
				this.updatePage()
				this.goodsList = []
				return this.navList[index].id
			}
		},
		methods: {
			// 排序选择
			async checkSort(id) {
				if (id == this.sortId) {
					this.isRise = !this.isRise
				} else {
					this.isRise = true
				}
				this.sortId = id
				this.updatePage()
				this.goodsList = []
				this.loading = true
				await this.getGoods()
				this.loading = false
			},
			moveHandle() {
				return
			}, 
			getElSize(id) {
				return new Promise((res, rej) => {
					uni.createSelectorQuery().in(this).select(id).boundingClientRect(data => {
						res(data);
					}).exec();
				})
			},
			checkPro(id) {
				if (id == this.proId) {
					this.proId = ''
				} else {
					this.proId = id
				}
			},
			async getGoods() {
				let isRise = this.isRise ? 'desc' : 'asc'
				let sort = this.sortList[this.sortId].type == 'default' ? '' : `&${this.sortList[this.sortId].type}=${isRise}`
				let goods = await this.$api.getProductList(
					`?cid=${this.cid}&page=${this.page}&limit=${10}&type=0&sid=${this.current==-1 ? '' : this.current}${sort}`)
				if (goods.status == 200) {
					let {
						data
					} = goods
					
					// this.$set(this, 'goodsList', arr)
					this.goodsList.push(...data)
					if (data.length < 10) {
						this.isLoading = 2
					}
					if (data.length == 0) {
						return false
					}
					return true
				} else {
					uni.showToast({
						icon: 'none',
						title: '数据错误，稍后重试'
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
			//顶部tab点击
			tabClick(index) {
				if (this.tabCurrentIndex == index || this.loading) {
					return
				}
				this.tabCurrentIndex = index

				setTimeout(async  () => {
					this.loading = true
					await this.getGoods()
					this.loading = false
				})
			},
			back() {
				uni.navigateBack()
			},
			// 查看产品详情
			goDetail(id) {
				uni.navigateTo({
					url: `/pages/index/goods_info?id=${id}`
				})
			},
			async changeCur(id) {
				if (this.current == id || this.loading) {
					return
				}
				this.current = id
				this.updatePage('goodsList')
				this.loading = true
				await this.getGoods()
				this.loading = false
			},
			checkRes(res, msg) {
				console.log("----------   res  msg ----------------")
				console.log(typeof res)
				console.log(res)
				if (res.errCode === 0) {
					uni.showToast({
						title: msg
					})
					return 200
				} else {
					uni.showModal({
						showCancel: false,
						title: '提示',
						content: res.msg
					})
					return false
				}
			},
			async addCart(id) {
				let abc = await this.getElSize('#page1')
				console.log(abc)
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
				} else {
					result = await this.$api.addCart({
						productId: id,
						cartNum: 1,
						new: 0
					})
					this.checkRes(result, '宝贝在购物车里等着您了～～')
				}

			},
		},
		// 到搜索页
		onNavigationBarSearchInputClicked() {
			uni.navigateTo({
				url: '/pages/index/search'
			})
		}
	}
</script>

<style scoped>
	page {
		width: 100%;
		height: 100vh;
	}

	.page {
		width: 100%;
		height: 100vh;
	}

	.activ_line {
		height: 100rpx;
		width: 5rpx;
		background-color: #ff4b2d;
	}

	.line {
		height: 100rpx;
		width: 5rpx;
	}
</style>
