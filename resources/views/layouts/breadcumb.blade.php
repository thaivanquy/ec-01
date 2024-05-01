<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent">
        <li class="breadcrumb-item">
            <a href="{!! route('backend.dashboard') !!}"><i class="nav-icon fas fa-tachometer-alt"></i> @lang('title.dashboard')</a>
        </li>
        @isset($items)
            @foreach ($items as $key => $item)
                @if ($loop->last)
                    <li class="breadcrumb-item">
                        {{ $item['title'] }}
                    </li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{!! $item['url'] !!}">{{ $item['title'] }}</a>
                    </li>
                @endif
            @endforeach
        @endisset
    </ol>
</nav>