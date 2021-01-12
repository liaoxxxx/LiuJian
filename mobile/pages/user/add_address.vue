<template>
	<!-- 新增收货地址界面 -->
	<view class="content">
		<view class="addbox">
			<view class="addbox_left">
				<view class="addbox_title">收货人</view>
				<view class="addbox_center">
					<input v-model="name" placeholder-style="font-size:28rpx;color:#b5b5b5" placeholder="请填写收货人姓名" />
				</view>
			</view>
			<view class="addbox_right">
				<image class="add_img" src="../../static/user/add.png" mode="widthFix"></image>
			</view>
		</view>
		<view class="addbox">
			<view class="addbox_left">
				<view class="addbox_title">手机号码</view>
				<view class="addbox_center">
					<input type="number" maxlength="11" v-model="phone" placeholder-style="font-size:28rpx;color:#b5b5b5" placeholder="请填写收货人手机号" />
				</view>
			</view>
			<view class="addbox_right">
				<image class="add_img"></image>
			</view>
		</view>
		<view class="addbox" @tap="openAddres">
			<view class="addbox_left">
				<view class="addbox_title">所在地区</view>
			</view>
			<view v-if="defSite" class="flex-2 flex justify-start align-center flex-wrap" style="">
				<text class="font mr-1" v-for="(item, index) in defSite" :key="index">{{item}}</text>
			</view>
			<view v-else class="flex-2 flex justify-center align-center">
				<text class="font text-grey">请选择所在地区</text>
			</view>
			<view class="addbox_right">
				<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
			</view>
		</view>

		<view class="addbox">
			<view class="addbox_left">
				<view class="addbox_title">详细地址</view>
				<view class="addbox_center">
					<input v-model="addressDetail" placeholder-style="font-size:28rpx;color:#b5b5b5" placeholder="街道/楼牌号等" />
				</view>
			</view>
			<view class="addbox_right">
				<image class="add_img"></image>
			</view>
		</view>

		<view class="xian"></view>


		<view class="add_bottom">
			<view class="lable_box">
				<view class="lable_box_left">标签</view>
				<view class="lable_box_right">
					<view v-for="(item, index) in tags" :class="{'active':isActive==index}" :key="index" class="backnavbar_item"
					 @click="change(index)">{{item}}</view>
					<!-- <view :class="{'active':isActive==1}" class="backnavbar_item" @click="change(1)">家</view>
					<view :class="{'active':isActive==2}" class="backnavbar_item" @click="change(2)">公司</view>
					<view :class="{'active':isActive==3}" class="backnavbar_item" @click="change(3)">学校</view> -->
					<view class="backnavbar_item" @click="change(-1)">+</view>
				</view>
			</view>

			<view class="switch_box">
				<view class="switch_box_left">
					<view>设置默认地址</view>
					<view class="tishi">提醒：每次下单会默认推荐该地址</view>
				</view>
				<view class="switch_box_right">
					<!-- 开关选择器 -->
					<switch :checked="checked" @change="switchChange"></switch>
				</view>
			</view>

			<button class="btn" @click="submit">保存</button>
		</view>
		<simple-address ref="simpleAddress" :pickerValueDefault="cityPickerValueDefault" @onConfirm="onConfirm" themeColor="#007AFF"></simple-address>
		<uni-popup ref="popup" type="dialog">
			<uni-popup-dialog mode="input" placeholder="请输入长度小于10的标签" title="添加标签" type="input" message="成功消息" :duration="2000"
			 :before-close="true" @close="close" @confirm="confirm"></uni-popup-dialog>
		</uni-popup>
	</view>
</template>

