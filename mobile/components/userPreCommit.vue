<template>
  <view class="goods-box">
    <view v-if="orderInfoUserPreCommitList.length>0" class="goods_box_right">
      <view class="goods_info_list" v-for="(item,index) in orderInfoUserPreCommitList" :key="index">
        <view>
          <text>{{ item.user_rec_cate_str }}:{{ item.user_weight_cate_str }}</text>
        </view>
        <view class="photos-groups">
          <view v-for="picItem in item.user_upload_photos" class="">
            <u-image class="photo-item" width="256rpx" height="256rpx" :src="picItem"></u-image>
          </view>
        </view>
      </view>
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
      orderInfoUserPreCommitList: {
				type: Array,
				default: () => {
					return  [/*{
            CreateTime: "2021-03-01T19:40:17.794Z"
            uid: 1
            unique: "81fe8996faa41710edc754bb824d765d"
            user_rec_cate_id: 1
            user_rec_cate_str: "塑料"
            user_upload_photos: []
            user_weight_cate_id: 1
            user_weight_cate_str: "10-50公斤"
          }, */]
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
	.goods-box {
		display: block;
		background: #fff;
		margin: 0rpx 15rpx 15rpx 15rpx;
		padding: 10rpx 15rpx 10rpx 0rpx;
		border-radius: 10rpx;
	}


  .goods_box_right{
    width: 100%;
  }
	.goods_info_list {
		font-size: 28rpx;
		color: #9e9e9e;
		padding: 10rpx 20rpx;
		border-bottom: #9e9e9e solid 2rpx;
	}

  .photos-groups {
    display: flex;
    justify-content: flex-start;
    flex-direction:row
  }

</style>
