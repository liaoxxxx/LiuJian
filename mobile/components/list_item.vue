<template>
	<view class="flex justify-between align-center" :style="wrapperStyle" :class="{border: border== 'border', 'border-top': border == 'top' || border == 'y', 'border-bottom': border == 'bottom' || border == 'y' }">
		<view :style="leftFontSty" class="flex-2 flex align-center">
			<slot name="left">
				<text>{{title}}</text>
			</slot>
		</view>
		<view class="flex justify-end align-center flex-5" v-if="showContent" :style="contentFontSty">
			<slot name="content">
				<text>{{content}}</text>
			</slot>
		</view>
		<slot name="right">
		</slot>

	</view>
</template>

<script>
	export default {
		props: {
			title: {
				type: String,
				default: '我是title'
			},
			content: {
				type: String,
				default: '我是内容'
			},
			border: {
				type: String,
				default: 'none'
			},
			leftFont: {
				type: Object,
				default: () => {
					return {
						fontSize: '26rpx',
						color: '#123',
						fontWeight: 400
					}
				}
			},
			showContent: {
				type: Boolean,
				default: true
			},
			contentFont: {
				type: Object,
				default: () => {
					return {
						fontSize: '26rpx',
						color: '#acacac',
						fontWeight: 400
					}
				}
			},
			wraStyle: {
				type: Object,
				default: () => {
					return {
						backgroundColor: '#fff',
						padding: '20rpx 20rpx 20rpx 20rpx'
					}
				}
			}
		},
		methods: {
			toLine(name) {
			  return name.replace(/([A-Z])/g,"-$1").toLowerCase();
			},
			objToStr(obj) {
				let styleArr = Object.keys(obj)
				let str = ''
				styleArr.forEach(item => {
					let name = this.toLine(item)
					str += name + ':' + obj[item] + ';'
				})
				return str
			}
		},
		computed: {
			wrapperStyle() {
				let str = this.objToStr(this.wraStyle)
				return str
			},
			leftFontSty() {
				let str = this.objToStr(this.leftFont)
				return str
			},
			contentFontSty() {
				let str = this.objToStr(this.contentFont)
				return str
			},
			
		}
	}
</script>

<style>
</style>
