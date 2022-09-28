@extends("admin.layouts.main")

@section("css")
    <link rel="stylesheet" href="{{ asset('plug/zTree/css/zTreeStyle.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('admin_static/role/set.css') }}" type="text/css">
@endsection

@section("content")
    <div class="admin-main layui-anim layui-anim-upbit">
        <fieldset class="layui-elem-field">
            <legend>配置权限</legend>
            <div class="layui-field-box">
                <form class="layui-form layui-form-pane">
                    <ul id="ztree" class="ztree"></ul>
                    <div class="layui-form-item text-center">
                        @permission('admin.role.setted')
                        <button type="button" data-url="{{ route('admin.role.setted', ['role' => $role->id]) }}" data-type="PATCH" class="layui-btn" lay-submit type="button" lay-filter="set">提交</button>
                        @endpermission
                    </div>
                </form>
            </div>
        </fieldset>
    </div>
@endsection

@section("js")
    <script type="text/javascript" src="{{ asset('plug/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plug/zTree/js/jquery.ztree.core.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plug/zTree/js/jquery.ztree.excheck.min.js') }}"></script>
    <script type="text/javascript">
        var setting = {
            check:{enable: true},
            view: {showLine: false, showIcon: false, dblClickExpand: false},
            data: {
                simpleData: {enable: true, pIdKey:'pid', idKey:'id'},
                key:{name:'title'}
            }
        };
        var zNodes = {!! $rules !!};
        function setCheck() {
            var zTree = $.fn.zTree.getZTreeObj("ztree");
            zTree.setting.check.chkboxType = { "Y":"ps", "N":"ps"};

        }
        $.fn.zTree.init($("#ztree"), setting, zNodes);
        setCheck();

        layui.use(['form', 'ori'], function () {
            var form = layui.form,
                ori = layui.ori;

            form.on('submit(set)', function () {
                var treeObj = $.fn.zTree.getZTreeObj("ztree"),
                    nodes=treeObj.getCheckedNodes(true),
                    v=[];
                var nodelength = nodes.length;
                for(var i = 0;i < nodelength; ++i){
                    if(nodes[i].id > 0){
                        v[v.length]=nodes[i].id;
                    }
                }
                ori.submit($(this), {rules: v});
                return false;
            })
        });
    </script>
@endsection
