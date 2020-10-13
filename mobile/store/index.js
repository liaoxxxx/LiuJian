import Vue from 'vue'
import Vuex from 'vuex'

import app from '../config/app.json'

import tabbar_store from "./modules/tabbar_store.js"	// 额
import location from './modules/location.js'			// 定位信息
import indexPage from './modules/indexPage.js'			// 首页信息
import goods from './modules/goods.js'					// 商品信息
import user from './modules/user.js'					// 用户信息
import address from './modules/address.js'				// 收货地址信息
import cart from './modules/cartAndOrder.js'			// 购物车和订单
import coupon from './modules/coupon.js'				// 优惠卷
Vue.use(Vuex)
let vm = new Vue();

const store = new Vuex.Store({
	actions: {},
	mutations: {
		clearCartAndOrder(state, res) {
			if (Array.isArray(state.cart[res])) {
				state.cart[res] = []
			} else {
				state.cart[res] = {}
			}
		},
		clearGoods(state, res) {
			if (Array.isArray(state.goods[res])) {
				state.goods[res] = []
			} else {
				state.goods[res] = {}
			}
		},
		clearUser(state, res) {
			if (Array.isArray(state.user[res])) {
				state.user[res] = []
			} else {
				state.user[res] = {}
			}
		}
	},
	modules: {
		tabbar: tabbar_store,
		location,
		indexPage,
		goods,
		user,
		address,
		cart,
		coupon
	}
})

export default store
