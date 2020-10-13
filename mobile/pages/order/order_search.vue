<template>
	<view class="content">
		<!-- #ifdef APP-PLUS -->
		<nav-bar id="nav" :navTop="navTop" v-model="inputValue" :value="inputValue" @input="handleInput" :focus="true" bgColor="#ff4b2d"
		 showSearch>
			<view slot="leftBtn" class="text-white px-2" @tap="back" hover-class="text-hover-secondary">
				<text class="iconfont icon-fanhui font-md mr-1"></text>
			</view>
		</nav-bar>
		<!-- #endif -->
		<!-- #ifdef MP-WEIXIN -->
		<nav-bar id="nav" :navTop="navTop" v-model="inputValue" :value="inputValue" @input="handleInput" :focus="true" bgColor="#ff4b2d"
		 showSearch>
			<view slot="leftBtn" class="text-white px-2" @tap="back" hover-class="text-hover-secondary">
				<text class="iconfont icon-fanhui font-md mr-1"></text>
			</view>
			<view class="flex-2" slot="rightBtn"></view>
		</nav-bar>
		<!-- #endif -->
		<scroll-view @scrolltolower="handleTolower" scroll-y="true" class="position-absolute" style="background-color: #eee;"
		 :style="scrollStyle">
			<view class="text_1 mb-4">
				<view v-if="loading" class="w-100 flex justify-center align-center" style="height: 450rpx;">
					<my-loading></my-loading>
				</view>
				<view v-else-if="orderList.length > 0" class="order_list">
					<view class="order_list_box" v-for="(item,index) in orderList" :key="item.id" @tap="toDetail(item.order_id)">
						<view class="order_top">
							<!-- 顶部栏 -->
							<view>{{item._add_time}}</view>
							<view class="order_top_right">
								<view class="delet_txt">{{item.status | checkStatus}}</view>
								<image v-if="item.type===4 || item.type===5" class="delet_img" :src="item.delet_img" mode="widthFix"></image>
							</view>
						</view>
						<view class="flex flex-column">
							<view class="order_center" v-for="it in item.cartInfo" :key="it.id">
								<view class="goods_img">
									<image class="goods" :src="it.productInfo.image" mode="widthFix"></image>
								</view>
								<view class="goods_name u-line-2">
									{{it.productInfo.store_name}}
								</view>
								<view class="goods_price">
									<view class="goods_price_1">￥{{it.truePrice}}</view>
									<view class="goods_price_2">×{{it.cart_num}}</view>
								</view>
							</view>
						</view>
						<view class="order_bottom">
							<view class="order_count">共{{item.total_num}}件，应付总额：<text class="red_txt">￥{{item.total_price}}</text></view>
							<view class="btn_box" v-if="item.status==0">
								<view class="btn_1" @tap.stop="cancelOrder(item.order_id)">取消订单</view>
								<view class="btn_2">付款({{item.total_price}})</view>
							</view>
							<view class="btn_box" v-if="item.status==1">
								<view class="btn_1">申请退款</view>
								<view class="btn_2">提醒发货</view>
							</view>
							<view class="btn_box" v-if="item.status==2">
								<view class="btn_2">确认收货</view>
								<view class="btn_1">申请退款</view>
								<view class="btn_1">查看物流</view>
							</view>
							<view class="btn_box" v-if="item.status==3">
								<view class="btn_1">申请退款</view>
								<view class="btn_2">立即评价</view>
							</view>
							<view class="btn_box" v-if="item.status==4">
								<view class="btn_1">申请退款</view>
							</view>
							<view class="btn_box" v-if="item.status==-1">
								<view class="btn_1">查看进度</view>
							</view>
							<view class="btn_box" v-if="item.status==-2">
								<view class="btn_1">服务评价</view>
							</view>
							<view class="btn_box" v-if="item.status==-3">
								<view class="btn_1">删除订单</view>
							</view>
						</view>
					</view>
					<loading-module :isLoading="isLoading"></loading-module>
				</view>
				<view v-else-if="orderList.length === 0" class="flex align-center flex-column justify-center">
					<image src="/static/search/no_record2.svg" style="height: 400rpx; width: 400rpx;" mode=""></image>
					<text class="text-grey font-weight-bolder mb-3 ">还没有此类订单欧～～</text>
					<navigator style="color: #007BFF" url="/pages/drink/drink">去下单>></navigator>
				</view>
			</view>
			<!-- #ifndef APP-PLUS -->
			<view style="height: 120rpx;"></view>
			<!-- #endif -->
		</scroll-view>
	</view>
</template>

