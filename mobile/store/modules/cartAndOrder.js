export default {
	state: {
		cartForValid: [],
		cartForInvalid: [],
		orderList: [],
		orderDetail: {}
	},
	getters: {
		/**
		* @author			前端小能手
		*/
		cartForValid: state => state.cartForValid,		// 过期购物车列表
		cartForInvalid: state => state.cartForInvalid,	// 有效购物车列表
		orderList: state => state.orderList,			// 订单列表
		orderDetail: state => state.orderDetail			// 订单详情
	},
	mutations: {
		/**
		* @author			前端小能手
		* getCartForValid	过期购物车列表
		* getCartForInvalid	有效购物车列表
		* changeCartNum		改变购物车商品数量
		* getOrderList		订单列表
		* getOrderDetail	订单详情
		*/
		getCartForValid(state, res) {
			state.cartForValid = res
		},
		getCartForInvalid(state, res) {
			state.cartForInvalid = res
		},
		changeCartNum(state, res) {
			let {num, id} = res
			let temp = state.cartForValid
			temp.forEach(item => {
				if (item.id == id) {
					item.cart_num += num
				}
			})
			state.cartForValid = temp
		},
		getOrderList(state, res) {
			let arr = state.orderList
			arr.push(...res)
			state.orderList = arr
		},
		getOrderDetail(state, res) {
			state.orderDetail = res
		}
	},
	actions: {
		/**
		* @author			前端小能手
		* getCartList		获取购物车列表
		* getOrderList		获取订单列表
		* getOrderDetail	获取订单详情
		*/
		getCartList({commit}, res) {
			if (res.status == 200) {
				let {data} = res
				let {valid, invalid} = data
				console.log(valid)
				commit('getCartForValid', valid)
				commit('getCartForInvalid', invalid)
			}
		},
		getOrderList({commit}, res) {
			if (res.status == 200) {
				let {data} = res
				console.log(data)
				commit('getOrderList', data) 
			}
		},
		getOrderDetail ({commit}, res) {
			if (res.status == 200) {
				let {data} = res
				console.log(data)
				commit('getOrderDetail', data) 
			}
		}
	}
}
