<template>
	<!-- 收藏界面 -->
	<view class="content">
		<view class="navbar">
			<view v-for="(item, index) in navList" :key="index" class="nav-item" :class="{ current: tabCurrentIndex === index }"
			 @tap="tabClick(index)">
				<view class="first_tabar" :class="{active:istrue==index}">
					{{ item.text }}
				</view>
			</view>
		</view>

		<!-- 显示区域 -->
		<view class="list">
			<view class="text_1" v-if="tabCurrentIndex==0">
				<view class="foot_block">
					<view class="foot_box" v-for="(item,index) in footList" :key="index">
						<view class="foot_goods">
							<view class="foot_goods_left">
								<image class="goods_img" :src="item.image" mode="widthFix"></image>
							</view>
							<view class="foot_goods_right">
								<view class="goods_title u-font-30 u-line-2">
									{{item.store_name}}
								</view>
								<view class="site u-font-30">库存：{{item.saales}}</view>
								<view class="goods_bottom">
									<view class="goods_price u-font-bold u-font-34">￥{{item.price}}</view>
									<view class="car">
										<image class="car_img" src="../../static/index/goods/shop_car.png" mode="widthFix" @click="addCart()"></image>
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>
			</view>
			<!-- <view class="text_1" v-if="tabCurrentIndex==1">
				<view class="foot_block">
					<view class="foot_box" v-for="(item,index) in footList" v-if="item.type===1" :key="index">
						<view class="foot_goods">
							<view class="foot_goods_left">
								<image class="goods_img" :src="item.goodsimg" mode="widthFix"></image>
							</view>
							<view class="foot_goods_right">
								<view class="goods_title u-font-30 u-line-2">
									{{item.goodstitle}}
								</view>
								<view class="site u-font-30">{{item.goods_site}}</view>
								<view class="goods_bottom">
									<view class="goods_price u-font-bold u-font-34">￥{{item.price}}</view>
									<view class="car">
										<image class="car_img" src="../../static/index/goods/shop_car.png" mode="widthFix"></image>
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>
			</view>
			<view class="text_1" v-if="tabCurrentIndex==2">
				<view class="foot_block">
					<view class="foot_box" v-for="(item,index) in footList" v-if="item.type===2" :key="index">
						<view class="foot_goods">
							<view class="foot_goods_left">
								<image class="goods_img" :src="item.goodsimg" mode="widthFix"></image>
							</view>
							<view class="foot_goods_right">
								<view class="goods_title u-font-30 u-line-2">
									{{item.goodstitle}}
								</view>
								<view class="site u-font-30">{{item.goods_site}}</view>
								<view class="goods_bottom">
									<view class="goods_price u-font-bold u-font-34">￥{{item.price}}</view>
									<view class="car">
										<image class="car_img" src="../../static/index/goods/shop_car.png" mode="widthFix"></image>
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>
			</view>
			<view class="text_1" v-if="tabCurrentIndex==3">
				<view class="foot_block">
					<view class="foot_box" v-for="(item,index) in footList" v-if="item.type===3" :key="index">
						<view class="foot_goods">
							<view class="foot_goods_left">
								<image class="goods_img" :src="item.goodsimg" mode="widthFix"></image>
							</view>
							<view class="foot_goods_right">
								<view class="goods_title u-font-30 u-line-2">
									{{item.goodstitle}}
								</view>
								<view class="site u-font-30">{{item.goods_site}}</view>
								<view class="goods_bottom">
									<view class="goods_price u-font-bold u-font-34">￥{{item.price}}</view>
									<view class="car">
										<image class="car_img" src="../../static/index/goods/shop_car.png" mode="widthFix"></image>
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>
			</view> 
			 <view class="text_1" v-if="tabCurrentIndex==4">
				<view class="foot_block">
					<view class="foot_box" v-for="(item,index) in footList" v-if="item.type===4" :key="index">
						<view class="foot_goods">
							<view class="foot_goods_left">
								<image class="goods_img" :src="item.goodsimg" mode="widthFix"></image>
							</view>
							<view class="foot_goods_right">
								<view class="goods_title u-font-30 u-line-2">
									{{item.goodstitle}}
								</view>
								<view class="site u-font-30">{{item.goods_site}}</view>
								<view class="goods_bottom">
									<view class="goods_price u-font-bold u-font-34">￥{{item.price}}</view>
									<view class="car">
										<image class="car_img" src="../../static/index/goods/shop_car.png" mode="widthFix"></image>
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>
			</view> -->
		</view>
	</view>
