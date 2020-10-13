<template>
	<view class="set_site_wrapper position-relative">
		<nav-bar bgColor="#ff4b2d" v-model="inputValue" @input="delay(handleInput)" :navTop="'0'" showSearch placeholder="请输入小区/大厦等名称">
			<view slot="leftBtn" class="text-white px-2" hover-class="text-hover-secondary">
				<text class="font-md mr-1">{{ site.city ? site.city : '定位中'}}</text>
				<text class="iconfont font-lg icon-triangle-bottom "></text>
			</view>
			<view slot="rightBtn" v-if="searchNearby.length > 1" class="text-white px-2" @tap="hideSearch" hover-class="text-hover-secondary">
				<text class="mr-1">取消</text>
			</view>
			<view v-else slot="rightBtn" class="text-white px-2" @tap="toMap" hover-class="text-hover-secondary">
				<text class="iconfont icon-ditu mr-1" style="font-size: 50rpx;"></text>
			</view>
		</nav-bar>
		<view id="position-scroll">
			<halving-line content="当前地址" light></halving-line>
			<view class="flex align-center py-3 font justify-between px-2">
				<text class="flex-5">{{ address ? address : '暂未获取到定位信息...' }}</text>
				<view class="flex flex-2 align-center" hover-class="text-hover-secondary">
					<text class="iconfont font-lg icon-loc-s mr-1"></text>
					<text> 重新定位 </text>
				</view>
			</view>
			<halving-line content="收货地址" light></halving-line>
			<view class="flex align-center py-3 font justify-center px-2">
				<text class="mr-2">这块呢暂时没找到和哪里对接</text>
				<view class="text-danger" hover-class="bg-grey">
					<text>登陆</text>
				</view>
			</view>
			<halving-line content="附近地址" light></halving-line>
		</view>
		<scroll-view scroll-y="true" class="position-absolute w-100" style="padding-bottom: 80rpx;" :style="scorllPosition">
			<position-list-item v-for="item in nearby" :key="item.id" :title="item.name" :content="site.city + ' ' + item.address "
			 :icon="'\ue611'">
				<view slot="right">
					<radio @tap="checkAddress(item.id)" :checked="radioAddress==item.id" :value="item.id"></radio>
				</view>
			</position-list-item>
		</scroll-view>
		<view @tap="hideSearch" v-if="searchNearby.length > 1" class="position-absolute w-100" :style="topScorllPosition">
			<scroll-view scroll-y="true" class="position-absolute bg-white top_scroll">
				<position-list-item @handleTap="searchItemTap(item.id)" v-for="item in searchNearby" :key="item.id" :title="item.name"
				 :icon="'\ue611'">
					<view slot="right">
						<text class="font-sm text-grey"> {{item.address | checkStr}} </text>
					</view>
				</position-list-item>
			</scroll-view>
		</view>
	</view>
</template>

<script>
	import {
		vuexData
	} from '../../common/commonMixin.js'
	import NavBar from '@/components/navBar.vue'
	import positionListItem from '@/components/position_list_item.vue'
	export default {
		data() {
			return {
				positionHeight: 0,
				radioAddress: '',
				windowHeight: 0,
				inputValue: ''
			}
		},
		mixins: [vuexData],
		components: {
			NavBar,
			positionListItem
		},
		filters: {
			checkStr(val) {
				if (typeof val == 'string') {
					return val
				} else {
					return ' '
				}
			}
		},
		computed: {
			scorllPosition() {
				let top = this.positionHeight
				top = uni.upx2px(120) + top
				return `top: ${top}px; left: 0; bottom: 0; right: 0;`
			},
			windowSize() {
				return `height: ${this.windowHeight}px`
			},
			topScorllPosition() {
				let height = this.windowHeight - uni.upx2px(80)
				return `height: ${height}px; top: 80rpx; background-color: rgba(1, 1,1, .3)`
			}
		},
		methods: {
			getElSize(id) {
				return new Promise((res, rej) => {
					uni.createSelectorQuery().in(this).select(id).boundingClientRect(data => {
						res(data);
					}).exec();
				})
			},
			handleInput() {
				this.getSiteForKeyword({
					keyword: this.inputValue,
					location: `${this.location.lng},${this.location.lat}`
				})
			},
			checkAddress(id) {
				this.radioAddress = id
			},
			// 跳转地图模式
			toMap() {
				uni.navigateTo({
					url: '/pages/index/set_site_map'
				})
			},
			hideSearch() {
				this.getSiteForKeyword({
					keyword: '',
					location: `${this.location.lng},${this.location.lat}`
				})
			},
			searchItemTap(id) {
				uni.showModal({
					title: '当前地址id',
					content: id + ' 下一步怎么接呢？'
				})
			}
		},
		async onReady() {
			this.$nextTick(async () => {
				let sizeData = await this.getElSize('#position-scroll')
				let {
					height
				} = sizeData
				this.positionHeight = height
				console.log(height)
				const {
					windowHeight
				} = uni.getSystemInfoSync()
				this.windowHeight = windowHeight
			})
		}
	}
</script>

<style scoped>
	.set_site_wrapper {
		padding-top: 80rpx;
		height: 100vh;
	}

	.top_scroll {
		height: 800rpx;
	}
</style>
