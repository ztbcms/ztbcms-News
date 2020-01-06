<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <h3></h3>
            <div class="filter-container">
                <el-tabs v-model="activeName" @tab-click="clickTabs">
                    <el-tab-pane label="基本信息" name="1">
                        <el-form :model="form">
                            <el-form-item label="标题" label-width="80px" >
                                <el-input v-model="form.title" style="width: 400px" placeholder="请输入内容"></el-input>
                            </el-form-item>

                            <el-form-item label="作者" label-width="80px" >
                                <el-input v-model="form.author_name" style="width: 400px" placeholder="请输入内容"></el-input>
                            </el-form-item>
<!--

                            <el-form-item label="缩略图" label-width="80px">
                                <template slot-scope="scope">
                                    <div>
                                        <span>图片尺寸比例：1:1，支持 png,jpg 格式</span>
                                        <div>
                                            <el-image
                                                    v-if="form.thumb"
                                                    style="width: 100px; height: 100px"
                                                    :src="form.thumb">
                                            </el-image>
                                        </div>
                                        <el-button type="primary" @click="gotoUploadFile">上传缩略图</el-button>

                                    </div>
                                </template>
                            </el-form-item>
-->
                            <el-form-item label="图片集" label-width="80px" required>
                                <span>图片尺寸比例：1：1，支持png\jpg 格式</span>
                                <div>
                                    <template v-for="(item, index) in form.images">
                                        <div class="imgListItem">
                                            <img :src="item" style="width: 128px;height: 128px;">
                                            <div class="deleteMask">
                                        <span class="el-icon-delete delete-icon"
                                              @click="deleteItemBanner(index)"></span>
                                                <div class="position-ctrl">
                                            <span class="pre-icon el-icon-caret-left"
                                                  @click="changePosition(index,-1)"></span>
                                                    <span class="next-icon el-icon-caret-right"
                                                          @click="changePosition(index,1)"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <el-button type="primary" @click="gotoUploadBanner">上传</el-button>
                                <el-button type="primary" @click="fetchImagesFromContent">提取内容中图片</el-button>
                            </el-form-item>

                            <el-form-item label="文章简介" label-width="80px">
                                <el-input v-model="form.description" style="width: 400px" placeholder="请输入内容"></el-input>
                            </el-form-item>
                            <el-form-item label="发布时间" label-width="80px" >
                                <el-date-picker
                                        v-model="form.release_date"
                                        value-format="yyyy-M-d"
                                        type="date"
                                        placeholder="选择日期">
                                </el-date-picker>
                            </el-form-item>
                            <el-form-item label="文章链接" label-width="80px" >
                                <el-input v-model="form.detail_url" style="width: 400px" placeholder="请输入内容"></el-input>
                            </el-form-item>

                            <div class="el-form-item">
                                <label class="el-form-item__label" style="width: 80px;">内容</label>
                                <div class="el-form-item__content" style="margin-left: 80px;line-height: 0;">
                                    <textarea id="editor_content" style="height: 500px;width: 750px;"></textarea>
                                </div>
                            </div>

                            <el-form-item>
                                <el-button type="primary" @click="doEdit">保存</el-button>
                            </el-form-item>
                        </el-form>

                    </el-tab-pane>

                </el-tabs>

            </div>
        </el-card>
    </div>

    <style>
        .filter-container {
            padding-bottom: 10px;
        }

        .imgListItem {
            height: 128px;
            border: 1px dashed #d9d9d9;
            border-radius: 6px;
            display: inline-flex;
            margin-right: 10px;
            margin-bottom: 10px;
            position: relative;
            cursor: pointer;
            vertical-align: middle;
        }

        .deleteMask {
            position: absolute;
            top: 0;
            left: 0;
            width: 128px;
            height: 128px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.6);
            color: #fff;
            font-size: 40px;
            opacity: 0;
        }

        .deleteMask:hover {
            opacity: 1;
        }

        .delete-icon {
            line-height: 128px;
            font-size: 20px;
        }

        .position-ctrl {
            position: absolute;
            font-size: 20px;
            width: 100%;
            justify-content: space-between;
            bottom: 0;
            left: 0;
        }

        .pre-icon {
            position: absolute;
            left: 10px;
            bottom: 0;
        }

        .next-icon {
            position: absolute;
            right: 10px;
            bottom: 0;
        }

    </style>

    <!-- 引入UEditor   -->
    <include file="../../Admin/View/Common/ueditor"/>
    <script>
        $(document).ready(function () {
            var ueditorInstance = UE.getEditor('editor_content');
            new Vue({
                el: '#app',
                data: {
                    activeName: "1",
                    form: {
                        id: '{:I("get.id")}',
                        title: '',
                        thumb: '',
                        description: '',
                        detail_url: '',
                        release_date: '',
                        content: '',
                        author_name: '',
                        images: []
                    },
                    pictureUploadStatus:'',
                },
                watch: {},
                filters: {},
                methods: {
                    clickTabs: function(tab){},
                    doEdit: function () {
                        var that = this
                        that.form.content = ueditorInstance.getContent()
                        $.ajax({
                            url: "/News/NewsManage/doEdit",
                            type: "post",
                            dataType: "json",
                            data: that.form,
                            success: function(res){
                                if(res.status){
                                    layer.msg('操作成功');
                                    if (window !== window.parent) {
                                        setTimeout(function () {
                                            window.parent.layer.closeAll();
                                        }, 1000);
                                    }
                                }else{
                                    layer.msg(res.msg)
                                }
                            }
                        });
                    },
                    getDetail: function (id) {
                        var that = this
                        $.ajax({
                            url: "/News/NewsManage/getDetail?id="+id,
                            type: "get",
                            dataType: "json",
                            success: function(res){
                                console.log(res)
                                if(res.status){
                                    that.form = res.data
                                    that.initUeditor()
                                }else{
                                    layer.msg(res.msg)
                                }
                            }
                        });
                    },
                    gotoUploadBanner: function () {
                        this.pictureUploadStatus = 2;
                        layer.open({
                            type: 2,
                            title: '上传图片',
                            content: "{:U('Upload/UploadCenter/imageUploadPanel', ['max_upload' => 9])}",
                            area: ['90%', '90%'],
                        })
                    },
                    gotoUploadFile: function () {
                        this.pictureUploadStatus = 1;
                        layer.open({
                            type: 2,
                            title: '上传图片',
                            content: "{:U('Upload/UploadCenter/imageUploadPanel', ['max_upload' => 1])}",
                            area: ['90%', '90%'],
                        })
                    },
                    //上传缩略图处理
                    onUploadedFile: function (event) {
                        var that = this;
                        var files = event.detail.files;
                        if (files) {
                            if(this.pictureUploadStatus == 1){
                                that.form.thumb = files[0].url
                            } else if(this.pictureUploadStatus == 2){
                                for (var i=0; i<files.length;i++){
                                    that.form.images.push(files[0].url)
                                }
                            }
                        }
                    },
                    initUeditor: function(){
                        var that = this
                        ueditorInstance.ready(function (editor) {
                            //ueditor 初始化
                            editor.setContent(that.form.content);
                        }.bind(this, ueditorInstance))
                    },
                    deleteItemBanner: function (index) {
                        this.form.images.splice(index, 1);
                    },
                    //交换图片位置
                    changePosition: function (index, exchange) {
                        var that = this;
                        var exchangePosition = index + exchange;
                        if (that.form.images[exchangePosition]) {
                            var tmp = that.form.images[index];
                            that.form.images.splice(index, 1, that.form.images[exchangePosition]);
                            that.form.images.splice(exchangePosition, 1, tmp);
                        }
                    },
                    fetchImagesFromContent: function(){
                        var that = this;
                        that.form.content = ueditorInstance.getContent()

                        var imgReg = /<img.*?(?:>|\/>)/gi //匹配图片中的img标签
                        var srcReg = /src=[\'\"]?([^\'\"]*)[\'\"]?/i // 匹配图片中的src
                        var str = that.form.content
                        var arr = str.match(imgReg)  //筛选出所有的img
                        var srcArr = []
                        for (var i = 0; i < arr.length; i++) {
                            let src = arr[i].match(srcReg)
                            // 获取图片地址
                            // srcArr.push(src[1])
                            that.form.images.push(src[1])
                        }
                    }
                },
                mounted: function () {
                    //上传图片监听回调
                    window.addEventListener('ZTBCMS_UPLOAD_FILE', this.onUploadedFile.bind(this));
                    if(this.form.id){
                        this.getDetail(this.form.id)
                    }
                    this.initUeditor()
                },
            })
        })
    </script>
</block>

