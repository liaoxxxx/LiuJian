export default {
	state: {
		userinfo: {},
		userCenter: {},
		integralList: [],
	},
	getters: {
		userCenter: state => state.userCenter,
		userinfo: state => state.userinfo,
		integralList: state => state.integralList,
		incomeIntegral: state => {
			return state.integralList.filter(item => {
				return item.pm == 1
			})
		},
		expenseIntegral: state => {
			return state.integralList.filter(item => {
				return item.pm == 0
			})
		},
	},
	mutations: {
		getUserCenter(state, res) {
			state.userCenter = res
		},
		getUserinfo(state, res) {
			state.userinfo = res
		},
		getUserIntegra(state, res) {
			state.integralList.push(...res)
		}
	},
	actions: {
		getUserCenter({
			commit
		}, res) {
			if (res.status == 200) {
				let {data} = res
				commit('getUserCenter', data)
			}
		},
		getUserinfo({commit}, res) {
			if (res.status == 200) {
				let {data} = res 
				commit('getUserinfo', data)
			}
		},
		getUserIntegra({commit}, res) {
			if (res.status == 200) {
				let {data} = res
				commit('getUserIntegra', data)
			}
		}
	}
}
