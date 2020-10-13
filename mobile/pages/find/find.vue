<template>
	<view class="content" :style="wraStyle">
		<view>
			<nav-tab id="navTab" idType :activeFont="{color: '#F6390D'}" :active="{width: '80rpx', height: '6rpx', backgroundColor: '#F6390D', borderRadius: '100rpx'}" maxPadding color="#123" :activeIndex="tabCurrentIndex" @handleTap="tabClick" :list="navList" :fixed="-1"
			 bgColor="#F8F9FA">
			 </nav-tab>
		</view>
		<view v-if="loading" class="w-100 flex justify-center align-center" style="height: 450rpx;">
			<my-loading></my-loading>
		</view>
		<view v-if="findList.length>0 && !loading" class="w-100 border-box">
			<view class="w-100" v-for="item in findList" :key="item.discovery_id" :id="'id' + item.discovery_id">
				<view :id="'img' + item.discovery_id" class="position-relative">
					<image :src="item.banner" style="width: 750rpx;" mode="widthFix"></image>
					<text class="position-absolute text-white font-weight-bolder" style="transform: translateX(-50%) translateY(-50%); top: 50%; left: 50%; font-size: 50rpx;" >{{item.title}}</text>
				</view>
				<view class="flex flex-wrap px-1">
					<view v-for="it in item.product_info" @tap="toGoodsDetail(it.id)" :key="it.id" class="pt-1 pl-2 pr-1 mb-2" style="max-width: 330rpx;">
						<hot-item :src="it.image" :name="it.store_name" :price="it.price"></hot-item>
					</view>
				</view>
			</view>
		</view>
		<!-- #ifndef APP-PLUS -->
		<footer-tabbar></footer-tabbar>
		<!-- #endif -->
	</view>
</template>

<script>
	import {vuexData} from '@/common/commonMixin.js'
	import hotItem from '@/components/hot_goods_item.vue'
	import navTab from '@/components/nav_tab.vue'
	export default {
		mixins: [vuexData],
		components: {
			hotItem,
			navTab
		},
		data() {
			return {
				tabCurrentIndex: 0,
				navHeight: 0,
				heightList: [],
				top: 0,
				loading: true
			}
		},
		async onLoad(options) {
			// 页面显示是默认选中第一个
			/**
			 * @author				前端小能手
			 * 初始化页面 获取数据
			 * 获取tab尺寸
			 * 初始化tabCurrentIndex
			 * 每类的高度
			 */
			this.loading = true
			let res = await this.$api.getFind()
			this.getFind(res)
			this.loading = false
			let navTab = await this.getElSize('#navTab')
			this.tabCurrentIndex = this.findList[0].discovery_id
			let { height } = navTab
			
			// #ifdef APP-PLUS
			this.navHeight = height + uni.upx2px(40)
			// #endif
			
			// #ifdef MP-WEIXIN
			this.navHeight = height + uni.upx2px(80)
			// #endif
			
			this.$nextTick(async () => {
				await this.getHeightList()
			})
		},
		computed: {
			/**
			 * @author				前端小能手
			 * 
			 * navList				tap列表
			 * wraStyle				顶部内边距
			 */
			navList() {
				let list = this.findList.map(item => {
					return {
						id: item.discovery_id,
						name: item.title
					}
				})
				console.log(list)
				return list
			},
			wraStyle() {
				return `padding-top: ${this.navHeight}px`
			}
		},
		methods: {
			//顶部tab点击
			async tabClick(id) {
				if (id == this.tabCurrentIndex) {
					return
				}
				this.tabCurrentIndex = id
				let style = await this.getElSize('#id' + id)
				console.log(style)
				let {top} = style
				this.top += (top - this.navHeight)
				uni.pageScrollTo({
					  scrollTop: this.top,
					  duration: 200
				})
			},
			/**
			 * @author				前端小能手
			 * 
			 * toGoodsDetail		查看商品详情
			 * getElSize			获取组件尺寸
			 * getHeightList		获取每类的高度(暂时有些问题)
			 */
			toGoodsDetail(id) {
				uni.navigateTo({
					url: "/pages/index/goods_info?id=" + id
				})
			},
			getElSize(id) {
				return new Promise((res, rej) => {
					uni.createSelectorQuery().in(this).select(id).boundingClientRect(data => {
						res(data)
					}).exec();
				})
			},
			async getHeightList() {
				let hei = 0
				this.findList.forEach(async item => {
					let style = await this.getElSize('#id' + item.discovery_id)
					let heightItem = {H: hei, id: item.discovery_id}
					let {height} = style
					hei += height
					this.heightList.push(heightItem)
				})
			}
		},
		// 监听页面滚动
		onPageScroll(e) {
			let {scrollTop} = e
			this.top = scrollTop
			// this.heightList.forEach(item => {
			// 	if (scrollTop >= item.H) {
			// 		this.tabCurrentIndex = item.id
			// 	}
			// })
			// switch (scrollTop) {
			// 	case 
			// }
		}
	}
</script>

<style>
	.content{
		padding-bottom: 200rpx;
	}
	/* 顶部选项卡 */
	.navbar{
		display: flex;
		flex-direction: row;
		width:100%;
		height: 80rpx;
		border-bottom: 1px solid #000000;
		padding-bottom: 20rpx;
	}
	.navbar .nav-item{
		display: flex;
		flex: 1;
		justify-content: center;
		padding-top: 30upx;
		font-size: 28upx;
	}
	.active{
		color: #f90603;
		font-size: 30upx;
		font-weight: bold;
		border-bottom: 3px solid #f90603;
	}
	
	
	
	.img_1{
		width: 100%;
	}
	.special_list{
		padding: 20rpx 15rpx 0rpx 15rpx;
		width: 100%;
		display: flex;
		flex-wrap: wrap;
		box-sizing: border-box;
	}
	.special_box{
		width: 48.8%;
		margin-right: 15rpx;
		padding: 0rpx 0rpx 20rpx 0rpx;
		box-sizing: border-box;
		border-radius: 10rpx;
		margin-bottom: 20rpx;
		border: solid #000000 1px;
		overflow: hidden;
	}
	.special_box:nth-child(even){
		margin-right: 0;
	}
	.good_input{
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
	}
	.list_img{
		text-align: center;
	}
	.list_img image{
		width: 100%;
	}
	.shop_car{
		width: 60rpx;
	}
	.list_name{
		font-size: 34rpx;
		font-weight: bold;
	}
	.Specifications{
		font-size: 20rpx;
		color: #868686;
	}
	/* .new_price{
		font-size: 28rpx;
		color: #fe0101;
		font-weight: bold;
	}
	.new_price_big{
		font-size: 34rpx;
	} */
</style>
