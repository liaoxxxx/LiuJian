<template>
	<!-- 设置页面 -->
	<view class="content">
		<view class="setmain" v-if="userinfo">
			<view class="setlist_top" @tap="setAvatar">
				<view class="setlist_left">
					<view>头像</view>
				</view>
				<view class="setlist_right">
					<image class="head_img" :src="tempPath ? tempPath : userinfo.avatar" style="height: 90rpx; width: 90rpx;"></image>
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>

			<view class="setlist" @click="openNickname">
				<view class="setlist_left">
					<view>昵称</view>
				</view>
				<view class="setlist_right">
					<view>{{newName ? newName : userinfo.nickname}}</view>
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>

			<view class="setlist">
				<view class="setlist_left">
					<view>性别</view>
				</view>
				<view class="setlist_right">
					<view>
						<switch style="transform: scale(0.6);" @change="checkSex" :checked="sexId" /> {{sexId ? '男' : '女'}}</view>
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>

			<view class="setlist" @tap="onShowDatePicker('date')">
				<view class="setlist_left">
					<view>生日</view>
				</view>
				<view class="setlist_right">
					<view>{{newBirthday ? newBirthday : userinfo.birthday}}</view>
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>
			<view class="setlist" @click="changePass">
				<view class="setlist_left">
					<view>登录密码</view>
				</view>
				<view class="setlist_right">
					<view>设置密码以保护账户安全</view>
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>

			<view class="setlist" @click="modify_phone">
				<view class="setlist_left">
					<view>绑定手机</view>
				</view>
				<view class="setlist_right">
					<view>{{userinfo.phone}}</view>
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>


			<view class="xian"></view>

			<view class="setlist_top" @click="openAbout">
				<view class="setlist_left">
					<view>关于我们</view>
				</view>
				<view class="setlist_right">
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>

			<view class="setlist">
				<view class="setlist_left">
					<view>当前版本</view>
				</view>
				<view class="setlist_right">
					<view>V1.0.0</view>
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>

			<view class="setlist">
				<view class="setlist_left">
					<view>清除缓存</view>
				</view>
				<view class="setlist_right">
					<view>12M</view>
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>
			<view class="w-100 flex justify-center my-3 font">
				<button type="default" class="text-grey" style="width: 600rpx;" @tap="exit">退出登陆</button>
			</view>
		</view>
		<uni-popup ref="popup" type="dialog">
			<uni-popup-dialog mode="input" placeholder="4~20个字符,可由数字中英文'_''.'组成" title="修改昵称" type="input" message="成功消息"
			 :duration="2000" :before-close="true" @close="close" @confirm="confirm"></uni-popup-dialog>
		</uni-popup>
		<mx-date-picker :show="showPicker" :type="type" format="yyyy-mm-dd" :value="newBirthday ? newBirthday : userinfo.birthday" :show-tips="true"
		 :show-seconds="true" @confirm="onSelected" @cancel="onSelected" />
	</view>
</template>

