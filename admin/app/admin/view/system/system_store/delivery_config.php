<!DOCTYPE html>
<html lang="zh-CN">
<head>
    {include file="public/head"}

    <link href="/system/frame/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
    <link href="/system/frame/css/style.min.css?v=3.0.0" rel="stylesheet">
    <title>{$title|default=''}</title>
    <style></style>
</head>
<body>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>门店配送设置</h5>
                </div>
                <div id="store-attr" class="mp-form" v-cloak="">
                    <Alert show-icon>
                        配置说明
                        <Icon type="ios-bulb-outline" slot="icon"></Icon>
                        <template slot="desc">Custom icon copywriting. Custom icon copywriting. Custom icon
                            copywriting.
                        </template>
                    </Alert>
                    <i-Form :label-width="80" style="width: 100%">
                        <template>
                            <Alert type="warning">{{form.name}} 配送设置</Alert>
                            <Form-Item>
                                <Row>
                                    <i-Col span="10">
                                        <span>启用 基础配送价：</span>
                                        <Checkbox v-model="form.active_distance_amount">勾选后启用</Checkbox>

                                    </i-Col>
                                    <i-Col span="10">
                                        <span>基础配送价(￥)：</span>
                                        <i-Input placeholder="基础配送价" v-model="form.base_amount" style="width: 50%"
                                                 type="text"></i-Input>
                                    </i-Col>
                                </Row>
                            </Form-Item>
                            <Form-Item>
                                <i-Col span="10">
                                    <span>最远配送距离(km)：</span>
                                    <i-Input placeholder="距离" v-model="form.max_distance" style="width: 50%"
                                             type="text"></i-Input>
                                </i-Col>
                                <i-Col span="10">
                                    <span>商品未配置重量时 单件默认重量(kg)：</span>
                                    <i-Input placeholder="重量" v-model="form.singleton_product_weight" style="width: 30%"
                                             type="text"></i-Input>
                                </i-Col>
                            </Form-Item>
                            <Alert type="success">距离阶梯配置</Alert>
                            <Form-Item>
                                <Row>
                                    <i-Col span="5">
                                        <Row>
                                            <span>启用 距离阶梯配送价：</span>
                                            <Checkbox v-model="form.active_base_amount">勾选后启用</Checkbox>
                                        </Row>
                                        <br>
                                        <Row>
                                            <span>基础距离配送价(￥)：</span>
                                            <i-Input placeholder="距离" v-model="form.base_distance_amount" style="width: 50%"
                                                     type="text"></i-Input>
                                        </Row>
                                    </i-Col>
                                    <i-Col span="18">
                                        <Row v-for="item in form.distance_amount_list ">
                                            <span>当距离大于:</span>
                                            <i-Input placeholder="输入距离" v-model="item.distance" style="width: 20%"
                                                     type="text"></i-Input>
                                            <span>每千米(km)</span>
                                            <i-Input placeholder="输入单位配送价" v-model="item.amount" style="width: 20%"
                                                     type="text"></i-Input>
                                            <span>此续程范围超出部分到下个续程间,每公里加收费用</span>
                                        </Row>
                                    </i-Col>
                                </Row>
                            </Form-Item>
                            <Alert type="success">重量阶梯配置</Alert>
                            <Form-Item>
                                <Row>
                                    <i-Col span="5">

                                        <Row>
                                            <span>启用重量阶梯配送价：</span>
                                            <Checkbox v-model="form.active_weight_amount">勾选后启用</Checkbox>
                                        </Row>
                                        <br>
                                        <Row>
                                            <span>基础重量配送价(￥)：</span>
                                            <i-Input placeholder="距离" v-model="form.base_weight_amount" style="width: 50%"
                                                     type="text"></i-Input>
                                        </Row>
                                    </i-Col>
                                    <i-Col span="18">
                                        <Row v-for="item in form.weight_amount_list ">
                                            <span>当重量大于:</span>
                                            <i-Input placeholder="输入重量" v-model="item.weight" style="width: 20%"
                                                     type="text"></i-Input>
                                            <span>每公斤(￥)</span>
                                            <i-Input placeholder="输入单位配送价" v-model="item.amount" style="width: 20%"
                                                     type="text"></i-Input>
                                            <span>此续重范围超出部分到下个续重间,每公斤加收费用</span>
                                        </Row>
                                    </i-Col>
                                </Row>
                            </Form-Item>
                        </template>
                        <Form-Item>
                            <Row>
                                <i-Col span="8" offset="6">
                                    <i-Button type="primary" @click="submit">提交</i-Button>
                                </i-Col>
                            </Row>
                        </Form-Item>
                    </i-Form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{__PLUG_PATH}city.js"></script>
<script>
    var storeDeliveryConfigData = {:json_encode($storeDeliveryConfig)}
    ;
    console.log(storeDeliveryConfigData)
    mpFrame.start(function (Vue) {
        $.each(city, function (key, item) {
            city[key].value = item.label;
            if (item.children && item.children.length) {
                $.each(item.children, function (i, v) {
                    city[key].children[i].value = v.label;
                    if (v.children && v.children.length) {
                        $.each(v.children, function (k, val) {
                            city[key].children[i].children[k].value = val.label;
                        });
                    }
                });
            }
        });
        new Vue({
            data: function () {
                return {
                    id: storeDeliveryConfigData.id || 0,
                    form: {
                        store_id:storeDeliveryConfigData.store_id,
                        active_base_amount: storeDeliveryConfigData.active_base_amount===1 ? true : false,
                        active_weight_amount: storeDeliveryConfigData.active_weight_amount ===1 ? true : false,
                        active_distance_amount: storeDeliveryConfigData.active_distance_amount ===1  ? true : false,

                        max_distance:storeDeliveryConfigData.max_distance || 0.0,

                        base_amount:storeDeliveryConfigData.base_amount || 0.0,
                        base_distance_amount:storeDeliveryConfigData.base_distance_amount || 0.0,
                        base_weight_amount:storeDeliveryConfigData.base_weight_amount || 0.0,

                        weight_amount_list: storeDeliveryConfigData.weight_amount_list || [],
                        distance_amount_list: storeDeliveryConfigData.distance_amount_list || [],
                        singleton_product_weight: storeDeliveryConfigData.singleton_product_weight
                    },
                    visible: false,
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
                    var that = this;
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
                    $eb.axios.post('{:Url("delivery_config")}' + (that.id ? '?id=' + that.id : ''), that.form).then(function (res) {
                        layer.close(index);
                        layer.msg(res.data.msg);
                        if (res.data.data.id) that.id = res.data.data.id;
                    }).catch(function (err) {
                        console.log(err);
                        layer.close(index);
                    })
                },

            },
            mounted: function () {
                window.changeIMG = this.changeIMG;
                window.selectAdderss = this.selectAdderss;
            }
        }).$mount(document.getElementById('store-attr'))
    })
</script>