<style lang="scss">
    @import "uview-ui/index.scss";
</style>
<script>
	export default {
		onLaunch: function() {
			console.log('App Launch')
			uni.onTabBarMidButtonTap(res => {
				uni.navigateTo({
					url: "/pages/drink/drink",
					fail(err) {
						console.log(err)
					}
				})
			})
		},
		onShow: function() {
			console.log('App Show')
		},
		onHide: function() {
			console.log('App Hide')
		}

	}
</script>

<style>
	/*每个页面公共css */
	@import "@/common/common.css";
	/* #ifndef APP-PLUS-NVUE */
	@import "@/common/iconfont.css";
	.u-font-bold {
		font-weight: bold;
	}

	.u-font-40 {
		font-size: 40rpx;
	}

	.u-font-36 {
		font-size: 36rpx;
	}

	.u-font-34 {
		font-size: 34rpx;
	}

	.u-font-32 {
		font-size: 32rpx;
	}

	.u-font-30 {
		font-size: 30rpx;
	}

	.u-font-28 {
		font-size: 28rpx;
	}

	.u-font-26 {
		font-size: 26rpx;
	}

	.u-font-22 {
		font-size: 22rpx;
	}

	.u-line-1 {
		text-overflow: -o-ellipsis-lastline;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 1;
		line-clamp: 1;
		-webkit-box-orient: vertical;
	}

	.u-line-2 {
		overflow: hidden;
		word-break: break-all;
		text-overflow: ellipsis;
		display: -webkit-box;
		/* 弹性伸缩盒 */
		-webkit-line-clamp: 2;
		line-clamp: 2;
		-webkit-box-orient: vertical;
		/* 设置伸缩盒子元素排列方式 */
	}

	/* #endif */
</style>
