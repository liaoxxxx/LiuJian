import request from '@/common/request.js'
let cart = {
	/**
	 * 购物车逻辑
	 * getCartList		获取购物车列表
	 * getCartCount		获取购物车数量
	 * delCart			删除购物车的商品
	 * addCart			添加商品到购物车
	 * cartUpdate		修改购物车商品数量
	 */
	getCartList() {
		return request('/api/cart/list', 'get')
	},
	getCartCount() {
		return request('/api/cart/count', 'get')
	},
	delCart(data) {
		return request('/api/cart/del', 'post', data)
	},
	addCart(data) {
		return request('/api/cart/add', 'post', data)
	},
	cartUpdate(data) {
		return request('/api/cart/addNum', 'post', data)
	},
	/**
	 *订单逻辑 
	 * getOrderList		获取订单列表
	 * getOrderDetail	获取订单详情
	 * getOrderExpress	获取订单物流
	 * addComment		订单评价
	 * getProduct		订单产品信息
	 * createOrder		创建订单
	 * cancelOrder		取消订单
	 * delOrder			删除订单
	 * getOrderDetail	获取订单详情
	 * getRefund		获取退款理由
	 */
	getOrderList(data = '') {
		return request('/api/order/list' + data, 'get')
	},
	getOrderDetail(data) {
		return request('/api/order/detail/' + data, 'get')
	},
	getOrderExpress(data) {
		return request('/api/order/express/' + data, 'get')
	},
	addComment(data) {
		return request('/api/order/comment', 'post', data)
	},
	getProduct(data) {
		return request('/api/order/product', 'post', data)
	},
	create(data) {
		return  request('/order/create', 'post', data)
	},
	cancelOrder(data) {
		return request('/api/order/cancel', 'post', data)
	},
	delOrder(data) {
		return request('/api/order/del', 'post', data)
	},

	getRefund() {
		return request('/api/order/refund/reason', 'get')
	},
	
	// 订单支付 orderPay
	orderPay(data) {
		return request('/api/order/pay', 'post', data)
	}
	
	
}
export default cart