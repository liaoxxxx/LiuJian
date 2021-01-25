<template>
	<view class="footer"  v-if="isShow" style="border-top: solid #ccc 1rpx;background: #fff;">
		<view :class="'footer_item ' + (item.size=='big'?'big_item':'')  " v-for="(item,index) in tabBar.list" :key="index"
		 @tap="switchTo" :data-url="item.pagePath">
			<view class="item-img-wrap">
				<image class="item-img" :src="'/'+ (currentRoute === item.pagePath ? item.selectedIconPath : item.iconPath)" alt=""></image>
				<view class="item-badge" v-if="item.badge">{{item.badge}}</view>
			</view>
			<span class="item-label" :style="{'color':(currentRoute === item.pagePath ? tabBar.selectedColor : tabBar.color)}">{{item.text}}</span>
		</view>
	</view>
</template>

<script>
	import Vue from 'vue';
	let vm = new Vue();

	export default {
		name: "footer-tabbar",
		props: {
			tabBar: {
				type: [Array, Object],
				default: () => {
					return vm.$store.state.tabbar.tabBar;
				}
			}
		},
		data() {
			return {
				isShow: true,
				currentRoute: vm.$util.getCurrentRoute(),
			};
		},
		computed: {},
		mounted() {},
		methods: {
			switchTo(e) {
				let url = e.currentTarget.dataset.url;
				console.log(url)
				uni.reLaunch({
					url: '/' + url
				});
			}
		},
	}
</script>

<style>
	* {
		box-sizing: border-box;
	}

	.footer {
		height: 120rpx;
		position: fixed;
		bottom: 0;
		left: 0;
		width: 100%;
		background-color: none;
		color: #fff;
		z-index: 999;
		
	}

	.footer_item {
		float: left;
		width: 25%;
		text-align: center;
		font-size: 26rpx;
		height: 120rpx;
		font-weight: 600;
	}

	.footer_item image {
		width: 64rpx;
		height: 64rpx;
	
	}
	.footer_item {
		position: relative;
		top: 0rpx;
	}
	.footer_item.big_item {
		position: relative;
		top: -50rpx;
	}

	.footer_item.big_item image {
		width: 118rpx;
		height: 118rpx;
		position: relative;
		top: 0rpx;
	}
	.item-label{
		position: relative;
		top: -10rpx;
	}
</style>
