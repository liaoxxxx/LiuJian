<template>
	<!-- 我的优惠券界面 -->
	<view class="content">
		<view class="scroll_block">
			<view class="navbar">
				<view v-for="item in navList" :key="item.id" class="nav-item" :class="{ current: tabCurrentIndex === item.id }"
				 @tap="tabClick(item.id)">
					<view :class="(item.id==tabCurrentIndex)?'active':'first_tabar'">
						{{ item.text }}
					</view>
				</view>
			</view>
		</view>

		<view class="list" v-if="showList">
<!-- 	"id": 6,
	"gid": 292,
	"coupon_title": "满100减50",
	"coupon_price": "50.00",
	"use_min_price": "100.00",
	"add_time": "2020/09/16",
	"end_time": "2020/10/31",
	"use_time": 0,
	"type": "get",
	"status": 0,
	"is_fail": 0,
	"_add_time": "2020/09/16",
	"_end_time": "2020/10/31",
	"_type": 1,
	"_msg": "可使用" -->
			<view class="text_1" >
				<view class="discount_block">
					<view class="discount_box" v-for="item in userCoupon" :key="item.id">
						<view class="discount_box_top">
							<view class="discount_box_left">
								<view>￥<text class="price_num">{{item.coupon_price}}</text></view>
								<view>{{ item.use_min_price != '0.00' ? '满' + item.use_min_price + '使用' : '无金额门槛'}}</view>
							</view>
							<view class="discount_box_right">
								<view class="discount_title">
									<view class="biaoqian">
										{{item._type == 0 ? '无门槛' : '满减卷'}}
									</view>
									<view class="u-line-1">{{item.coupon_title}}</view>
								</view>
								<view class="discount_time">
									<view class="discount_bottom_top">
										{{item.add_time}}-{{item.end_time}}
									</view>
									<view class="discount_btn">
										立即使用
									</view>
								</view>
							<!-- 	<view class="discount_time" v-if="item.status===1">
									<view class="discount_bottom_top">
										{{item.endtime}}到期
										<text class="tishi">(仅剩1天)</text>
									</view>
									<view class="discount_btn">
										立即使用
									</view>
								</view> -->
							</view>
						</view>
						<view class="explain">
							{{0 | checkRangeType}}
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>


<script>
	import {vuexData} from '@/common/commonMixin.js'
	export default {
		mixins: [vuexData],
		filters: {
			checkRangeType(val) {
				let rangeTypeList = [
					'全部商品可用',
					'指定分类可用',
					'指定商品可用'
				]
				let rangeType = rangeTypeList[val]
				return rangeType
			}
		},
		data() {
			return {
				showList: false,
				status: 0,
				tabCurrentIndex: 0,
				navList: [{
						text: '全部',
						id: 0
					},
					{
						text: '未使用',
						id: 1
					},
					{
						text: '已使用',
						id: 2,
					},
					{
						text: '已过期',
						id: 3,
					}
				],
				// type=1未使用，type=2已使用，type=3已过期，type=4即将过期
				//status=1即将过期
			}
		},
		async onLoad(options) {
			// 页面显示是默认选中第一个
			this.tabCurrentIndex = 0;
			uni.showLoading({
				title: '加载中...'
			})
			let userCoupon = await this.$api.getUserCoupon(this.tabCurrentIndex)
			this.getUserCoupon(userCoupon)
			this.showList = true
			uni.hideLoading()
		},
		methods: {
			//顶部tab点击
			async tabClick(index) {
				this.tabCurrentIndex = index;
				let userCoupon = await this.$api.getUserCoupon(this.tabCurrentIndex)
				this.getUserCoupon(userCoupon)
			},
		}
	}
</script>

<style>
	page {
		width: 100%;
		height: 100%;
		background: #f5f5f5;
	}

	.content {
		padding-bottom: 200rpx;
	}

	.list {
		padding-top: 110rpx;
	}

	.scroll_block {
		height: 110upx;
		width: 100%;
		overflow: hidden;
		position: fixed;
		z-index: 99;
		/* background: #07C160; */
	}

	/* 顶部切换栏 */
	.navbar {
		display: flex;
		flex-direction: row;
		align-items: center;
		height: 110rpx;
		padding-left: 28rpx;
	}

	.nav-item {
		height: 110upx;
		line-height: 110rpx;
		margin-right: 15rpx;
		display: flex;
		align-items: center;
		font-size: 26upx;
	}

	.nav-item:last-child {
		margin-right: 0;
	}

	.first_tabar {
		padding: 0rpx 20rpx;
		border-radius: 30rpx;
		height: 58rpx;
		line-height: 58rpx;
		background: #eaeaea;
	}

	.active {
		color: #fff;
		padding: 0rpx 30rpx;
		height: 58rpx;
		line-height: 58rpx;
		border-radius: 30rpx;
		background: #fe0101;
	}




	.discount_block {
		padding: 0rpx 20rpx;
	}

	.discount_box {
		background: #fff;
		border-radius: 20rpx;
		margin-bottom: 15rpx;
	}

	.discount_box_top {
		display: flex;
		flex-direction: row;
		height: 200rpx;
	}

	.discount_box_left {
		flex: 1;
		border-bottom: dashed #dabfc3 1px;
		border-right: dashed #dabfc3 1px;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		font-size: 28rpx;
		color: #343434;
	}

	.price_num {
		font-size: 70rpx;
		font-weight: bold;
	}

	.discount_box_right {
		flex: 2;
		border-bottom: dashed #dabfc3 1px;
		display: flex;
		flex-direction: column;
		justify-content: center;
		padding: 0 20rpx;
	}

	.discount_title {
		display: flex;
		flex-direction: row;
		align-items: center;
		font-size: 28rpx;
		color: #343434;
		margin-bottom: 20rpx;
	}

	.biaoqian {
		font-size: 24rpx;
		color: #fff;
		background: #f93810;
		padding: 2rpx 12rpx 6rpx 12rpx;
		margin-right: 15rpx;
	}

	.biaoqian_2 {
		font-size: 24rpx;
		color: #fff;
		background: #ccc;
		padding: 2rpx 12rpx 6rpx 12rpx;
		margin-right: 15rpx;
	}

	.discount_time {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
	}

	.discount_bottom_top {
		font-size: 24rpx;
		color: #999;
	}

	.tishi {
		color: #f93810;
		padding-left: 15rpx;
	}

	.discount_btn {
		font-size: 26rpx;
		color: #fff;
		background: #f93810;
		padding: 0rpx 20rpx;
		border-radius: 30rpx;
		height: 56rpx;
		line-height: 56rpx;
	}

	.discount_btn_2 {
		font-size: 26rpx;
		color: #fff;
		background: #ccc;
		padding: 0rpx 20rpx;
		border-radius: 30rpx;
		height: 56rpx;
		line-height: 56rpx;
	}

	.explain {
		font-size: 26rpx;
		color: #343434;
		padding: 20rpx 50rpx 30rpx 50rpx;
	}
</style>
