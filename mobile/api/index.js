import common from './common.js' // 公共api引入
import product from './product.js' // 商品相关
import cart from './cart.js' //购物车相关
import address from './address.js'	// 收货地址
import coupon from './coupon.js'	// 优惠卷
// 公共api
export default {
	...common,
	...product,
	...cart,
	...address,
	...coupon
}
