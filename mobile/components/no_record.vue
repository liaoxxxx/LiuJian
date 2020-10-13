<template>
	<view class="flex w-100 align-center flex-column justify-center">
		<image :src="img ? img : imgList[imgIndex]" :style="imgSize" mode=""></image>
		<text class="font text-grey">{{ comtentText }}</text>
	</view>
</template>

<script>
	export default {
		props: {
			img: { // 图片路径
				type: String
			},
			imgIndex: { // 图片序号
				type: Number,
				default: 1
			},
			imgHeight: { // 图片高度
				type: Number,
				default: 400
			},
			imgWidth: { // 图片宽度
				type: Number,
				default: 400	
			},
			comtentText: { // 提示文字
				type: String,
				default: '暂无记录...'
			}
		},
		data() {
			return {
				imgList: [
					'/static/search/no_record1.svg',
					'/static/search/no_record2.svg',
					'/static/search/no_record3.svg',
				]
			}
		},
		computed: {
			imgSize() {
				return `height: ${this.imgHeight}rpx; width: ${this.imgWidth}rpx;`
			}
		}
	}
</script>

<style>

</style>
