<template>
	<view class="page">
		<scroll-view class="position-absolute w-100 top-0" scroll-y="true" :style="scrollStyle">
			<view>
				<halving-line bgColor="#eee"></halving-line>
				<view class="px-2 py-1">
					<address-item showImg @handleTap="setAddress" v-if="addressList.length > 0" :address="defAddress">
					</address-item>
					<view v-else class="border rounded flex align-center justify-between py-5">
						<view class="flex-2 flex justify-center align-center">
							<text class="iconfont text-grey" style="font-size: 50rpx;">&#xe60e;</text>
						</view>
						<text class=" flex-6 flex align-center justify-between text-grey" style="text-decoration: underline; font-style: oblique;">前往设置收货地址>></text>
					</view>
				</view>
				<halving-line class="font-weight-bolder" color="#111" content="商品清单" bgColor="#eee"></halving-line>
				<view class="flex flex-column">
					<list-item v-if="orderInfo.productInfo" class="mb-3" :content="`共`+ orderInfo.productTotalNum +'件' " :wraStyle="{padding: '20rpx 20rpx 20rpx 40rpx '}"
					 :contentFont="{fontSize: '26rpx', color: '#123', fontWeight: 400 }">
						<view slot="left" class="flex flex-nowrap overflow-hidden">
							<image v-for="item in orderInfo.productInfo" class="mr-1" style="height: 150rpx; width: 150rpx" :src=" item.image | checkImg"
							 mode=""></image>
						</view>
						<view slot="right" class="flex-1 flex justify-center">
							<text class="iconfont text-grey">&#xe708;</text>
						</view>
					</list-item>
					<list-item v-else class="mb-3" content="共计1件" :wraStyle="{padding: '20rpx 20rpx 20rpx 40rpx '}" :contentFont="{fontSize: '26rpx', color: '#123', fontWeight: 400 }">
						<view slot="left" class="flex">
							<image v-if="goodsInfo" style="height: 150rpx; width: 150rpx" :src="goodsInfo.image" mode=""></image>
						</view>
						<view slot="right" class="flex-1 flex justify-center">
							<text class="iconfont text-grey">&#xe708;</text>
						</view>
					</list-item>
					<list-item title="配送时间" :content="time | format">
						<view slot="right" class="flex-1 flex justify-center">
							<picker-plus @confirm="checkTime" :startRule="nowTime" mode="YMDhm">
								<text class="iconfont text-grey">&#xe708;</text>
							</picker-plus>
						</view>
					</list-item>
				</view>
				<halving-line bgColor="#eee"></halving-line>
				<view class="flex flex-column">
					<list-item title="发票">
						<view slot="right" class="flex-1 flex justify-center">
							<picker :value="billIndex" :range="billTypes" @change="checkBill">
								<text class="iconfont text-grey">&#xe708;</text>
							</picker>
						</view>
						<view slot="content" class="flex-5 flex justify-end font-sm">

							<text class="text-danger">{{billTypes[billIndex]}} </text>
							<text>(商品明细-个人)</text>
						</view>
					</list-item>
					<list-item title="优惠卷">
						<view slot="content" class="flex-5 flex justify-end font-sm">
							<tag v-if="orderInfo.usableCoupon && orderInfo.usableCoupon.length" :content="`${orderInfo.usableCoupon.length}张可用`"></tag>
							<tag v-else content="无可用优惠卷" bgColor="#adadad"></tag>
						</view>
						<view slot="right" class="flex-1 flex justify-center">
							<text class="iconfont text-grey">&#xe708;</text>
						</view>
					</list-item>
					<list-item :title="`使用积分 共${userCenter.integral}`">
						<view slot="content" class="flex-5 flex justify-end font-sm">
							<input v-model="integral" class="font-sm" placeholder="输入使用积分" type="number">
						</view>
					</list-item>
					<view class="flex flex-column px-2">
						<view class="mb-2">
							<text class="font-sm">订单备注</text>
						</view>
						<view>
							<textarea style="height: 150rpx; width: 700rpx;" class="font-sm" v-model="remark" placeholder="选填,给我留言吧～～" />
							</view>
					</view>
				</view>
				<halving-line bgColor="#eee"></halving-line>
				<view class="flex flex-column">
					<list-item v-for="item in money" :title="item.title" :key="item.id">
						<view slot="content" class="flex-5 flex justify-end font-sm">
							<text :class="{'text-danger': item.id > 0, 'text-body': item.id == 0}" class="font-weight-bold">{{item.id > 0 ? `-` : ''}} ¥{{item.content}}</text>
						</view>
					</list-item>
				</view>
				<halving-line class="font-weight-bolder" color="#111" content="付款方式" bgColor="#eee"></halving-line>
				
				<view class="flex flex-column">
					<list-item v-for="item in payType" :key="item.id">
						<view class="" slot="left" class="flex align-center">
							<text class="iconfont font-lg mr-2" style="font-size: 48rpx;" :style="item.color">{{item.title}}</text>
							<text class="font-sm"> {{item.name}} </text>
						</view>
						<view slot="content">
							<radio @tap="radioAddress = item.id" :checked="radioAddress==item.id" :value="item.id"></radio>
						</view>
					</list-item>
				</view>
				<halving-line bgColor="#eee"></halving-line>
				<halving-line bgColor="#eee"></halving-line>
			</view>
			
		</scroll-view>
		<!-- 底部 -->
		<view class="border-top flex w-100 align-center position-fixed bottom-0 bg-white font" style="height: 120rpx;">
			<view class="flex-4 flex flex-column align-end pr-2">
				<view class="flex font-sm align-end">
					<text class="text-grey">共计2件,</text>
					<view class="ml-2">
						<text>合计</text>
						<text class="text-danger ml-2">¥</text>
						<text class="font font-weight-bold text-danger">{{orderInfo.totalPrice}}</text>
					</view>
				</view>
				<view class="text-grey font-sm">
					节省:
					<text class='ml-2'>
						{{orderInfo.discount}}元
					</text>
				</view>
			</view>
			<view class="flex-2 flex align-center justify-center">
				<my-btn @handleTap="addOrder" bgColor="#f40" color="#fff" content="确认订单"></my-btn>
			</view>
		</view>
	</view>
