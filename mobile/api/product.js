import request from '@/common/request.js'
let product = {
	/**
	 * 商品相关接口
	 * getProductList		商品列表		sid(商品id)	cid(父级id)	keyword(搜索关键字)	priceOrder(价格排序)	salesOrder(销量排序)	news(是否新品)	page(当前页)
	 * getProductDetail		获取商品详情
	 * getEvalList			获取产品评价列表
	 * getHotGoods			获取推荐商品
	 */
	getProductList(data) {
		return request('/api/product/list' + data, 'get')
	},
	getHot(data) {
		return request('/api/product/hot' + data, 'get')
	},
	getProductDetail(data) {
		return request('/api/product/detail/' + data, 'get')
	},
	getEvalList(data='') {
		return request('/api/product/reply/list/' + data, 'get')
	},
	getHotGoods(data='') {
		return request('/api/product/hot' + data, 'get')
	}
}
export default product
