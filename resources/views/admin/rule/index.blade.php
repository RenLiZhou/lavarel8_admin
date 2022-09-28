@extends("admin.layouts.main")

@section("css")
	<link rel="stylesheet" href="{{ asset('plug/treegrid/css/jquery.treegrid.css') }}">
@endsection

@section("content")
	<blockquote class="layui-elem-quote">
		@permission('admin.rule.create')
		<a class="layui-btn layui-btn-normal add" data-url="{{ route('admin.rule.create') }}">添加权限</a>
		@endpermission
		<span class="layui-word-aux">添加权限后需要清除缓存</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</blockquote>
	<div class="layui-form">
	  	<table class="layui-table tree">
		    <thead>
				<tr>
					<th style="min-width: 50px;">ID</th>
					<th style="min-width: 50px;">图标</th>
					<th style="min-width: 100px;">权限名称</th>
					<th style="min-width: 150px;">链接</th>
					<th style="min-width: 150px;">权限</th>
					<th style="min-width: 70px;">是否为菜单</th>
					<th style="min-width: 100px;">是否记录日志</th>
					<th style="min-width: 100px;">排序</th>
					<th style="min-width: 130px;">操作</th>
				</tr>
		    </thead>
		    <tbody class="links_content">
			@foreach ($rules as $rule)
				<tr class="treegrid-{{$rule['id']}} @if($rule['pid']!=0) treegrid-parent-{{$rule['pid']}} @endif">
					<td>{{ $rule['id'] }}</td>
					<td><i class="layui-icon {{ $rule['icon'] }}"></i></td>
					<td style="text-align:left;">{{ $rule['ltitle'] }}</td>
					<td style="text-align:left;">{{ $rule['href'] }}</td>
					<td style="text-align:left;">{{ $rule['rule'] }}</td>
					<td>@if($rule['type'] == 1) <span style="color: #6CB778;">是</span> @else <span style="color: #ff9a3f;">否</span> @endif</td>
					<td>
						<input data-url="{{ route('admin.rule.islog', ['rule' => $rule['id']]) }}" data-type="POST" type="checkbox" lay-skin="switch" lay-text="是|否" lay-filter="isLog" @if ($rule['islog'] == 1) checked @endif>
					</td>
					<td>
						<input data-url="{{ route('admin.rule.sort', ['rule' => $rule['id']]) }}" data-type="POST" name="sort" type="text" class="layui-input"  value="{{$rule['sort']}}" />
					</td>
					<td>
						@permission('admin.rule.edit')
						<a data-url="{{ route('admin.rule.edit', ['rule' => $rule['id']]) }}" class="layui-btn layui-btn-xs edit">
							<i class="layui-icon">&#xe642;</i>
							编辑
						</a>
						@endpermission
						@permission('admin.rule.destroy')
						<a data-url="{{ route('admin.rule.destroy', ['rule' => $rule['id']]) }}" data-type="DELETE" class="layui-btn layui-btn-danger layui-btn-xs del">
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
	<script type="text/javascript" src="{{ asset('plug/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plug/treegrid/js/jquery.treegrid.js') }}"></script>
	<script type="text/javascript">
        layui.use(['form', 'ori'], function () {
            var form = layui.form,
                ori = layui.ori,
                dialog = layui.dialog;

			$('.tree').treegrid({});//全部展开
			// $('.tree').treegrid({initialState: 'collapsed', show: false});//不展开

			form.on('switch(isLog)',function (data) {
                var isLog = data.elem.checked;
                var _that = $(this);
                ori.submit(_that, {}, '', function () {
                    _that.prop('checked', !isLog);
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
                    'title': '添加权限',
                    'content': $(this).attr('data-url'),
                    'area': ['44%', '80%']
                });
            });

            $('.edit').click(function () {
                dialog.pop({
                    'title': '编辑权限',
                    'content': $(this).attr('data-url'),
                    'area': ['44%', '80%']
                });
            });

            $('[name="sort"]').click(function () {
                var _that = $(this);
                var val = _that.val();
                dialog.prompt('修改排序', val, function (sort) {
                    if (parseFloat(sort).toString() == "NaN") {
                        dialog.erMsg('请输入数字');
                        return false;
					}
                    ori.submit(_that, {sort: sort}, function () {
                        _that.val(sort);
					});
				});
			});

        });
	</script>
@endsection

