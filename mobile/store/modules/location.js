import {
	getRegeo,
	getRegeoForKeyword
} from '@/js_sdk/Lyn4ever-gaodeRoutePlanning/lyn4ever-gaode.js'

export default {
	state: {
		location: {},
		site: {},
		address: '',
		nearby: [],
		searchNearby: []
	},
	getters: {
		location: state => state.location,
		site: state => state.site,
		address: state => state.address,
		nearby: state => state.nearby,
		searchNearby: state => state.searchNearby
	},
	mutations: {
		getLocation(state, res) { // 坐标
			state.location = res
		},
		getSite(state, res) { // 城市
			state.site = res
		},
		getAddress(state, res) { // 详细地址
			state.address = res
		},
		getNearby(state, res) { // 附近地址
			state.nearby = res
		},
		getSiteForKeyword(state, res) { // 搜索地址
			res = res.filter(data => {
				return typeof data.id == 'string'
			})
			state.searchNearby = res
			console.log(state.searchNearby)
		}
	},
	actions: {
		getSite({
			commit
		}, res) {
			getRegeo(data => {
				let regeo = data[0]
				let tempLocation = res.split(',')
				let longitude = tempLocation[0]
				let latitude = tempLocation[1]
				let {
					addressComponent,
					pois: nearby,
					formatted_address: address
				} = regeo.regeocodeData
				commit('getLocation', {
					lat: latitude,
					lng: longitude
				})
				commit('getSite', addressComponent)
				commit('getAddress', address)
				commit('getNearby', nearby)
			}, res)
		},
		getSiteForKeyword({
			commit
		}, res) {
			console.log(res)
			if (!res.keyword) {
				commit('getSiteForKeyword', [])
				return
			}
			getRegeoForKeyword(data => {
				console.log(data)
				if (data.tips) {
					commit('getSiteForKeyword', data.tips)
				}
			}, res.keyword, res.location)
		}
	}
}
