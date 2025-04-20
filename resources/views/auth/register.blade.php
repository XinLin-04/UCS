@extends('layouts.header')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
    
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"  autocomplete="name" autofocus>
    
                                @if ($errors->has('name'))
                                     @foreach ($errors->get('name') as $i => $error)
                                        @if ($i < 2)
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $error }}</strong>
                                        </span>
                                        @endif
                                    @endforeach
                                @endif
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
    
                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email">
                                    <ul class="email-requirements mt-2" id="email-requirements" style="display: none;">
                                        <li id="valid-email" class="text-danger">✘ Must be a valid email format (e.g., example@1utar.my)</li>
                                    </ul>
                                    <span id="email-feedback" class="invalid-feedback" role="alert" style="display: none;">
                                        <strong id="email-feedback-text"></strong>
                                    </span>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        autocomplete="new-password">
                                    <!-- Password requirements list -->
                                    <ul class="password-requirements mt-2" style="display: none;">
                                        <li id="length" class="text-danger">✘ Minimum 8 characters</li>
                                        <li id="uppercase" class="text-danger">✘ At least 1 uppercase letter (A–Z)</li>
                                        <li id="lowercase" class="text-danger">✘ At least 1 lowercase letter (a–z)</li>
                                        <li id="number" class="text-danger">✘ At least 1 number (0–9)</li>
                                        <li id="special" class="text-danger">✘ At least 1 special character (! @ # $ % ^ & *)</li>
                                    </ul>
                                    <span id="password-feedback" class="invalid-feedback" role="alert"
                                        style="display: {{ $errors->has('password') ? 'block' : 'none' }};">
                                        <strong id="password-feedback-text">{{ $errors->first('password') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                            
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                    <span id="confirm-password-feedback" class="invalid-feedback" role="alert" style="display: none;">
                                        <strong id="confirm-password-feedback-text"></strong>
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" id="Register" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
