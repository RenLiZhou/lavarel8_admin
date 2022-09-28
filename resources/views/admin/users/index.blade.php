@extends("admin.layouts.main")

@section("content")
    <blockquote class="layui-elem-quote">
        <div class="layui-input-inline">
            <input type="text" name="username" value="{{ $search }}" placeholder="用户名" class="layui-input">
        </div>
        <a class="layui-btn search">查询</a>
        <span class="layui-word-aux">输入用户名进行查找</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </blockquote>
    <div class="layui-form">
        <table class="layui-table">
            <thead>
            <tr>
                <th style="min-width: 50px;">ID</th>
                <th style="min-width: 150px;">用户名</th>
                <th style="min-width: 70px;">是否启用</th>
                <th style="min-width: 100px;">手机号</th>
                <th style="min-width: 120px;">邮箱</th>
                <th style="min-width: 100px;">创建时间</th>
            </tr>
            </thead>
            <tbody  class="links_content">
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->username }}</td>
                <td>@if($user->status==1) 正常
                    @else 禁用
                    @endif</td>
                <td>{{ $user->phone }}</td>
                <td><span class="layui-elip" style="display: inline-block; width: 150px">{{ $user->email }}</span></td>
                <td>{{ $user->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div style="text-align: right;">
    {{ $users->appends(['search'=>$search])->links('admin.layouts.paginate') }}
    </div>
@endsection

@section("js")
    <script type="text/javascript">
        layui.use(['form', 'ori'], function () {
            var form = layui.form,
                ori = layui.ori,
                dialog = layui.dialog,
                $ = layui.$;

            $('.search').click(function () {
                var search = $('[name="username"]').val();
                location.href = "{{ route('user.index') }}" + '?search=' + search;
            });

            form.on('switch(active)',function (data) {
                var isActive = data.elem.checked, val, _that = $(this);
                val = (isActive === true) ? 1 : 0;
                ori.submit(_that, {field: 'status', val: val}, '', function () {
                    _that.prop('checked', !isActive);
                    form.render('checkbox');
                })
                return false;
            });
        });
    </script>
@endsection