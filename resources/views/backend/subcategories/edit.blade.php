@extends('adminlte::page')

@section('content')
<h3 class="pt-2 pl-2">{{ __('title.sub_category_management') }}</h3>
@include('layouts.breadcumb', [
    'items' => [
        [
            'title' => __('title.sub_category_list'),
            'url' => route('backend.subcategories.index'),
        ],
        [
            'title' => __('title.sub_category_edit'),
            'url' => 'javascript:void();',
        ]
    ],
])
<form action="{{ route('backend.subcategories.update', $subCategory->id) }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.name') }} <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $subCategory->name) }}">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.slug') }} <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" id="slug" value="{{ old('slug', $subCategory->slug) }}" readonly>
                                @error('slug')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.category') }} <span class="text-danger">(*)</span></label>
                                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $category->id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.status') }} <span class="text-danger">(*)</span></label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    @foreach (config('common.status') as $k => $v)
                                        <option value="{{ $k }}" {{ old('status', $subCategory->status) == $k ? 'selected' : '' }}>{{ ucfirst($v) }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <a href="{{ route('backend.subcategories.index') }}" class="btn btn-sm bg-navy mr-2">Back</a>
        <button class="btn btn-sm btn-success mr-2" type="submit">{{ __('title.save') }}</button>
    </div>
</form>
@endsection
@section('css')
    <style>

    </style>
@endsection
@section('js')
    <script type="text/javascript">
         $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });

        // Get Slug
        $(document).on('keyup', '#name', function(e){
            let _this = $(this);
            let data = {
                'title': _this.val()
            }
            $.ajax({
                url: "{{ route('backend.slug') }}",
                type: 'GET',
                data: data,
                success: function(res) {
                    if(res['status'] == true) {
                        $('#slug').val(res['slug']);
                    }
                },
                error: function(err) {
                    console.log('Lỗi: ' + err);
                }
            });
            
        });
    </script>
@endsection