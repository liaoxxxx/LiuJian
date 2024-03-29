import request from '@/common/request.js'
let address = {
	/**
	 * 地址相关
	 * getAddressList		获取地址列表
	 * getAddressDetail		获取地址详情（动态路由参数）
	 * editAddress			修改或添加地址
	 * delAddress			删除地址
	 * getAddressDef		获取默认地址
	 * setAddressDef		设置默认地址
	 */
	getAddressList(data) {
		return request('/userAddr/list' + data, 'get')
	},
	getAddressDetail(data = '') {
		return request('/userAddr/find?id=' + data, 'get')
	},
	editAddress(data) {
		return request('/userAddr/save', 'post', data)
	},
	delAddress(id) {
		return request('/userAddr/del?id='+id, 'get')
	},
	getAddressDef(data='') {
		return request('/api/address/default' + data, 'get')
	},
	setAddressDef(data) {
		return request('/api/address/default/set', 'post', data)
	}
	
}
export default address
