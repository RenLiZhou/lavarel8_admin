@extends("admin.layouts.main")

@section("content")
	<blockquote class="layui-elem-quote">
		@permission('admin.role.store')
		<a class="layui-btn layui-btn-normal add" data-url="{{ route('admin.role.store') }}" data-type="POST">添加角色</a>
		@endpermission
	</blockquote>
	<div>
	  	<table class="layui-table">
		    <thead>
				<tr>
					<th style="min-width: 50px;width: 50px;">ID</th>
					<th>角色</th>
					<th style="min-width: 230px;width: 230px;">操作</th>
				</tr>
		    </thead>
		    <tbody class="links_content">
			@foreach ($roles as $role)
				<tr>
					<td>{{ $role->id }}</td>
					<td class="role-name">{{ $role->name }}</td>
					<td>
						@permission('admin.role.update')
						<a class="layui-btn layui-btn-xs edit"  data-url="{{ route('admin.role.update', ['role' => $role->id]) }}" data-type="PUT">
							<i class="layui-icon">&#xe642;</i>
							编辑
						</a>
						@endpermission
						@permission('admin.role.set')
						<a data-url="{{ route('admin.role.set', ['role' => $role->id]) }}" class="layui-btn layui-btn-warm layui-btn-xs set">
							<i class="layui-icon"></i>
							配置权限
						</a>
						@endpermission
						@permission('admin.role.destroy')
						<a data-url="{{ route('admin.role.destroy', ['role' => $role->id]) }}" data-type="DELETE" class="layui-btn layui-btn-danger layui-btn-xs del">
							<i class="layui-icon">&#xe640;</i>
							删除
						</a>
						@endpermission
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
@endsection

@section("js")
	<script type="text/javascript">
        layui.use(['form', 'ori'], function () {
            var form = layui.form,
                ori = layui.ori,
                dialog = layui.dialog,
				$ = layui.$;


            $('.del').click(function () {
                var _that = $(this);
                dialog.confirm('确认删除', function () {
                    ori.submit(_that, '', function () {
                        _that.closest('tr').remove();
                    });
                });
            });

            var html = '<div class="or-mid"><form class="layui-form layui-form-pane" style="width:95%;"><br/>\n' +
                '  <div class="layui-form-item">\n' +
                '    <label class="layui-form-label">角色名</label>\n' +
                '    <div class="layui-input-block">\n' +
                '      <input type="text" name="name" lay-verify="required" placeholder="请输入角色名" autocomplete="off" class="layui-input">\n' +
                '    </div>\n' +
                '  </div>\n' +
                '  <div class="layui-form-item">\n' +
                '    <div class="layui-input-block">\n' +
                '      <button class="layui-btn" lay-submit lay-filter="role">立即提交</button>\n' +
                '      <button type="reset" class="layui-btn layui-btn-primary">重置</button>\n' +
                '    </div>\n' +
                '  </div>\n' +
                '</form></div>';

            $('.add').click(function () {
                var _that = $(this);
                dialog.modal(html, ['35%', '40%'], '添加角色');
                form.on('submit(role)', function (data) {
                    ori.submit(_that, data.field, function () {
                        location.reload();
					});
                    return false;
				});
            });

            $('.edit').click(function () {
                var _that = $(this);
                var modalIndex = dialog.modal(html, ['35%', '40%'], '编辑角色');
                $('[name="name"]').val(_that.closest('tr').find('.role-name').text());
                form.on('submit(role)', function (data) {
                    ori.submit(_that, data.field, function () {
                        dialog.close(modalIndex);
                        _that.closest('tr').find('.role-name').text(data.field.name);
                    });
                    return false;
                });
            });

            $('.set').click(function () {
                dialog.pop({
					title: '配置角色权限',
					content: $(this).attr('data-url'),
					area: ['90%', '90%']
				});
			});

        });
	</script>
@endsection

