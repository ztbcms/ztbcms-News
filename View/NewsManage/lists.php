<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <h3>新闻管理</h3>
            <div class="filter-container">
                <el-input v-model="listQuery.title" placeholder="标题搜索" style="width: 200px;"
                          class="filter-item"></el-input>
                <el-button class="filter-item" type="primary" style="margin-left: 10px;"
                           @click="search">
                    筛选
                </el-button>
                <el-button type="primary" @click="toEdit()">添加</el-button>

            </div>
            <el-table
                :data="list"
                border
                fit
                highlight-current-row
                style="width: 100%;"
            >
                <el-table-column label="标题" align="center">
                    <template slot-scope="scope">
                        <span>{{ scope.row.title }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="图片集" align="center">
                    <template slot-scope="scope">
                        <template v-for="image in scope.row.images">
                            <el-image
                                style="width: 100px; height: 100px;margin-left: 4px"
                                :src="image"
                                :preview-src-list="[image]">
                            </el-image>
                        </template>
                    </template>
                </el-table-column>
                <el-table-column label="发布日期" align="center">
                    <template slot-scope="scope">
                        <span>{{ scope.row.release_date }}</span>
                    </template>
                </el-table-column>

                <el-table-column label="链接" align="center">
                    <template slot-scope="scope">
                        <a :href="scope.row.detail_url" target="_blank">{{ scope.row.detail_url }}</a>
                    </template>
                </el-table-column>

                <el-table-column label="操作" align="center" width="230" class-name="small-padding fixed-width">
                    <template slot-scope="scope">
                        <el-button type="primary" size="mini" @click="toEdit(scope.row)">
                            编辑
                        </el-button>
                        <el-button size="mini" type="danger"
                                   @click="toDelete(scope.row)">
                            删除
                        </el-button>
                    </template>
                </el-table-column>

            </el-table>

            <div class="pagination-container">
                <el-pagination
                    background
                    layout="prev, pager, next, jumper"
                    :total="total"
                    v-show="total>0"
                    :current-page.sync="listQuery.page"
                    :page-size.sync="listQuery.limit"
                    @current-change="getList"
                >
                </el-pagination>
            </div>

        </el-card>
    </div>

    <style>
        .filter-container {
            padding-bottom: 10px;
        }

        .pagination-container {
            padding: 32px 16px;
        }
    </style>

    <script>
        $(document).ready(function () {
            new Vue({
                el: '#app',
                data: {
                    dialogFormVisible: false,
                    form: {
                        id: '',
                        name: '',
                    },
                    list: [],
                    total: 0,
                    listQuery: {
                        page: 1,
                        limit: 20,
                        title: ''
                    },
                },
                watch: {},
                filters: {},
                methods: {
                    search: function () {
                        this.listQuery.page = 1;
                        this.getList();
                    },
                    getList: function () {
                        var that = this;
                        $.ajax({
                            url: "/News/NewsManage/getList",
                            type: "post",
                            dataType: "json",
                            data: that.listQuery,
                            success: function(res){
                                if(res.status){
                                    that.list = res.data.items
                                    that.total = res.data.total_items
                                }else{
                                    layer.msg(res.msg)
                                }
                            }
                        });
                    },
                    doDelete: function (id) {
                        var that = this;
                        $.ajax({
                            url: "/News/NewsManage/doDelete",
                            type: "post",
                            dataType: "json",
                            data: {id: id},
                            success: function(res){
                                layer.msg(res.msg)
                                if(res.status){
                                    that.getList()
                                }
                            }
                        });
                    },
                    toDelete: function (item) {
                        var that = this;
                        layer.confirm('是否确定删除该项内容吗？', {
                            btn: ['确认', '取消'] //按钮
                        }, function () {
                            that.doDelete(item.id)
                            layer.closeAll();
                        }, function () {
                            layer.closeAll();
                        });
                    },
                    toEdit: function(item = null){
                        var  that = this
                        var url = "/News/NewsManage/edit"
                        if(item){
                            url += '?id=' + item.id
                        }
                        layer.open({
                            type: 2,
                            title: '操作',
                            shadeClose: true,
                            area: ['90%', '90%'],
                            content: url,
                            end: function(){
                                that.getList()
                            }
                        });
                    }
                },
                mounted: function () {
                    this.getList();
                },

            })
        })
    </script>
</block>