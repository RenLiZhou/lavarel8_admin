<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lyadmin</title>
    <meta name="description" content="simple admin" />
    <meta name="keyword" content="layui,laravel,admin" />
    <link rel="stylesheet" href="{{ asset('layui/css/layui.css') }}"/>
    <link rel="stylesheet" href="{{ asset('admin_static/index/index.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('admin_static/index/or.ico') }}"/>
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">Lyadmin</div>
        <!-- 头部区域（可配合layui已有的水平导航） -->
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item min-hide" lay-unselect>
                <a href="javascript:;" layadmin-event="flexible" class="hideMenu" title="侧边伸缩">
                    <i class="layui-icon layui-icon-shrink-right"></i>
                </a>
            </li>
            <li class="layui-nav-item min-hide"><a href="">控制台</a></li>
            <li class="layui-nav-item orinfy-msg"><a href="">消息<span class="layui-badge-dot"></span></a></li>
            <li class="layui-nav-item min-hide">
                <a href="javascript:;">缓存</a>
                <dl class="layui-nav-child">
                    <dd><a id="flush-cache" data-url="{{ route('admin.cache.flush') }}" data-type="PUT">刷新缓存</a></dd>
                    <dd><a id="clean-cache" data-url="{{ route('admin.cache.clean') }}" data-type="DELETE">清除缓存</a></dd>
                </dl>
            </li>
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item avt-hide">
                <a href="javascript:;">
                    <img src="{{ asset('admin_static/index/face.jpg') }}" class="layui-nav-img">
                    <span>{{ $admin->username }}</span>
                </a>
                <dl class="layui-nav-child">
                    @permission('admin.admin.safe')
                    <dd><a href="javascript:void(0);" id="pwd-set" data-url="{{ route('admin.admin.safe', ['admin' => $admin->id]) }}">
                            安全设置
                        </a></dd>
                    @endpermission
                </dl>
            </li>
            <li class="layui-nav-item">
                <form name="out" method="post">
                    <a href="{{ route('admin.logout') }}" >退出</a>
                </form>
            </li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree"  lay-filter="left-nav">
                {{--默认--}}
                <li class="layui-nav-item layui-this">
                    <a href="javascript:;" data-url="{{ route('admin.first') }}" data-id="first" class="menu-first">
                        <i class="layui-icon layui-icon-home"></i>
                        <cite>控制台</cite>
                    </a>
                </li>
                {{--默认--}}

            </ul>
        </div>
    </div>

    <div class="layui-body">
        <!-- 内容主体区域 -->
        <div class="layui-tab layui-tab-brief" lay-filter="top-tab" lay-allowClose="true" style="margin: 0;">
            <ul class="layui-tab-title top-tab"></ul>
            <div class="layui-tab-content"></div>
        </div>
    </div>

    <div class="layui-footer">
        <!-- 底部固定区域 -->
        © Lyadmin
    </div>
    <!-- 移动端菜单弹出按钮 -->
    <div class="site-tree-mobile layui-hide"><i class="layui-icon">&#xe602;</i></div>
    <!--移动端菜单弹出阴影效果-->
    <div class="site-mobile-shade"></div>
</div>
<script src="{{ asset('layui/layui.js') }}"></script>
<script>
    layui.config({
        base: '/admin_static/base/'
    });
    layui.use(['element', 'ori', 'cms'], function(){
        var element = layui.element,
            layer = layui.layer,
            dialog = layui.dialog,
            $ = layui.jquery
            ori = layui.ori;

        var cms = layui.cms('left-nav', 'top-tab');
        var menu = {!! $menu !!};
        // 添加菜单
        cms.addNav(menu, 0, 'id', 'pid', 'title', 'href');

        cms.bind(50 + 36 + 4 + 30); //头部高度 + 顶部切换卡标题高度 + 顶部切换卡内容padding + 底部高度
        // 默认打开窗口
        cms.clickNavId('first');

        // pc模式下菜单隐藏效果
        $(".hideMenu").on("click",function(){
            $('.layui-layout-admin').toggleClass('menu-hide');
        });
        // 移动端下菜单隐藏效果
        $('.site-tree-mobile').click(function () {
            $('.layui-layout-admin').toggleClass('site-mobile');
        });
        // 移动端菜单按钮是否显示
        $('.site-mobile-shade').click(function(){
            $('.layui-layout-admin').toggleClass('site-mobile');
        });
        $(window).resize(function() {
            // 屏幕调整清除多余class
            $('.layui-layout-admin').removeClass('site-mobile').removeClass('menu-hide');
        })

        $('#pwd-set').click(function () {
            dialog.pop({
                'title': '修改密码',
                'content': $(this).attr('data-url'),
            });
        });

        $('#flush-cache').click(function () {
            ori.submit($(this), '', function () {
                location.reload();
            });
        });

        $('#clean-cache').click(function () {
            ori.submit($(this));
        });
    });
</script>
</body>
</html>
