@extends('layouts.app')
@section('content')

    
        <div class="card">
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- <div class="card">
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div> --}}
    

@endsection