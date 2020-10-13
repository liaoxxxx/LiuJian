<template>
	<view class="w-100 bg-hover-light flex align-center" :class="{'py-4': content, 'bg-grey': light}" :style="style" style="height: 20rpx;">
		<text v-if="content" class="font pl-2 text-grey" :style="fontStyle">
			{{ content }}
		</text>
	</view>
</template>

<script>
	export default {
		props: {
			content: {
				type: String
			},
			light: {
				type: Boolean,
				default: false
			},
			bgColor: {
				type: String
			},
			color: {
				type: String
			}
		},
		computed: {
			style() {
				return `background-color: ${this.bgColor};`
			},
			fontStyle() {
				return `color: ${this.color};`
			}
		}
	}
</script>

<style>
</style>
