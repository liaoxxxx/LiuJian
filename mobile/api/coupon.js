import request from '@/common/request.js'
let coupon = {
	/**
	 * 优惠卷
	 * getCouponList		获取可领取优惠卷列表
	 * exchangeCoupon		兑换优惠卷
	 * getCouponOrder		获取优惠卷订单列表
	 * getUserCoupon		获取用户已领取优惠卷
	 * usrAddCoupon			领取优惠卷
	 */
	getCouponList() {
		return request('/api/coupon/couponsList', 'get')
	},
	exchangeCoupon(data) {
		return request('/api/coupon/exchange', 'post', data)
	},
	getCouponOrder(data='') {
		return request('/api/coupon/order/' + data, 'get')
	},
	getUserCoupon(data=0) {
		return request('/api/coupon/user/' + data, 'get')
	},
	usrAddCoupon(data) {
		return request('/api/coupon/receive', 'post', data)
	}
	
	
}
export default coupon
