<template>
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
</template>

<script>
	export default {
		props: {
			goodsDetail: {
				type: Object,
				required: true
			},
			tempPro: {
				type: Array,
				required: true
			}
		},
		data() {
			return {
				proId: ''
			}
		},
		methods: {
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
			}
		},
		computed: {
			selectedPro() {
				if (this.productAttr.length > 0 && this.proId != '') {
					return this.productAttr.filter(item => item.unique == this.proId)[0]
				}
				return ''
			}
		}
	}
</script>

<style>
</style>
