@extends('adminlte::page')

@section('content')
<h3 class="pt-2 pl-2">{{ __('title.brand_management') }}</h3>
@include('layouts.breadcumb', [
    'items' => [
        [
            'title' => __('title.brand_list'),
            'url' => 'javascript:void();',
        ],
    ],
])
<div class="card-body p-0">
    <form action="{{ route('backend.brands.index') }}" method="GET" id="formSubmit">
        <div class="row pb-3">
            <div class="col-sm-2">
                <select class="form-control changeSubmit" id="perpage" name="per_page">
                    @for ($i = 10; $i <= 100; $i += 10)
                        <option value="{{ $i }}" {{ request()->input('per_page') == $i ? 'selected' : '' }}>{{ $i }} {{ __('title.record') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control changeSubmit" id="status" name="status">
                    <option value="" selected>{{ 'Choose' . ' ' . __('title.status') }}</option>
                    @foreach (config('common.status') as $k => $v)
                        <option value="{{ $k }}" {{ request()->input('status') == $k ? 'selected' : '' }}>{{ Str::ucfirst($v) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Type your keywords here" value="{{ request()->input('keyword') }}" name="keyword">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 d-flex justify-content-end">
                <a href="{{ route('backend.brands.create') }}" class="btn btn-success">Add New</i></a>
            </div>
        </div>
    </form>
    <div class="table-full-width table-responsive">
        <table class="table table-striped table-hover">
            <thead>
              <tr class="text-center">
                <th>{{ __('title.id') }}</th>
                <th>{{ __('title.name') }}</th>
                <th>{{ __('title.status') }}</th>
                <th>{{ __('title.action') }}</th>
              </tr>
            </thead>
            <tbody>
                @if (count($brands) > 0)
                    @foreach($brands as $key => $brand)
                        <tr class="text-center">
                            <td class="align-middle">
                                {{ $brand->id }}
                            </td>
                            <td class="align-middle">
                                {{ $brand->name }}
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-sm {{ ($brand->status == \App\Enums\CommonEnum::Active) ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                                    {{ ucfirst(config('common.status')[$brand->status]) }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('backend.brands.detail', $brand->id) }}" class="btn btn-sm btn-info mr-2">Detail</a>
                                <a href="{{ route('backend.brands.edit', $brand->id) }}" class="btn btn-sm btn-warning mr-2">Edit</a>
                                <a href="javascript:;" class="btn btn-sm btn-danger" id="btnDelete" data-id="{{ $brand->id }}">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center bg-white">
                            {{ __('message.no_data') }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-4">
            {{ $brands->onEachSide(2)->links('layouts.pagination') }}
        </div>
    </div>
</div>
@endsection
@section('css')
    <style type="text/css">
        
    </style>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).on('change', '.changeSubmit', function() {
            $('#formSubmit').submit();
        });

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });

        //Delete Category
        $(document).on('click', '#btnDelete', function(e) {
            let _this = $(this);
            let brandID = _this.attr('data-id');

            Swal.fire({
                icon: "success",
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('backend.brands.delete', ['brandInfo' => ':brandID']) }}".replace(':brandID', brandID),
                        type: 'POST',
                        data: brandID,
                        success: function success(res) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your file has been deleted.",
                                icon: "success"
                            }).then(function (result) {
                                if (result.isConfirmed) { 
                                    location.reload();
                                }
                            });
                        },
                        error: function error(err) {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "An error has occurred."
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection