<template>
	<!-- 注册界面 -->
	<view class="content">
		<view>
			<view class="slogan">你好，</view>
			<view class="slogan">欢迎来到点点酒！</view>
			
			<view class="input_box bg-input-grey">
				<view class="input_box_left">
					<view>
						<!-- 选择器 -->
						<picker @change="bindPickerChange" :value="index" :range="array">
							<view class="uni-input">{{array[index]}}</view>
						</picker>
					</view>
					<image class="xiala_img" src="../../static/user/xiala.png" mode="widthFix"></image>
				</view>
				<view class="input_box_right">
					<input v-model="phone" :maxlength="11"  @input="getphoneValue" placeholder="请输入手机号码" placeholder-style="font-size:28rpx;padding-left:2rpx;color:#999" />
				</view>
				<view class="cha_img_box">
					<image v-if="showpass==true" class="cha_img" @tap="onClick" src="../../static/user/cha.png" mode="widthFix"></image>
				</view>
			</view>
			
			
			<view class="input_box">
				<view class="px-2 bg-input-grey flex align-center rounded" style="height: 90rpx;">
					<input v-model="code" @input="getphoneValue" placeholder="请输入短信验证码" placeholder-style="font-size:28rpx;padding-left:2rpx;color:#999" />
				</view>
				<view @click="getcode" class="ml-2" :class="phoneOk == false ? 'input_box_btn' : 'input_box_btn_2'">
					获取验证码
				</view>
			</view>
			
			<view class="tishi" v-if="btnshow==false">
				未注册用户登录成功后默认已注册
			</view>
			
			<button @click="setpass" :class="btnshow == false ? 'btn' : 'btn_2'">下一步</button>
			
			<view class="passLogin" @click="opensetPass">密码登录</view>
			
			
			<view class="login_bottom" v-if="btnshow==false">
				<view class="bottom_top">
					<view class="xian"></view>
					<view class="bottom_top_txt">其他登录方式</view>
					<view class="xian"></view>
				</view>
				<view class="weixin_box">
					<image class="weixin" src="../../static/user/weixin.png" mode="widthFix"></image>
				</view>
			</view>
		</view>
		
		<!-- 已发送验证码 -->
		<!-- <view v-if="fasong==true">
			<view class="slogan_2">请输入验证码</view>
			<view class="tishi_2">
				验证码已发送至 <text class="tishi_3">188 6666 9999</text>
			</view>
			<input placeholder="请输入验证码" />
			<view class="tishi_2">59秒后重新发送验证码</view>
			<button class="btn_2">登录</button>
		</view> -->
		
	</view>
</template>

<script>
	import {commonApi} from '../../api/index.js'
	export default {
		data() {
			return {
				btnshow:false,
				showpass:false,
				phone:'',
				code:'',
				array: ['+86', '+13'],
				index: 0,
				fasong:false
			}
		},
		onLoad() {
			let phone = uni.getStorageSync('user_phone')
			if (phone) {
				this.phone = phone
			}
		},
		mounted() {
			// this.getHome()
		},
		methods: {
			bindPickerChange: function(e) {
				console.log('picker发送选择改变，携带值为', e.target.value)
				this.index = e.target.value
			},
			opensetPass(){
				uni.navigateTo({
					url:'accountLogin'
				})
			},
			onClick: function(phone) {
				this.phone = '';
				this.btnshow = false;
				this.showpass = false
			},
			getphoneValue(){
				if(this.phone != '' && this.code != ''){
					this.btnshow = true
				}else{
					this.btnshow = false
				}
				if(this.phone.length>=1){
					this.showpass = true
				}else{
					this.showpass = false
				}
			},
			//获取短信验证码
			getcode(){
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
				this.getCode()
				// postIn(getregister).then((res)=>{
				// 	// this.phone = res.account
				// 	account: this.phone
				// 	captcha: this.code
				// 	console.log(this.phone)
				// 	console.log(this.captcha)
				// 	console.log(res)
				// })
				// .catch((err)=>{
				// 	console.log(err)
				// })
			},
			async getCode() {
				console.log(6466646446)
				let code = await this.$api.getVerify({phone: this.phone})
				if(code.msg == "发送失败") {
					uni.showModal({
						title: '提示',
						content: '验证码模块暂时不可用: 测试手机：18275719628； 测试验证码：123456'
					})
				}
			},
			// 进入下一步设置密码
			setpass(){
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
				if (that.code === '') {
					uni.showToast({
						title: '请输入手机验证码',
						icon: 'none',
						duration: 2000
					})
					return
				}
				this.login()
				// //电话
				// let loginphone = that.phone;
				// //验证码
				// let logincode = that.code;
				// uni.navigateTo({
				// 	url: 'set_pass?phone='+loginphone+'&code='+logincode
				// })
			},
			async login() {
				let res = await this.$api.codeLogin({phone: this.phone, captcha: this.code})
				console.log(res)
				if (res.status == 200) {
					let {token} = res.data
					uni.showToast({
						title: '登陆成功'
					})
					uni.setStorageSync('token', token)
					uni.setStorageSync('user_phone', this.phone)
					setTimeout(() => {
						// #ifdef APP-PLUS
						uni.switchTab({
							url: '/pages/index/index'
						})
						// #endif
						// #ifdef MP-WEIXIN
						uni.navigateTo({
							url: '/pages/index/index'
						})
						// #endif
					}, 200)
				} else {
					uni.showModal({
						title: '提示',
						content: '用户名或密码错误'
					})
				}
			
		
			
			}
		},
		computed: {
			phoneOk() {
				return /^1[3-9]\d{9}$/.test(this.phone)
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
	.slogan{
		font-size: 46rpx;
		font-weight: bold;
		padding-bottom: 20rpx;
	}
	.slogan_2{
		font-size: 46rpx;
		font-weight: bold;
		padding-top: 70rpx;
	}
	.input_box{
		display: flex;
		flex-direction: row;
		align-items: center;
		height: 90rpx;
		/* background: #f8f8f8; */
		margin-top: 80rpx;
		border-radius: 15rpx;
	}
	.input_box_left{
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: center;
		flex: 1.5;
		font-size: 30rpx;
		border-right: #b5b5b5 4rpx solid;
		/* margin-right: 20rpx; */
	}
	.xiala_img{
		width: 22rpx;
		height: 22rpx;
		margin-left: 10rpx;
	}
	.input_box_right{
		flex: 4;
		padding-left: 20rpx;
		margin-left: 10rpx;
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
	.cha_img_box{
		width: 46rpx;
		height: 46rpx;
		padding: 0 20rpx;
	}
	.cha_img{
		width: 100%;
		height: 100%;
	}
	.tishi{
		font-size: 26rpx;
		color: #999;
		padding: 30rpx 0rpx 0rpx 0rpx;
	}
	.tishi_2{
		font-size: 28rpx;
		color: #999;
		padding: 30rpx 0;
	}
	.tishi_3{
		color: #000;
		padding-left: 20rpx;
	}
	.passLogin{
		font-size: 28rpx;
		color: #ff372c;
		padding: 30rpx 0;
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
	
	
	.bottom_top{
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: center;
		padding-top: 200rpx;
		margin-bottom: 50rpx;
	}
	.bottom_top_txt{
		padding: 0 30rpx;
		color: #999;
		font-size: 28rpx;
	}
	.xian{
		border-bottom: #dfdfdf solid 1rpx;
		flex: 1;
	}
	.weixin_box{
		text-align: center;
	}
	.weixin{
		width: 100rpx;
		height: 100rpx;
		margin-left: -30rpx;
	}
</style>
