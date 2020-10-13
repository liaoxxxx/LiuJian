<template>
	<scroll-view scroll-x="true" class="flex w-100 py-2 pl-3" style="z-index: 888; white-space: nowrap;" :style="wrapperStyle">
		<view class="flex" style="flex-direction: row;">
			<view v-if="!idType" class="flex" v-for="(item, index) in list" :key="item.id" @tap="$emit('handleTap', index)">
				<view class="flex-column font flex px-2 justify-start font-weight-bolder align-center mr-2">
					<view class="flex justify-center" :class="maxPadding ? 'px-2' : ''">
						<text :style=" activeIndex == index ? activeText : '' ">{{ item.name }}</text>
					</view>
					<text v-if="activeIndex == index" :style="activeStyle"></text>
				</view>
			</view>
			<view v-if="idType" class="flex" v-for="item in list" :key="item.id" @tap="$emit('handleTap', item.id)">
				<view class="flex-column font flex px-2 justify-start font-weight-bolder align-center mr-2">
					<view class="flex justify-center" :class="maxPadding ? 'px-2' : ''">
						<text :style=" activeIndex == item.id ? activeText : '' ">{{ item.name }}</text>
					</view>
					<text v-if="activeIndex == item.id" :style="activeStyle"></text>
				</view>
			</view>
		</view>
	</scroll-view>
</template>

<script>
	export default {
		props: {
			list: {			// nav数据
				type: Array,
				required: true
			},
			fixed: {		// 是否fixed定位
				type: Number
			},
			activeIndex: {	// 选中的tab
				type: Number,
				default: 0
			},
			bgColor: {		// 背景色
				type: String,
				default: ''
			},
			maxPadding: {	// padding大小
				type: Boolean,
				default: false
			},
			color: {		// 文字颜色
				type: String,
				default: '#fff'
			},
			active: {		// 选中下划线样式
				type: Object,
				default: () => {
					return {
						width: '40rpx',
						height: '6rpx',
						backgroundColor: '#fff',
						borderRadius: '100rpx'
					}
				}
			},
			activeFont: {	// 选中文字样式
				type: Object,
				default: () => {
					return {}
				}
			},
			idType: {		// id模式
				type: Boolean,
				default: false
			}
		},
		methods: {
			/**
			 * @author				前端小能手
			 * 
			 * toLine				驼峰转中华线
			 * objToStr				对象变style
			 */
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
			/**
			 * @author				前端小能手
			 * 
			 * wrapperStyle			整体样式
			 * activeStyle			选中下划线样式
			 * activeText			选中文字样式
			 */
			wrapperStyle() {
				let isFixed = this.fixed ? `position: fixed; top: ${this.fixed}rpx;` : ''
				return `${isFixed}background-color: ${this.bgColor}; color: ${this.color}`
			},
			activeStyle() {
				let str = this.objToStr(this.active)
				return str
			},
			activeText() {
				let str = this.objToStr(this.activeFont)
				return str
			}
		}
	}
</script>

<style scoped>
	.avtive_flag {
		width: 40rpx;
		height: 6rpx;
		background-color: #fff;
		border-radius: 100rpx;
	}
</style>
