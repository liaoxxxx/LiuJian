<template>
	<view class="content">
		<scroll-view v-if="detail && showTop" @scrolltolower="scrolltolower" scroll-y="true" class="position-absolute" :style="scrollStyle">
			<view class="w-100">
				<view class="scroll_top">
					<!-- banner轮播 -->
					<swiper class="swiper" indicator-dots="true" autoplay="true" circular="true">
						<swiper-item class="swiper_item" v-for="(item,index) in detail.slider_image" :key="index">
							<image class="swip-img" :src="item"></image>
						</swiper-item>
					</swiper>
				</view>
			</view>
			<view class="content_2">
				<view class="py-1 font-weight-bolder flex align-center">
					<view class="flex-1 flex align-start">
						<tag rounded="circle" :size="26" :py="2" content="特价"></tag>
					</view>
					<text class="ml-2 flex-6">{{detail.store_name}}</text>
				</view>
				<view class="font text-grey">
					{{detail.store_info}}
				</view>
				<view class="price_box">
					<text class="mr-2">￥{{nowPrice}}</text>
					<!-- <text class="onlyone">单件￥{{detail.ot_price}}</text> -->
				</view>
				<view class="flex font text-grey mb-2 justify-between">
					<text>库存{{detail.stock}}件</text>
					<text>销量{{detail.sales}}</text>
				</view>
				<view v-if="detail.vip_price" class="flex rounded-radius py-3 bg-main align-center justify-between px-1 text-white">
					<view class="flex align-center">
						<text style="font-size: 50rpx;" class="pt-1 iconfont mr-1">&#xe61b;</text>
						<text>会员价¥ {{detail.vip_price}}</text>
					</view>
					<view class="flex align-center">
						<text class="mr-1">立即开通</text>
						<text style="font-size: 50rpx;" class="iconfont">&#xe618;</text>
					</view>
				</view>
			</view>

			<view class="xian"></view>
			<view class="content_3">
				<view v-if="productAttr.length > 0" class="kinds_box">
					<view style="margin-right: 30rpx;">规格</view>
					<view v-for="item in productAttr" class="position-relative mr-2" :key="item.unique">
						<view @tap="checkProId(item.unique)" :class="{proActive: proId == item.unique}" class="kinds">{{item.suk}}</view>
						<view class="position-absolute" style="top: -12px; right: -8px;">
							<tag content="推荐" rounded="angle"></tag>
						</view>
					</view>

				</view>

				<view class="peisong">
					由<text class="address">酒来来·增城站</text>提供配送服务
				</view>

				<view class="security">
					<view class="security_left">
						<image class="security_img" src="../../static/user/camera.png" mode="widthFix"></image>
					</view>
					<view class="security_right">
						正品保真-酒品安全已由中国人民保险承保
					</view>
				</view>
			</view>
			<view class="xian"></view>
			<!-- 用户评价 -->
			<view class="flex flex-column w-100 pt-2" v-if="goodsDetail.replyCount != 0">
				<view class="flex justify-between p-3  font">
					<view class="flex align-center">
						<text class="font-weight-bolder mr-2">评价</text>
						<text class="font-sm">{{goodsDetail.replyCount}}</text>
					</view>
					<view class="flex align-center">
						<text class="font-sm"> 好评度 {{goodsDetail.replyChance}}% </text>
						<text class="iconfont font">&#xe618;</text>
					</view>
				</view>
				<view v-for="(item, index) in comments" :key="index">
					<evaluate :data="item"></evaluate>
					<view class="xian"></view>
				</view>
			</view>
			<view class="flex justify-center align-center py-2" v-else>
				<image src="../../static/search/no_record3.svg" style="width: 100rpx; height: 100rpx" mode=""></image>
				<text class="text-grey" style="font-size: 50rpx;">该商品暂无评论</text>
			</view>
			<view class="xian mb-3"></view>
			<load upwardText="上拉或点击加载详情" @loadTap="showDetail=false" v-if="showDetail" color="white"></load>
			<rich-text class="w-100 position-relative" v-else :nodes="detailInfo"></rich-text>
			<!-- <view class="w-100 position-relative" v-else v-html="detailInfo"></view> -->
		</scroll-view>
		<!-- 底部购买模块 -->
		<view class="flex w-100 bg-white position-fixed align-center bottom-0 pt-2 pb-3" style="border-top: 2px solid #ebebeb; height: 140rpx; box-sizing: border-box;">
			<view class="flex-3 flex justify-between px-2">
				<view v-for="item in iconBtns" :key="item.id" @tap="iconTap(item.event)" hover-class="bg-hover-light" class=" px-1 flex flex-column justify-between align-center">
					<text v-if="item.activeIcon" class="iconfont" :class="{'text-danger': activeIcon}" style="font-size: 50rpx;">{{activeIcon ? item.activeIcon : item.icon}}</text>
					<text v-else class="iconfont" style="font-size: 50rpx;">{{item.icon}}</text>
					<text class="font-sm">{{item.content}}</text>
				</view>
			</view>
			<view class="flex-4 flex justify-between pr-1">
				<my-btn color="#fff" content="加入购物车" @handleTap="addCart(0)" bgColor="#f40" bold></my-btn>
				<my-btn color="#fff" content="立即购买" @handleTap="addOrder" bgColor="#FBAB07" bold></my-btn>
			</view>
		</view>
	</view>
