export default {
	state: {
		addressList: [],
		defaultAddress: {},
		nowAddressKey: ''
	},
	getters: {
		addressList: state => state.addressList,
		nowAddressKey: state => state.nowAddressKey,
		defaultAddress: state => state.defaultAddress
	},
	mutations: {
		getAddressList(state, res) {
			state.addressList = res
		},
		setNowAddress(state, res) {
			state.nowAddressKey = res
		},
		getDefAddress(state, res) {
			state.defaultAddress = res
		},
		removeAddress(state, res) {
			let addressList = state.addressList
			addressList = addressList.splice(res, 1)
		}
	},
	actions: {
		getAddressList({commit}, res) {
			if (res.errCode === 0) {
				let {addressList} = res.data
				commit('getAddressList', addressList)
			}
		},
		getDefAddress({commit}, res) {
			if (res.errCode === 0) {
				let {data} = res
				commit('getDefAddress', data)
			}
		}
	}
}