<script>
	import {
		vuexData
	} from '@/common/commonMixin.js'
	import uniPopup from '@/components/uni-popup/uni-popup.vue'
	import uniPopupDialog from '@/components/uni-popup/uni-popup-dialog.vue'
	import pickerPlus from '@/components/e-picker-plus/e-picker-plus.vue'
	import MxDatePicker from "@/components/mx-datepicker/mx-datepicker.vue";
	import moment from '@/common/moment.js'
	export default {
		// /^[a-zA-Z0-9_-]{4,16}$/;
		data() {
			return {
				newName: '',
				reg: /^[0-9A-Za-z\u4e00-\u9fa5_-]{4,16}$/,
				sexId: 0,
				newBirthday: '',
				tempPath: '',
				show: false,
				type: '',
				showPicker: false,
				value: '',
				url: ''
			}
		},
		mixins: [vuexData],
		components: {
			uniPopup,
			uniPopupDialog,
			pickerPlus,
			MxDatePicker
		},
		onLoad() {

		},
		computed: {
			nowTime() {
				let time = moment(Date.now() + 1800000).format('YYYY-MM-DD HH:mm')
				return time
			}
		},
		methods: {
			setAvatar() {
				uni.chooseImage({
					success: (chooseImageRes) => {
						console.log(chooseImageRes)
						this.tempPath = chooseImageRes.tempFiles[0].path
						const tempFilePaths = chooseImageRes.tempFilePaths;
						let token = uni.getStorageSync('token')
						uni.showLoading({
							title: '头像上传中'
						})
						uni.uploadFile({
						    url: 'http://111.229.128.239:1003/api/upload/image', //仅为示例，非真实的接口地址
						    filePath: tempFilePaths[0],
						    name: 'file',
							header: {token},
						    success: (uploadFileRes) => {
								uni.hideLoading()
						        if (uploadFileRes.statusCode == 200) {
									let {data} = uploadFileRes
									data = JSON.parse(data)
									let flag = this.checkRes(data, '上传成功')
									if (flag) {
										let imgRes = data.data
										let {url} = imgRes
										this.url = url
									}
								} else {
									uni.showToast({
										icon: 'none',
										title: '上传失败请稍后再试'
									})
								}
						    },
							fail(err) {
								console.log(err)
							}
						})
					}
				})
			},
			openNickname() {
				this.$refs.popup.open()
			},
			close(done) {
				done()
			},
			checkSex() {
				this.sexId = !this.sexId

			},
			onShowDatePicker(type) {
				this.type = type;
				this.showPicker = true
			},
			onSelected(e) { //选择
				this.showPicker = false;
				if (e) {
					this.newBirthday = e.value;
				}
			},
			confirm(done, value) {
				if (!this.reg.test(value)) {
					uni.showToast({
						title: '请输入符合规则的昵称',
						icon: 'none'
					})
					return
				} else {
					this.newName = value
					done()
				}
			},
			checkTime(e) {
				let {
					result
				} = e
				console.log(e, result, moment(result).format('X'))
				this.newBirthday = result
			},
			modify_phone() {
				uni.navigateTo({
					url: 'modify_phone'
				})
			},
			changePass() {
				uni.navigateTo({
					url: 'changePass'
				})
			},
			openAbout() {
				uni.navigateTo({
					url: 'about'
				})
			},
			exit() {
				uni.removeStorageSync('token')
				uni.showToast({
					title: '用户已退出'
				})
				uni.navigateTo({
					url: '/pages/login/login_mode'
				})
			},
			initSex() {
				let sex = this.userinfo.gender
				this.sexId = sex == '男' ? true : false
			}

		},
		async onReady() {
			let userinfo = await this.$api.getUserinfo()
			this.getUserinfo(userinfo)
			console.log(userinfo)
			this.initSex()
		},
		async beforeDestroy() {
 			let res = await this.$api.updateUserinfo({
				nickname: this.newName,
				gender: this.sexId ? 1 : 0,
				birthday: this.newBirthday ? moment(this.newBirthday).format('X') : '',
				avatar: this.url
			})
		}
	}
</script>

<style>
	page {
		background: #fff;
	}

	.content {
		background: #f5f5f5;
	}



	.setmain {
		background: #fff;
	}

	.xian {
		height: 15rpx;
		background: #f5f5f5;
	}

	.setlist_top {
		margin: 0 30rpx;
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
		border-bottom: solid #e1e1e1 1rpx;
		height: 130rpx;
	}

	.setlist {
		margin: 0 30rpx;
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
		border-bottom: solid #e1e1e1 1rpx;
		height: 110rpx;
	}

	.setlist_left {
		font-size: 30rpx;
	}

	.setlist_right {
		display: flex;
		flex-direction: row;
		align-items: center;
		color: #9a9a9a;
		font-size: 26rpx;
	}

	.head_img {
		height: 100rpx;
		width: 100rpx;
		border-radius: 50%;
	}

	.more_img {
		height: 36rpx;
		width: 36rpx;
		margin-left: 15rpx;
	}
</style>
