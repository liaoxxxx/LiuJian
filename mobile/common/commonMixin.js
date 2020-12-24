import {
	mapActions,
	mapGetters,
	mapMutations
} from 'vuex'

export const vuexData = {
	data() {
		return {
			regTimer: null
		}
	},
	computed: {
		...mapGetters([
			'location',			// 定位信息
			'site',				// 城市
			'address',			// 详细地址
			'nearby',			// 定位附近地址列表
			'searchNearby',
			'goodsList',		// 商品列表
			'userinfo',			// 用户信息
			'userCenter',		// 用户中心
			'detail',			// 商品详情
			'addressList',		// 收获地址列表
			'nowAddressKey',	// 选择的地址id
			'defaultAddress',	// 默认收获地址
			'cartForValid',		// 过期购物车列表
			'cartForInvalid',	// 有效购物车列表
			'productAttr',		// 商品规格列表
			'goodsDetail',		// 商品详情
			'userCoupon',		// 用户领取的优惠卷
			'activity',			//
			'orderList',		// 订单列表
			'integralList',		// 积分详情列表
			'incomeIntegral',	// 积分收入
			'expenseIntegral',	// 积分支出
			'hotGoodsList',		// 推荐商品
			'orderDetail',		// 订单详情
			'findList',			// 发现页面数据
		])
	},
	methods: {
		...mapActions([
			'getSite',				// 获取定位地址
			'getSiteForKeyword',	// 获取定位地址（关键字）
			'getGoodsList',			// 获取商品列表
			'getUserinfo',			// 获取用户信息
			'getUserCenter',		// 获取用户中心数据
			'getGoodsDetail',		// 获取商品详情
			'getAddressList',		// 获取收获地址列表
			'getDefAddress',		// 获取默认收获地址
			'getCartList',			// 获取购物车列表
			'getUserCoupon',
			'getOrderList',
			'getUserIntegra',
			'getHotGoodsList',
			'getOrderDetail',		// 获取订单详情
			'getFind',				// 获取发现页面列表
		]),
		...mapMutations(['setNowAddress', 'changeCartNum', 'removeAddress', 'clearCartAndOrder', 'clearGoods', 'clearUser']),
		delay(callback, deep = 300) { // 防抖函数
			clearTimeout(this.regTimer)
			this.regTimer = setTimeout(() => {
				callback()
			}, deep)
		},
		checkRes(promise, msg) {
			promise.then(function (res){
				console.log('-----------------------')
				console.log(res)
				if (res.Status && res.Status === 200) {
					if (msg) {
						uni.showToast({
							title: msg
						})
					} else {

					}
					return 200
				} else {
					uni.showModal({
						showCancel: false,
						title: '提示',
						content: res.msg
					})
					return false
				}
			})

		}
	},
	async created() {
		if (!this.address) { // 位置请求
			uni.getLocation({
				type: 'gcj02',
				success: data => {
					let {
						latitude,
						longitude
					} = data
					this.getSite(`${longitude},${latitude}`)
				}
			})
		}
	}
}


export const page = {
	data() {
		return {
			isLoading: 1,
			page: 1
		}
	},
	methods: {
		async scrolltolower(callback) {
			// 说明没有更多或正在请求
			if (this.isLoading == 2 || this.isLoading == 0) {
				return
			}
			this.isLoading = 0
			this.page ++
			let res = await callback()
			if (res) {
				this.isLoading = 1
			} else {
				this.isLoading = 2
			}
		},
		updatePage(data) {
			this.page = 1
			this.isLoading = 1
			if (data) {
				this[data] = []
			}
		}
	},
	created() {
		this.updatePage()
	}

}