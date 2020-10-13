<template>
	<view class="content">
		<view class="changetop">
			<input v-model="exchange_num" placeholder="输入或张贴兑换码" type="text" style="height: 98rpx;color: #666;background: #f5f5f5;font-size: 30rpx;"/>
			<button @click="submit">确定兑换</button>
		</view>
		
		<view class="change_txt">
			<view class="title">什么是兑换码</view>
			<view class="title_2">
				兑换码是酒来来或合作商家发放的优惠券兑换凭证。用户兑换成功后，
				可以在【我的-我的优惠券中查看用户对应的优惠券】
			</view>
			<view class="title">兑换码如何使用</view>
			<view class="title_2">
				兑换码由12位数字组成。兑换时请输入完成。输入完成后点击【确认兑换按钮】
				即可完成兑换
			</view>
			<view class="title">什么是兑换码</view>
			<view class="title_2">
				1.请在兑换码有效期内完成兑换，过期将无法兑换
			</view>
			<view class="title_2">
				2.一个兑换码只能兑换一次，不可重复兑换
			</view>
			<view class="title_2">
				3.兑换成功后，优惠券具体的使用规则以优惠券中展示的信息为准
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				exchange_num: '',
			}
		},
		onLoad() {

		},
		methods: {
			submit() {
				if (this.exchange_num == '') {
					uni.showToast({
						icon: 'none',
						title: '请输入或张贴兑换码'
					})
					return
				}else{
					uni.showToast({
						icon: 'none',
						title: '兑换成功'
					})
				}
			}
		}
	}
</script>

<style>
	.changetop{
		width: 80%;
		margin: 0 auto;
		padding: 70rpx 0rpx 40rpx 0rpx;
	}
	input{
		font-size: 30rpx;
		color: #979797;
		margin: 0 auto;
		text-align: center;
		border-radius: 10rpx;
	}
	button{
		margin-top: 50rpx;
		background: #fe8c4d;
		color: #fff;
		font-size: 30rpx;
		height: 100rpx;
		line-height: 100rpx;
	}
	
	.change_txt{
		padding: 0 30rpx;
	}
	.title{
		font-size: 34rpx;
		font-weight: bold;
		padding: 30rpx 0;
	}
	.title_2{
		font-size: 28rpx;
		color: #101010;
		line-height: 45rpx;
	}
</style>
