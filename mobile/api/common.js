import request from '@/common/request.js'
let common = {
	/**
	 * @author			前端小能手
	 * 用户逻辑接口
	 * login			登陆		account（账号，手机号）	password(密码)
	 * wxLogin			微信授权登陆
	 * getVerify		获取验证码
	 * register			注册		account(手机号)		password(密码)		captcha(验证码)		spread(邀请码)
	 * getUserCenter	用户中心
	 * codeLogin		验证码登陆	18275719628	123456
	 * getUserinfo		获取用户信息
	 * getIntegralList  获取积分明细列表
	 * userCollect		用户收藏
	 * collectDel		取消收藏
	 * getCollectList	收藏列表
	 * getUserFootList  获取用户足迹
	 * updateUserinfo	修改用户信息
	 * getRechargeMenu	获取会员套餐
	 */
	login(data) {
		return request('/user/login', 'post', data);
	},
	wxLogin(data) {
		return request('/api/wechat/mp_auth', 'post', data);
	},
	codeLogin(data) {
		return request('/api/login/mobile', 'post', data);
	},
	getVerify(data) {
		return request('/api/register/verify', 'post', data);
	},
	register(data) {
		return request('/api/register', 'post', data);
	},
	getUserCenter() {
		return request('/api/userCenter', 'get');
	},
	getUserinfo() {
		return request('/api/userinfo', 'get')
	},
	getIntegralList(data = '') {
		return request('/api/integral/list' + data, 'get')
	},
	userCollect(data) {
		return request('/api/collect/add', 'post', data)
	},
	getCollectList(data = '') {
		return request('/api/collect/user' + data, 'get')
	},
	collectDel(data) {
		return request('/api/collect/del', 'post', data)
	},
	getUserFootList(data = '') {
		return request('/api/collect/foot' + data, 'get')

	},
	delCollect(data) {
		return request('/api/collect/del', 'post', data)
	},
	updateUserinfo(data) {
		return request('/api/user/edit', 'post', data)
	},
	getRechargeMenu() {
		return request('/api/user/level/rechargeMenu', 'post')
	},

	 /** @author		前端小能手
	 * 首页
	 * getIndex		获取首页数据
	 */
	getSkeleton(data) {

		 // return  request.post('/home/skeleton', data)
		return request('/home/skeleton', 'post');
	},
	/**
	 *@author			前端小能手
	 * 发现页面
	 * getFind			获取发现页数据
	 */
	getFind() {
		return request('/api/discovery', 'get')
	},
	/**
	 * @author			前端小能手
	 * 分类
	 * getCategory		获取商品分类
	 */
	getCategory() {
		return request('/api/product/category', 'get')
	},

}
export default common
