{{-- <x-guest-layout>
    <div class="main-container">

        <div class="mb-4 text-sm text-white">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <div class="form-group float-label">
                    <input type="text" class="form-control " value="" name="email">
                    <label class="form-control-label "><i class="las la-user"></i>
                        Email</label>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />

                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col">
                    <button type="submit" id="recaptcha" class="rounded shadow btn loginBtn btn-block">
                        {{ __('Email Password Reset Link') }}</button>
                </div>
            </div>


        </form>
    </div>
</x-guest-layout> --}}


@component('layouts.guest')
    <div class="account-section bg_img" data-background="assets/images/bg/bg-5.jpg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-7">
                    <div class="account-card">
                        <div class="account-card__header bg_img overlay--one" data-background="assets/images/bg/bg-6.jpg">
                            <p> {{ __('Forgot your password? No problem. Just let us know your phone number and we will sent you a password reset code that will allow you to choose a new one.') }}
                            </p>
                        </div>
                        <div class="account-card__body">

                            <x-auth-session-status class="mb-4" :status="session('status')" />


                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <!-- Email Address -->
                                <div>
                                    <div class="form-group float-label">
                                        <input type="text" class="form-control " value="" name="phone_number">
                                        <label class="form-control-label "><i class="las la-user"></i>
                                            Phone number</label>
                                        <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />

                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col">
                                        <button type="submit" id="recaptcha" class="cmn-btn">
                                            {{ __('Send Password Reset code') }}</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcomponent

<div class="mb-4 text-sm text-white">
    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
</div>
<x-auth-session-status class="mb-4" :status="session('status')" />
