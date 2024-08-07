@extends('adminlte::page')

@section('content')
<h3 class="pt-2 pl-2">{{ __('title.category_management') }}</h3>
@include('layouts.breadcumb', [
    'items' => [
        [
            'title' => __('title.category_list'),
            'url' => 'javascript:void();',
        ],
    ],
])
<div class="card-body p-0">
    <form action="{{ route('backend.categories.index') }}" method="GET" id="formSubmit">
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
                <a href="javascipt::void(0)" class="btn bg-gray mr-3" data-toggle="modal" data-target="#importModal">Import CSV</a>
                <a href="{!! route('backend.categories.export', [
                    'page' => \Request::input('page'),
                    'per_page' => \Request::input('per_page'),
                    'keyword' => \Request::input('keyword'),
                    'status' => \Request::input('status'),
                ]) !!}" class="btn bg-lightblue mr-3 {{ $categories->count() ? '' : 'disabled' }}">
                    {{ __('title.export') }}
                </a>
                <a href="{{ route('backend.categories.create') }}" class="btn btn-success">Add New</i></a>
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
                @if (count($categories) > 0)
                    @foreach($categories as $key => $category)
                        <tr class="text-center">
                            <td class="align-middle">
                                {{ $category->id }}
                            </td>
                            <td class="align-middle">
                                {{ $category->name }}
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-sm {{ ($category->status == \App\Enums\CommonEnum::Active) ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                                    {{ ucfirst(config('common.status')[$category->status]) }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('backend.categories.detail', $category->id) }}" class="btn btn-sm btn-info mr-2">Detail</a>
                                <a href="{{ route('backend.categories.edit', $category->id) }}" class="btn btn-sm btn-warning mr-2">Edit</a>
                                <a href="javascript:;" class="btn btn-sm btn-danger" id="btnDelete" data-id="{{ $category->id }}">Delete</a>
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
            {{ $categories->onEachSide(2)->links('layouts.pagination') }}
        </div>
    </div>
</div>
<!-- Import Modal -->
<form action="{{ route('backend.categories.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Modal Import CSV</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span>&times;</span>
            </button>
            </div>
                <div class="modal-body">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('file') is-invalid @enderror" name="file" id="customFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                        @error('file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary importCSV">Import CSV</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@section('css')
    <style type="text/css">
        
    </style>
@endsection
@section('js')
    <script type="text/javascript">
        // Import File CSV
        bsCustomFileInput.init()
        @if ($errors->any())
            $('#importModal').modal('show');
        @endif

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
            let categoryID = _this.attr('data-id');

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
                        url: "{{ route('backend.categories.delete', ['categoryInfo' => ':categoryID']) }}".replace(':categoryID', categoryID),
                        type: 'POST',
                        data: categoryID,
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