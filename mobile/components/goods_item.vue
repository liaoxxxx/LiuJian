<template>
	<view class="goods_box" @tap="$emit('goDetail')">
		<view class="goods_box_left">
			<image class="goods_img" :src="src" :style="imgStyle"></image>
		</view>
		<view class="goods_box_right">
			<view class="goods_title u-line-2">{{name}}</view>
			<view v-if="showInfo" class="goods_info">
				<view class="goods_info_list" v-for="(item_2,index) in goodsinfo" :key="index">
					{{item_2}}
				</view>
			</view>
			<view v-if="showLable" class="goods_lable">
				<view v-for="(item_3,index) in goodslable" :key="index">
					<view :class="item_3 == '特价' ? 'goods_lable_list' : 'goods_lable_list_2' ">
						{{item_3}}
					</view>
				</view>
			</view>
			<view class="goods_price"> 
				<view class="goods_price_left">
					￥{{price}}
				</view>
				<view v-if="showCart" class="goods_price_right" hover-class="my_active" @tap.stop="$emit('addCart')">
					<image class="car_img" src="/static/index/goods/shop_car.png" mode="widthFix"></image>
				</view>
			</view>
		</view>

		<!-- // 抽屉 -->
		<!-- <drawer fixed ref="drawer" type="bottom">
			<view class=" w-100 borer-box position-absolute flex py-2 flex-column px-2 bottom-0 bg-white">
				<view v-if="goodsDetail.storeInfo" class="flex border-bottom py-1">
					<image class="mr-2" style="height: 220rpx; width: 220rpx;" :src="goodsDetail.storeInfo.image"></image>
					<view class="flex flex-column mt-3">
						<view class="text-danger align-end flex mb-1">
							<text class="font-sm">¥</text>
							<text class="font-md font-weight-bolder">{{proId == '' ? goodsDetail.storeInfo.price : selectedPro.price}}</text>
						</view>
						<view class="font text-grey mb-1">库存{{ proId == '' ? goodsDetail.storeInfo.stock : selectedPro.stock }}件</view>
						<view class="font">{{ proId == '' ? '请选择套餐类型' : '已选择： ' + selectedPro.suk}}</view>
					</view>
				</view>
				<view class="flex flex-column mb-2">
					<halving-line bg-color="#fff" content="规格选择"></halving-line>
					<view class="flex flex-wrap">
						<view class="mr-1 mb-1 " v-for="item in productAttr" :key="item.unique">
							<tag @handleTap="checkPro(item.unique)" :content="item.suk" :size="30" :color="proId == item.unique ? '#ececec' : '#333'"
							 bold :bgColor="proId == item.unique ? '#e52' : '#eee'" rounded="radius"> </tag>
						</view>
					</view>
				</view>
				<view class="mb-2">
					<my-btn content="确认" color="#fefeee" @handleTap="proAddCart" bold bgColor="#e52"></my-btn>
				</view>
			</view>
		</drawer> -->
	</view>
</template>

<script>
	import drawer from '@/components/drawer.vue'
	import {
		vuexData
	} from '@/common/commonMixin.js'
	export default {
		components: {
			drawer
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
			goodslable: {
				type: Array,
				default: () => {
					return ['特价', '满300减50', '买一送一']
				}
			},
			name: {
				type: String
			},
			price: {
				type: [String, Number]
			},
			src: {
				type: String
			},
			showCart: {
				type: Boolean,
				default: true
			},
			showLable: {
				type: Boolean,
				default: true
			},
			showInfo: {
				type: Boolean,
				default: true
			},
			size: {
				type: Number,
				default: 250
			}
		},
		computed: {
			imgStyle() {
				return `width: ${this.size}rpx; height: ${this.size}rpx`
			},
			selectedPro() {
				if (this.productAttr.length > 0 && this.proId != '') {
					return this.productAttr.filter(item => item.unique == this.proId)[0]
				}
				return ''
			}
		},
		methods: {
			// 有规格 加入购物车
			// async proAddCart() {
			// 	if (this.proId == '') {
			// 		uni.showToast({
			// 			icon: '',
			// 			title: '请选择商品规格～～'
			// 		})
			// 	} else {
			// 		let result = await this.$api.addCart({
			// 			productId: this.selectedPro.product_id,
			// 			uniqueId: this.selectedPro.unique,
			// 			cartNum: 1,
			// 			new: 0
			// 		})
			// 		this.checkRes(result, '宝贝在购物车里等着您了～～')
			// 		this.$refs.drawer.hide()
			// 	}
			// },
			// async addCart(id) {
			// 	let goodsDetail = await this.$api.getProductDetail(id)
			// 	if (goodsDetail.status != 200) {
			// 		uni.showModal({
			// 			showCancel: false,
			// 			title: '提示',
			// 			content: '数据错误请稍后重试。。。'
			// 		})
			// 		return
			// 	}
			// 	console.log('99999999999999999999999999999999')
			// 	this.clearGoods('goodsDetail')
			// 	this.getGoodsDetail(goodsDetail)
			// 	if (this.productAttr.length > 0) {
			// 		this.$refs.drawer.show()
			// 	} else {
			// 		let result = await this.$api.addCart({
			// 			productId: id,
			// 			cartNum: 1,
			// 			new: 0
			// 		})
			// 		this.checkRes(result, '宝贝在购物车里等着您了～～')
			// 	}
			// },
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
