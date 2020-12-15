{extend name="public/container"}
{block name="head_top"}
<link href="{__PLUG_PATH}iview/dist/styles/iview.css" rel="stylesheet">
<!-- 全局js -->
<style>
    .box {
        width: 0px;
    }


    .store-image img {
        height: 20px;
        width: 20px;
    }


    .header-select select {
        width: 200px;
        height: 30px;
        border-radius: 2px;
        border: 1px solid darkgrey;
        padding: 0 5px;
    }

    .header-select select option {
        padding: 0 5px;
    }

    .header-select {
        width: 100%;
        float: left;

    }

    .header-select:nth-child(2) {
        margin-left: 300px;

    }

    .header-select select {
        width: 200px;
        height: 30px;
        border-radius: 2px;
        border: 1px solid darkgrey;
        padding: 0 5px;
    }

    .header-select select option {
        padding: 0 5px;
    }

    #store-stat-group > .style-column > div {
        float: left;
        margin-left: 10px;
    }


</style>
{/block}
{block name="content"}
<div id="app">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>管理员绑定门店</h5>
                </div>


                <div class="ibox-content check-group" id="store-group">
                    <div class="header-select">
                        <div class="layui-form-item">
                            <label for="">选择管理员:</label>
                            <select v-model="adminId" name="adminId" lay-verify="required">
                                <option v-for="item in adminList" :value="item.id">id: {{item.id}}&nbsp;&nbsp; {{item.account}}&nbsp;&nbsp;&nbsp;&nbsp;姓名:{{item.real_name}}</option>
                            </select>
                        </div>
                    </div>
                    <div v-for="item in storeList">
                        <label for="">{{item.name}}</label>
                        <input type="checkbox" :checked="item.check" @click="checkStore(item)">
                    </div>
                    <div>
                        <button class="btn btn-primary btn-xs" @click="submit()">提交</button>
                        <button class="btn btn-primary btn-xs" @click="reverseCheck()">反选</button>
                        <button class="btn btn-primary btn-xs" @click="allCheck()">全选</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{/block}
{block name="script"}
<script>
    require(['vue', 'axios', 'layer'], function (Vue, axios, layer) {
        new Vue({
            el: "#app",
            data: {
                adminList: [],
                adminId: 0,
                storeList: [
                    {
                        add_time: "2020-08-04 23:39:15",
                        address: "广西,南宁,兴宁区",
                        day_time: "07:00:00 - 23:30:00",
                        detailed_address: "明秀东路178号",
                        id: 1,
                        image: "http://kaifa.crmeb.net/uploads/attach/2019/08/13/26b2896f313fb594884fb992e33c5fa8.jpg",
                        introduction: "点点酒兴宁店",
                        latitude: "22.851907",
                        longitude: "108.330107",
                        name: "点点酒 兴宁店",
                        phone: "18676684597",
                        status: 1,
                    },
                ],
            },
            watch:{
                adminList:function () {
                    this.adminId=this.adminList[0].id
                }
            },
            methods: {
                getAllStore() {
                    console.log("methods: getAllStore")
                    let that = this;
                    axios.get('/admin/system.SystemStore/store_list?group_by=province,city,district').then((res) => {
                        res = res.data
                        if (res.code === 0) {
                            that.storeList = res.data
                        }
                       // that.group()
                    })
                },
                getAllAdmin() {
                    console.log("methods: getAllAdmin")
                    let that = this;
                    axios.get('/admin/setting.SystemAdmin/list').then((res) => {
                        res = res.data
                        console.log(res.data.list)
                        if (res.code === 200) {
                            that.adminList = res.data.list
                        }
                        console.log(that.adminList)
                    })
                },

               /* group: function () {
                    let storeListTemp = [];
                    console.log(this.storeList)
                    for (const storeKey in this.storeList) {

                        if (!storeListTemp.indexOf(this.storeList[storeKey].province)) {
                            console.log(storeListTemp.indexOf(this.storeList[storeKey].province))
                            storeListTemp.push(this.storeList[storeKey].province)

                        }
                    }
                    console.log('+++++++++++++++++++++')
                    console.log(storeListTemp)
                },*/
                checkStore: function (storeItem) {
                    //处理门店站点列表
                    for (let storekey in this.storeList) {
                        if (this.storeList[storekey].id === storeItem.id) {
                            this.storeList[storekey].check = this.storeList[storekey].check !== true;
                        }
                    }
                    //处理门店站点id列表
                    this.storeIdList = Array.from(new Set(this.storeIdList))  //去重
                    let hasIndex = this.storeIdList.indexOf(storeItem.id)
                    if (hasIndex === -1) {
                        this.storeIdList.push(storeItem.id)
                    } else {
                        this.storeIdList.splice(hasIndex, 1)
                    }
                },

                submit: function () {
                    let that = this;
                    axios.post('/admin/setting.SystemAdmin/bindStore', {adminId: this.adminId,storeList: this.storeFilter()}).then((res) => {
                        res = res.data

                        if (res.code === 200) {
                            $eb.message('success', res.data.msg || '提交成功!');
                        } else {
                            $eb.message('error', res.data.msg || '提交失败!');
                        }
                    });
                },

                reverseCheck:function(){

                },
                allCheck:function(){
                    
                }

                storeFilter: function () {
                    let storeListTemp = []
                    for (const sKey in this.storeList) {
                        if (this.storeList[sKey].check) {
                            storeListTemp.push(this.storeList[sKey].id)
                        }
                    }

                    return storeListTemp;
                }

            },
            mounted: function () {
                this.getAllStore()
                this.getAllAdmin()
            }
        });
    })

</script>

{/block}
