<template>
	<view v-if="isShow" class="flex w-100 flex-column justify-center" @tap="hide" :style="drawer" :class="{'position-fixed': fixed, 'position-absolute': !fixed, 'z-index0': !isShow, 'z-index1': isShow }" style="background-color: rgba(1,1,1, .4); z-index: 9999999;">
		<slot></slot>
	</view>
</template>

<script>
	export default {
		props: {
			type: {
				type: String,
				default: 'bottom'
			},
			fixed: {
				type: Boolean,
				default: false
			}
		},
		data() {
			return {
				bottom: 0,
				left: -750,
				windowHeight: 0,
				timer: null,
				isShow: false
			}
		},
		computed: {
			drawer() {
				switch (this.type) {
					case 'bottom':
						return `top: 0; left: 0; height: ${this.windowHeight}px; transform: translateY(${this.bottom}px);`
					case 'left':
						return `top: 0; left: 0; height: ${this.windowHeight}px; transform: translateX(${this.left}px);`
					case 'right':
						return `top: 0; left: 0; height: ${this.windowHeight}px; transform: translateY(-${this.left}px);`
				}
			}
		},
		methods: {
			animationBottom(isShow) {
				clearInterval(this.timer)
				let speed = 90
				this.timer = setInterval(() => {
					speed = speed * 0.9
					if(isShow == 'show') {
						if (speed <= 40) {
							speed = 40
						}
						this.bottom -= speed
						if (this.bottom == 0 || this.bottom < 0) {
							this.bottom = 0
							clearInterval(this.timer)
						}
					} else {
						if (speed <= 1) {
							speed = 1
						}
						this.bottom += speed
						if (this.bottom == this.windowHeight || this.bottom > this.windowHeight) {
							this.bottom = this.windowHeight
							clearInterval(this.timer)
						}
					}
				}, 16)
			},
			animationLeft(isShow) {
					
			},
			animationRight(isShow) {

			},
			moveAnimation(isShow) {
				switch (this.type) {
					case 'bottom':
						this.animationBottom(isShow);
					case 'left':
						this.animationLeft(isShow);
					case 'right':
						this.animationRight(isShow);
				}
			},
			hide() {
				this.isShow = false
				this.moveAnimation('hide')
				this.$emit('handleHide')
			},
			show() {
				this.isShow = true
				this.moveAnimation('show')
				this.$emit('handleShow')
			}
		},
		mounted() {
			this.$nextTick(() => {
				let {
					windowHeight
				} = uni.getSystemInfoSync()
				this.windowHeight = windowHeight
				this.bottom = windowHeight
			})
		}
	}
</script>

<style scoped>
	.z-index0 {
		opacity: 0;
	}
	.z-index1 {
		opacity: 1;
	}
</style>
