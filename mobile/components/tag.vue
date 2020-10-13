<template>
	<view :hover-class="hover ? 'hover-active' : '' " class="flex py px-1 align-center justify-center" @tap.stop="$emit('handleTap')" :class="[`rounded-${rounded}`, bold ? 'font-weight-bolder' : '', border ? 'border' : '']" :style="bgStyle">
		<text class="flex" :style="fontStyle"> {{content}} </text>
	</view>
</template>

<script>
	export default {
		props: {
			content: {
				type: [String, Number],
			},
			bgColor: {
				type: String,
				default: '#f40'
			},
			color: {
				type: String,
				default: '#fafefc'
			},
			size: {
				type: Number,
				default: 20
			},
			rounded: {
				type: String,
				default: 'none'
			},
			bold: {
				type: Boolean,
				default: false
			},
			border: {
				type: Boolean,
				default: false
			},
			hover: {
				type: Boolean,
				default: false
			},
			py: {
				type: Number
			},
			px: {
				type: Number
			}
		},
		computed: {
			bgStyle() {
				return `background-color: ${this.bgColor}; padding: ${this.py ? this.py : this.size/2}rpx ${ this.px ? this.px : this.size/1.4}rpx;`
			},
			fontStyle() {
				return `color: ${this.color}; font-size: ${this.size}rpx;`
			}
		}
	}
</script>

<style scoped>
	.hover-active {
		opacity: .4;
	}
</style>
