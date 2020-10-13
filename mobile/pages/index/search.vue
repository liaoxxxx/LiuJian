<template>
	<view class="page">
		<!-- #ifdef APP-PLUS -->
		<view class="nav-top position-fixed w-100"></view>
		<!-- #endif -->
		<nav-bar v-model="inputValue" :value="inputValue" @input="handleInput" :focus="focus" bgColor="#ff4b2d" showSearch
		 placeholder="请输入搜索内容">
			<view slot="leftBtn" class="text-white px-2" @tap="back" hover-class="text-hover-secondary">
				<text class="iconfont icon-fanhui font-md mr-1"></text>
			</view>
		</nav-bar>

		<scroll-view v-if="loading" class="flex pt-2  flex-column position-absolute" scroll-y="true" :style="scrollPosition">
			<view class="w-100 flex justify-center align-center" style="height: 450rpx;">
				<my-loading></my-loading>
			</view>
		</scroll-view>

		<scroll-view v-else-if="inputValue.length == 0" class="flex pt-2  flex-column position-absolute" scroll-y="true"
		 :style="scrollPosition">
			<view class="flex align-center border-bottom py-2 pl-2">
				<text class="text-grey font">热门搜索</text>
			</view>
			<view class="w-100 flex py-3 px-3 flex-wrap">
				<view v-for="item in hotSearch" :key="item.id" @tap="setKeyWord(item.content)" class="border flex align-center justify-center rounded-circle py-1 px-2 mt-2 mr-2">
					<text class="font">
						{{item.content}}
					</text>
				</view>
			</view>
			<halving-line></halving-line>
			<view class="flex align-center border-bottom py-2 pl-2">
				<text class="text-grey font">搜索记录</text>
			</view>
			<view v-if="searchHistory.length > 0" class="w-100 flex py-3 px-3 flex-wrap">
				<view v-for="(item, index) in searchHistory" @tap="setKeyWord(item)" :key="index" class="border flex align-center justify-center rounded-circle py-1 px-2 mt-2 mr-2">
					<text class="font">
						{{item}}
					</text>
				</view>
			</view>
			<no-record v-else></no-record>
		</scroll-view>
		<view v-if="goodsList.length > 0 && inputValue.length != 0 && !loading" class="flex w-100 border-box justify-between position-fixed" style="height: 120rpx;" :style="sortStyle">
			<view class="flex justify-center py-4 px-1" style="width: 100%; background-color: rgba(252, 252, 252, .9);" @tap.stop="checkSort(item.id)"
			 v-for="item in sortList">
				<text class="font" :class="{'text-admin': item.id == sortId}">{{item.name}}</text>
				<text class="iconfont text-grey font" :class="{'text-admin': item.id == sortId}">&#xe700;</text>
			</view>
		</view>
		<scroll-view v-if="goodsList.length > 0 && inputValue.length != 0 && !loading" class="flex border-box flex-column position-absolute" scroll-y="true"
		 :style="scrollStyle">
			<view class=" border-box px-2">
				<goods-item @goDetail="goDetail(item.id)" @addCart="addCart(item.id)" v-for="item in goodsList" :key="item.id"
				 :name="item.store_name" :src="item.image" :price="item.price">
				</goods-item>
			</view>
		</scroll-view>

		<scroll-view v-if="inputValue.length != 0 && goodsList.length == 0 && !loading" class="flex pt-2  flex-column position-absolute"
		 scroll-y="true" :style="scrollPosition">
			<view class="w-100">
				<no-record comtentText="没有搜索到相关商品～～"></no-record>
			</view>
		</scroll-view>
		<!-- <drawer @handleHide="draHide" fixed ref="drawer" type="bottom">
		<add-cart-modul :goodsDetail="goodsDetail" :tempPro="hotGoodsList" ></add-cart-modul>
		</drawer> -->
	</view>
</template>

<script>
	import NavBar from '@/components/navBar.vue'
	import NoRecord from '@/components/no_record.vue'
	import drawer from '@/components/drawer.vue'
	import addCartModul from '@/components/add_cart_modul.vue'
	import goodsItem from '@/components/goods_item.vue'
	import {
		vuexData,
		page
	} from '@/common/commonMixin.js'
	export default {
		components: {
			NavBar,
			NoRecord,
			goodsItem,
			drawer
		},
		mixins: [vuexData, page],
		data() {
			return {
				inputValue: '',
				searchHistory: [],
				loading: false,
				focus: false,
				isRise: true,
				sortId: 0,
				hotSearch: [{
						content: '黄尾袋鼠',
						id: 1
					},
					{
						content: '哈尔滨',
						id: 2
					},
					{
						content: '法国',
						id: 3
					},
					{
						content: '洋酒',
						id: 4
					},
					{
						content: '清酒',
						id: 5
					},
					{
						content: '一滴入魂',
						id: 6
					}
				],
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
				this.clearGoods('goodsList')
				this.loading = true
				await this.getGoods()
				this.loading = false
			},
			back() {
				uni.navigateBack()
			},
			handleInput(val) {
				this.inputValue = val
				this.clearGoods('goodsList')
				this.loading = true
				this.delay(this.searchGoods)
			},
			setKeyWord(value) {
				console.log(value)
				this.inputValue = value
			},
			async searchGoods() {
				this.clearGoods('goodsList')
				await this.getGoods()
				this.loading = false
				if (this.inputValue) {
					if (this.searchHistory.length > 15) {
						this.searchHistory.pop()
						this.searchHistory.push(this.inputValue)
					} else {
						this.searchHistory.push(this.inputValue)
					}
				}
				this.searchHistory = [...new Set(this.searchHistory)]
			},
			async getGoods() {
				let isRise = this.isRise ? 'desc' : 'asc'
				let sort = this.sortList[this.sortId].type == 'default' ? '' : `&${this.sortList[this.sortId].type}=${isRise}`
				let res = await this.$api.getProductList(
					`?page=${this.page}&limit=${10}&type=0&keyword=${this.inputValue}&${sort}`)
				this.getGoodsList(res)
				console.log(this.goodsList)
			},
			goDetail(id) {
				uni.navigateTo({
					url: '/pages/index/goods_info?id=' + id
				})
			}
		},
		computed: {
			scrollPosition() {
				// #ifdef APP-PLUS
				let top = uni.upx2px(166)
				// #endif
				// #ifdef MP-WEIXIN
				let top = uni.upx2px(84)
				// #endif
				return `top: ${top}px; bottom: 0; left: 0; right: 0;`
			},
			scrollStyle() {
				// #ifdef APP-PLUS
				let top = uni.upx2px(286)
				// #endif
				// #ifdef MP-WEIXIN
				let top = uni.upx2px(204)
				// #endif
				return `top: ${top}px; bottom: 0; left: 0; right: 0;`
			},
			sortStyle() {
				// #ifdef APP-PLUS
				let top = uni.upx2px(166)
				// #endif
				// #ifdef MP-WEIXIN
				let top = uni.upx2px(84)
				// #endif
				return `top: ${top}px;`
			}
		},
		mounted() {
			this.clearGoods('goodsList')
			this.focus = true
			let history = uni.getStorageSync('search_history')
			if (history) {
				this.searchHistory = history
			}
		},
		onUnload() {
			uni.setStorageSync('search_history', this.searchHistory)
		}
	}
</script>

<style scoped>
	page {
		background: #f5f5f5;
	}

	/* 顶部填充 */
	.nav-top {
		background: linear-gradient(to right, #ff4b2d, #fd4341);
		z-index: 99;
		height: 170rpx;
		top: 0;
	}
</style>
