@extends("admin.layouts.main")

@section("content")
    <blockquote class="layui-elem-quote">
        <form id="searchForm" class="layui-form">
            <div class="layui-input-inline">
                <input type="text" name="name" value="{{ $search_data['name'] }}" placeholder="用户名" class="layui-input">
            </div>

            <input type="hidden" name="per_page" value="{{ $search_data['per_page'] }}" />
            <a class="layui-btn search">查询</a>
            <span class="layui-word-aux">输入用户名进行查找</span>

            @permission('admin.admin.create')
                <a class="layui-btn layui-btn-normal add float-right" data-url="{{ route('admin.admin.create') }}">添加管理员</a>
            @endpermission
        </form>
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
                <th style="min-width: 150px;">角色</th>
                <th style="min-width: 100px;">创建时间</th>
                <th style="min-width: 125px;width: 125px;">操作</th>
            </tr>
            </thead>
            <tbody  class="links_content">
            @foreach($admins as $admin)
            <tr>
                <td>{{ $admin->id }}</td>
                <td>{{ $admin->username }}</td>
                <td><input data-url="{{ route('admin.admin.active', ['admin' => $admin->id]) }}" data-type="PATCH" type="checkbox" name="status" lay-skin="switch" lay-filter="active" lay-text="启用|禁用" @if($admin->status==1) checked @endif></td>
                <td>{{ $admin->mobile }}</td>
                <td><span class="layui-elip" style="display: inline-block; width: 150px">{{ $admin->email }}</span></td>
                <td><span style="display: inline-block; width: 150px;" class="layui-elip">@foreach($admin->roles as $role) {{ $role->name }} @endforeach</span></td>
                <td>{{ $admin->created_at->format('Y-m-d') }}</td>
                <td>
                    @permission('admin.admin.edit')
                    <a data-url="{{ route('admin.admin.edit', ['admin' => $admin->id]) }}" class="layui-btn layui-btn-xs edit">
                        <i class="layui-icon">&#xe642;</i>编辑
                    </a>
                    @endpermission
                    @permission('admin.admin.destroy')
                    <a data-url="{{ route('admin.admin.destroy', ['admin' => $admin->id]) }}" data-type="DELETE" class="layui-btn btn-gap10 layui-btn-danger layui-btn-xs del">
                        <i class="layui-icon">&#xe640;</i>删除
                    </a>
                    @endpermission
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div style="text-align: right;">
    {{ $admins->appends($search_data)->links('admin.layouts.paginate') }}
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
                var search_data = $('#searchForm').serialize();
                location.href = "{{ route('admin.admin.index') }}" + '?' + search_data;
            });

            form.on('switch(active)',function (data) {
                var isActive = data.elem.checked, val, _that = $(this);
                ori.submit(_that, {}, '', function () {
                    _that.prop('checked', !isActive);
                    form.render('checkbox');
                })
                return false;
            });

            $('.del').click(function () {
                var _that = $(this);
                dialog.confirm('确认删除', function () {
                    ori.submit(_that, '', function () {
                        _that.closest('tr').remove();
                    });
                });
            });

            $('.add').click(function () {
                dialog.pop({
                    'title': '添加管理员',
                    'content': '{{ route('admin.admin.create') }}',
                    'area': ['44%', '80%']
                });
            });

            $('.edit').click(function () {
                dialog.pop({
                    'title': '编辑管理员',
                    'content': $(this).attr('data-url'),
                    'area': ['44%', '80%']
                });
            });

        });
    </script>
@endsection
