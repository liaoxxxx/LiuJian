<template>
	<view class="flex flex-column w-100 border-box content">
			<view class="flex flex-column py-5 px-5 mt-3 mb-4">
				<text class="font-lg font-weight-bolder">你好,</text>
				<text class="font-lg font-weight-bolder mb-2">请选择登陆方式</text>
				<text class="font">欢迎来到点点酒！</text>
			</view>
			<view class="flex flex-column">
				<view class="w-100 flex justify-center text-white mb-3">
					<!-- #ifdef MP-WEIXIN -->
					<button type="primary" style="height: 98rpx; width: 680rpx; background-color: #1AAD19;" class="flex justify-center align-center" open-type="getUserInfo" @getuserinfo="getUserInfo">
					 <text class="iconfont mr-1" style="font-size: 60rpx;">&#xe620;</text>
					 <text class="font-lg ml-1">微信登陆</text>
					 </button>
					<!-- #endif -->
					<!-- #ifndef MP-WEIXIN -->
					<button type="primary" style="height: 98rpx; width: 680rpx; background-color: #1AAD19;" class="flex justify-center align-center" @tap="wxLogin" >
						<text class="iconfont mr-1" style="font-size: 60rpx;">&#xe620;</text>
						<text class="font-lg ml-1">微信登陆</text>
					</button>
					<!-- #endif -->
				</view>
				<view class="w-100 flex justify-center text-dark">
					<button @tap="toPhoneLogin" class="flex justify-center align-center" style="height: 98rpx; width: 680rpx; background-color: #F0F0F0;" type="default">
						<text class="iconfont mr-1" style="font-size: 50rpx;">&#xe604;</text>
						<text class="font-lg ml-1">手机号登陆</text>
					</button>
				</view>
			</view>
	<!-- 	<view class="flex-1 flex justify-center align-center">
			<view class="position-relative flex flex-column align-center">
				<text class="iconfont" style="font-size: 120rpx; color: #07C160;">&#xe620;</text>
				<button type="default" style="opacity: 0;height: 100%;width: 100%; position: absolute; top: 0; left: 0;" open-type="getUserInfo"
				 @getuserinfo="getUserInfo"></button>
				
				
				<button @tap="wxLogin" style="opacity: 0;height: 100%;width: 100%; position: absolute; top: 0; left: 0;" type="default"></button>
				<text class="font text-grey">微信一键登陆</text>
			</view>
		</view> -->
		<!-- <view class="flex-1 flex justify-center align-center border-top" style="width: 500rpx;">
			<view class="position-relative flex flex-column align-center">
				<text class="iconfont" style="font-size: 120rpx; color: #0086F1;">&#xe6a2;</text>
				<button @tap="toPhoneLogin" style="opacity: 0;height: 100%;width: 100%; position: absolute; top: 0; left: 0;" type="default"></button>
				<text class="font text-grey">手机号登陆</text>
			</view>
		</view> -->
	</view>
</template>

<script>
	import {
		vuexData
	} from '@/common/commonMixin.js'
	export default {
		mixins: [vuexData],
		data() {
			return {

			}
		},
		methods: {
			getUserInfo(data) {
				uni.login({
					provider: 'weixin',
					success: async (res) => {
						const {
							key,
							code
						} = res
						console.log(data.detail)
						const {
							encryptedData,
							iv
						} = data.detail
						let result = await this.$api.wxLogin({
							login_type: 'routine',
							cache_key: key,
							code,
							encryptedData,
							iv
						})
						let flag = this.checkRes(result, '已登陆')
						if (flag) {
							const {
								token
							} = result.data
							uni.setStorageSync('token', token)
							uni.navigateTo({
								url: '/pages/index/index',
								fail(err) {
									console.log(err)
								}
							})
						}
					},
					fail(err) {
						uni.showModal({
							title: '提示',
							showCancel: false,
							content: err.errMsg
						})
					}
				})
			},
			// #ifdef APP-PLUS
			wxLogin() {
				uni.login({
					provider: 'weixin',
					success: function(loginRes) {
						console.log(loginRes);
					}
				});
			},
			// #endif
			toPhoneLogin() {
				uni.navigateTo({
					url: '/pages/login/login'
				})
			}
		}
	}
</script>

<style scoped>
	.content {
		height: 100vh;
	}
</style>
