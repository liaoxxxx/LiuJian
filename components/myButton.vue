<template>
	<view @tap.stop="$emit('handleTap')" :style="warpStyle" hover-class="my_active" :class="{border: border, 'py-1': py == 1, 'py-2': py == 2, 'px-1': px == 1, 'px-2': px == 2, 'px-3': px == 3}" class=" flex justify-center align-center bg-grey rounded-circle">
		<text :class="size ? `font-${size}` : 'font'" :style="textStyle">{{content}}</text>
		<text v-if="icon" class="iconfont ml-2" :class="size ? `font-${size}` : 'font ' + `${icon}`" :style="iconStyle"></text>
	</view>
</template>

<script>
	export default {
		props: {
			content: {
				type: String,
				default: '我是按钮'
			},
			size: {
				type: String,
				default: ''
			},
			bgColor: {
				type: String,
				default: ''
			},
			color: {
				type: String,
				default: '#111'
			},
			bold: {
				type: Boolean,
				default: false
			},
			iconColor: {
				type: String,
				default: '#111'
			},
			icon: {
				type: String
			},
			border: {
				type: Boolean,
				default: false
			},
			px: {
				type: Number,
				default: 3
			},
			py: {
				type: Number,
				default: 2
			}
		},
		computed: {
			warpStyle() {
				return `background-color: ${this.bgColor};`
			},
			textStyle() {
				return `color: ${this.color}; font-weight: ${this.bold ? 'bold' : ''};`
			},
			iconStyle() {
				return `color: ${this.iconColor}; font-weight: ${this.bold ? 'bold' : ''};`
			}
		}
	}
</script>

<style scoped>
	.my_active {
		opacity: .6;
	}
</style>
