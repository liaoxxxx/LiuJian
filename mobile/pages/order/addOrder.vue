<template>
	<view class="page">
		<scroll-view class="position-absolute w-100 top-0 main" scroll-y="true" :style="scrollStyle">
			<view>
        <view class="recycle-cate-block first-layer-block">
          <text class="first-layer-title">回收品类</text>
          <view class="recycle-cate-row">
            <view @click="selectRecycleCate(item,index)" v-for="(item ,index) in recycleCateList" :class="{'recycle-cate-item':true,'recycle-cate-selected':index===recycleCateSelectedIndex}" >
              <view>
                <img :src="item.src" alt="">
              </view>
              <text>{{item.name}}</text>
            </view>
          </view>
        </view>

        <halving-line bgColor="#eee"></halving-line>
        <view class="first-layer-block">
          <text class="first-layer-title">今日指导价</text>
          <u-cell-group v-for="gItem in guidePriceList" >
            <u-cell-item   :title="gItem.name" :label="gItem.desc" :arrow="false">
              <view slot="right-icon" >
                <text>{{gItem.num}}元/{{gItem.unit}}</text>
              </view>
            </u-cell-item>
          </u-cell-group>
        </view>


        <halving-line bgColor="#eee"></halving-line>
        <view class="recycle-require-block first-layer-block">
          <text class="first-layer-title">回收要求</text>
          <view class="recycle-require-row">
            <view v-for="item in requireList" class="recycle-require-item">
              <view>
                <img :src="item.src" alt="">
              </view>
              <text>{{item.name}}</text>
            </view>
          </view>
        </view>

        <halving-line bgColor="#eee"></halving-line>
        <view class="recycle-weight-block first-layer-block">
          <text class="first-layer-title">预估重量</text>
          <view class="recycle-weight-row">
            <view @click="selectRecycleWeight(item,index)" v-for="(item,index) in weightList" :class="{'recycle-weight-item':true,'recycle-weight-selected':index===recycleWeightSelectedIndex}"  >
              <view>
                {{item.text}}
              </view>
              <text>{{item.name}}</text>
            </view>
          </view>
          <view class="recycle-weight-add-row">
            <view>
              <text>未满100公斤，不需要添加图片</text>
            </view>
            <view>
              <button class="recycle-weight-add-btn">+ 添加品类</button>
            </view>
          </view>

          <view class="recycle-weight-notice">
            <view class="recycle-weight-notice-title">注意事项</view>
            <view class="recycle-weight-notice-content">
              <text>1. 因为回收成本原因，社区，写字楼，单元楼价格面谈</text>
              <br>
              <text>2. 小于10公斤暂不保证上门回收</text>
              <br>
              <text>3. 重量超过100公斤序提交照片供回收员参考</text>
            </view>
          </view>
        </view>


        <halving-line bgColor="#eee"></halving-line>
				<view class="flex flex-column first-layer-block">
					<list-item v-if="orderInfo.productInfo" class="mb-3" :content="`共`+ orderInfo.productTotalNum +'件' " :wraStyle="{padding: '20rpx 20rpx 20rpx 40rpx '}"
					 :contentFont="{fontSize: '26rpx', color: '#123', fontWeight: 400 }">
						<view slot="left" class="flex flex-nowrap overflow-hidden">
							<image v-for="item in orderInfo.productInfo" class="mr-1" style="height: 150rpx; width: 150rpx" :src=" item.image | checkImg"
							 mode=""></image>
						</view>
						<view slot="right" class="flex-1 flex justify-center">
							<text class="iconfont text-grey">&#xe708;</text>
						</view>
					</list-item>
				</view>
			</view>

      <view class="px-2 py-1 first-layer-block">
        <view class="flex flex-column px-2">
          <view class="mb-2">
            <text class="font-sm first-layer-title">订单备注</text>
          </view>
          <view>
            <textarea style="height: 150rpx; width: 700rpx;" class="font-sm" v-model="remark" placeholder="选填,给我留言吧～～"/>
          </view>
        </view>
        <list-item title="预约时间" :content="time | format">
          <view slot="right" class="flex-1 flex justify-center">
            <picker-plus @confirm="checkTime" :startRule="nowTime" mode="YMDhm">
              <text class="iconfont text-grey">&#xe708;</text>
            </picker-plus>
          </view>
        </list-item>
        <list-item title="预约地址">
        </list-item>
        <address-item showImg @handleTap="setAddress" v-if="addressList.length > 0" :address="defAddress">
        </address-item>
        <view v-else class="border rounded flex align-center justify-between py-5">
          <view class="flex-2 flex justify-center align-center">
            <text class="iconfont text-grey" style="font-size: 50rpx;">&#xe60e;</text>
          </view>
          <text @click="setAddress" class=" flex-6 flex align-center justify-between text-grey"
                style="text-decoration: underline; font-style: oblique;">前往设置收货地址>>
          </text>
        </view>
      </view>
      <halving-line bgColor="#eee"></halving-line>
      <!-- 底部 -->
      <view class="bottom-block     bg-white font">
        <view class="add-order-exceptions-row">
          <view>确认下单将自动默认同意
            <text class="add-order-exceptions">《小胖纸上门回收免责条款》</text>
          </view>
        </view>
        <view class="add-order-row">
          <button @click="addOrder('giving')" class="add-order-button">公益赠送</button>
          <button @click="addOrder('appointment')" class="add-order-button">立即预约</button>
        </view>
      </view>
		</scroll-view>

	</view>
