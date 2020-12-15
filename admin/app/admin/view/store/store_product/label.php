<!DOCTYPE html>
<html lang="zh-CN">
<head>
    {include file="public/head"}
    <title>{$title|default=''}</title>
    <style>
        .check{color: #f00}
        .demo-upload{
            display: block;
            height: 33px;
            text-align: center;
            border: 1px solid transparent;
            border-radius: 4px;
            overflow: hidden;
            background: #fff;
            position: relative;
            box-shadow: 0 1px 1px rgba(0,0,0,.2);
            margin-right: 4px;
        }
        .demo-upload img{
            width: 100%;
            height: 100%;
            display: block;
        }
        .demo-upload-cover{
            display: none;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,.6);
        }
        .demo-upload:hover .demo-upload-cover{
            display: block;
        }
        .demo-upload-cover i{
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            margin: 0 2px;
        }
    </style>
    <script>
        window.test=1;
    </script>
</head>
<body>
<div id="store-attr" class="mp-form" v-cloak="">
    <i-Form :label-width="80" style="width: 100%" v-show="hidden == false">
        <Form-Item>
            <Row>
                <i-Col span="5">
                    <i-Button type="dashed" long @click="hiddenBool" icon="plus-round">添加新标签</i-Button>
                </i-Col>
            </Row>
        </Form-Item>
    </i-Form>
    <i-Form :label-width="80" style="width: 100%" v-show="hidden == true">
        <Form-Item
                :label="'标签名称:'">
            <Row>
                <i-Col style="position: relative;margin-right: 6px"  span="5"
                       v-for="(item, index) in items"
                       :key="index">
                    <i-Input type="text" v-model="item.label_name" placeholder="设置名称"></i-Input>
                    <i-Button style="position: absolute;top:0;right:0;margin-top:1px;border: none;font-size: 8px;line-height: 1.8" type="ghost" @click="handleRemove(index)" v-show="item.attrHidden == true"><Icon type="close-round" /></i-Button>
                    <i-Button style="position: absolute;top:0;right:0;margin-top:1px;border: none;font-size: 8px;line-height: 1.8" type="ghost" @click="attrHiddenBool(item)" v-show="item.attrHidden == false"><Icon type="checkmark-round"></Icon></i-Button>
                </i-Col>
                <i-Col span="5">
                    <i-Button type="dashed" long @click="handleAdd" icon="plus-round">添加新标签</i-Button>
                </i-Col>
            </Row>
        </Form-Item>

        <Form-Item>
            <Row>
                <i-Col span="2" offset="2">
                    <i-Button type="primary" @click="submit">提交</i-Button>
                </i-Col>
                <i-Col span="2" offset="1">
                    <i-Button type="error" @click="clear">清空所有标签</i-Button>
                </i-Col>
            </Row>
        </Form-Item>

    </i-Form>
    <Spin fix v-show="submiting == true">保存中...</Spin>
</div>
<script>
    var _vm ;
    mpFrame.start(function(Vue){
        new Vue({
            data () {
                return {
                    hidden:false,
                    attrHidden:false,
                    submiting :false,
                    items: <?php echo $result && isset($result) && !empty($result) ? json_encode($result) : 'false'; ?> || [
                        {
                            label_name:'',
                            attrHidden:false
                        }
                    ]
                }
            },
            watch:{
                items:{
                    handler:function(){
//                        this.attrs = [];
                    },
                    deep:true
                }
            },
            methods: {
                createFrame:function(title,src,opt){
                    opt === undefined && (opt = {});
                    var h = parent.document.body.clientHeight - 100;
                    return layer.open({
                        type: 2,
                        title:title,
                        area: [(opt.w || 700)+'px', (opt.h || h)+'px'],
                        fixed: false, //不固定
                        maxmin: true,
                        moveOut:false,//true  可以拖出窗外  false 只能在窗内拖
                        anim:5,//出场动画 isOutAnim bool 关闭动画
                        offset:'auto',//['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
                        shade:0,//遮罩
                        resize:true,//是否允许拉伸
                        content: src,//内容
                        move:'.layui-layer-title'
                    });
                },
                attrHiddenBool(item){
                    if(item.label_name == ''){
                        $eb.message('error','请填写标签名称');
                    }else{
                        item.attrHidden = true;
                    }
                },
                hiddenBool(){
                    this.hidden = true;
                },
                handleAdd () {
                    if(!this.checkLabel())return ;
                    this.items.push({
                        label_name: '',
                        attrHidden:false
                    });
                },
                checkLabel(){
                    var bool = true;
                    this.items.map(function(item){
                        if(!bool) return;
                        if(!item.label_name){
                            $eb.message('error','请填写标签名称');
                            bool = false;
                        }
                    });
                    return bool;
                },
                handleRemove (index) {
                    if(this.items.length > 1)
                        this.items.splice(index,1);
                    else
                        $eb.message('error','请设置至少一个标签');
                },
                submit(){
                    var that = this;
                    that.submiting = true;
                    if(!this.checkLabel()) return ;
                    for(let attr in that.attrs){
                        that.attrs[attr].check = false;
                    }
                    $eb.axios.post("{:Url('set_label',array('id'=>$id))}",{items:this.items}).then(function(res){
                        that.submiting = false;
                        if(res.status == 200 && res.data.code == 200){
                            $eb.message('success',res.data.msg || '编辑成功!');
                            $eb.closeModalFrame(window.name);
                        }else{
                            $eb.message('error',res.data.msg || '请求失败!');
                        }
                    }).catch(function(err){
                        $eb.message('error',err);
                    })
                },
                clear(){
                    var that = this;
                    requirejs(['sweetalert'], function (swel) {
                        swel({
                            title: "您确定要清空产品标签吗",
                            text: "删除后将无法恢复，请谨慎操作！",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "是的，我要清空！",
                            cancelButtonText: "让我再考虑一下…",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        }).then(function () {
                            $eb.axios.post("{:Url('clear_label',array('id'=>$id))}", {
                                items: that.items,
                                attrs: that.attrs
                            }).then(function (res) {
                                if (res.status == 200 && res.data.code == 200) {
                                    $eb.message('success', res.data.msg || '清空成功!');
                                    window.location.reload();
                                } else {
                                    $eb.message('error', res.data.msg || '清空失败!');
                                }
                            }).catch(function (err) {
                                $eb.message('error', err);
                            })
                        }).catch(console.log);
                    });
                }
            },
            mounted (){
                _vm = this;
                var resultAdmin = <?php echo $result && isset($result) && !empty($result) ? json_encode($result) : 'false'; ?>;
                if(resultAdmin) this.hidden = true;

                window.changeIMG = (index,pic)=>{
                    _vm.setAttrPic(index,pic);
                };
            }
        }).$mount(document.getElementById('store-attr'));
    });
</script>
</body>
