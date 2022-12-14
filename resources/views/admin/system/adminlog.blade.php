@extends("admin.layouts.main")

@section("content")
    <blockquote class="layui-elem-quote">
        <form id="searchForm" class="layui-form">
            <div class="layui-input-inline">
                <input type="text" name="search" value="{{ $search_data['search'] }}" placeholder="IP,URL" class="layui-input">
            </div>

            <input type="hidden" name="per_page" value="{{ $search_data['per_page'] }}" />
            <a class="layui-btn search">查询</a>
            <span class="layui-word-aux">输入IP,URL进行查找</span>
        </form>
    </blockquote>
    <div class="layui-form">
        <table class="layui-table">
            <thead>
            <tr>
                <th style="min-width: 50px;">ID</th>
                <th style="min-width: 50px;">管理员</th>
                <th style="min-width: 70px;">类型</th>
                <th style="min-width: 90px;">ip</th>
                <th style="min-width: 130px;">请求URL</th>
                <th style="min-width: 70px;">请求方式</th>
                <th style="min-width: 120px;">参数</th>
                <th style="min-width: 100px;">创建时间</th>
            </tr>
            </thead>
            <tbody  class="links_content">
            @foreach($datas as $data)
            <tr>
                <td>{{ $data->id }}</td>
                <td>{{ $data->admin_id }}</td>
                <td>
                    @if($data->type==1)
                        登录日志
                    @else
                        行为日志
                    @endif
                </td>
                <td>{{ $data->ip }}</td>
                <td>{{ $data->url }}</td>
                <td>{{ $data->method }}</td>
                <td><a class="layui-btn layui-btn-xs showparam" data-param="{{ $data->param }}">参数详情</a></td>
                <td>{{ $data->created_at }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div style="text-align: right;">
    {{ $datas->appends($search_data)->links('admin.layouts.paginate') }}
    </div>
@endsection

@section("js")
    <script type="text/javascript">
        layui.use(['form', 'ori'], function () {
            var form = layui.form,
                dialog = layui.dialog,
                $ = layui.$;

            $('.search').click(function () {
                var search_data = $('#searchForm').serialize();
                location.href = "{{ route('admin.admin.admin.log') }}" + '?' + search_data;
            });

            $('.showparam').click(function () {
                dialog.modal("<p style='padding:10px;max-width:100%;word-wrap:break-word'>"+JSON.stringify($(this).data('param'))+"</p>");
            });
        });
    </script>
@endsection