</template>

<script>
import tag from '@/components/tag.vue'
import listItem from '@/components/list_item.vue'
import pickerPlus from '@/components/e-picker-plus/e-picker-plus.vue'
import {vuexData} from '@/common/commonMixin.js'
import moment from '@/common/moment.js'
import addressItem from '@/components/address_item.vue'

export default {
		components: {
			tag,
			listItem,
			pickerPlus,
			addressItem
		},
		mixins: [vuexData],
		filters: {
			format(str) {
				let nowDay = moment().format('YYYY-MM-DD').split(' ')[0].split('-').slice(1)
				if (str) {
					let arr = str.split(' ')
					let date = arr[0]
					let dateArr = date.split('-').slice(1)
					if (nowDay[0] === dateArr[0] && nowDay[1] === dateArr[1]) {
						let timeStr = dateArr[0] + '月' + dateArr[1] + '日 [今天] ' + arr[1] + '前送达'
						return timeStr
					} else {
						let timeStr = dateArr[0] + '月' + dateArr[1] + '日 ' + arr[1] + '前送达'
						return timeStr
					}
				} else {
					return '-------'
				}
			},
			checkImg(str) {
				if (str) {
					let check = str.startsWith('http')
					if (check) {
						return str
					} else {
						return 'http://111.229.128.239:1003' + str
					}
				}
			}
		},
		data() {
			return {
				windowHeight: 0, // 滚动view高度


        recycleCateSelectedIndex:0,
        recycleCateSelectedItem:null,

        recycleWeightSelectedIndex:0,
        recycleWeightSelectedItem:null,

        addressId:0,
        orderKey:'a5244f9w8fr74bhj4b3b1e89d2v3hj2',

				remark: '我是你爸爸',		// 备注
				preengageTime: "2020-12-29 12:00",		// 配送时间
        recycleProductList:[
          {
            weightCateId:1,
            weightCateStr:'10 -50公斤',
            photos:[
                "aa.jpg",
                "bb.jpg"
            ]
          }
        ],
				cntitems: '',	// 商品数量
				orderInfo: {},


        recycleCateList: [
          {
            id: 0,
            src: '/static/index/menu/menu_4.png',
            name: '废纸',
            subtitle: '杂纸，纯黄纸',
            url: '',
            remainder:0,
            backgroundColor:"#ffc728"
          },
          {
            id: 1,
            src: '/static/index/menu/menu_2.png',
            name: '塑料',
            subtitle: '塑料瓶，塑料杯',
            url: '',
            remainder:1,
            backgroundColor:"#289bff"
          },
          {
            id: 2,
            src: '/static/index/menu/menu_1.png',
            name: '金属',
            subtitle: '废旧不锈钢',
            url: '/pages/index/Special_Offer',
            remainder:0,
            backgroundColor:"#a9a9fa"
          },
          {
            id: 3,
            src: '/static/index/menu/menu_1.png',
            name: '其他废品',
            subtitle: '家电，家具，衣物，玻璃',
            url: '/pages/index/Special_Offer',
            remainder:1,
            backgroundColor:"#84ff58"
          }
        ],


        requireList: [
          {
            id: 0,
            src: '/static/index/menu/menu_4.png',
            name: '拒绝掺水',
            url: ''
          },
          {
            id: 1,
            src: '/static/index/menu/menu_2.png',
            name: '拒绝掺杂',
            url: ''
          },
          {
            id: 2,
            src: '/static/index/menu/menu_1.png',
            name: '单次10KG起',
            url: '/pages/index/Special_Offer'
          }
        ],

        weightList:[
          {
            id:1,
            text: '10-50公斤',
            type: 'between',
            min: 10,
            max: 50,
          },
          {
            id:2,
            text: '50-100公斤',
            type: 'between',
            min: 10,
            max: 50,
          },
          {
            id:3,
            text: '100公斤以上',
            type: 'more-than',
            min: 10,
            max: 50,
          },
        ],


        guidePriceList:[
          {
            desc: '纯色纸箱，如家点包装箱',
            name: '黄纸',
            num: 1.3,
            unit:'公斤'
          },
          {
            desc: '纯色纸箱，如家点包装箱',
            name: '花纸',
            num: 1.8,
            unit:'公斤'
          },{
            desc: '纯色纸箱，如家点包装箱',
            name: '统纸',
            num: 0.3,
            unit:'公斤'
          },
        ]
			}
		},
		methods: {

			// 确认订单 方法
			async addOrder(type) {
				// this.nowAddressKey  // 地址id
				// this.integral  // 使用积分的值
				// this.remark  //备注信息
				// this.radioAddress  //不确定后断要的值  支付渠道
				let data = {
					orderKey: this.orderKey,
					addressId: this.addressId,
					mark: this.remark,
					isPreengage: 1,
          preengageTime:this.preengageTime,
					real_name: '二驴',
					phone: 13333333333,
          recycleProductList:this.recycleProductList
				}
				let result =  this.$api.create(data)
				let res = this.checkRes(result, '订单已创建～～')
			},
			// 选择收货时间
			checkTime(e) {
				let {result} = e
				let defaultM = this.time.split(':')
				let defaultMstr = defaultM[defaultM.length - 1]
				let resultM = result.split(':')
				let resultMstr = resultM[resultM.length - 1]
				if (resultMstr === 'undefined') {
					resultM[resultM.length - 1] = defaultMstr
					this.time = resultM.join(':')
				} else {
					this.time = result
				}
			},
			// 设置收货地址
			setAddress() {
				uni.navigateTo({
					url: '/pages/user/address_list'
				})
			},
			//
      selectRecycleCate(cateItem,index) {
			  this.recycleCateSelectedItem=cateItem
        this.recycleCateSelectedIndex=index
        //console.log(index)
      },
      selectRecycleWeight(weightItem,index) {
        this.recycleWeightSelectedItem=weightItem
        this.recycleWeightSelectedIndex=index
        //console.log(index)
      },
    },
		computed: {
			// 滚动
			scrollStyle() {
				let height = this.windowHeight - uni.upx2px(120)
				return `height: ${height}px`
			},
			// 现在的时间
			nowTime() {
				let time = moment(Date.now() + 1800000).format('YYYY-MM-DD HH:mm')
				return time
			},
			// 收货地址
			defAddress() {

			},

		},
		async onReady() {

		},
		onLoad(option) {

		}
	}
