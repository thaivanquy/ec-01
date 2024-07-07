@extends('adminlte::page')

@section('content')
<h3 class="pt-2 pl-2">{{ __('title.user_management') }}</h3>
@include('layouts.breadcumb', [
    'items' => [
        [
            'title' => __('title.user_list'),
            'url' => 'javascript:void();',
        ],
    ],
])
<div class="card-body p-0">
    <form action="{{ route('backend.users.index') }}" method="GET" id="formSubmit">
        <div class="row pb-3">
            <div class="col-sm-2">
                <select class="form-control changeSubmit" id="perpage" name="per_page">
                    @for ($i = 10; $i <= 100; $i += 10)
                        <option value="{{ $i }}" {{ request()->input('per_page') == $i ? 'selected' : '' }}>{{ $i }} {{ __('title.record') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control changeSubmit" id="role_id" name="role_id">
                    <option value="" selected>{{ 'Choose' . ' ' . __('title.user_group') }}</option>
                    @foreach (config('common.role') as $k => $v)
                        <option value="{{ $k }}" {{ request()->input('role_id') == $k ? 'selected' : '' }}>{{ Str::ucfirst($v) }}</option>
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
                <a href="{!! route('backend.users.export', [
                    'page' => \Request::input('page'),
                    'per_page' => \Request::input('per_page'),
                    'keyword' => \Request::input('keyword'),
                    'role_id' => \Request::input('role_id'),
                ]) !!}" class="btn bg-lightblue mr-3 {{ $users->count() ? '' : 'disabled' }}">
                    {{ __('title.export') }}
                </a>
                <a href="{{ route('backend.users.create') }}" class="btn btn-success">Add New</a>
            </div>
        </div>
    </form>
    <div class="table-full-width table-responsive">
        <table class="table table-striped table-hover">
            <thead>
              <tr class="text-center">
                <th>{{ __('title.id') }}</th>
                <th width="10%">{{ __('title.avatar') }}</th>
                <th>{{ __('title.information') }}</th>
                <th>{{ __('title.address') }}</th>
                <th>{{ __('title.role') }}</th>
                <th>{{ __('title.publish') }}</th>
                <th>{{ __('title.action') }}</th>
              </tr>
            </thead>
            <tbody>
                @if (count($users) > 0)
                    @foreach($users as $key => $user)
                        <tr class="text-center">
                            <td class="align-middle">
                                <div>{{ $user->id }}</div>
                            </td>
                            <td class="align-middle">
                                <div>
                                    @php
                                        $avatarUrl = !empty($user->avatar) ? asset('storage/images/' . $user->avatar) : Vite::asset('resources/images/no-avt.png');
                                    @endphp
                                    <img src="{{ $avatarUrl }}" alt="avatar" class="rounded-circle" width="100" height="100">
                                </div>
                            </td>
                            <td class="align-middle"> 
                                <div><strong>{{ __('title.name') }}</strong>: {{ $user->name }}</div>
                                <div><strong>{{ __('title.email') }}</strong>: {{ $user->email }}</div>
                                <div><strong>{{ __('title.phone') }}</strong>: {{ $user->phone }}</div>
                                @php
                                    $gender = !empty($user->gender) ? ucfirst(config('common.gender')[$user->gender]) : '';
                                @endphp
                                <div><strong>{{ __('title.gender') }}</strong>: {{ $gender }}</div>
                                <div><strong>{{ __('title.birthday') }}</strong>: {{ \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') }}</div>
                            </td>
                            <td class="align-middle">
                                <div><strong>{{ __('title.address') }}</strong>: {{ $user->address }}</div>
                                <div><strong>{{ __('title.ward') }}</strong>: {{ !empty($user->ward_id) ? \App\Services\WardService::getWardByUser($user->ward_id)->name : '' }}</div>
                                <div><strong>{{ __('title.district') }}</strong>: {{ !empty($user->district_id) ? \App\Services\DistrictService::getDistrictByUser($user->district_id)->name : '' }}</div>
                                <div><strong>{{ __('title.province') }}</strong>: {{ !empty($user->province_id) ? \App\Services\ProvinceService::getProvinceByUser($user->province_id)->name : '' }}</div>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-sm bg-lightblue">
                                    {{ ucfirst(config('common.role')[$user->role_id]) }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input changePublish" id="customSwitch{{ $user->id }}" data-user-id="{{ $user->id }}" name="publish" value="{{ $user->publish }}" {{ $user->publish == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="customSwitch{{ $user->id }}"></label>
                                </div>
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('backend.users.detail', $user->id) }}" class="btn btn-sm btn-info mr-2">Detail</a>
                                <a href="{{ route('backend.users.edit', $user->id) }}" class="btn btn-sm btn-warning mr-2">Edit</a>
                                <button class="btn btn-sm btn-danger" id="btnDelete" data-id="{{ $user->id }}">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center bg-white">
                            {{ __('message.no_data') }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
          <div class="d-flex justify-content-center mt-4">
            {{ $users->onEachSide(2)->links('layouts.pagination') }}
        </div>
    </div>
</div>
@endsection
@section('css')
    <style>
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

        //Delete User
        $(document).on('click', '#btnDelete', function(e) {
            let _this = $(this);
            let userID = _this.attr('data-id');

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
                        url: "{{ route('backend.users.delete', ['userInfo' => ':userID']) }}".replace(':userID', userID),
                        type: 'POST',
                        data: userID,
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
        
        $(document).on('change', '.changePublish', function(e) {
            e.preventDefault();

            let _this = $(this);
            let userID = _this.attr('data-user-id');
            let value = _this.val();
            let data = {
                'publish': value,
                'user_id': userID
            }
            console.log(data)
            $.ajax({
                url: "{{ route('backend.users.changePublish', ['userInfo' => ':userID']) }}".replace(':userID', userID),
                type: 'POST',
                data: data,
                success: function success(res) {

                },
                error: function error(err) {
                    console.log('Error' + err);
                }
            });
        });
    </script>
@endsection