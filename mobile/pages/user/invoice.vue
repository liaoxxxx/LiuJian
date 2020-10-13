<template>
	<!-- 发票管理页面 -->
	<view class="content">
		<view class="navbar">
			<view v-for="(item, index) in navList" :key="index"  class="nav-item" :class="{ current: tabCurrentIndex === index }" @tap="tabClick(index)">
				<view :class="(index==tabCurrentIndex)?'active':'first_tabar'">
				{{ item.text }}
				</view>
			</view>
		</view>
		
		<view class="list">
			<view class="text_1" v-show="tabCurrentIndex===0">111</view>
			<view class="text_1" v-show="tabCurrentIndex===1">222</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				tabCurrentIndex: 0,
				istrue:0,
				navList: [
					{
						text: '电子发票',
					},
					{
						text: '纸质发票',
					}
				],
			}
		},
		onLoad(options) {
			// 页面显示是默认选中第一个
			this.tabCurrentIndex = 0;
		},
		methods: {
			//顶部tab点击
			tabClick(index) {
				this.tabCurrentIndex = index;
			},
		}
	}
</script>

<style>
	page{background: #fff;}
	
	/* 左侧选项卡 */
	.navbar{
		display: flex;
		display: inline-flex;
		background: #fff;
		height: 100upx;
		/* border-bottom: 1px solid #000000; */
		width: 100%;
	}
	.navbar .nav-item{
		display: flex;
		flex: 1;
		justify-content: center;
		padding-top: 30upx;
		font-size: 32upx;
		font-weight: bold;
	}
	.active{
		color: #ff3025;
		background-size: 90%;
		border-bottom: 3px solid #ff3025;
	}
</style>
