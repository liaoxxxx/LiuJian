
<template>
	<!-- 我的足迹界面 -->
	<view class="content">
		<!-- 	<view class="navbar">
			<view v-for="(item, index) in navList" :key="index"  class="nav-item" :class="{ current: tabCurrentIndex === index }" @tap="tabClick(index)">
				<view class="first_tabar" :class="{active:istrue==index}">
				{{ item.text }}
				</view>
			</view>
		</view> -->

		<!-- 显示区域 -->
		<view class="list">
			<view class="foot_block">
				<view class="foot_box" v-for="item in showList" :key="item.id" @tap="toDetail(item.id)">
					<view class="foot_goods">
						<view class="foot_goods_left">
							<image class="goods_img" :src="item.image" mode="widthFix"></image>
						</view>
						<view class="foot_goods_right">
							<view class="goods_title u-font-30 u-line-2">
								{{item.store_name}}
							</view>
							<view class="site u-font-30">库存:{{item.sales}}</view>
							<view class="goods_bottom">
								<view class="goods_price u-font-bold u-font-34">￥{{item.price}}</view>
							</view>
						</view>
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
	export default {
		mixins: [vuexData],
		data() {
			return {
				showList: [],
				tabCurrentIndex: 0,
				istrue: 0,
				footList: []
			}
		},
		async onLoad(options) {
			// 页面显示是默认选中第一个
			this.tabCurrentIndex = 0;
			uni.showLoading({
				title: '加载中...'
			})
			let addfood = await this.$api.getUserFootList('?page=0&limit=10')
			let {
				data
			} = addfood
			this.showList = data
			uni.hideLoading()
		},
		methods: {
			//顶部tab点击
			// tabClick(index) {
			// 	var _this = this
			// 	_this.tabCurrentIndex = index
			// 	_this.istrue = index
			// },
			/**
			 * @author				前端小能手
			 * toDetail				查看商品详情
			 */
			toDetail(id) {
				uni.navigateTo({
					url: `/pages/index/goods_info?id=${id}`
				})
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

	.foot_time {
		padding-bottom: 40rpx;
	}

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
