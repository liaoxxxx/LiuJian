<template>
	<!-- 设置密码界面 -->
	<view class="content">
		<view class="slogan_2">找回密码</view>
		<view class="tishi_2">为了保护您的账户安全，一天只能操作一次，否则账户将会被锁定无法登录</view>
		
		
		<view class="input_box">
			<view class="input_box_left">
				<view>
					<!-- 选择器 -->
					<picker @change="bindPickerChange" :value="index" :range="array">
						<view class="uni-input">{{array[index]}}</view>
					</picker>
				</view>
				<image class="xiala_img" :src="xiala" mode="widthFix"></image>
			</view>
			<view class="input_box_right">
				<input v-model="phone" @input="getphoneValue" placeholder="请输入手机号码" placeholder-style="font-size:28rpx;padding-left:2rpx;color:#999" />
			</view>
			<view class="cha_img">
				<image v-if="showphone==true" class="cha_img" @tap="onClick" src="../../static/user/cha.png" mode="widthFix"></image>
			</view>
		</view>
		
		
		<view class="input_box">
			<view class="input_box_right">
				<input v-model="code" @input="getphoneValue" placeholder="请输入短信验证码" placeholder-style="font-size:28rpx;padding-left:2rpx;color:#999" />
			</view>
			<view :class="btnshow == false ? 'input_box_btn' : 'input_box_btn_2'">
				获取验证码
			</view>
		</view>
		
		<button @click="getcomplete" :class="btnshow == false ? 'btn' : 'btn_2'">确认提交</button>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				code:'',
				btnshow:false,
				phone:'',
				array: ['+86', '+13'],
				index: 0,
				showphone:false,
				xiala:'../../static/user/xiala.png'
			}
		},
		onLoad() {

		},
		methods: {
			onClick: function(phone) {
				this.phone = '';
				this.btnshow = false;
				this.showphone = false;
			},
			getphoneValue(){
				if(this.phone != ''){
					this.btnshow = true
				}else{
					this.btnshow = false
				}
				if(this.phone.length>=1){
					this.showphone = true
				}else{
					this.showphone = false
				}
			},
			bindPickerChange: function(e) {
				console.log('picker发送选择改变，携带值为', e.target.value)
				this.index = e.target.value
			},
			//完成提交
			getcomplete(){
				var that = this
				if (that.phone === '') {
					uni.showToast({
						title: '请输入手机号码',
						icon: 'none',
						duration: 2000
					})
					return
				}
				if (/^1[3-9]\d{9}$/.test(that.phone)) {} else {
					uni.showToast({
						title: '请输入正确的手机号',
						icon: 'none',
						duration: 2000
					})
					return
				}
			}
		}
	}
</script>

<style>
	page{
		width: 100%;
		height: 100%;
	}
	.content{
		padding: 30rpx 60rpx;
	}
	.input_box_btn{
		background: #aeaeae;
		color: #fff;
		height: 90%;
		display: flex;
		flex-direction: row;
		align-items: center;
		font-size: 28rpx;
		padding: 0 20rpx;
		border-radius: 15rpx;
	}
	.input_box_btn_2{
		background: #ff372c;
		color: #fff;
		height: 90%;
		display: flex;
		flex-direction: row;
		align-items: center;
		font-size: 28rpx;
		padding: 0 20rpx;
		border-radius: 15rpx;
	}
	.slogan_2{
		font-size: 46rpx;
		font-weight: bold;
		padding-top: 70rpx;
		padding-right: 20rpx;
	}
	.tishi_2{
		font-size: 28rpx;
		color: #b5b5b5;
		padding-top: 30rpx;
		line-height: 45rpx;
	}
	.input_box{
		display: flex;
		flex-direction: row;
		align-items: center;
		height: 90rpx;
		background: #f8f8f8;
		margin-top: 80rpx;
		border-radius: 15rpx;
	}
	.input_box_left{
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: center;
		flex: 1.3;
		font-size: 30rpx;
		border-right: #b5b5b5 4rpx solid;
		margin-right: 20rpx;
	}
	.xiala_img{
		width: 22rpx;
		height: 22rpx;
		margin-left: 10rpx;
	}
	.input_box_right{
		flex: 4;
	}
	.cha_img{
		width: 46rpx;
		height: 46rpx;
		margin-right: 20rpx;
		margin-left: 4rpx;
	}
	
	
	
	button::after{
		border: none;
	}
	.btn{
		background: #dfdfdf;
		color: #fefefe;
		font-size: 28rpx;
		height: 90rpx;
		line-height: 90rpx;
		margin-top: 60rpx;
	}
	.btn_2{
		background: #fe492f;
		color: #fefefe;
		font-size: 28rpx;
		height: 90rpx;
		line-height: 90rpx;
		margin-top: 60rpx;
	}
</style>
