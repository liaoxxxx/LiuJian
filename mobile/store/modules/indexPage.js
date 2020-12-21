export default {
	state: {
		indexBanner: [],
		indexTopMenus: [],
		indexRoll: [],
		indexBastInfo: {}, // 专场
		indexFirstInfo: {}, //单品
		activity: []
	},
	getters: {
		indexBanner: state => state.indexBanner,
		indexTopMenus: state => state.indexTopMenus,
		indexRoll: state => state.indexRoll,
		indexBastInfo: state => state.indexBastInfo,
		indexFirstInfo: state => state.indexFirstInfo,
		activity: state => state.activity
		
	},
	mutations: {
		getBanner(state, res) {
			state.indexBanner = res
		},
		getTopMenus(state, res) {
			state.indexTopMenus = res
		},
		getRoll(state, res) {
			state.indexRoll = res
		},
		getBastInfo(state, res) {
			state.indexBastInfo = res
		},
		getFirstInfo(state, res) {
			if(res.msg && res.msg == 'imgErr') {
				let arr = state.indexFirstInfo.firstList
				arr[res.index].image = '/static/index/goods/info_1.png'
				state.indexFirstInfo.firstList = arr
			} else {
				state.indexFirstInfo = res
			}
		},
		getActivity(state,res) {
			state.activity = res
		}
	},
	actions: {
		getIndex({
			commit
		}, res) {
			if (res.Status === 200) {
				let {
					data
				} = res
				let {
					banner
				} = data
				let {
					menus
				} = data
				let {
					roll
				} = data
				let {
					info,
					activity
				} = data
				let {
					bastInfo,
					firstInfo,
					bastList,
					firstList
				} = info
				console.log(activity)
				commit('getBanner', banner)
				commit('getTopMenus', menus)
				commit('getRoll', roll)
				commit('getActivity', activity)
				commit('getBastInfo', {
					bastInfo,
					bastList
				})
				commit('getFirstInfo', {
					firstInfo,
					firstList
				})
			}
		},
	}
}