</template>

<script>
	import load from '@/components/loadingModule.vue'
	import tag from '@/components/tag.vue'
	import {
		vuexData
	} from '@/common/commonMixin.js'
	import evaluate from '@/components/evaluate.vue'
	export default {
		components: {
			load,
			evaluate,
			tag
		},
		mixins: [vuexData],
		data() {
			return {
				images: [{
						img: '../../static/index/goods/info_1.png'
					},
					{
						img: '../../static/index/goods/info_1.png'
					}
				],
				comments: [],
				showDetail: true,
				windowHeight: 0,
				activeIcon: false,
				showTop: false,
				proId: '',
				id: null,
				iconBtns: [{
						id: 0,
						icon: '\ue60f',
						content: '客服',
						event: 'service'
					},
					{
						id: 1,
						icon: '\ue666',
						activeIcon: '\ue621',
						content: '收藏',
						event: 'collect'
					},
					{
						id: 2,
						icon: '\ue602',
						content: '购物车',
						event: 'cart'
					}
				]
			}
		},
		async onLoad(option) {
			let {
				id
			} = option
			this.id = id
			this.getDetail(id)
			let res = await this.$api.getEvalList(`${id}?page=0&limit=10&type=0`)
			if (res.status == 200) {
				this.comments = res.data
			}
		},
		onReady() {
			this.$nextTick(async () => {
				let {
					windowHeight
				} = uni.getSystemInfoSync()
				this.windowHeight = windowHeight
			})
		},
		methods: {
			async addCart(isNew) {
				if (this.productAttr.length > 0 && this.proId == '') {
					uni.showToast({
						icon: 'none',
						title: '请选择商品规格'
					})
				} else {
					let result
					if (this.productAttr.length > 0) {
						let uniqueId = this.productAttr.filter(item => item.unique == this.proId)[0].unique
						result = await this.$api.addCart({
							productId: this.id,
							cartNum: 1,
							uniqueId,
							new: isNew
						})
					} else {
						result = await this.$api.addCart({
							productId: this.id,
							cartNum: 1,
							new: isNew
						})
					}
					if (isNew == 1) {
						let flag = this.checkRes(result)
						if (flag) {
							return result.data
						}
					} else {
						this.checkRes(result, '宝贝在购物车中等您～～')
					}
				}
			},
			checkProId(id) {
				if (id == this.proId) {
					console.log('一样')
					this.proId = ''
				} else {
					this.proId = id
				}
			},
			async addOrder() {
				let res = await this.addCart(1)
				let {cartId} = res
				uni.navigateTo({
					url: '/pages/order/addOrder?cartIds=' + cartId
				})
				// if (this.productAttr.length > 0 && this.proId == '') {
				// 	uni.showToast({
				// 		icon: 'none',
				// 		title: '请选择商品规格'
				// 	})
				// } else if (this.productAttr.length > 0) {
				// 	uni.navigateTo({
				// 		url: '/pages/order/addOrder?id=' + this.id + '&uniqueId=' + this.proId
				// 	})
				// } else {
				// 	uni.navigateTo({
				// 		url: '/pages/order/addOrder?id=' + this.id
				// 	})
				// }

			},
			async getDetail(id) {
				uni.showLoading({
					title: '努力加载中...'
				})
				let detail = await this.$api.getProductDetail(`${id}`)
				this.showTop = true
				uni.hideLoading()
				this.getGoodsDetail(detail)
			},
			scrolltolower() {
				this.showDetail = false
			},
			iconTap(event) {
				switch (event) {
					case 'service':
						uni.showModal({
							title: '提示',
							content: '客服服务暂未开启'
						})
						break
					case 'collect':
						this.addCollect()
						break
					case 'cart':
						uni.navigateTo({
							url: '/pages/index/shopcar'
						})
						break
				}
			},
			async addCollect() {
				// uni.showToast({
				// 	icon: 'none',
				// 	title: '模拟收藏功能'
				// })
				console.log(this.id)
				let res = await this.$api.userCollect(`?category=product&id=${this.id}`)
				console.log(res)
				this.activeIcon = !this.activeIcon
			}
		},
		computed: {
			nowPrice() {
				if (this.proId != '') {
					return this.productAttr.filter(item => item.unique == this.proId)[0].price
				} else {
					return this.detail.price
				}
			},
			scrollStyle() {
				let height = this.windowHeight - uni.upx2px(140);
				return `height: ${height}px; width: 750rpx; top: 0px;`
			},
			detailInfo() {
				console.log(this.detail)
				let newContent = this.detail.description.replace(/<img[^>]*>/gi, function(match, capture) {
					match = match.replace(/style="[^"]+"/gi, '').replace(/style='[^']+'/gi, '');
					match = match.replace(/width="[^"]+"/gi, '').replace(/width='[^']+'/gi, '');
					match = match.replace(/height="[^"]+"/gi, '').replace(/height='[^']+'/gi, '');
					return match;
				});
				newContent = newContent.replace(/style="[^"]+"/gi, function(match, capture) {
					match = match.replace(/width:[^;]+;/gi, 'max-width:100%;').replace(/width:[^;]+;/gi, 'max-width:100%;');
					return match;
				});
				newContent = newContent.replace(/<br[^>]*\/>/gi, '');
				newContent = newContent.replace(/\<img/gi,
					'<img style="max-width:100%;height:auto;display:inline-block;margin:10rpx auto;"');
				return newContent;
			}
		}
	}
