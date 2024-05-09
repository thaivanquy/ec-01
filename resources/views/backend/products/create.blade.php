@extends('adminlte::page')

@section('content')
<h3 class="pt-2 pl-2">{{ __('title.product_management') }}</h3>
@include('layouts.breadcumb', [
    'items' => [
        [
            'title' => __('title.product_list'),
            'url' => route('backend.products.index'),
        ],
        [
            'title' => __('title.product_create'),
            'url' => 'javascript:void();',
        ]
    ],
])
<form action="{{ route('backend.brands.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Product Attributes</h5>
                            <span class="font-italic">Allows you to sell products with different attributes, for example clothes in different <strong class="text-danger">colors</strong> and <strong class="text-danger">sizes</strong>. Each attribute will be a line in the version list below.</span>
                            <hr/>
                            <div>
                                <input type="checkbox" name="accept" id="attributeCheckbox" value="" class="turnOnAttribute"> 
                                <label for="attributeCheckbox" class="ml-2">This product has many attributes.</label>
                            </div>
                        </div>
                    </div>
                    <div class="attribute-wrapper" hidden>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <span class="text-info">Select Attributes</span>
                            </div>
                            <div class="col-md-8">
                                <span class="text-info">Select the value of the attribute.(Enter 2 characters to search)</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <select name="" id="" class="float-none">
                                    <option value="">Please Select Attributes</option>
                                    <option value="">Color</option>
                                    <option value="">Size</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <input type="text" disabled class="form-control">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-info px-5">Add New Attribute.</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <a href="{{ route('backend.products.index') }}" class="btn btn-sm bg-navy mr-2">Back</a>
        <button class="btn btn-sm btn-success mr-2" type="submit">{{ __('title.save') }}</button>
        <button class="btn btn-sm bg-teal" type="submit" name="action" value="save_and_new">Save and New</button>
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

        $('select').niceSelect();

        if ($('.turnOnAttribute').length) {
            $(document).on('change', '.turnOnAttribute', function(e){
                console.log('', $('.turnOnAttribute'))
                if ($('.turnOnAttribute:checked').length !== 0) {
                    $('.attribute-wrapper').removeAttr('hidden');
                } else {
                    $('.attribute-wrapper').attr("hidden", true);
                }
            });
        }
    </script>
@endsection