<script>
	import simpleAddress from '@/components/simple-address/simple-address.vue'
	import uniPopup from '@/components/uni-popup/uni-popup.vue'
	import uniPopupDialog from '@/components/uni-popup/uni-popup-dialog.vue'
	import {
		vuexData
	} from '@/common/commonMixin.js'
	export default {
		components: {
			uniPopup,
			uniPopupDialog,
			simpleAddress
		},
		mixins: [vuexData],

		data() {
			return {
				isActive: null,
				checked: false,
				id: '', // 如果是修改地址信息，地址id
				name: '', // 收货人姓名
				phone: '', // 收货人手机号
				cityPickerValueDefault: [0, 0, 1], //省市区信息索引
				labelArr: [], // 省市区信息
				addressDetail: '', //详细地址信息
				tags: ['家', '公司', '学校'],
				isDefault: 0,
				updateCityPick: []
			}
		},
		methods: {
			async initAddress() {
				let res = await this.$api.getAddressDetail(this.id)

				if (res.errCode===0) {
					let address  = res.data.address
					this.name = address.real_name
					this.phone = address.phone
					this.addressDetail = address.detail
					this.checked = address.is_default === 1
					this.isDefault = address.is_default
					let arr = [address.province, address.city, address.district]
					this.updateCityPick = arr
				}
			},
			change(index) {
				if (index == -1) {
					this.$refs.popup.open()
				}
				this.isActive = index
			},
			close(done) {

				done()
			},

			confirm(done, value) {
				if (value.length > 10) {
					uni.showToast({
						icon: 'none',
						title: 'tag长度最多为3'
					})
				} else {
					this.tags.push(value)
				}
				done()
			},
			async submit() {
				// "data": {
				// 	"id": 1,
				// 	"real_name": "阿萨德",
				// 	"phone": "18275719628",
				// 	"province": "广西壮族自治区",
				// 	"city": "南宁市",
				// 	"district": "青秀区",
				// 	"detail": "民族大道1号人民大会堂",
				// 	"post_code": 530000,
				// 	"longitude": "0",
				// 	"latitude": "0",
				// 	"is_default": 0
				// }
				if (!this.name || this.name.length < 2) {
					uni.showToast({
						icon: 'none',
						title: '请填写正确姓名'
					})
					return
				}
				if (!this.phone || this.phone.length < 11) {
					uni.showToast({
						icon: 'none',
						title: '请填写正确手机号'
					})
					return
				}
				if (!this.addressDetail) {
					uni.showToast({
						icon: 'none',
						title: '请填写详细地址'
					})
					return
				}
				let data = {
					real_name: this.name,
					phone: this.phone,
					province: this.defSite[0],
					city: this.defSite[1],
					district: this.defSite[2],
					is_default: this.isDefault,
					post_code: 202020+"",
					detail: this.addressDetail
				}
				if (this.id) {
					data.id = this.id
				}
				let res = await this.$api.editAddress(data)
				let flag = this.checkRes(res, `${this.id ? '地址修改成功' : '地址添加成功'}`)
				if (flag) {
					uni.navigateTo({
						url: '/pages/user/address_list'
					})
				}

			},
			switchChange: function(e) {
				this.checked = !this.checked
				if (this.checked) {
					this.isDefault = 1
				} else {
					this.isDefault = 0
				}
			},
			openAddres() {
				// 根据 label 获取
				if (this.defSite) {
					let index = this.$refs.simpleAddress.queryIndex(this.defSite, 'label')
					this.cityPickerValueDefault = index.index
					this.$refs.simpleAddress.open()
				} else {
					this.cityPickerValueDefault = [0, 0, 1]
					this.$refs.simpleAddress.open();
				}

			},
			onConfirm(e) {
				console.log(e)
				let {
					labelArr
				} = e
				console.log(labelArr)
				this.labelArr = labelArr
				let index = this.$refs.simpleAddress.queryIndex(labelArr, 'label')
				console.log(index)
				this.cityPickerValueDefault = index.index
			}
		},
		computed: {
			defSite() {
				if (this.id) {
					if (this.labelArr.length > 0) {
						let defArr = this.labelArr
						return defArr
					}
					return this.updateCityPick
				} else {
					if (this.labelArr.length > 0) {
						let defArr = this.labelArr
						return defArr
					}
					if (this.site.city) {
						let {
							city,
							province,
							district
						} = this.site
						let defArr = [province, city, district]
						return defArr
					}
				}

			}
		},
		beforeDestroy() {
			uni.setStorageSync('addressTags', this.tags)
		},
		onLoad(option) {
			let tags = uni.getStorageSync('addressTags')
			if (tags) {
				this.tags = tags
			}
			console.log(tags)
			let {
				id
			} = option
			this.id = id
			if (id != undefined) {
				console.log(id)
				this.initAddress()
			}
		}
	}
</script>

<style>
	page {
		width: 100%;
		height: 100%;
	}

	.addbox {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
		height: 120rpx;
		padding: 0 30rpx;
		border-bottom: #e1e1e1 solid 1rpx;
	}

	.addbox_left {
		display: flex;
		flex-direction: row;
		flex: 1;
	}

	.addbox_title {
		font-size: 30rpx;
		flex: 1;
	}

	input {
		/* background: #07C160; */
	}

	.addbox_center {
		flex: 3;
		width: 100%;
	}

	.add_img {
		width: 46rpx;
		height: 46rpx;
	}

	.more_img {
		width: 40rpx;
		height: 40rpx;
	}

	.lable_box {
		display: flex;
		flex-direction: row;
		padding: 30rpx;
		border-bottom: #e1e1e1 solid 1rpx;
	}

	.lable_box_left {
		flex: 1;
		font-size: 30rpx;
	}

	.lable_box_right {
		display: flex;
		flex-direction: row;
		background: #fff;
		/* padding: 36rpx 15rpx; */
		flex-wrap: wrap;
		flex: 4;
	}

	.backnavbar_item {
		color: #999;
		height: 64rpx;
		line-height: 64rpx;
		padding: 0 50rpx;
		font-size: 28rpx;
		text-align: center;
		border: solid #999 1px;
		border-radius: 50rpx;
		margin: 15rpx;
	}

	.active {
		color: #f94e2f;
		border: solid #f94e2f 1px;
	}

	.switch_box {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
		font-size: 30rpx;
		padding: 30rpx;
	}

	.tishi {
		font-size: 26rpx;
		color: #b5b5b5;
		padding-top: 20rpx;
	}

	.xian {
		height: 8rpx;
		background: #f5f5f5;
	}


	button::after {
		border: none;
	}

	.btn {
		background: #f63b0d;
		color: #fff;
		border-radius: 50rpx;
		font-size: 32rpx;
		width: 92%;
		margin: 0 auto;
		margin-top: 120rpx;
		/* position: absolute;
		bottom: 5%;
		left: 4%;
		right: 4%; */
	}
</style>