</script>

<style>
	.proActive {
		background-color: #f40;
		color: #fefefe;
	}

	.content_2 {
		padding: 0 30rpx;
	}

	.xian {
		background: #f5f5f5;
		height: 10rpx;
		margin-top: 20rpx;
	}

	.goods_name {
		font-size: 32rpx;
		font-weight: bold;
		margin-bottom: 15rpx;
	}

	.price_box {
		display: flex;
		flex-direction: row;
		align-items: center;
		height: 80rpx;
		line-height: 80rpx;
		color: red;
		font-size: 46rpx;
		font-weight: bold;
	}

	.onlyone {
		color: #b5b5b5;
		font-size: 20rpx;
		padding: 0 20rpx;
	}

	.goods_describe {
		color: #b5b5b5;
		font-size: 20rpx;
		line-height: 38rpx;
		letter-spacing: 2rpx;
	}

	.tejia {
		background: #007AFF;
		color: #fff;
		font-size: 20rpx;
		height: 30rpx;
		line-height: 30rpx;
		padding: 0 6rpx 2rpx 6rpx;
	}

	.swip-img {
		width: 100%;
		height: 100%;
	}

	.swiper_item {
		width: 750rpx;
		height: 700rpx;
		position: relative;
	}

	.swiper {
		height: 700rpx;
	}


	.kinds_box {
		display: flex;
		flex-direction: row;
		align-items: center;
		flex-wrap: wrap;
		font-size: 30rpx;
		padding: 30rpx;
	}

	.peisong {
		height: 90rpx;
		line-height: 90rpx;
		background: #f5f5f5;
		font-size: 30rpx;
		padding-left: 30rpx;
	}

	.address {
		color: red;
		padding: 0 10rpx;
	}

	.kinds {
		padding: 2rpx 30rpx 4rpx 30rpx;
		border: #999 1px solid;
		border-radius: 50rpx;
		margin-right: 30rpx;
		margin-bottom: 10rpx;
	}

	.security {
		display: flex;
		flex-direction: row;
		align-items: center;
		padding-left: 30rpx;
		padding-top: 30rpx;
	}

	.security_img {
		width: 30rpx;
		height: 30rpx;
	}

	.security_right {
		font-size: 28rpx;
		color: #b5b5b5;
	}
</style>
