<template>
	<view class="flex flex-column bg-white rounded-radius">
		<view class="p flex justify-center align-center border-box">
			<image :src="src" mode="" style="height: 360rpx; width: 320rpx;"></image>
		</view>
		<view class="flex flex-column px-1">
			<text class="font font-hide">{{name}}</text>
			<text class="font text-danger font-weight-bolder"> {{price}} </text>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {

			}
		},
		props: {
			name: {
				type: String,
				default: '我是名字'
			},
			src: {
				type: String,
				default: 'http://kaifa.crmeb.net/uploads/attach/2019/08/13/e882d4dffdca67b65a7a1e66fb209c4c.jpg'
			},
			price: {
				type: [String, Number],
				default: '99.99'
			}
		}
	}
</script>

<style scoped>
	.font-hide {
		overflow: hidden;
		word-break: break-all;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		line-clamp: 2;
		-webkit-box-orient: vertical;
	}
</style>
