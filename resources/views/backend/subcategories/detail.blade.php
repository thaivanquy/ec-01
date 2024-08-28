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
            'title' => __('title.sub_category_detail'),
            'url' => 'javascript:void();',
        ]
    ],
])
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('title.name') }} <span class="text-danger">(*)</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ $subCategory->name }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('title.slug') }} <span class="text-danger">(*)</span></label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" id="slug" value="{{ $subCategory->slug }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('title.category') }} <span class="text-danger">(*)</span></label>
                            <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" disabled>
                                <option value="{{ $subCategory->category->id }}">{{ $subCategory->category->name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('title.status') }} <span class="text-danger">(*)</span></label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" disabled>
                                @foreach (config('common.status') as $k => $v)
                                    <option value="{{ $k }}" {{ ($subCategory->status) == $k ? 'selected' : '' }}>{{ ucfirst($v) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <a href="{{ route('backend.subcategories.edit', $subCategory->id) }}" class="btn btn-sm btn-warning mr-2">Edit</a>
    <a href="{{ route('backend.subcategories.index') }}" class="btn btn-sm bg-navy mr-2">Back</a>
</div>
@endsection
@section('css')
    <style>

    </style>
@endsection
@section('js')
    <script type="text/javascript">
    </script>
@endsection