</template>

<script>
	import {
		vuexData
	} from '@/common/commonMixin.js'
	export default {
		mixins: {
			vuexData
		},
		data() {
			return {
				num: {
					page: '2',
					limit: ''
				},
				tabCurrentIndex: 0,
				istrue: 0,
				navList: [{
						text: '全部'
					},
					{
						text: '白酒'
					},
					{
						text: '红酒'
					},
					{
						text: '洋酒'
					},
					{
						text: '下酒菜'
					}
				],
				// type:1白酒,type:2红酒,type:3洋酒,type:4下酒菜
				footList: [
					// {
					// 	type:1,
					// 	goodsimg:'../../static/index/goods/area_1.png',
					// 	goodstitle:'澳洲原瓶原装进口袋王白酒D1298干红葡萄酒白酒礼盒顺丰包邮',
					// 	goods_site:'增城站',
					// 	price:'436.00'
					// },
					// {
					// 	type:2,
					// 	goodsimg:'../../static/index/goods/area_1.png',
					// 	goodstitle:'澳洲原瓶原装进口袋王红酒D1298干红葡萄酒红酒礼盒顺丰包邮',
					// 	goods_site:'增城站',
					// 	price:'436.00'
					// },
					// {
					// 	type:3,
					// 	goodsimg:'../../static/index/goods/area_1.png',
					// 	goodstitle:'澳洲原瓶原装进口袋王洋酒D1298干红葡萄酒洋酒礼盒顺丰包邮',
					// 	goods_site:'增城站',
					// 	price:'436.00'
					// },
					// {
					// 	type:4,
					// 	goodsimg:'../../static/index/goods/area_1.png',
					// 	goodstitle:'澳洲原瓶原装进口袋王下酒菜D1298干红葡萄酒下酒菜礼盒顺丰包邮',
					// 	goods_site:'增城站',
					// 	price:'436.00'
					// }
				]
			}
		},
		async onLoad(options) {
			// 页面显示是默认选中第一个
			this.tabCurrentIndex = 0;
			uni.showLoading({
				title: '加载中....'
			})
			let userCollect = await this.$api.getCollectList('?page=0&limit=10&type=0', this.tabCurrentIndex)
			if (userCollect.status == 200) {
				console.log("获取成功")
			} else {
				console.log("获取数据失败")
			}
			let {
				data
			} = userCollect
			this.footList = data
			uni.hideLoading()

		},
		methods: {
			//顶部tab点击
			tabClick(index) {
				var _this = this
				_this.tabCurrentIndex = index
				_this.istrue = index
			},
			// 点击添加购物车
			addCart() {
				// let addcart = this.$api.addCart(
				// 	this.footList
				// )
			}
		}
	}
</script>

<style>
	page {
		width: 100%;
		height: 100%;
	}

	.content {
		padding-bottom: 100rpx;
	}

	.active {
		width: 60%;
		text-align: center;
		color: #ff0500 !important;
		font-size: 30rpx;
		border-bottom: solid #ff0500 4px;
		padding-bottom: 8rpx;
		font-weight: bold;
	}

	.navbar {
		display: flex;
		display: inline-flex;
		width: 100%;
		padding-bottom: 30rpx;
		border-bottom: #eaeaea 8rpx solid;
	}

	.navbar .nav-item {
		display: flex;
		flex: 1;
		justify-content: center;
		background: #fff;
		font-size: 30rpx;
		color: #000;
		padding-top: 10rpx;
	}



	.foot_box {
		padding: 30rpx 20rpx 30rpx 20rpx;
		border-bottom: #eaeaea 1px solid;
	}

	/* .foot_time{
		padding-bottom: 40rpx;
	} */
	.foot_goods {
		display: flex;
		flex-direction: row;
	}

	.foot_goods_right {
		display: flex;
		flex-direction: column;
		padding-top: 8rpx;
	}

	.goods_bottom {
		flex: 1;
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: flex-end;
	}

	.site {
		padding-top: 30rpx;
		color: #999;
	}

	.goods_price {
		color: #ff0500;
		padding-bottom: 10rpx;
	}

	.goods_img {
		/* width: 100%;
		height: 100%; */
		width: 250rpx;
		height: 250rpx;
		margin-right: 10rpx;
	}

	.car_img {
		width: 54rpx;
		height: 54rpx;
	}
</style>
