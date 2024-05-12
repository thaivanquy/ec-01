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
                            <h5>Product Information</h5>
                            <hr/>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.title') }} <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" value="{{ old('title') }}">
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.slug') }} <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" id="slug" value="{{ old('slug') }}" readonly>
                                @error('slug')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('title.short_description') }}</label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror summernote" name="short_description" id="short_description">
                                    {{ old('short_description') }}
                                </textarea>
                                @error('short_description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('title.description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror summernote" name="description" id="description">
                                    {{ old('description') }}
                                </textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                        <div class="attribute-body">
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12 attribute-foot">
                                <button type="button" class="btn btn-outline-info px-5 btn-add-attribute">Add New Attribute.</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-attribute"></div>
        </div>
    </div>
    <div class="row ml-1">
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
        $('.summernote').summernote({
            height: 300,
        });
        const noImg = "{{ Vite::asset('resources/images/noimage.jpg') }}"
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

        //Turn On Atrribute
        if ($('.turnOnAttribute').length) {
            $(document).on('change', '.turnOnAttribute', function(e){
                if ($('.turnOnAttribute:checked').length !== 0) {
                    $('.attribute-wrapper').removeAttr('hidden');
                } else {
                    $('.attribute-wrapper').attr("hidden", true);
                }
            });
        }

        const attributes = @json($attributes);
        // Button add Atrribute
        if ($('.btn-add-attribute').length) {
            $(document).on('click', '.btn-add-attribute', function(e) {
                let html = renderHTML(attributes);
                $('.attribute-body').append(html);
                $(this).prop("disabled", true);
                disabledChooseAttributed();
                removeButtonAttribute(attributes);
            });
        }

        const renderHTML = (attributes) => {
            let html = `
                <div class="row my-3 attribute-item">
                    <div class="col-md-3">
                        <select name="" id="" class="float-none choose-attribute niceSelect">
                            <option value="">Please Select Attributes</option>`;
                attributes.forEach(function (attribute) {
                    html += `<option value="${attribute.id}">${attribute.name}</option>`
                });
            html += `
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" disabled class="form-control">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-delete-attribute"><i class="fa fa-trash"></i></button>
                    </div>
                </div>`;
            return html;
        };

        // The event change select option then allow add extra atribute
        $(document).on('change', '.choose-attribute', function(e){
            let _this = $(this);
            let attributeId = _this.val();
            if (attributeId != '') {
                _this.parents('.col-md-3').siblings('.col-md-8').html(select2Attribute(attributeId));
                $('.selectAttribute').each(function() {
                    getSelect2($(this));
                });
            } else {
                _this.parents('.col-md-3').siblings('.col-md-8').html('<input type="text" disabled="" class="form-control">');
            }
            disabledChooseAttributed();
        });

        //The event delete attribute
        $(document).on('click', '.btn-delete-attribute', function(e){
            let _this = $(this);
            _this.parents('.attribute-item').remove();
            removeButtonAttribute(attributes);
            createListAtrribute();
        });
        
        const disabledChooseAttributed = () => {
            let selectedValues = [];
            $('.choose-attribute').each(function(e) {
                let _this = $(this);
                let selected = _this.find('option:selected').val();
                if (selected != "") {
                    selectedValues.push(selected);
                }
            });

            selectedValues.forEach(function(e) {
                $('.choose-attribute').find(`option[value="${e}"]`).prop('disabled', true);
            });

            $('.niceSelect').niceSelect('destroy'); 
            $('.niceSelect').niceSelect(); 
        };

        //Check if item of attribute > attributes in databse then remove button add attribute
        const removeButtonAttribute = (attributes) => {
            let attributeItem = $('.attribute-item').length;
            if (attributeItem >= attributes.length) {
                $('.btn-add-attribute').remove();
            } else {
                $('.attribute-foot').html('<button type="button" class="btn btn-outline-info px-5 btn-add-attribute">Add New Attribute.</button>');
            }
        };

        //The event wap input to select2 multiple
        const select2Attribute = (attributeId) => {
            let html = `<select class="selectAttribute form-control attribute-${attributeId}" name="attribute[${attributeId}][]" multiple data-attr-id="${attributeId}"></select>`

            return html;
        };
        
        const getSelect2 = (object) => {
            let data = {
                'attributeId': object.attr('data-attr-id')
            }
            $(object).select2({
                minimumInputLength: 1,
                placeholder: 'Enter at least one characters to search',
                ajax: {
                    url: "{{ route('backend.attribute') }}",
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            keyword: params.term,
                            option: data,
                        }
                    },
                    processResults: function(data) {
                        return {
                            results: data.items
                        }
                    },
                    cache: true
                }
            });
        };

        // The event take all value of the attribute
        $(document).on('change', '.selectAttribute', function(e){
            createListAtrribute();
        });

        const createListAtrribute = () => {
            let attributes = [];
            let attributeTitle = [];
            $('.attribute-item').each(function () {
                let _this = $(this);
                let attributeId = _this.find('.choose-attribute option:selected').val();
                let attributeText = _this.find('.choose-attribute option:selected').text();
                let attributeValue = $(`.attribute-${attributeId}`).select2('data');

                const attrVals = attributeValue.map(item => ({ [attributeText]: item.text }));
                attributeTitle.push(attributeText);
                attributes.push(attrVals);
            });
            
            if (attributes.length > 0) {
                attributes = attributes.reduce(
                    (a,b) => a.flatMap(d => b.map(e => ({...d, ...e})))
                )
                $('.table-attribute').prop('hidden', false).html(renderListAttribute(attributes, attributeTitle));
            } else {
                $('.table-attribute').prop('hidden', true).html('');
            }            
        };

        //Render list attribute value
        const renderListAttribute = (attributes, attributeTitle) => {
            let html = `<div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>List Product Attributes</h5>
                            <div class="table-full-width table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th>{{ __('title.image') }}</th>`
                                    attributeTitle.forEach(function(attr){
                                        html +=`<th>${attr}</th>`
                                    });
                                    html +=`<th>Quantity</th>
                                            <th>Price</th>
                                            <th>SKU</th>
                                            <th>Barcode</th>
                                        </tr>
                                    </thead>
                                    <tbody>`
                                    attributes.forEach(function(attribute) {
                                        html +=`<tr class="text-center">
                                                    <td class="align-middle">
                                                        <img src="${noImg}" alt="no-img" width="50" height="50">
                                                    </td>`
                                                $.each(attribute, function(key, value) {
                                                    html +=`<td class="align-middle">
                                                            ${value}
                                                            </td>`
                                                })
                                            html +=`<td class="align-middle">
                                                        <input type="number" class="form-control" name="" id="">
                                                    </td>
                                                    <td class="align-middle">
                                                        <input type="number" class="form-control" name="" id="">
                                                    </td>
                                                    <td class="align-middle">
                                                        <input type="text" class="form-control" name="" id="">
                                                    </td>
                                                    <td class="align-middle">
                                                        <input type="text" class="form-control" name="" id="">
                                                    </td>
                                                </tr>`
                                    });
                                       
                                    html +=`</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`
            return html;
        };
    </script>
@endsection