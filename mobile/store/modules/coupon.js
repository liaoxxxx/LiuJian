export default {
	state: {
		userCoupon: []
	},
	getters: {
		userCoupon: state => state.userCoupon
	},
	mutations: {
		getUserCoupon(state, res) {
			state.userCoupon = res
		}
	},
	actions: {
		getUserCoupon({ commit }, res) {
			if (res.status == 200) {
				let { data } = res
				commit('getUserCoupon', data)
			}
		}
	}
}
