<template>
	<view class="border-bottom flex justify-between px-2 py-3 w-100 align-center box" @tap.stop="$emit('handleTap')">
		<view class="flex align-center">
			<view class="flex align-center mr-2" v-if="leftIcon">
				<text class="iconfont text-grey" style="font-size: 60rpx;" >{{icon}}</text>
			</view>
			<view class="flex flex-column">
				<view class="mb-1">
					<text class="font-md  font-weight-bolder">{{title}}</text>
				</view>
				<slot name="content">
					<view v-if="content">
						<text class="font-sm text-grey" style="width: 500rpx;">{{ content }}</text>
					</view>
				</slot>
			</view>
		</view>
		<slot name="right">
			
		</slot>
	</view>
</template>

<script>
	
	export default {
		props: {
			icon: {
				type: String,
				default: ''
			},
			leftIcon: {
				type: Boolean,
				default: true
			},
			title: {
				type: String,
				default: '我是title'
			},
			content: {
				type: String
			}
		}
	}
</script>

<style scoped>
	.box {
		/* #ifndef APP-NVUE */
		box-sizing: border-box;
		/* #endif */
	}
</style>
