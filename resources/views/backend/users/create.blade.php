@extends('adminlte::page')

@section('content')
<h3 class="pt-2 pl-2">{{ __('title.user_management') }}</h3>
@include('layouts.breadcumb', [
    'items' => [
        [
            'title' => __('title.user_list'),
            'url' => route('backend.users.index'),
        ],
        [
            'title' => __('title.user_create'),
            'url' => 'javascript:void();',
        ]
    ],
])
<form action="{{ route('backend.users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <div class="mb-2">
                            <label>{{ __('title.avatar') }}</label>
                        </div>
                        <div class="upload">
                            <div class="mb-3">
                                <img src="{{ Vite::asset('resources/images/no-avt.png') }}" alt="no-avt" width="100" height="100" id="avtPreview">
                            </div>
                            <div class="round">
                                <i class = "fa fa-camera" style = "color: #fff;"></i>
                                <input type="file" class="" name="avatar" id="avatar" value="" accept=".jpg, .jpeg, .png">
                                @error('avatar')
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
                                <label>{{ __('title.email') }} <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.name') }} <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
                                @error('name')
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
                                <label>{{ __('title.user_group') }} <span class="text-danger">(*)</span></label>
                                <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
                                    <option value="">[Select Group User]</option>
                                    @foreach (config('common.role') as $k => $v)
                                        <option value="{{ $k }}" {{ old('role_id') == $k ? 'selected' : '' }}>{{ ucfirst($v) }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.gender') }}</label>
                                <select name="gender" id="gender" class="form-control" value="{{ old('gender') }}">
                                    <option value="">[Select Gender]</option>
                                    @foreach (config('common.gender') as $k => $v)
                                        <option value="{{ $k }}" {{ old('gender') == $k ? 'selected' : '' }}>{{ ucfirst($v) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.password') }} <span class="text-danger">(*)</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.password_confirmation') }} <span class="text-danger">(*)</span></label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="">
                                @error('password_confirmation')
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
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('title.province') }}</label>
                                <select name="province_id" id="province_id" class="form-control setupSelect2 province location" style="height: 38px;" data-target="district">
                                    <option value="0">[Select Province]</option>
                                    @if (isset($provinces))
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->code }}" @if (old('province_id') == $province->code) selected @endif>{{ $province->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('title.district') }}</label>
                                <select name="district_id" id="district_id" class="form-control setupSelect2 district location" data-target="ward">
                                    <option value="0">[Select District]</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.ward') }}</label>
                                <select name="ward_id" id="ward_id" class="form-control setupSelect2 ward">
                                    <option value="0">[Select Ward]</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.address') }}</label>
                                <input type="text" class="form-control" name="address" value="{{ old('address') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('title.phone') }}</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>{{ __('title.birthday') }}</label>
                            <div class="input-group date" id="birthday">
                                <input type="text" class="form-control" name="birthday" value="{{ old('birthday') }}" placeholder="dd/mm/yyyy">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('title.description') }}</label>
                                <textarea class="form-control" rows="4" name="description">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <a href="{{ route('backend.users.index') }}" class="btn btn-sm bg-navy mr-2">Back</a>
        <button class="btn btn-sm btn-success mr-2" type="submit">{{ __('title.save') }}</button>
        <button class="btn btn-sm bg-teal" type="submit" name="action" value="save_and_new">Save and New</button>
    </div>
</form>
@endsection
@section('css')
    <style>
        .select2-container .select2-selection--single {
            height: 38px;
        }

        .upload{
            width: 125px;
            position: relative;
        }

        .upload img{
            border-radius: 50%;
        }

        .upload .round{
            position: absolute;
            bottom: 0;
            right: 25px;
            background: #00B4FF;
            width: 32px;
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
    </style>
@endsection
@section('js')
    <script type="text/javascript">
        $('.setupSelect2').select2();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });

        // Get Location
        $(document).on('change', '.location', function(){
            let _this = $(this);
            let option = {
                'data': {
                    'location_id': _this.val(),
                },
                'target': _this.attr('data-target'),
            }
            
            sendDataToGetLocation(option);
            
        });

        const sendDataToGetLocation = (option) => {
            $.ajax({
                url: "{{ route('backend.location') }}",
                type: 'GET',
                data: option,
                success: function(res) {
                    $(`.${option.target}`).html(res.html)

                    if (districtID != '' && option.target == 'district') {
                        $('.district').val(districtID).trigger('change');
                    }

                    if (wardID != '' && option.target == 'ward') {
                        $('.ward').val(wardID).trigger('change');
                    }
                },
                error: function(err) {
                    console.log('Lỗi: ' + err);
                }
            });
        }

        //Preview Before Upload
        $(document).on('change', '#avatar', function(){ 
            $('#avtPreview').attr('src', URL.createObjectURL(this.files[0]));
        });

        //DatetimePicker
        $('#birthday').datepicker({
            format: 'yyyy-mm-dd',
            todayBtn: true,
            clearBtn: true,
            orientation: "bottom auto",
            autoclose: true,
            todayHighlight: true,
            endDate: new Date()
        });

        //Progess code old location
        let provinceID = '{{ old('province_id') }}';
        let districtID = '{{ old('district_id') }}';
        let wardID = '{{ old('ward_id') }}';

        if (provinceID !== '') {
            $('.province').val(provinceID).trigger('change');
        }

    </script>
@endsection