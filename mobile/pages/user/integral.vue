<template>
	<!-- 我的积分页面 -->
	<view class="content" id="content">
		<view class="integral_top">
			<view class="integral_top_box" style="background: url(../../static/user/jifen_bg.png)no-repeat;background-size: 100% 100%;">
				<view class="jifen">{{userCenter.integral}}</view>
				<view class="jifen_txt">当前积分</view>
				<view class="navbar ">
					<view v-for="(item, index) in navList" :key="index" class="nav-item" :class="{ current: tabCurrentIndex === index }"
					 @tap="tabClick(index)">
						<view :class="(index==tabCurrentIndex)?'active':'first_tabar'">
							{{ item.text }}
						</view>
					</view>
				</view>
			</view>
		</view>

		<view class="list">
			<view class="text_1">
				<view class="infolist" v-for="(item,index) in checkIntegral" :key="index">
					<view class="infolist_left">{{item.mark}}</view>
					<view class="infolist_right">
						<text v-if="item.pm==1">+{{item.number}}</text>
						<text v-if="item.pm==0">-{{item.number}}</text>
					</view>
				</view>
			</view>
		</view>
		<loading-module notDataText="我是有底线的～～" :isLoading="isLoading"></loading-module>
		<!-- 	<view class="text_1" v-show="tabCurrentIndex===1">
				<view class="infolist" v-for="(item,index) in infolist" v-show="item.type==1" :key="index">
					<view class="infolist_left">{{item.name}}</view>
					<view class="infolist_right">
						<text>+{{item.income}}</text>
					</view>
				</view>
			</view>
			
			<view class="text_1" v-show="tabCurrentIndex===2">
				<view class="infolist" v-for="(item,index) in infolist" v-show="item.type==2" :key="index">
					<view class="infolist_left">{{item.name}}</view>
					<view class="infolist_right">
						<text>-{{item.income}}</text>
					</view>
				</view>
			</view> -->
	</view>
</template>

<script>
	import {
		vuexData,
		page
	} from '@/common/commonMixin.js'
	export default {
		mixins: [vuexData, page],
		data() {
			return {
				jifen: 7689,
				// type:1,//收入积分
				// type:2,//支出积分
				scrollH: 0,
				windowHeight: 0,
				infolist: [{
						type: 1,
						name: '购买积分',
						income: 199
					},
					{
						type: 2,
						name: '使用积分',
						income: 20
					}
				],
				tabCurrentIndex: 0,
				istrue: 0,
				navList: [{
						text: '积分明细',
					},
					{
						text: '收入积分',
					},
					{
						text: '支出积分',
					}
				],
			}
		},
		async onLoad(options) {
			// 页面显示是默认选中第一个
			this.tabCurrentIndex = 0;
			this.clearUser('integralList')
			uni.showLoading({
				title: '加载中...'
			})
			await this.getIntegralList()
			uni.hideLoading()
			this.$nextTick(() => {
				let {
					windowHeight
				} = uni.getSystemInfoSync()
				this.windowHeight = windowHeight
			})
		},
		methods: {
			//顶部tab点击
			tabClick(index) {
				this.tabCurrentIndex = index;
			},
			async getIntegralList() {
				let integralList = await this.$api.getIntegralList(`?page=${this.page}&limit=10`)
				this.getUserIntegra(integralList)
			},
			getElSize(id) {
				return new Promise((res, rej) => {
					uni.createSelectorQuery().in(this).select(id).boundingClientRect(data => {
						res(data);
					}).exec();
				})
			}
		},
		computed: {
			checkIntegral() {
				switch (this.tabCurrentIndex) {
					case 0:
						return this.integralList
					case 1:
						return this.incomeIntegral
					case 2:
						return this.expenseIntegral
				}
			}
		},
		async onPageScroll(e) {
			let {
				scrollTop
			} = e

			if (this.scrollH == 0) {
				let data = await this.getElSize('#content')
				let {
					height
				} = data
				this.scrollH = height
			}
			if (this.scrollH - (scrollTop + this.windowHeight) < 20) {
				this.scrolltolower(this.getIntegralList)
			}
		}
	}
</script>

<style>
	page {
		background: #fff;
	}


	.jifen {
		font-size: 60rpx;
		font-weight: bold;
		color: #fc4e2c;
		padding-top: 80rpx;
	}

	.jifen_txt {
		font-size: 26rpx;
		color: #fc4e2c;
	}

	.integral_top {
		text-align: center;
		background: linear-gradient(to right, #ff501a, #ff3025);
		padding-top: 60rpx;
	}

	.integral_top_box {
		height: 400rpx;
	}



	/* 头部选项卡 */
	.navbar {
		position: absolute;
		margin-top: 120rpx;
		display: flex;
		flex-direction: row;
		height: 80upx;
		/* border-bottom: 1px solid #000000; */
		width: 100%;
		position: sticky;
		top: 0rpx;
	}

	.navbar .nav-item {
		display: flex;
		flex: 1;
		justify-content: center;
		padding-top: 30upx;
		font-size: 28upx;
		color: #eee;
	}

	.active {
		color: #fff;
		font-size: 32upx;
		font-weight: bold;
		background-size: 90%;
		/* border-bottom: 3px solid #78c144; */
	}


	.infolist {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		height: 80rpx;
		line-height: 80rpx;
		font-size: 28rpx;
		border-bottom: solid #e1e1e1 1rpx;
		padding: 0 30rpx;
		margin: 0 30rpx;
	}

	.infolist_right {
		color: #fc4e2c;
	}
</style>
