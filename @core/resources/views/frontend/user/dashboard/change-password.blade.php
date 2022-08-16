@extends('frontend.user.dashboard.user-master')

@section('page-title')
    <li class="list-item"><a href="#">{{__('Change Password')}}</a></li>
@endsection


@section('site-title')
    {{__('Change Password')}}
@endsection

@section('section')

        <div class="parent my-5">
            <h2 class="title">{{__('Change Password')}}</h2>
            <form action="{{route('user.password.change')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="old_password">{{__('Old Password')}}</label>
                    <input type="password" class="form-control" id="old_password" name="old_password"
                           placeholder="{{__('Old Password')}}">
                </div>
                <div class="form-group">
                    <label for="password">{{__('New Password')}}</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="{{__('New Password')}}">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">{{__('Confirm Password')}}</label>
                    <input type="password" class="form-control" id="password_confirmation"
                           name="password_confirmation" placeholder="{{__('Confirm Password')}}">
                </div>
                <div class="btn-wrapper">
                    <button id="save" type="submit" class="btn-default">{{__('Save changes')}}</button>
                </div>
            </form>
        </div>


@endsection

@push('scripts')
    <script>
        <x-btn.save/>
    </script>

@endpush
