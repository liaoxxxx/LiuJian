<template>
	<view class="goods_box" @tap="$emit('goDetail')">
		<view class="goods_box_right">
				<view class="goods_info_list" v-for="(item_2,index) in goodsinfo" :key="index">
					{{item_2}}
				</view>
    </view>
	</view>
</template>

<script>

	import {
		vuexData
	} from '@/common/commonMixin.js'
	export default {
		components: {
		},
		mixins: [vuexData],
		data() {
			return {
				proId: '',
			}
		},
		props: {
			goodsinfo: {
				type: Array,
				default: () => {
					return ['没啥', '真没啥']
				}
			},
		},
		computed: {

		},
		methods: {

		}
	}
</script>

<style scoped>
	.goods_box {
		display: flex;
		flex-direction: row;
		background: #fff;
		margin: 0rpx 15rpx 15rpx 15rpx;
		padding: 10rpx 15rpx 10rpx 0rpx;
		border-radius: 10rpx;
	}

	.goods_box_left {
		flex: 1;
		margin-right: 15rpx;
	}

	.goods_box_right {
		flex: 2;
	}

	.goods_info {
		display: flex;
		flex-direction: row;
		align-items: center;
	}

	.goods_lable {
		display: flex;
		flex-direction: row;
		align-items: center;
		margin-top: 10rpx;
	}

	.goods_price {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
		margin-top: 15rpx;
		color: #ff0409;
		font-size: 32rpx;
		font-weight: bold;
	}

	.goods_title {
		font-size: 28rpx;
		padding-top: 10rpx;
	}

	.goods_info_list {
		font-size: 28rpx;
		color: #9e9e9e;
		padding-right: 20rpx;
		border-right: #9e9e9e solid 4rpx;
	}

	.goods_lable_list {
		font-size: 20rpx;
		border: solid #a46a59 1px;
		margin-right: 15rpx;
		height: 34rpx;
		line-height: 34rpx;
		padding: 0 20rpx 4rpx 20rpx;
		border-radius: 10rpx;
	}

	.goods_lable_list_2 {
		font-size: 20rpx;
		border: solid #bd2626 1px;
		margin-right: 15rpx;
		height: 34rpx;
		line-height: 34rpx;
		padding: 0 20rpx 4rpx 20rpx;
		border-radius: 10rpx;
	}

	.goods_img {
		width: 100%;
		height: 100%;
	}

	.car_img {
		width: 50rpx;
		height: 50rpx;
	}

	.my_active {
		opacity: .3;
	}
</style>