</template>

<script>
	import tag from '@/components/tag.vue'
	import listItem from '@/components/list_item.vue'
	import pickerPlus from '@/components/e-picker-plus/e-picker-plus.vue'
	import {vuexData} from '@/common/commonMixin.js'
	import moment from '@/common/moment.js'
	import addressItem from '@/components/address_item.vue'
	export default {
		components: {
			tag,
			listItem,
			pickerPlus,
			addressItem
		},
		mixins: [vuexData],
		filters: {
			format(str) {
				let nowDay = moment().format('YYYY-MM-DD').split(' ')[0].split('-').slice(1)
				if (str) {
					let arr = str.split(' ')
					let date = arr[0]
					let dateArr = date.split('-').slice(1)
					if (nowDay[0] == dateArr[0] && nowDay[1] == dateArr[1]) {
						let timeStr = dateArr[0] + '月' + dateArr[1] + '日 [今天] ' + arr[1] + '前送达'
						return timeStr
					} else {
						let timeStr = dateArr[0] + '月' + dateArr[1] + '日 ' + arr[1] + '前送达'
						return timeStr
					}
				} else {
					return '-------'
				}
			},
			checkImg(str) {
				if (str) {
					let check = str.startsWith('http')
					if (check) {
						return str
					} else {
						return 'http://111.229.128.239:1003' + str
					}
				}
			}
		},
		data() {
			return {
				windowHeight: 0, // 滚动view高度
				integral: '',	// 使用积分
				billIndex: 0,	// 发票索引
				billTypes: ['电子', '纸质'], // 发票类型
				remark: '',		// 备注
				radioAddress: 0,	// 支付方式索引
				time: '',		// 配送时间	
				cntitems: '',	// 商品数量
				totalamount: '',	// 商品总金额
				orderDetail: null,	// 从购物车跳转过来时的商品
				orderInfo: {},
				payType: [
					{
						id: 0,
						title: '\ue620',
						color: 'color: #00C800;',
						name: '微信支付',
						type: 'weixin'
					},
					{
						id: 1,
						title: '\ue608',
						color: 'color: #288CF8;',
						name: '支付宝',
						type: 'alipay'
					},
					{
						id: 2,
						title: '\ue70e',
						color: 'color: #FF8A4E;',
						name: '货到付款',
						type: 'offline'
					}
				]
			}
		},
		methods: {
			// 选择发票类型
			checkBill(e) {
				this.billIndex = e.target.value
			},
			// 初始化订单数据
			async initOrderIfo() {
				let result = await this.$api.createOrder({cartId: this.cartIds})
				console.log(result)
				let res = this.checkRes(result)
				if (res) {
					this.orderInfo = result.data
				}
			},
			// 确认订单 方法
			async addOrder() {
				// this.nowAddressKey  // 地址id
				// this.integral  // 使用积分的值
				// this.remark  //备注信息
				// this.radioAddress  //不确定后断要的值  支付渠道
				let data = {
					orderKey: this.orderInfo.orderKey,
					addressId: this.defAddress.id,
					couponId: 0,
					payType: this.payType[this.radioAddress].type,
					useIntegral: this.integral ? this.integral : 0.0,
					mark: this.remark,
					shipping_type: 3,
					take_time: this.time,
					real_name: '二驴',
					phone: 13333333333
				}
				console.log(data)
				let result = await this.$api.orderPay(data)
				console.log(result)
				let res = this.checkRes(result, '订单已创建～～')
				
				// this.userCenter.nickname		用户姓名
				// this.userCenter.phone		用户手机号
				
				// couponId  优惠卷id
				// payType 支付类型 暂不考虑  货到付款会有不同
				// combinationId  拼团暂不涉及 or 普通商品
				// pinkId 是否拼团 暂不涉及
				// seckill_id 秒杀暂不涉及
				// formId 表单id？？？
				// bargainId 砍价id暂不涉及
				// shipping_type 暂不涉及 取货方式
			},
			// 选择收货时间
			checkTime(e) {
				let {result} = e
				let defaultM = this.time.split(':')
				let defaultMstr = defaultM[defaultM.length - 1]
				let resultM = result.split(':')
				let resultMstr = resultM[resultM.length - 1]
				if (resultMstr == 'undefined') {
					resultM[resultM.length - 1] = defaultMstr
					this.time = resultM.join(':')
				} else {
					this.time = result
				}
			},
			// 设置收货地址
			setAddress() {
				uni.navigateTo({
					url: '/pages/user/address_list'
				})
			}
		},
		computed: {
			// 滚动
			scrollStyle() {
				let height = this.windowHeight - uni.upx2px(120)
				return `height: ${height}px`
			},
			// 现在的时间
			nowTime() {
				let time = moment(Date.now() + 1800000).format('YYYY-MM-DD HH:mm')
				return time
			},
			// 收货地址
			defAddress() {
				let def = {}
				if (this.orderInfo.addressInfo && this.nowAddressKey == '') {
					def = this.orderInfo.addressInfo.filter(item => item.is_default == 1)[0]
				} else if(this.orderInfo.addressInfo && this.orderInfo.addressInfo.length > 0) {
					def = this.orderInfo.addressInfo.filter(item =>  item.id == this.nowAddressKey)[0]
				}
				console.log(def)
				return def
			},
			// 商品详情
			goodsInfo() {
				let orderDetail = uni.getStorageSync('orderDetail') // 购物车跳转时 会把详情添加到缓存
				
				if(orderDetail) { 	// 从购物车跳转过来
					this.orderDetail = orderDetail
					return orderDetail
				}
				if (typeof this.id == 'string' && this.id != '') { 	// 直接下单
					let obj = this.goodsDetail
					let proArr
					if (obj.productAttr.length > 0) {
						proArr = Object.values(obj.productValue)
						this.detail.proArr = proArr
					}
					return this.detail
				}
			},
			// 商品金额计算
			money() {
				let coupon = 0
				let integral = 0
				let arr = [
					{
						id: 0,
						title: '商品金额',
						content: this.orderInfo.totalPrice
					},
					{
						id: 1,
						title: '优惠',
						content: this.orderInfo.discount
					},
					{
						id: 2,
						title: '优惠卷',
						content: coupon
					},
					{
						id: 3,
						title: '积分',
						content: integral
					}
				]
				return arr
			}
		},
		async onReady() {
			this.$nextTick(() => {
				let {windowHeight} = uni.getSystemInfoSync()
				this.windowHeight = windowHeight
			})
			if (this.userCenter.uid == undefined) {
				let userCenter = await this.$api.getUserCenter()
				this.getUserCenter(userCenter)
			}
			let addressList = await this.$api.getAddressList('?page=0&limit=10')
			let time = moment(Date.now() + 1800000).format('YYYY-MM-DD HH:mm')
			this.time = time
			let defaultAddress = await this.$api.getAddressDef()
			this.getDefAddress(defaultAddress)
			this.getAddressList(addressList)
		},
		onLoad(option) {
			let {cartIds} = option
			this.cartIds = cartIds
			this.initOrderIfo()
			
			this.$nextTick(() => {
				if(!this.defAddress) {
					uni.showModal({
						title: '提示',
						content: '请完善地址信息',
						success(res) {
							 if (res.confirm) {
							        uni.navigateTo({
							        	url: '/pages/user/add_address'
							        })
							    }
						}
					})
				}
			})
			
			 // uni.getProvider({ 
				//  service: 'payment',
				//  success(data) {
				//  	console.log(data)
				//  }
			 // })
			 // uni.requestPayment({
			 //     provider: 'alipay',
			 //     orderInfo: 'orderInfo', //微信、支付宝订单数据
			 //     success: function (res) {
			 //         console.log('success:' + JSON.stringify(res));
			 //     },
			 //     fail: function (err) {
			 //         console.log('fail:' + JSON.stringify(err));
			 //     }
			 // });
		}
	}
</script>

<style scoped>
	.page {
		height: 100vh;
	}
</style>
