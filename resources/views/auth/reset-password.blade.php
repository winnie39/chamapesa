@component('layouts.guest')
    <div class="account-section bg_img" data-background="assets/images/bg/bg-5.jpg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-7">
                    <div class="account-card">
                        <div class="account-card__header bg_img overlay--one" data-background="assets/images/bg/bg-6.jpg">
                            {{-- <p> {{ __('Forgot your password? No problem. Just let us know your phone number and we will sent you a password reset code that will allow you to choose a new one.') }}
                            </p> --}}
                        </div>
                        <div class="account-card__body">

                            <form method="POST" action="{{ route('password.store') }}"
                                class="login-form mt-50 verify-gcaptcha">
                                @csrf
                                <x-auth-session-status class="mb-4" :status="session('status')" />

                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <!-- Email Address -->
                                <div class="form-group float-label">
                                    <input class="form-control checkUser" id="phone_number" name="phone_number"
                                        type="text">
                                    <label class="form-control-label "><i class="las la-envelope-open"></i> Phone
                                        number (eg 07XXXXXXXX) </label>
                                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2 text-start" />
                                </div>

                                <div>
                                    <div class="form-group float-label">
                                        <input type="text" class="form-control " value="" name="reset_code">
                                        <label class="form-control-label "><i class="las la-user"></i>
                                            Reset code</label>
                                        <x-input-error :messages="$errors->get('reset_code')" class="mt-2" />

                                    </div>
                                </div>

                                <div>
                                    <div class="form-group float-label">
                                        <input type="password" class="form-control " value="" name="password">
                                        <label class="form-control-label "><i class="las la-user"></i>
                                            New password</label>
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                                    </div>
                                </div>


                                <div>
                                    <div class="form-group float-label">
                                        <input type="password" class="form-control " value=""
                                            name="password_confirmation">
                                        <label class="form-control-label "><i class="las la-user"></i>
                                            Confirm password</label>
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col">
                                        <button type="submit" id="recaptcha" class="cmn-btn">
                                            {{ __('Reset password') }}</button>
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
