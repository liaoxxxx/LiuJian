<template>
	<!-- 设置密码界面 -->
	<view class="content">
		<view class="slogan_2">请输入验证码</view>
		<view class="tishi_2">8~20个字符，不可以是纯数字</view>
		<view class="input_box_right">
			<input v-model="password" :type="type" @input="getphoneValue" class="el-input" placeholder="请输入密码" placeholder-style="border:none;outline:none;font-size:28rpx;padding-left:2rpx;color:#b5b5b5" />
			<view v-if="showpass==true">
				<image class="cha_img" @tap="onClick" src="../../static/user/cha.png" mode="widthFix"></image>
				<image class="cha_img" @tap="changeImg" :src="eye" mode="widthFix"></image>
			</view>
		</view>
		
		<view class="slogan_2">推荐码<text class="tishi_2" style="margin-left: 20rpx;">选填</text></view>
		<view class="input_box_right">
			<input v-model="inviteCode" type="text" @input="getphoneValue" placeholder="请输入推荐码" placeholder-style="font-size:28rpx;padding-left:2rpx;color:#b5b5b5" />
		</view>
		<button @click="getcomplete" :class="btnshow == false ? 'btn' : 'btn_2'">完成</button>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				timer: null,
				phone:'',//电话
				code:'',//验证码
				password:'',//密码
				inviteCode:'',//推荐人
				btnshow:false,
				showpass:false,
				type: 'password',
				eye: require("../../static/user/hide.png"),
				togggle: [
					require("../../static/user/show.png"),
					require("../../static/user/hide.png")
				],
				hide: true,
			}
		},
		onLoad: function (option) { //option为object类型，会序列化上个页面传递的参数 
	 		console.log(option.phone); //打印出上个页面传递的参数。
		 	console.log(option.code);//打印出上个页面传递的参数。
			this.phone = option.phone;
			this.code = option.code;
	 	},
		methods: {
			timeUp() {
				clearInterval(this.timer)
			},
			//清除输入
			onClick: function(password) {
				this.password = '';
				this.btnshow = false;
				this.showpass = false
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
			// 键盘触发
			getphoneValue(){
				if(this.password != '' && this.inviteCode != ''){
					this.btnshow = true
				}else{
					this.btnshow = false
				}
				if(this.password.length>=1){
					this.showpass = true
				}else{
					this.showpass = false
				}
			},
			//完成提交
			getcomplete(){
				var that = this
				if (that.password === '') {
					uni.showToast({
						title: '密码必须填写',
						icon: 'none',
						duration: 2000
					})
					return
				}
				if (that.inviteCode === '') {
					uni.showToast({
						title: '推荐码必须填写',
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
	.slogan_2{
		font-size: 46rpx;
		font-weight: bold;
		padding-top: 70rpx;
		padding-right: 20rpx;
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
		margin-top: 80rpx;
		border-radius: 15rpx;
		padding: 0 30rpx;
	}
	.el-input {
		flex: 1;
		outline:none;
		border: none;
		background: none;
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
