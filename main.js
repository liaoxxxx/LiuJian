import Vue from 'vue'
import App from './App'
// 引用第三方
import store from './store'

import request from './common/request.js'
import api from './api/index.js'
import url from './common/config.js'
import * as util from './common/util.js'
// 自定义组件
import footerTabbar from './components/page/footer-tabbar.vue'
import halvingLine from './components/halving_line.vue'
import loadingModule from './components/loadingModule.vue'
import myBtn from './components/myButton.vue'
import myLoading from './components/loading.vue'
Vue.component('footer-tabbar', footerTabbar)
Vue.component('halving-line', halvingLine)
Vue.component('loading-module', loadingModule)
Vue.component('my-btn', myBtn)
Vue.component('my-loading', myLoading)
Vue.prototype.$store = store
Vue.config.productionTip = false
Vue.prototype.$request = request
Vue.prototype.$api = api
Vue.prototype.$url = url
Vue.prototype.$util = util
if (process.env.NODE_ENV  === 'development') {
	Vue.prototype.ftpserver = 'http://download.yisuapp.cn'
} else {
	Vue.prototype.ftpserver = 'http://download.yisuapp.cn'
}
Vue.prototype.checkLogin = function(backpage, backtype){
	let userInfo  = uni.getStorageSync('userlogininfo')
	if(userInfo == '') {
		uni.redirectTo({url:'/pages/login/login?backpage='+backpage+'&backtype='+backtype})
		return false
	} else {
		api.info({token: userInfo.token}).then(res => {
			uni.setStorage({
				key: 'userlogininfo',
				data: {
					token: userInfo.token,
					userInfo: res.data.data
				}
			})
			userInfo = {
				token: userInfo.token,
				userInfo: res.data.data
			}
		}).catch(res => {
		　　console.log(res)
		})
	}
	return userInfo
}
Vue.prototype.doLogout = function() {
	let userInfo  = uni.getStorageSync('userlogininfo')
	api.logout({token: userInfo.token}).then(res => {
		uni.redirectTo({url:'/pages/login/login'})
		uni.removeStorageSync('userlogininfo')
	}).catch(res => {
	　　console.log(res)
	})
}
App.mpType = 'app'
const app = new Vue({
	store,
	util,
    ...App
})

app.$mount()
