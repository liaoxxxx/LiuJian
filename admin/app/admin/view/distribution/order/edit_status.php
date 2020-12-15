<!DOCTYPE html>
<html lang="zh-CN">
<head>
    {include file="public/head"}

    <link href="/system/frame/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
    <link href="/system/frame/css/style.min.css?v=3.0.0" rel="stylesheet">
    <title>{$title|default=''}</title>
    <style>
        .demo-split {
            height: 200px;
            border: 1px solid #dcdee2;
        }

        .demo-split-pane {
            padding: 10px;
        }
    </style>
</head>
<body>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div id="store-attr" class="mp-form" v-cloak="">
                    <i-Form span="24">
                        <Row>
                            <i-Col span="12">
                                <Row>
                                    <i-Col span="8" offset="4">
                                        <Avatar :src="form.deliveryInfo.store.image" style="height:80px;width:80px"/>
                                    </i-Col>
                                    <i-Col span="12">
                                        <span>{{form.deliveryInfo.store.name}}</span>
                                        <br>
                                        <br>
                                        <br>
                                        <span>{{form.deliveryInfo.store.address}}{{form.deliveryInfo.store.detailed_address}}</span>
                                    </i-Col>
                                </Row>
                            </i-Col>

                            <i-Col span="12">
                                <Row>
                                    <i-Col span="8" offset="4">
                                        <Avatar :src="form.deliveryInfo.deliveryman.avatar"
                                                style="height:80px;width:80px"/>
                                    </i-Col>
                                    <i-Col span="12">
                                        <span>{{form.deliveryInfo.deliveryman.real_name}}</span>
                                        <br>
                                        <br>
                                        <br>
                                        <span>{{form.deliveryInfo.deliveryman.phone}}</span>
                                    </i-Col>
                                </Row>
                            </i-Col>
                        </Row>
                        <Row>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                        </Row>
                        <Row>
                            <i-Col span="12" offset="14">
                               <!-- <Row>
                                    <i-Input v-model="adminRemark" placeholder="Enter something..."
                                             style="width: 300px"/>
                                </Row>-->
                                <Row>
                                    <i-select :model.sync="delivery_statusModel" style="width:200px">
                                        <i-option v-for="item in deliveryStatusList" :value="item.value">{{ item.label }}</i-option>
                                    </i-select>
                                    <i-Button type="primary" @click="submit">提交</i-Button>
                                </Row>
                            </i-Col>
                        </Row>
                    </i-Form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let deliveryInfo = JSON.parse('{:json_encode($deliveryInfo)}')
    let deliveryStatusList = JSON.parse('{:json_encode($deliveryStatusList)}')
    let delivery_status=deliveryInfo.delivery_status
    console.log(delivery_status)
    console.log(deliveryInfo)
    console.log(deliveryStatusList)

    mpFrame.start(function (Vue) {

        new Vue({
            data: function () {
                return {
                    form: {
                        deliveryInfo: deliveryInfo,
                        deliveryStatusList: deliveryStatusList
                    },
                    delivery_statusModel:delivery_status,
                    visible: false,
                    adminRemark: '输入订单配送的备注',
                }
            },
            methods: {

                createFrame: function (title, src, opt) {
                    opt === undefined && (opt = {});
                    var h = parent.document.body.clientHeight - 100;
                    return layer.open({
                        type: 2,
                        title: title,
                        area: [(opt.w || 700) + 'px', (opt.h || h) + 'px'],
                        fixed: false, //不固定
                        maxmin: true,
                        moveOut: false,//true  可以拖出窗外  false 只能在窗内拖
                        anim: 5,//出场动画 isOutAnim bool 关闭动画
                        offset: 'auto',//['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
                        shade: 0,//遮罩
                        resize: true,//是否允许拉伸
                        content: src,//内容
                        move: '.layui-layer-title'
                    });
                },


                submit: function () {
                    let that = this;
                    let data ={delivery_status:that.delivery_statusModel,id:that.form.deliveryInfo.id}
                    console.log(that.delivery_statusModel)
                    /*if (!that.form.name) return $eb.message('error', '请填写门店行名称');
                    if (!that.form.phone) return $eb.message('error', '请输入手机号码');
                    if (!that.isPhone(that.form.phone)) return $eb.message('error', '请输入正确的手机号码');
                    if (!that.form.address) return $eb.message('error', '请选择门店地址');
                    if (!that.form.detailed_address) return $eb.message('error', '请填写门店详细地址');
                    if (!that.form.image) return $eb.message('error', '请选择门店logo');
                    if (!that.form.valid_time) return $eb.message('error', '请选择核销时效');
                    if (!that.form.day_time) return $eb.message('error', '请选择门店营业时间');
                    if (!that.form.latlng) return $eb.message('error', '请选择门店经纬度！');*/
                    var index = layer.load(1, {
                        shade: [0.5, '#fff']
                    });
                    let parentIndex=parent
                    $eb.axios.post('{:Url("")}',data).then(function (res) {
                        layer.close(index);
                        layer.msg(res.data.msg);

                    }).catch(function (err) {
                        console.log(err);
                        layer.close(index);
                    })
                },

            },
            mounted: function () {

            }
        }).$mount(document.getElementById('store-attr'))
    })
</script>