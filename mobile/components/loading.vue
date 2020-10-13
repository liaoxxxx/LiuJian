<template>
	<view class="flex justify-center">
		<text v-for="item in 6" class="iconfont font-lg" :class="{'text-info': item == index, 'text-grey': item != index}">&#xe603;</text>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				index: 0,
				timer: null
			}
		},
		created() {
			this.timer = setInterval(() => {
				if (this.index > 6) {
					this.index = 0
				}
				this.index ++
			}, 320)
		},
		beforeDestroy() {
			clearInterval(this.timer)
		}
	}
</script>

<style>
</style>
