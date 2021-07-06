<template>
	<!-- 订单主页 -->
	<view class="content">
		<view class="scroll_block w-100 mb-1">
			<scroll-view class="scroll-view_H" scroll-x="true">
				<view class="navbar position-relative  border-box px-2">
					<view v-for="(item) in navList" :key="item.id" class="nav-item pr-1" :class="{ current: tabCurrentIndex === item.id }"
					 @tap="tabClick(item.id)">
						<view :class="(item.id===tabCurrentIndex)?'active':'first_tabar'">
							{{ item.name }}
						</view>
					</view>
				</view>
			</scroll-view>
		</view>
		<scroll-view @scrolltolower="handleTolower" scroll-y="true" :style="scrollStyle">
			<view class="text_1 mb-4">
				<view v-if="loading">
					<skeleton :row="36" animate :loading="loading" style="margin-top:24rpx;">
					</skeleton>
				</view>
				<view v-else-if="orderList.length > 0" class="order_list">
					<view class="order_list_box" v-for="(item,index) in orderList" :key="item.id" @click="toDetail(item.id)">
						<view class="order_top">
              <text></text>
							<text>{{item._add_time}}</text>
              <text>{{item.order_id}}</text>
							<view class="order_top_right">
								<view class="delet_txt">{{type | checkStatus}}</view>
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
							<view class="btn_box" v-if="type==0">
								<view class="btn_1">取消订单</view>
								<view class="btn_2">付款({{item.total_price}})</view>
							</view>
							<view class="btn_box" v-if="type==-1">
								<view class="btn_1">查看进度</view>
							</view>
							<view class="btn_box" v-if="type==-2">
								<view class="btn_1">服务评价</view>
							</view>
					</view>

				</view>
          <loading-module v-if="isLoading != 2" :isLoading="isLoading"></loading-module>

          <view v-if="orderList.length == 0" class="flex align-center flex-column justify-center">
					<image src="/static/search/no_record2.svg" style="height: 400rpx; width: 400rpx;" mode=""></image>
					<text class="text-grey font-weight-bolder mb-3 ">还没有此类订单欧～～</text>
					<navigator style="color: #007BFF" url="/pages/drink/drink">去下单>></navigator>
				</view>
			</view>
      </view>
			<view class="flex flex-column border-box w-100 position-relative" v-if="isLoading == 2 && !loading">
				<view class="flex justify-center font text-grey">
					<text class="">————————</text>
					<text class="text-dark mx-1">你可能还喜欢</text>
					<text>————————</text>
				</view>
				<view class="flex flex-wrap px-1" v-if="hotGoodsList.length > 0">
					<view v-for="item in hotGoodsList" @tap="toGoodsDetail(item.id)" :key="item.id" class="pt-1 pl-2 pr-1 mb-2" style="max-width: 330rpx;">
						<hot-item :src="item.image" :name="item.store_name" :price="item.price"></hot-item>
					</view>
				</view>
				<loading-module :isLoading="hotLoading" notDataText="我是有底线的～～～"></loading-module>
			</view>
		</scroll-view>
		<!-- #ifndef APP-PLUS --> 
		<footer-tabbar></footer-tabbar>
		<!-- #endif -->
	</view>
</template>

<script>
	import {
		vuexData,
		page
	} from '@/common/commonMixin.js'
	import hotItem from '@/components/hot_goods_item.vue'
	import skeleton from '@/components/xinyi-skeleton/skeleton.vue'
	export default {
		mixins: [vuexData, page],
		components: {
			skeleton,
			hotItem
		},
		filters: {
			checkStatus(val) {
				let statusArr = [{
						name: '待服务',
						id: 0,
					},
					{
						name: '服务中',
						id: 1
					},
					{
						name: '已完成',
						id: 4
					}
				]
				return statusArr.filter(item => item.id == val)[0].name
			}
		},
		data() {
			return {
				tabCurrentIndex: 0,
				type: 0,
				windowHeight: 0,
				loading: true,
				hotPage: 1,
				hotLoading: 1,
				navList: [{
						name: '待服务',
						id: 0,
					},
					{
						name: '服务中',
						id: 1
					},
					{
						name: '已完成',
						id: 4
					}
				],
				dataList: [{
						goodsname: '卡尔斯特领地卡尔斯特领地卡尔斯特领地卡尔斯特领地卡尔斯特领地',
						price: '499'
					},
					{
						goodsname: '卡尔斯特领地',
						price: '499'
					},
				],
				//orderList:[]
				//type:1.待付款//2.代发货//3.待收货//4.待评价//5.已取消
			}
		},
		async onLoad(options) {
			const {
				type
			} = options
			// 页面显示是默认选中第一个
			this.type = type ? type : 0
			this.tabCurrentIndex = type ? type : 0;
			console.log(this.type)
			this.$nextTick(async () => {
				// 请求商品列表
				let {
					windowHeight
				} = uni.getSystemInfoSync()
				this.windowHeight = windowHeight
			})
			this.loading = true
			await this.initOrderList()
			this.loading = false
			this.clearGoods('hotGoodsList')
		},
		methods: {
			/**
			 * @author			前端小能手
			 * toSearch			搜索订单页面
			 * cancelOrder		取消订单
			 * toGoodsDetail	跳转产品详情
			 * handleTolower	页面滑动到底继续加载（下一页）
			 * hotBottom		当所有订单加载完触底
			 * getHotGoods		获取推荐商品
			 * tabClick			切换订单种类
			 * initOrderList	请求订单列表
			 * goLogistics		查看物流详情（目前只是模拟）
			 * toDetail			查看订单详情
			 * getElSize		获取标签尺寸
			 * refund			申请退款			
			 */
			toSearch() {

				uni.navigateTo({
					url: "/pages/index/goods_info?id=" + id
				})
			},
			handleTolower() {
				if (this.isLoading == 2 && !this.loading) {
					this.hotBottom(this.getHotGoods)
				} else {
					this.scrolltolower(this.initOrderList)
				}
			},
			async hotBottom(callback) { 
				// 说明没有更多或正在请求
				if (this.hotLoading == 2 || this.hotLoading == 0) {
					return
				}
				this.hotLoading = 0
				let res = await callback()
				if (res) {
					this.hotLoading = 1
				} else {
					this.hotLoading = 2
				}
				this.hotPage ++
			},
			async getHotGoods() {
				let hots = await this.$api.getHotGoods(`?page=${this.hotPage}&limit=10`)
				console.log(hots)
				let flag = this.checkRes(hots)
				if (flag){
					this.getHotGoodsList(hots)
					if (hots.data.length < 10) {
						return false
					} else {
						return true
					}
				}
			},
			//顶部tab点击
			async tabClick(index) {
				if(this.type === index || this.loading) {
					return
				}
				this.tabCurrentIndex = index
				this.type = index
				this.clearCartAndOrder('orderList')
				this.updatePage()
				this.loading = true
				await this.initOrderList()
				this.loading = false
				console.log(this.orderList.length == 0, this.orderList.length)
					
				if (this.orderList.length == 0 && !this.loading) {
					this.hotPage = 1
					this.hotBottom(this.getHotGoods)
				}
			},
			async initOrderList() {
				let res = await this.$api.getOrderList(`?page=${this.page}&limit=10`)
				//let  result= this.checkRes(res,res.msg)
        console.log(res)
				this.getOrderList(res)
				if (res.data.length < 10) {
					this.isLoading = 2
					return false
				} else {
					return true
				}
			},
			// 查看物流详情
			goLogistics() {
				console.log('物流')
				uni.navigateTo({
					url: './logistics',
					fail(err) {
						console.log(err)
					}
				})

			},
			toDetail(id) {
				uni.navigateTo({
					url: '/pages/order/order_detail?id=' + id + '&type=' + this.type
				})
			},
			getElSize(id) {
				return new Promise((res, rej) => {
					uni.createSelectorQuery().in(this).select(id).boundingClientRect(data => {
						res(data);
					}).exec();
				})
			},
			refund(id) {
				uni.navigateTo({
					url: '/pages/order/refund?orderId=' + id
				})
			}
		},
		computed: {
			scrollStyle() {
				let height = this.windowHeight - uni.upx2px(130)
				return `height: ${height}px`
			}
		}
	}
