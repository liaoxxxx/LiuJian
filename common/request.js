// const defaultUrl = 'http://111.229.128.239:1003'
import * as defaultUrl from './config.js'
import api from '@/api/index.js'
function request(url, type, data, header) {
	return uni.request({
		method: type,
		data,
		url: defaultUrl.default+url,
		header: {
			...header,
			token: uni.getStorageSync('token') ? uni.getStorageSync('token') : ''
		}
	}).then(res => {
		console.log(res)
		if (res[1].statusCode == 200) {
			if(res[1].data.status == 410000 || res[1].data.status == 410001) {
				
				// uni.login({
				// 	provider: 'weixin',
				// 	success: async (res) => {
				// 		const {key, code} = res
				// 		uni.getUserInfo({
				// 			provider: 'weixin',
				// 			success: async (data) => {
				// 				console.log({login_type: 'routine', cache_key: key, code})
				// 				const {encryptedData, iv} = data
				// 				console.log({login_type: 'routine', cache_key: key, code, encryptedData, iv})
				// 				let result = await api.wxLogin({login_type: 'routine', cache_key: key, code, encryptedData, iv})
				// 				if (result.status == 200) {
				// 					const {token} = result.data
				// 					uni.setStorageSync('token', token)
				// 					uni.showToast({
				// 						title: '登陆成功'
				// 					})
				// 					uni.switchTab({
				// 						url: '/pages/index/index'
				// 					})
				// 				} else {
				// 					uni.showModal({
				// 						title: '提示',
				// 						content: data.msg
				// 					})
				// 				}
				// 			}
				// 		})
						
				// 	},
				// 	fail(err) {
				// 		uni.showModal({
				// 			title: '提示',
				// 			showCancel: false,
				// 			content: err.errMsg
				// 		})
				// 	}
				// })
				// uni.getUserInfo({
					
				// })
			
				
				
				uni.showToast({
					title:'请先登陆',
					icon: 'none'
				})
				setTimeout(() => {
					uni.navigateTo({
						url: '/pages/login/login_mode',
						fail(err) {
							console.log(err)
						}
					})
				}, 200)
			
			}
			let {data} = res[1]
			return data
		}
	}).catch(err => {
		console.log(err)
		uni.hideLoading()
		return Promise.reject(err)
	})
}

export default request
