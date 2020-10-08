import Vue from 'vue'; // 引入vue
import app from '../config/app.json';

let vm = new Vue();

/**
 * 判断字符串是否以给定字符串开头
 * haystack: http://www.ebestmall.com
 * needles: http
 */
export const startsWith = function(haystack, needles) {
	let newStr = haystack.indexOf(needles);
	if (newStr == 0) {
		return true;
	} else if (newStr == -1) {
		return false;
	} else {
		return false;
	}
}

/**
 * 获取首页路径
 */
export const getHomePagePath = function() {
	let homePage = '/pages/index/index';
	return homePage;
}

/**
 * 获取当前页面路由
 */
export const getCurrentRoute = function() {
	let pageRoutes = getCurrentPages();
	let route = pageRoutes[pageRoutes.length - 1].route;
	return route;
}

/**
 * 获取上一页面路由
 */
export const getBackRoute = function(delta = 1, full = true) {
	let homePagePath = this.getHomePagePath();
	let pages = getCurrentPages();
	// let currentPage = pages[pages.length - 1];
	let previousPage = pages[pages.length - 1 - delta];
	if (previousPage) {
		// console.log('返回路径', previousPage.route);
		// console.log('previousPage', previousPage);
		if (previousPage.$mp) {
			let params = previousPage.$mp.query;
			if (params && full) {
				return previousPage.route + this.urlEncode(params, true);
			} else {
				return previousPage.route;
			}
		}
		// 小程序/5+App
		if (previousPage.options) {
			let mpParams = previousPage.options;
			if (mpParams && full) {
				return previousPage.route + this.urlEncode(mpParams, true);
			} else {
				return previousPage.route;
			}
		}
	} else {
		return homePagePath;
	}
}

/**
 * 返回上一页
 * 
 * 特别注意: 该方法不能在watch里面使用
 */
export const backPage = function(page = 1) {
	let backUrl = this.getBackRoute(page);
	if (!startsWith(backUrl, "/")) {
		backUrl = '/' + backUrl;
	}
	this.redirect(backUrl);
}

/**
 * 对象转url参数
 * @param {*} data
 * @param {*} isPrefix
 */
export const urlEncode = function(data, isPrefix = false) {
	let prefix = isPrefix ? '?' : ''
	let _result = []
	for (let key in data) {
		let value = data[key]
		// 去掉为空的参数
		if (['', undefined, null].includes(value)) {
			continue
		}
		if (value.constructor === Array) {
			value.forEach(_value => {
				_result.push(encodeURIComponent(key) + '[]=' + encodeURIComponent(_value))
			})
		} else {
			_result.push(encodeURIComponent(key) + '=' + encodeURIComponent(value))
		}
	}
	return _result.length ? prefix + _result.join('&') : ''
}

/**
 * 重定向，自动判断navigateTo|switchTab|reLaunch
 * this.$util.redirect('/pages/user/user')
 */
export const redirect = function(url) {
	if (startsWith(url, "http")) {
		redirectFail(url);
		return;
	}

	if (url !== '' && !startsWith(url, "/")) {
		url = '/' + url;
	}

	let method = 'navigateTo';
	let tabBarList = vm.$store.state.tabbar.tabBar.list;
	tabBarList.forEach((item, index) => {
		if (('/' + item.pagePath) == url) {
			method = 'switchTab';
		}
	});
	switch (method) {
		case 'switchTab':
			uni.switchTab({
				url: url,
				success: res => {},
				fail: () => {
					redirectFail(url);
				},
				complete: () => {}
			});
			break;
		case 'navigateTo':
			uni.navigateTo({
				url: url,
				success: res => {},
				fail: () => {
					redirectFail(url);
				},
				complete: () => {}
			});
			break;
		default:
			uni.reLaunch({
				url: url,
				success: res => {},
				fail: () => {
					redirectFail(url);
				},
				complete: () => {}
			});
	}

	function redirectFail(url) {
		if (startsWith(url, "http")) {
			// #ifdef MP
			uni.navigateTo({
				url: '/pages/web-view/web-view?url=' + url,
				success: res => {},
				fail: () => {
					redirectFail(url);
				},
				complete: () => {}
			});
			// #endif

			// #ifdef H5
			// window.location.href = 'http://wpa.qq.com/msgrd?v=3&uin=' + qq + '&site=qq&menu=yes';
			// window.location.href = url;
			window.open(url)
			// #endif

			// #ifdef APP-PLUS
			/* plus.runtime.openURL(
				'mqq://im/chat?chat_type=wpa&uin=' + qq + '&version=1&src_type=web'
			); */
			plus.runtime.openWeb(url);
			// #endif
		} else {
			uni.showToast({
				icon: 'none',
				title: '"' + url + '"不是有效的连接',
				duration: 1500
			});
			return;
		}
	}
}

/**
 * 根据app.json动态设置tabBar
 */
export const setTabBarSync = function() {
	let tabBar = vm.$store.state.tabbar.tabBar;
	uni.setTabBarStyle({
		color: tabBar.color,
		selectedColor: tabBar.selectedColor,
		backgroundColor: tabBar.backgroundColor,
		borderStyle: tabBar.borderStyle
	});
	tabBar.list.forEach((item, index) => {
		uni.setTabBarItem({
			index: index,
			pagePath: item.pagePath,
			iconPath: item.iconPath,
			selectedIconPath: item.selectedIconPath,
			text: item.text
		})
	});
}

