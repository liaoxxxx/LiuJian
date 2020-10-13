<template>
	<view @tap="$emit('loadTap')" class="flex align-center bg-hover-light justify-center py-2" :class="`bg-`+bgColor">
		<view ref="rotate" class="flex justify-center loading align-center mr-2" v-if="isLoading === 0">
			<image src="/static/image/loading.png" style="height: 40rpx; width: 40rpx;" mode=""></image>
		</view>
		<text class="font" :class="`text-`+color">{{text}}</text>
	</view>
</template>

<script>
	// #ifdef APP-PLUS-NVUE
	const animation = weex.requireModule('animation')
	// #endif 
	export default {
		props: {
			isLoading: {
				default: 1
			},
			loadingText: {
				type: String,
				default: '加载中...'
			},
			notDataText: {
				type: String,
				default: '没有更多订单了～～'
			},
			upwardText: {
				type: String,
				default: '上拉加载更多'
			},
			color: {
				type: String,
				default: 'muted'
			},
			bgColor: {
				type: String,
				default: 'grey'
			}
		},
		methods: {
			// #ifdef APP-PLUS-NVUE
			rotate(ref, num) {
				this.$nextTick(() => {
					animation.transition(this.$refs[ref], {
						styles: {
							transform: 'rotateZ(360deg)',
						},
						duration: 1200,
						timingFunction: 'ease',
					}, () => {
						this.rotate('rotate')
					})
				})
			}
			// #endif
		},
		computed:{
			text() {
				if (this.isLoading == 0) {
					return this.loadingText
				} else if (this.isLoading == 1) {
					return this.upwardText
				}else {
					return this.notDataText
				}
			}
		},
		watch: {
			isLoading() {
				
				console.log('bianhua ------', this.isLoading)
			}
		},
		mounted() {
			// #ifdef APP-PLUS-NVUE
			this.rotate('rotate')
			// #endif
		}
	}
</script>

<style scoped>
	.loading {
		transform: rotateZ(0deg);
		/* #ifndef APP-PLUS-NVUE */
		animation-duration: 1300ms;
		animation-name: loading;
		animation-iteration-count: infinite;
		/* #endif */
	}

	/* #ifndef APP-PLUS-NVUE */
	@keyframes loading {
		from {
			transform: rotateZ(0deg)
		}

		to {
			transform: rotateZ(360deg);
		}
	}
	/* #endif */
</style>