</script>

<style scoped>
	.page {
		height: 100vh;
	}

  .main{
    overflow: hidden;
    margin-bottom: 300rpx;
  }


  .first-layer-block{
    padding: 10rpx;
  }

  .first-layer-block .first-layer-title{
    font-size: 28rpx;
    font-weight: bold;
    margin:20rpx 10rpx ;
  }
  .recycle-cate-block{
    padding: 22rpx;
  }

  .recycle-cate-row{
    text-align: center;
    display: flex;
    justify-content: space-around;
  }
  .recycle-cate-item{
    box-sizing: border-box;
    font-size: 28rpx;
    border-radius: 5rpx;
    width: 20%;
    padding: 20rpx;
  }
  .recycle-cate-item img{
    width: 80%;
  }
  .recycle-cate-selected{
    border: 1rpx solid #1AAD19;
  }





  .recycle-require-row{
    text-align: center;
    display: flex;
    justify-content: space-around;
  }
  .recycle-require-item{
    font-size: 20rpx;
    border-radius: 5rpx;
    width: 25%;
    padding: 20rpx;
  }
  .recycle-require-item img{
    width: 60%;
  }



  .recycle-weight-block{

  }
  .recycle-weight-row{
    text-align: center;
    display: flex;
    justify-content: space-around;
  }
  .recycle-weight-item{
    color: #1AAD19;
    width: 28%;
    background-color: #d8d8d8;
    border-radius: 5rpx;
    height: 64rpx;
    line-height:56rpx;
    padding: 5rpx;
  }
  .recycle-weight-selected{
    border: 1rpx solid #1AAD19;
  }


  .recycle-weight-add-row{
    margin-top: 20rpx;
    border-bottom: 1rpx solid #d8d8d8;
    padding: 10rpx 10rpx 10rpx 10rpx;
    display: flex;
    justify-content: space-evenly;
  }
  .recycle-weight-add-btn{
    width: 200rpx;
    height: 64rpx;
    line-height: 64rpx;
    font-size: 28rpx;
    box-sizing: border-box;
  }
  .recycle-weight-notice{
    width: 100%;
  }


  .recycle-weight-notice-title {
    color: #1aad19;
    margin: 0 auto;
    width: 100%;
    text-align: center;
  }
  .recycle-weight-notice-content{
    font-size:  24rpx
  }


  .bottom-block{
    height: 160rpx;
    padding: 16rpx auto;
  }

  .add-order-exceptions-row{
    width: 100%;
    display: block;
    font-size: 24rpx;
    text-align: center;
    margin: 20rpx auto;
  }
  .add-order-exceptions{
    color: #1aad19;
  }
  .add-order-row{
    width: 100%;
    display: flex;
    margin: 10rpx 0 0 0;
    justify-content: space-between;
  }
  .add-order-button{
    color: #f2f2f2;
    width: 42.5%;
    height: 64rpx;
    line-height: 64rpx;
    border-radius: 8rpx;
  }
  .add-order-button uni-view{
    color: #f2f2f2;
  }
  .add-order-button:nth-child(1){

    background-color: #0d7cfc;
  }

  .add-order-button:nth-child(2){
    background-color: #1AAD19;
  }

</style>