</script>

<style>
	page {
		width: 100%;
		height: 100%;
		background: #f5f5f5;
	}

	.content {}

	.list {
		padding-top: 110rpx;
	}

	/* .scroll_block最外层盒子用来隐藏scroll滚动条,土办法仅供参考 */
	.scroll_block {
		height: 110upx;
		overflow: hidden;

		background: #f5f5f5;
		z-index: 99;
		/* background: #07C160; */
	}

	/* white-space: nowrap;文本不会换行，文本会在在同一行上继续，直到遇到 <br> 标签为止。 */
	.scroll-view_H {
		white-space: nowrap;
		width: 100%;
		height: 125upx;
	}

	/* 顶部切换栏 */
	.navbar {
		display: flex;
		flex-direction: row;
		align-items: center;
		height: 110rpx;
		/* border-bottom: 1px solid #000000; */
	}

	.nav-item {
		height: 110upx;
		line-height: 110rpx;
		margin-right: 15rpx;
		display: flex;
		align-items: center;
		font-size: 26upx;
		width: 33.33%;
	}

	.first_tabar {
		padding: 0rpx 30rpx;
		border-radius: 30rpx;
		height: 58rpx;
		line-height: 58rpx;
		background: #eaeaea;
		width: 100%;
	}

	.active {
		color: #fff;
		padding: 0rpx 30rpx;
		height: 58rpx;
		line-height: 58rpx;
		border-radius: 30rpx;
		background: #fe0101;
	}


	/* 订单列表 */
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


	/* 想买更多 */
	.more_box {
		background: #fff;
		position: absolute;
		left: 0;
		right: 0;
		/* bottom: 20%; */
		height: 100%;
		height: auto;
		padding-bottom: 200rpx;
	}

	.more_top {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: center;
		font-size: 30rpx;
		color: #4b4b4b;
		padding-top: 30rpx;
		padding-bottom: 10rpx;
	}

	.xian {
		width: 26%;
		border-bottom: #e5e5e5 solid 1rpx;
	}


	.special_list {
		padding: 20rpx 15rpx 0rpx 15rpx;
		width: 100%;
		display: flex;
		flex-wrap: wrap;
		box-sizing: border-box;
	}

	.special_box {
		width: 48%;
		margin-right: 20rpx;
		padding: 0rpx 0rpx 20rpx 0rpx;
		box-sizing: border-box;
		border-radius: 15rpx;
		margin-bottom: 20rpx;
		border: solid #e5e5e5 1px;
		overflow: hidden;
	}

	.special_box:nth-child(even) {
		margin-right: 0;
	}

	.good_input {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
		margin-top: 30rpx;
		padding: 0 15rpx;
	}

	.list_img {
		text-align: center;
	}

	.list_img image {
		width: 100%;
	}

	.shop_car {
		width: 60rpx;
	}

	.list_name {
		font-size: 30rpx;
		height: 80rpx;
		/* background: #007AFF; */
		padding: 0 15rpx;
	}

	.Specifications {
		font-size: 32rpx;
		color: #e50d3e;
	}

	.label {
		background: #e50d3e;
		font-size: 20rpx;
		color: #fff;
		padding: 2rpx 10rpx;
		border-radius: 4rpx;
	}
</style>
