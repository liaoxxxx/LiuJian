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
		if (res[1].data.Code === 200) {
			if(res[1].data.Status === 410000 || res[1].data.Status === 410001) {

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


// service.js文件
/*

import Vue from 'vue'
import axios from 'axios'
import url_config from "./config";
const axiosInstance = axios.create({
	withCredentials: true,
	crossDomain: true,
	baseURL:url_config,
	timeout: 6000
})

// request拦截器,在请求之前做一些处理
axiosInstance.interceptors.request.use(
	config => {
		// if (store.state.token) {
		//     // 给请求头添加user-token
		//     config.headers["user-token"] = store.state.token;
		// }
		console.log('请求拦截成功')
		return config;
	},
	error => {
		console.log(error); // for debug
		return Promise.reject(error);
	}
);

//配置  成功后的拦截器
axiosInstance.interceptors.response.use(res => {
	if (res.data.status=== 200) {
		return res.data
	} else {
		return Promise.reject(res.data.msg);
	}
}, error => {
	return Promise.reject(error)
})

function request (){

}*/
