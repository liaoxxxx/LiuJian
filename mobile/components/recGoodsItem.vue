<template>
	<view class="goods_box" @tap="$emit('goDetail')">
		<view v-if="recycleProductList.length>0" class="goods_box_right">
				<view class="goods_info_list" v-for="(item,index) in recycleProductList" :key="index">
					<text>{{item.recCateStr}}:{{item.weightCateStr}}</text>
          <view v-for="picItem in item.photos" class="photos-groups">
            <u-image width="256rpx" height="256rpx" :src="picItem"></u-image>
          </view>
				</view>
    </view>
    <view v-else class="empty-row">
        <view>什么都没有，</view>
    </view>
	</view>
</template>

<script>


export default {
  data() {
			return {

      }
		},
		props: {
      recycleProductList: {
				type: Array,
				default: () => {
					return  [/*{
            weightCateId: 1,
            weightCateStr: '10-50公斤',
            recCateId: 1,
            recCateStr: '旧废纸',
            photos: [
              'https://ss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=2286765395,3894158643&fm=26&gp=0.jpg',
              'https://img.zcool.cn/community/01f4155a90fe00a8012045b3da7387.jpeg@260w_195h_1c_1e_1o_100sh.jpg'
            ]
          }, {
            weightCateId: 1,
            weightCateStr: '100-500公斤',
            recCateId: 1,
            recCateStr: '旧废纸',
            photos: [
              'https://ss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=2286765395,3894158643&fm=26&gp=0.jpg',
              'https://img.zcool.cn/community/01f4155a90fe00a8012045b3da7387.jpeg@260w_195h_1c_1e_1o_100sh.jpg'
            ]
          }*/]
				}
			},
		},
		computed: {

		},
		methods: {

		}
	}
</script>

<style scoped>
	.goods_box {
		display: flex;
		flex-direction: row;
		background: #fff;
		margin: 0rpx 15rpx 15rpx 15rpx;
		padding: 10rpx 15rpx 10rpx 0rpx;
		border-radius: 10rpx;
	}

  .goods_box_right{
    width: 100%;
  }
	.goods_info_list {
    width: 100%;
		font-size: 28rpx;
		color: #9e9e9e;
		padding-right: 20rpx;
		border-right: #9e9e9e solid 4rpx;
	}

  .photos-groups {
    width: 100%;
    display: flex;
    justify-content: flex-start;
    flex-direction:row
  }

  .empty-row{
    text-align: center;
    width: 100%;
    margin-top: 50rpx;
  }
</style>
