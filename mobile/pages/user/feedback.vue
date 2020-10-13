<template>
	<!-- 我要反馈界面 -->
	<view class="content">
		<view class="backlist">
			<view>反馈类型</view>
			<view class="backlist_right">
				<image class="kefu_img" src="../../static/user/kefu.png" mode="widthFix"></image>
				<view>联系客服</view>
			</view>
		</view>
		<view class="backnavbar">
			<view :class="{'active':isActive==1}" class="backnavbar_item" @click="change(1)">BUG</view>
			<view :class="{'active':isActive==2}" class="backnavbar_item" @click="change(2)">建议</view>
			<view :class="{'active':isActive==3}" class="backnavbar_item" @click="change(3)">吐槽</view>
			<view :class="{'active':isActive==4}" class="backnavbar_item" @click="change(4)">其他</view>
		</view>
		
		<view class="backlist">
			<view>反馈内容(必填)</view>
		</view>
		<view class="backcontent">
			<textarea placeholder-style="color:#afafaf;font-size:28rpx" placeholder="请填写描述,以便我们跟好的为你们提供帮助~"/>
			<view class="camera">
				<image class="camera_img" src="../../static/user/camera.png" mode="widthFix"></image>
			</view>
		</view>
		
		<view class="backlist">
			<view>联系方式</view>
		</view>
		<view class="inputlist">
			<input type="text" placeholder-style="color:#afafaf;font-size:28rpx;" placeholder="请留下你您的邮箱或者电话,方便我们联系您哦~" />
		</view>
		
		<view class="choosebox">
			<radio :checked="bianhao==false" value="1" @click="radio(1)"></radio>匿名反馈
		</view>
		
		<button>提交</button>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				isActive: 1,
				bianhao:false
			}
		},
		onLoad() {

		},
		methods: {
			change(type) {
				this.isActive = type
			},
			radio(e){
				this.bianhao = !this.bianhao;
			}
		}
	}
</script>

<style>
	page{
		width: 100%;
		height: 100%;
		background: #f5f5f5;
	}
	.kefu_img{
		width: 44rpx;
		height: 44rpx;
		margin-right: 10rpx;
	}
	.backlist{
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
		height: 100rpx;
		/* background: #07C160; */
		font-size: 30rpx;
		padding: 0 30rpx;
	}
	.inputlist{
		height: 100rpx;
		background: #fff;
		padding: 0 30rpx;
	}
	.backlist_right{
		display: flex;
		flex-direction: row;
		align-items: center;
		color: #f94e2f;
	}
	.backnavbar{
		display: flex;
		flex-direction: row;
		background: #fff;
		padding: 36rpx 15rpx;
	}
	.backnavbar_item{
		background: #f8f8f8;
		color: #999;
		height: 66rpx;
		line-height: 66rpx;
		font-size: 26rpx;
		text-align: center;
		border: solid #999 1px;
		border-radius: 10rpx;
		flex: 1;
		margin: 15rpx;
	}
	.active{
		color: #f94e2f;
		border: solid #f94e2f 1px;
	}
	
	
	.backcontent{
		padding: 30rpx;
		background: #fff;
	}
	textarea{
		width: 100%;
		color:#646464;
		font-size:28rpx;
		height: 150rpx;
	}
	input{
		width: 100%;
		color:#646464;
		font-size:28rpx;
		height: 100rpx;
	}
	.camera{
		margin-top: 15rpx;
		border: dashed #999 1rpx;
		width: 180rpx;
		height: 180rpx;
		text-align: center;
		line-height: 200rpx;
		border-radius: 15rpx;
	}
	.camera_img{
		width: 50rpx;
		height: 50rpx;
	}
	
	
	.choosebox{
		font-size: 30rpx;
		padding: 30rpx;
		display: flex;
		flex-direction: row;
		align-items: center;
	}
	
	button::after{
		border: none;
		border-radius: 0;
	}
	button{
		background: linear-gradient(to right,#ff4b2d,#fd4341);
		border-radius: 0;
		color: #fff;
		font-size: 34rpx;
		position: absolute;
		bottom: 0;
		width: 100%;
	}
</style>
