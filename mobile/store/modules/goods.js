export default {
	state: {
		goodsList: [],
		goodsDetail: {},
		hotGoodsList: []
	},
	getters: {
		goodsList: state => state.goodsList,
		goodsDetail: state => state.goodsDetail,
		detail: state => state.goodsDetail.storeInfo,
		hotGoodsList: state => state.hotGoodsList,
		productAttr: state => {
			if (state.goodsDetail.productValue && !Array.isArray(state.goodsDetail.productValue)) {
				return 	Object.values(state.goodsDetail.productValue)
			}
			return []
		}
	},
	mutations: {
		getGoodsList(state, res) {
			let arr = state.goodsList.concat(res)
			state.goodsList = arr
		},
		getGoodsDetail(state, res) {
			state.goodsDetail = res
		},
		getHotGoodsList(state, res) {
			state.hotGoodsList.push(...res)
		}
	},
	actions: {
		getGoodsList({commit}, res) {
			if (res.status == 200) {
				let {data} = res
				commit('getGoodsList', data)
			}
		},
		getGoodsDetail({commit}, res) {
			if(res.status == 200) {
				let {data} = res
				commit('getGoodsDetail', data)
			}
		},
		getHotGoodsList({commit}, res) {
			if (res.status == 200) {
				let {data} = res
				commit('getHotGoodsList', data)
			}
		}
	}
}
