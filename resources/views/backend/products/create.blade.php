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
<form action="{{ route('backend.products.store') }}" method="POST">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.price') }} <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control @error('regular_price') is-invalid @enderror format-price" name="regular_price" value="{{ old('regular_price') }}">
                                @error('regular_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.price_sale') }}</label>
                                <input type="text" class="form-control @error('compare_price') is-invalid @enderror format-price" name="compare_price" id="compare_price" value="{{ old('compare_price') }}">
                                @error('compare_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.barcode') }} <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control @error('barcode') is-invalid @enderror inputBarcode" name="barcode" value="{{ old('barcode') }}" readonly>
                                @error('barcode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <svg id="barcode"></svg>
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
            <div class="table-attribute">
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Product Status</h5>
                            <hr/>
                        </div>
                        <div class="col-md-12">
                            @foreach (config('common.publish') as $k => $v)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="publish" id="publish{{ $k }}" value="{{ $k }}" {{ old('publish') == $k ? 'checked' : '' }}>
                                    <label class="form-check-label" for="publish{{ $k }}">{{ ucfirst($v) }}</label>
                                </div>    
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror                              
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Product Category</h5>
                            <hr/>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('title.category') }} <span class="text-danger">(*)</span></label>
                                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                    <option value="">[Choose Category]</option>
                                    @if ($categories->isNotEmpty())
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('title.sub_category') }}</label>
                                <select name="sub_category_id" id="sub_category_id" class="form-control @error('sub_category_id') is-invalid @enderror" disabled>
                                    <option value="">[Choose Sub Category]</option>
                                </select>
                                @error('sub_category_id')
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
                            <h5>Product Brand</h5>
                            <hr/>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('title.brand') }}</label>
                                <select name="brand_id" id="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                                    <option value="">[Choose Brand]</option>
                                    @if ($brands->isNotEmpty())
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('brand_id')
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
                            <h5>Product Featured</h5>
                            <hr/>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <select name="is_featured" id="is_featured" class="form-control @error('is_featured') is-invalid @enderror">
                                    @foreach (config('common.featured') as $k => $v)
                                        <option value="{{ $k }}" {{ old('is_featured') == $k ? 'selected' : '' }}>{{ ucfirst($v) }}</option>
                                    @endforeach
                                </select>
                                @error('is_featured')
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
                            <h5>Product Image</h5>
                            <hr/>
                        </div>
                        <div class="col-md-12">
                            <label>{{ __('title.image') }} <span class="text-danger">(*)</span></label>
                            <div class="preview">
                                <img src="{{ Vite::asset('resources/images/no-image.png') }}" alt="no-image" class="preview-image" width="490" height="300"/>
                            </div>
                            <div class="button_section">
                                <div class="button_group">
                                  <label for="input-image"><i class="fa fa-upload"></i> Upload Image</label>
                                  <input type="file" name="image" id="input-image" accept=".jpg, .jpeg, .png"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
        .upload{
            width: 125px;
            position: relative;
        }
        .upload .round{
            position: absolute;
            bottom: -8px;
            right: 16px;
            background: #CFCFCF;
            width: 29px;
            height: 32px;
            line-height: 33px;
            text-align: center;
            border-radius: 50%;
            overflow: hidden;
        }

        .upload .round input[type = "file"]{
            position: absolute;
            transform: scale(2);
            opacity: 0;
        }

        input[type=file]::-webkit-file-upload-button{
            cursor: pointer;
        }

        .button_section{
            display: flex;
            flex-direction: row;
            gap: 20px;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .button_group {
            margin: 20px 0 0 0;
            border: 2px solid black;
            border-radius: 15px;
            box-sizing: border-box;
            transition: all 300ms cubic-bezier(.23, 1, 0.32, 1);
        }
        .button_group input{
            display: none;
        }

         .button_group label{
            cursor: pointer;
            padding: 10px 185px;
            margin: 0;
        }
        
        .button_group:hover{
            color: #fff;
            background-color: #1A1A1A;
        }

        img {
            max-width: 100%;
            object-fit: contain;
            width: 100%;
        }
    </style>
@endsection
@section('js')
    <script type="text/javascript">
        //Generated Barcode
        const formatBarcode = (barcodeVal) => {
            return barcodeVal.substring(0, 1) + " " + barcodeVal.substring(1, 7) + " " + barcodeVal.substring(7);
        };

        const val1 = Math.floor(1000 + Math.random() * 9999);
        const val2 = Math.floor(100000 + Math.random() * 999999);
        const barcodeVal = `893${val1}${val2}`;
        $('.inputBarcode').val(barcodeVal);
        JsBarcode("#barcode", formatBarcode(barcodeVal), {
            format: "CODE128",
            lineColor: "#0aa",
            width: 2.8,
            height: 60,
        });
        //
        $('.summernote').summernote({
            height: 300,
        });

        //Preview Image Before Upload 
        $(document).on('change', '#input-image', function(){ 
            $('.preview-image').attr('src', URL.createObjectURL(this.files[0]));
        });        

        //Preview Before Upload
        $(document).on('change', '.image', function(){ 
            const preview = $(this).closest('tr').find('.avtPreview');
            preview.attr('src', URL.createObjectURL(this.files[0]));
        });

        const noImg = "{{ Vite::asset('resources/images/noimage.jpg') }}"
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });

        // Get Slug
        $(document).on('keyup', '#title', function(e){
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
                let price = $('input[name="regular_price"]').val();
                let barcode = $('input[name="barcode"]').val(); 
                if (price == '' || barcode == '') {
                    Swal.fire({
                        icon: "error",
                        title: "Sorry !!!",
                        text: "You must enter price and barcode to use this function!",
                    }).then(() => {
                        $(this).prop('checked', false);
                    });
                    return false;
                }

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
                $('#table-attribue thead').html('');
                $('#table-attribue tbody').html('');
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
            $('#table-attribue tbody').html('');
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

        // The event refresh table attribute
        $(document).on('click', '.refresh-table', function() {
            $('input[name="price[]"]').val($('input[name="compare_price"]').val() != 0 ? $('input[name="compare_price"]').val() : $('input[name="regular_price"]').val());
        });

        // The event take all value of the attribute
        $(document).on('change', '.selectAttribute', function(e){
            $('#table-attribue thead').html('');
            $('#table-attribue tbody').html('');
            createListAtrribute();
        });

        const createListAtrribute = () => {
            let attributeVals = [];
            let variants = [];
            let attributeTitle = [];
            $('.attribute-item').each(function () {
                let _this = $(this);
                let attributeId = _this.find('.choose-attribute option:selected').val();
                let attributeText = _this.find('.choose-attribute option:selected').text();
                let attributeValue = $(`.attribute-${attributeId}`).select2('data');
                if (attributeValue && Array.isArray(attributeValue)) {
                    const attrId = attributeValue.map(item => ({ [attributeId]: item.id }));
                    const attrVals = attributeValue.map(item => ({ [attributeText]: item.text }));
                    attributeTitle.push(attributeText);
                    attributeVals.push(attrVals);
                    variants.push(attrId);
                }
            });

            if (attributeVals.length > 0) {
                attributeVals = attributeVals.reduce(
                    (a,b) => a.flatMap(d => b.map(e => ({...d, ...e})))
                )

                variants = variants.reduce(
                    (a,b) => a.flatMap(d => b.map(e => ({...d, ...e})))
                )

                if ($('.table-attribute thead').length === 0) {
                    $('.table-attribute').prop('hidden', false).append(createHeaderTable(attributeTitle));
                }
                
                attributeVals.forEach((item, index) => {
                    let row = createVariantRow(item, variants[index]);
                    $('.table-attribute').find('#table-attribue tbody').append(row);
                });
            } else {
                $('.table-attribute').prop('hidden', true).html('');
            }            
        };

        const createHeaderTable = (attributeTitle) => {
            let header = `
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <h5>List Product Attributes</h5>
                                    <span>
                                        <i role="button" title="Change Price like above" class="fas fa-sync-alt refresh-table" style="font-size:22px;color:#FFD700"></i>
                                    </span>
                                </div>
                                <div class="table-full-width table-responsive">
                                    <table class="table table-hover" id="table-attribue">
                                        <thead>
                                            <tr class="text-center">
                                                <th>{{ __('title.image') }}</th>`
                                        attributeTitle.forEach(function(attr){
                                            header +=`<th>${attr}</th>`
                                        });
                                        header +=`<th>Quantity</th>
                                                <th>Price</th>
                                                <th>SKU</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`
            return header;
        };

        // CREATE ROW TABLE VARRIANT
        const createVariantRow = (attributeItem, variantItem) => {
            let attributeName = Object.values(attributeItem).join(', ');
            let attributeId = Object.values(variantItem).join(', ');
            let classModified = attributeId.replace(/, /g, '-');

            let tbody = `<tr class="text-center variant-${classModified}">
                            <td class="align-middle">
                                <div class="upload">
                                    <div class="mb-3">
                                        <img src="${noImg}" alt="no-img" width="70" height="70" class="avtPreview">
                                    </div>
                                    <div class="round">
                                        <i class = "fa fa-camera" style = "color: #fff;"></i>
                                        <input type="file" class="image" name="image[]" accept=".jpg, .jpeg, .png">    
                                    </div>
                                </div>
                            </td>`
                        Object.values(attributeItem).forEach(value => {
                            tbody += `<td class="align-middle">${value}</td>`;
                        });
                    tbody +=`<td class="align-middle">
                                <input type="number" class="form-control" name="quantity[]" id="">
                            </td>
                            <td class="align-middle">
                                <input type="text" class="form-control format-price" name="price[]" id="" value="${$('input[name="compare_price"]').val() != 0 ? $('input[name="compare_price"]').val() : $('input[name="regular_price"]').val()}">
                            </td>
                            <td class="align-middle">
                                <input type="text" class="form-control" name="sku[]" id="" value="${$('.inputBarcode').val()}">
                            </td>
                            <td hidden>
                                <input type="text" name="name[]" value="${attributeName}">
                                <input type="text" name="id[]" value="${attributeId}">
                            </td>
                        </tr>`
            return tbody;
        };

        //Render list attribute value
        // const renderListAttribute = (attributes, attributeTitle, variants) => {
        //     let html = `<div class="card">
        //         <div class="card-body">
        //             <div class="row">
        //                 <div class="col-md-12">
        //                     <div class="d-flex justify-content-between">
        //                         <h5>List Product Attributes</h5>
        //                         <span>
        //                             <i role="button" title="Change Price like above" class="fas fa-sync-alt refresh-table" style="font-size:22px;color:#FFD700"></i>
        //                         </span>
        //                     </div>
        //                     <div class="table-full-width table-responsive">
        //                         <table class="table table-hover">
        //                             <thead>
        //                                 <tr class="text-center">
        //                                     <th>{{ __('title.image') }}</th>`
        //                             attributeTitle.forEach(function(attr){
        //                                 html +=`<th>${attr}</th>`
        //                             });
        //                             html +=`<th>Quantity</th>
        //                                     <th>Price</th>
        //                                     <th>SKU</th>
        //                                 </tr>
        //                             </thead>
        //                             <tbody>`
        //                             attributes.forEach(function(attribute, index) {
        //                                 html +=`<tr class="text-center">
        //                                             <td class="align-middle">
        //                                                 <div class="upload">
        //                                                     <div class="mb-3">
        //                                                         <img src="${noImg}" alt="no-img" width="70" height="70" class="avtPreview">
        //                                                     </div>
        //                                                     <div class="round">
        //                                                         <i class = "fa fa-camera" style = "color: #fff;"></i>
        //                                                         <input type="file" class="image" name="image[]" accept=".jpg, .jpeg, .png">    
        //                                                     </div>
        //                                                 </div>
        //                                             </td>`
        //                                         let attributeArray = [];
        //                                         let attributeIdArray = [];
        //                                         $.each(attribute, function(key, value) {
        //                                             html +=`<td class="align-middle">
        //                                                     ${value}
        //                                                     </td>`
        //                                             attributeArray.push(value);
        //                                         })
        //                                         $.each(variants[index], function(key, value) {
        //                                             attributeIdArray.push(value);
        //                                         })

        //                                         let attributeString = attributeArray.join(', ');
        //                                         let attributeId = attributeIdArray.join(', ');
        //                                     html +=`<td class="align-middle">
        //                                                 <input type="number" class="form-control" name="quantity[]" id="">
        //                                             </td>
        //                                             <td class="align-middle">
        //                                                 <input type="text" class="form-control format-price" name="price[]" id="" value="${$('input[name="compare_price"]').val() != 0 ? $('input[name="compare_price"]').val() : $('input[name="regular_price"]').val()}">
        //                                             </td>
        //                                             <td class="align-middle">
        //                                                 <input type="text" class="form-control" name="sku[]" id="" value="${$('.inputBarcode').val()}">
        //                                             </td>
        //                                             <td hidden>
        //                                                 <input type="text" name="name[]" value="${attributeString}">
        //                                                 <input type="text" name="id[]" value="${attributeId}">
        //                                             </td>
        //                                         </tr>`
        //                             });
                                       
        //                             html +=`</tbody>
        //                         </table>
        //                     </div>
        //                 </div>
        //             </div>
        //         </div>
        //     </div>`
        //     return html;
        // };

        //The event get sub category by category id
        $(document).on('change', '#category_id', function(e){
            let _this = $(this);
            let data = {
                category_id: _this.val()
            }

            if (_this.val() != '') {
                $('#sub_category_id').prop('disabled', false);
            } else {
                $('#sub_category_id').prop('disabled', true);
            }

            $.ajax({
                url: "{{ route('backend.getSubCategoriesByCategoryId') }}",
                type: 'GET',
                data: data,
                success: function(res) {
                    if(res['message'] == 'Success') {
                        $('#sub_category_id').html(res['data']);
                    }
                },
                error: function(err) {
                    console.log('Lỗi: ' + err);
                }
            });
        });

        // The event format price
        $('.format-price').on('keyup', function() {
            let value = parseInt($(this).val().replace(/\./g, ''), 10);

            if (isNaN(value)) {
                $(this).val('');
                return false;
            }

            let formattedValue = new Intl.NumberFormat('vi-VN').format(value);
            $(this).val(formattedValue);

        });
    </script>
@endsection