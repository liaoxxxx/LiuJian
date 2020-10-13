<template>
	<view class="w-100 pt-2">
		<view class="px-2 mb-5">
			<view v-for="(item, index) in addressList" :class="{'mb-5': index == addressList.length - 1}" class="mb-2" :key="item.id">
				<address-item shadow :address="item" showIcon :showImg="item.is_default == 1">
					<radio @tap="checkAddress(item.id)" :checked="nowAddressKey==item.id" :value="item.id" v-if="item.is_default != 1 && form != 'forUser' "
					 slot="left" value="" />
					<view slot="right" class="flex mr-1">
						<view @tap.stop="delAddress(item.id)" hover-class="hover-success">
							<text class="iconfont text-grey" style="font-weight: 900; font-size: 40rpx;">&#xe614;</text>
						</view>
						<view @tap.stop="toSetAddress(item.id)" class="ml-2" hover-class="hover-success">
							<text class="iconfont text-grey" style="font-weight: 900; font-size: 40rpx;">&#xe610;</text>
						</view>
					</view>
				</address-item>
			</view>
			<my-btn bgColor="#F6390D" color="#fff" iconColor="#fff" @handleTap="toAddAddress" content="添加新地址" icon="icon-xiugai1"></my-btn>
		</view>
	</view>
</template>

<script>
	import addressItem from '@/components/address_item.vue'
	import {
		vuexData
	} from '@/common/commonMixin.js'
	export default {
		mixins: [vuexData],
		components: {
			addressItem
		},
		data() {
			return {
				form: ''
			}
		},
		methods: {
			checkAddress(id) {
				this.setNowAddress(id)
			},
			toSetAddress(id) {
				uni.navigateTo({
					url: '/pages/user/add_address?id=' + id
				})
			},
			toAddAddress() {
				uni.navigateTo({
					url: '/pages/user/add_address'
				})
			},
			delAddress(id) {
				uni.showModal({
					title: '提示',
					content: '确定要删除该地址吗？',
					success: async (res) => {
						if (res.confirm) {
							let result = await this.$api.delAddress({
								id: id
							})
							let res = this.checkRes(result, '地址删除成功～～')
							if (res) {
								this.addressList.forEach((item, index) => {
									if (item.id == id) {
										this.removeAddress(index)
									}
								})
							}
						} else if (res.cancel) {
							console.log('用户点击取消');
						}
					}
				})
			}
		},
		async onReady() {
			let list = await this.$api.getAddressList('?page=0&limit=10')
			this.getAddressList(list)
		},
		onLoad(option) {
			let {
				form
			} = option
			if (form == 'forUser') {
				this.form = form
			}
		}
	}
</script>

<style scoped>
	.hover-success {
		background-color: rgba(200, 200, 200, .6);
	}
</style>
