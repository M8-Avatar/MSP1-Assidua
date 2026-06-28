@extends('layouts.app')

@section('title', 'Profil')
@section('page-title', 'Mon profil')
@section('page-subtitle', 'Gérez vos informations personnelles')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="card border-0 mb-4" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
            <div class="card-body p-4">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card border-0 mb-4" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
            <div class="card-body p-4">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="card border-0 mb-4" style="border-radius:.8125rem;border:1px solid rgba(229,57,53,.2)">
            <div class="card-body p-4">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
@endsection