<script>
	import NavBar from '@/components/navBar.vue'
	import {
		vuexData,
		page
	} from '@/common/commonMixin.js'
	export default {
		filters: {
			checkStatus(val) {
				let statusArr = [
					{
						name: '全部',
						id: '',
					},
					{
						name: '待付款',
						id: 0,
					},
					{
						name: '待发货',
						id: 1
					},
					{
						name: '待收货',
						id: 2
					},
					{
						name: '待评价',
						id: 3
					},
					{
						name: '已完成',
						id: 4
					},
					{
						name: '退款中',
						id: -1
					},
					{
						name: '已退款',
						id: -2
					},
					{
						name: '退款',
						id: -3
					}
				]
				return statusArr.filter(item => item.id == val)[0].name
			}
		},
		components: {
			NavBar
		},
		mixins: [vuexData, page],
		data() {
			return {
				inputValue: '', // 搜索关键字
				loading: true, // 是否加载中
				windowHeight: 0, // 页面高度
				type: 0,		// 订单类型
				ratio: 0, 		// 宽高比大于2则为全面屏
				orderList:[]
			}
		},
		async onLoad() {
			this.$nextTick(async () => {
				let {
					windowHeight,
					windowWidth
				} = uni.getSystemInfoSync()
				this.ratio = windowHeight/windowWidth
				this.windowHeight = windowHeight
			})
			this.clearCartAndOrder('orderList')
			this.loading = true
			await this.searchOrder()
		},
		methods: {
			/**
			 * @author			前端小能手
			 * handleInput		输入方法
			 * searchOrder		关键字搜索订单
			 * back				返回上一页
			 * handleTolower	上拉触底事件
			 * toDetail			查看订单详情
			 */
			handleInput(val) {
				this.inputValue = val
				this.clearCartAndOrder('orderList')
				this.loading = true
				this.delay(this.searchOrder)
			},
			async searchOrder() {
				let res = await this.$api.getOrderList(`?page=${this.page}&limit=10&type=&search=${this.inputValue}`)
				console.log(res)
				this.checkRes(res)
				this.getOrderList(res) 
				this.loading = false
			},
			back() {
				uni.navigateBack() 
			},
			handleTolower() {
				this.scrolltolower(this.searchOrder)
			},
			toDetail(id) {
				uni.navigateTo({
					url: '/pages/order/order_detail?id=' + id
				})
			}
		},
		computed: {
			/**
			 * @author			前端小能手
			 * scrollStyle		列表容器样式
			 * navTop			navBar据屏幕顶距离
			 */
			navTop() {
				if (this.ratio > 2) {
					return '40'
				} else {
					return '18'
				}
			},
			scrollStyle() {
				let height = this.windowHeight - (Number(this.navTop) + uni.upx2px(100))
				let top = Number(this.navTop) + uni.upx2px(100)
				return `height: ${height}px; top: ${top}px;`
			}
		}
	}
</script>

<style scoped>
	page {
		width: 100%;
		height: 100vh;
		background: #ff4b2d;
	}
	.content {
		width: 100%;
		height: 100vh;
		background: #ff4b2d;
	}
	.order_list {}
	
	.order_list_box {
		margin-bottom: 30rpx;
		padding: 0 20rpx;
		background: #fff;
	}
	
	
	
	.order_top {
		display: flex;
		flex-direction: row;
		align-items: center;
		height: 90rpx;
		justify-content: space-between;
		border-bottom: #e8e8e8 solid 1px;
		color: #4d4d4d;
		font-size: 30rpx;
	}
	
	.order_top_right {
		display: flex;
		flex-direction: row;
		align-items: center;
	}
	
	.delet_img {
		width: 40rpx;
		height: 40rpx;
		border-left: #4d4d4d solid 4rpx;
		padding-left: 15rpx;
		margin-left: 15rpx;
	}
	
	
	.goods_img {
		width: 20%;
		margin-right: 40rpx;
		margin-top: 25rpx;
	}
	
	.goods {
		width: 100%;
	}
	
	.goods_name {
		width: 60%;
		font-size: 30rpx;
		margin-top: 30rpx;
	}
	
	.goods_price {
		width: 17%;
		text-align: right;
		margin-top: 30rpx;
	}
	
	.goods_price_1 {
		color: #4b4b4b;
		font-size: 28rpx;
	}
	
	.goods_price_2 {
		color: #858585;
		font-size: 26rpx;
		padding-top: 10rpx;
	}
	
	.order_center {
		display: flex;
		flex-direction: row;
		align-items: flex-start;
		height: 200rpx;
		border-bottom: #e8e8e8 solid 1px;
	}
	
	.order_bottom {
		display: flex;
		flex-direction: column;
		align-items: flex-end;
	}
	
	.btn_box {
		display: flex;
		flex-direction: row;
	}
	
	.order_count {
		padding: 20rpx 0;
		font-size: 26rpx;
		color: #4d4d4d;
	}
	
	.red_txt {
		color: #e8204f;
	}
	
	.btn_1 {
		border: solid #bbb 1px;
		color: #5e5e5e;
		height: 56rpx;
		line-height: 56rpx;
		padding: 0 20rpx;
		text-align: center;
		border-radius: 40rpx;
		font-size: 28rpx;
		margin-bottom: 30rpx;
		margin-left: 15rpx;
	}
	
	.btn_2 {
		color: #fff;
		background: #f5390b;
		height: 56rpx;
		padding: 0 20rpx;
		line-height: 56rpx;
		text-align: center;
		border-radius: 40rpx;
		font-size: 28rpx;
		margin-bottom: 30rpx;
		margin-left: 15rpx;
	}
</style>
