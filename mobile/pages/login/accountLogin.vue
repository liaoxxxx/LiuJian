<template>
	<!-- 账号密码登录界面 -->
	<view class="content">
		<view class="slogan">账号密码登录</view>
		
		<view class="input_box_right">
			<input v-model="phone" type="text" :maxlength="11" @input="getphoneValue" class="el-input" placeholder="请输入手机号码" placeholder-style="font-size:28rpx;padding-left:2rpx;color:#646464" />
			<view v-if="showphone==true">
				<image class="cha_img" @tap="onClick_2" src="../../static/user/cha.png" mode="widthFix"></image>
			</view>
		</view>
		
		<view class="input_box_right">
			<input v-model="password" :type="type" @input="getphoneValue" class="el-input" placeholder="请输入密码" placeholder-style="border:none;outline:none;font-size:28rpx;padding-left:2rpx;color:#b5b5b5" />
			<view v-if="showpass==true">
				<image class="cha_img" @tap="onClick" src="../../static/user/cha.png" mode="widthFix"></image>
				<image class="cha_img" @tap="changeImg" :src="eye" mode="widthFix"></image>
			</view>
		</view>
		<view class="passLogin">
			<view @click="openlogin" :class="btnshow == false ? 'codeLogin' : 'codeLogin_2'">
				验证码登录
			</view>
			<view @click="openforgetPass" class="forget">
				忘记密码？
			</view>
		</view>
		
		<button @click="getcomplete" :class="btnshow == false ? 'btn' : 'btn_2'">登录</button>
	
	
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
</template>

<script>
	export default {
		data() {
			return {
				timer: null,//定时器参数
				password:'',
				phone:'',
				btnshow:false,
				showpass:false,
				showphone:false,
				type: 'password',
				eye: require("../../static/user/hide.png"),
				togggle: [
					require("../../static/user/show.png"),
					require("../../static/user/hide.png")
				],
				hide: true,
			}
		},
		onLoad() {

		},
		methods: {
			//清除定时器
			timeUp() {
				clearInterval(this.timer)
			},
			onClick: function(password) {
				this.password = '';
				this.btnshow = false;
				this.showpass = false
			},
			onClick_2: function(phone) {
				this.phone = '';
				this.btnshow = false;
				this.showphone = false
			},
			changeImg() {
				this.hide = !this.hide
				if (this.hide) {
					this.type = 'password'
				} else {
					this.type = 'text'
				}
				let temp = ''
				this.togggle.forEach(item => {
					if (item != this.eye) {
						temp = item
					}
				})
				this.eye = temp
			},
			getphoneValue(){
				if(this.password != '' && this.phone != ''){
					this.btnshow = true
				}else{
					this.btnshow = false
				}
				if(this.password.length>=1){
					this.showpass = true
				}else{
					this.showpass = false
				}
				if(this.phone.length>=1){
					this.showphone = true
				}else{
					this.showphone = false
				}
			},
			//返回登录界面
			openlogin(){
				uni.navigateTo({
					url:'login'
				})
			},
			openforgetPass(){
				uni.navigateTo({
					url:'forgetPass'
				})
			},
			//登录提交
			getcomplete(){
				var that = this
				if (that.phone === '') {
					uni.showToast({
						title: '请输入电话号码',
						icon: 'none',
						duration: 2000
					})
					return
				}
				if (/^1[3-9]\d{9}$/.test(that.phone)) {} else {
					uni.showToast({
						title: '请输入正确的手机号码',
						icon: 'none',
						duration: 2000
					})
					return
				}
				if (that.password === '') {
					uni.showToast({
						title: '请输入密码',
						icon: 'none',
						duration: 2000
					})
					return
				}
				if(!(/(?!^[0-9]+$)^.{8,20}$/.test(that.password))){ 
					uni.showToast({
						title: '密码8-20个字不能纯数字',
						icon: 'none',
						duration: 2000
					})
					return
				}
				this.login()
			},
			async login() {
				let res = await this.$api.login({account: this.phone, password: this.password})
				if(res.msg == '账号或密码错误') {
					uni.showModal({
						title: '测试号',
						content: '账号： 18275719628 ； 密码： jack1314'
					})
				} else {
					let {token} = res.data
					uni.setStorageSync('token', token)
					uni.uni.switchTab({
						url: '/pages/index/index'
					})
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
	.slogan{
		font-size: 46rpx;
		font-weight: bold;
		padding-bottom: 50rpx;
	}
	.tishi_2{
		font-size: 28rpx;
		color: #999;
		padding-top: 30rpx;
	}
	.input_box_right{
		display: flex;
		flex-direction: row;
		align-items: center;
		height: 90rpx;
		background: #f8f8f8;
		margin-top: 40rpx;
		border-radius: 15rpx;
		padding: 0 30rpx;
	}
	.el-input {
		flex: 1;
		outline:none;
		border: none;
		background: none;
		font-size:28rpx;
		color:#666
	}
	.cha_img{
		width: 46rpx;
		height: 46rpx;
		padding: 0 6rpx;
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
		margin-top: 80rpx;
	}
	.btn_2{
		background: #fe492f;
		color: #fefefe;
		font-size: 28rpx;
		height: 90rpx;
		line-height: 90rpx;
		margin-top: 80rpx;
	}
	
	
	
	.passLogin{
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
		font-size: 28rpx;
		color: #b5b5b5;
		padding: 30rpx 0;
	}
	.codeLogin{
		color: #4688d9;
	}
	.codeLogin_2{
		color: #fe492f;
	}
	
	
	
	.bottom_top{
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: center;
		padding-top: 200rpx;
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
		position: absolute;
		bottom: 5%;
		margin-left: -30rpx;
	}
</style>
