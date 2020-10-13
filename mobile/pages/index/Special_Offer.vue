<template>
	<!-- 特价专区页面 -->
<!-- 	<view class="content">
		<view>
			<image class="top_img" src="../../static/index/banner/zhuanqu.png" mode="widthFix"></image>
		</view>
		
		<view class="special_list">
			<view class="special_box" v-for="(item, index) in dataList" :key="index">
				<view class="list_img">
					<image src="../../static/index/goods/area_1.png" mode="widthFix"></image>
				</view>
				<view class="list_name u-line-1">{{item.goodsname}}</view>
				<view class="good_input">
					<view class="Specifications u-line-1">{{item.goods_kind}}</view>
					<image class="shop_car" src="../../static/index/goods/shop_car.png" mode="widthFix"></image>
				</view>
				<view class="original">原价：{{item.first_price}}元</view>
				<view class="new_price">新人专享价：<text class="new_price_big">{{item.new_price}}元</text></view>
			</view>
		</view>
	</view> -->
</template>

<script>
	export default {
		data() {
			return {
				dataList:[
					{goodsname:'卡尔斯特领地',goods_kind:'700ml*12瓶',first_price:'499',new_price:'299'},
					{goodsname:'卡尔斯特领地',goods_kind:'700ml*12瓶',first_price:'499',new_price:'299'},
					{goodsname:'卡尔斯特领地',goods_kind:'700ml*12瓶',first_price:'499',new_price:'299'},
				]
			}
		},
		onLoad() {

		},
		methods: {

		}
	}
</script>

<style>
	page{
		width: 100%;
		height: 100%;
		background: #ad5cf2;
	}
	.top_img{
		width: 100%;
	}
	
	
	
	.special_list{
		padding: 20rpx 30rpx 30rpx 30rpx;
		width: 100%;
		display: flex;
		flex-wrap: wrap;
		box-sizing: border-box;
	}
	.special_box{
		width: 48%;
		margin-right: 20rpx;
		padding: 10rpx 15rpx 20rpx 15rpx;
		box-sizing: border-box;
		border-radius: 20rpx;
		margin-bottom: 20rpx;
		background: #fff;
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
		width: 250rpx;
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
	.original{
		font-size: 26rpx;
		color: #ff8c4a;
		text-decoration: line-through;
	}
	.new_price{
		font-size: 28rpx;
		color: #fe0101;
		font-weight: bold;
	}
	.new_price_big{
		font-size: 34rpx;
	}
</style>
