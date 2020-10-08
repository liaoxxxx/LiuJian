<template>
	<!-- #ifdef APP-PLUS-NVUE -->
	<view class="flex py-1 w-100 nav_top align-center" :class="{'position-fixed': fixed}" style="height: 80rpx" :style="navString">
	<!-- #endif -->
	<!-- #ifndef APP-PLUS-NVUE -->
	<view class="flex py-1 w-100 nav_top align-center" :class="{'position-fixed': fixed}" style="height: 80rpx" :style="navString">
	<!-- #endif -->
		<slot name="leftBtn"></slot>
		<view class="flex flex-4 justify-center position-relative align-center px-2" v-if="showSearch">
			<text class=" iconfont font position-absolute" :style="'left: 36rpx; color:' + placeholderColor" > &#xe60b;</text>
			<input v-model="keyWord" style="height: 60rpx;" class="bg-white font px-5 flex-1 rounded-circle" @input="input" :focus="focus" :disabled="notFocus" @tap="handleTap" @focus="handleFocus" :placeholder-style="placeholderStyle" type="text" value="" :placeholder="placeholder" />
		</view>
		<view v-else class="flex flex-4 justify-center align-center">
			{{ content }}
		</view>
		<slot name="rightBtn"></slot>
	</view>
</template>

<script>
	export default {
		props: {
			placeholder: { // 占位内容
				type: String,
				default: '请输入内容'
			},
			showSearch: { // 是否现实输入框
				type: Boolean,
				default: false
			},
			content: { // 标题内容
				type: String,
				default: '我是标题'
			},
			placeholderColor: { // 展位文字颜色
				style: String,
				default: '#acacac'
			},
			bgColor: { // 背景色
				type: String,
				default: "#fff"
			},
			fixed: { // 是否固定到顶部
				type: Boolean,
				default: true
			},
			notFocus: { // 输入框不可聚焦
				type: Boolean,
				default: false
			},
			focus: { // 是否加载后聚焦
				type: Boolean,
				default: false
			},
			value: { // 输入内容
				type: [String, Number]
			},
			navTop: {// top值
				type: String
			}
		},
		data() {
			return {
				keyWord: ''
			};
		},
		methods: {
			handleFocus() {
				this.$emit('focus')
			},
			// 点击事件
			handleTap() {
				this.$emit('toSearch')
			},
			// 输入时间
			input(e) {
				console.log(e)
				if (typeof e == 'string') {
					this.$emit('input', e)
				} else {
					this.$emit('input', e.detail.value)
				}
			}
		},
		computed: {
			navString() {
				return `background-color: ${this.bgColor}; ${this.navTop ? 'top:' + this.navTop + 'px;' : ''}`
			},
			placeholderStyle() {
				return `color: ${this.placeholderColor}`
			}
		},
		watch: {
			value() {
				this.input(this.value)
				this.keyWord = this.value
			}
		}
	}
</script>

<style scoped>
.nav_top {
	/* #ifdef MP-WEIXIN */
	z-index: 1000;
	top: 0;
	/* #endif */
	/* #ifdef APP-PLUS */
	z-index: 1000;
	top: 60rpx;
	/* #endif */
}
</style>
