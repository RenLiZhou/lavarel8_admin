<div class="layui-box layui-laypage layui-laypage-default" id="layui-laypage-0">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <a href="javascript:;" class="disabled">&laquo;</a>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <a href="javascript:;">{{ $element }}</a>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="layui-laypage-curr"><em class="layui-laypage-em"></em><em>{{ $page }}</em></span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
    @else
        <a href="javascript:;">&raquo;</a>
    @endif

    {{ $paginator->url(1) }}
</div>
<div class="paginate-select-main">
    <select class="paginate-select-page" onChange="_jM.paginatorSelect(this, '{{ $paginator->url(1) }}')">
        <option value="10" @if($paginator->perPage() == 10) selected @endif>10</option>
        <option value="30" @if($paginator->perPage() == 30) selected @endif>30</option>
        <option value="50" @if($paginator->perPage() == 50) selected @endif>50</option>
        <option value="100" @if($paginator->perPage() == 100) selected @endif>100</option>
    </select>
    <span>Total：{{ $paginator->total() }}</span>
</div>

<script src="{{ asset('admin_static/js/jquery.min.js') }}"></script>
<script src="{{ asset('admin_static/js/public.js') }}"></script>