/**
 * 路由权限拦截
 */
export const authRoutes = function(path = '', role = 'user') {
	// 使用方式1：onLoad() {this.$util.authRoutes();}
	// 使用方式2：onLoad() {this.$util.authRoutes(this.$util.getCurrentRoute(), 'user');}
	if (!path) {
		path = this.getCurrentRoute();
	}
	let routes = app.authRoute.list;
	let state = vm.$store.state;
	routes.forEach(item => {
		if (item.path == path && item.role == role) {
			if (!state.hasLogin) {
				this.loginShowModal(true);
			}
		}
	})
}

/**
 * 登录提示框
 */
export const loginShowModal = function(forcedLogin) {
	let state = vm.$store.state;

	uni.showModal({
		title: '未登录',
		content: '您未登录，需要登入后才能继续',
		/**
		 * 如果需要强制登录，不显示取消按钮
		 */
		// showCancel: !forcedLogin,
		success: (res) => {
			if (res.confirm) {
				/**
				 * 如果需要强制登录，使用reLaunch方式
				 */
				if (state.forcedLogin) {
					uni.reLaunch({
						url: '/pages/auth/login'
					});
				} else {
					uni.navigateTo({
						url: '/pages/auth/login'
					});
				}
			} else if (forcedLogin) {
				if (getCurrentPages().length >= 1) {
					uni.navigateBack({
						delta: 1
					});
				} else {
					uni.reLaunch({
						url: '/pages/index/index'
					});
				}

			}
		}
	});
}

export const now = Date.now || function() {
	return new Date().getTime();
};

export const isArray = Array.isArray || function(obj) {
	return obj instanceof Array;
};

export function dateFormat(obj) {
	let year = obj.getFullYear();
	let month = ("0" + (obj.getMonth() + 1)).slice(-2);
	let day = ("0" + obj.getDate()).slice(-2);
	return year + "-" + month + "-" + day;
}

export function timeFormat(dateTimeStamp) {}

export const numberFormat = function(value, decimal = false, prefix = '', suffix = '') {
	var obj = {
		prefix: prefix || "", // 前缀：￥|$
		suffix: suffix || "", // 后缀：元|件|+|-
		decimal: decimal || false, // 是否显示小数点
		int: undefined, //整数位
		dec: undefined, //小数位
		targ: "", //正负
		times: ['', '万', '亿', '万亿', '亿亿']
	}
	value = String(value);
	var reg = /^-?\d+\.?\d+$/;
	if (!reg.test(value)) {
		//console.log("请输入数字");
		return 0;
	}

	if (value[0] == "-") {
		obj.targ = "-";
		value = value.substring(1, value.length)
	}

	var times = 0;
	value = Number(value);
	while (value > 10000) {
		value = value / 10000;
		times++;
	}

	value = value.toFixed(2)

	var arr = String(value).split(".")
	obj.dec = arr[1];
	obj.int = arr[0];
	if (obj.int.length > 3) {
		obj.int = obj.int.replace(/(.{1})/, '$1,')
	}
	if (obj.decimal) {
		return obj.prefix + obj.targ + obj.int + "." + obj.dec + obj.times[times] + obj.suffix;
	} else {
		return obj.prefix + obj.targ + obj.int + obj.times[times] + obj.suffix;
	}
}

export function object_merge(object1, object2) {
	return Object.assign(object1, object2);
}

export function toTree(data, parentKey = 'parent_id', childrenKey = 'children') {
	// 删除 所有 children,以防止多次调用
	data.forEach(function(item) {
		delete item[childrenKey];
	});

	// 将数据存储为 以 id 为 KEY 的 map 索引数据列
	var map = {};
	data.forEach(function(item) {
		map[item.id] = item;
	});

	// console.log('map', map);

	var val = [];
	data.forEach(function(item) {

		// 以当前遍历项，的parent_id,去map对象中找到索引的id
		var parent = map[item[parentKey]];

		// 如果找到索引，那么说明此项不在顶级当中,那么需要把此项添加到，他对应的父级中
		if (parent) {

			(parent[childrenKey] || (parent[childrenKey] = [])).push(item);

		} else {
			//如果没有在map中找到对应的索引ID,那么直接把 当前的item添加到 val结果集中，作为顶级
			val.push(item);
		}
	});

	return val;
}

/* 数组分组 */
export const dataGroupBy = function(array, func) {
	let groups = {};
	array.forEach(function(o) {
		let group = JSON.stringify(func(o));
		groups[group] = groups[group] || [];
		groups[group].push(o);
	});
	return Object.keys(groups).map(function(group) {
		return groups[group];
	});
}

/* 判断对象{}是否为空 */
export const isEmptyObject = function(obj) {
	for (var key in obj) {
		return false; //返回false，不为空对象
	}
	return true; //返回true，为空对象
}

export const getQueryString = function(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if (r != null) return unescape(r[2]);
	return null;
}
