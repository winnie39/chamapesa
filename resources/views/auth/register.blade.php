@component('layouts.guest')
    <div class="account-section bg_img" data-background="assets/images/bg/bg-5.jpg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-7">
                    <div class="account-card">
                        <div class="account-card__header bg_img overlay--one" data-background="assets/images/bg/bg-6.jpg">
                            <h2 class="section-title">Welcome to <span class="base--color"> {{ config('app.name') }} </span>
                            </h2>
                            <p>Welcome to {{ config('app.name') }} Investment, where hourly returns redefine investing. Join
                                us now for high
                                returns and financial empowerment. Register today and unlock your path to prosperity!</p>
                        </div>
                        <div class="account-card__body">
                            <form method="POST" action="/register">
                                @csrf
                                <h2 class="pb-5 text-center">Create an Account</h2>

                                <div class="form-group float-label">
                                    <input class="form-control checkUser" id="name" name="name" type="text">
                                    <label class="form-control-label "><i class="las la-user-tag"></i> Name</label>
                                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-start" />

                                </div>
                                {{-- <div class="form-group float-label">
                                    <input class="form-control checkUser" id="email" name="email" type="text">
                                    <label class="form-control-label "><i class="las la-user-tag"></i> Email</label>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-start" />
                                </div> --}}

                                <input value="{{ request()->input('ref') }}" class="form-control checkUser"
                                    id="referral_code" name="referral_code" type="hidden">

                                <div class="form-group float-label">
                                    <input class="form-control checkUser" id="phone_number" name="phone_number"
                                        type="text">
                                    <label class="form-control-label "><i class="las la-envelope-open"></i> Phone
                                        number (eg 07XXXXXXXX) </label>
                                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2 text-start" />
                                </div>
                                <div class="form-group float-label position-relative">
                                    <input class="form-control " id="password" name="password" type="password">
                                    <label class="form-control-label "><i class="las la-unlock-alt"></i> Password</label>
                                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-start" />
                                </div>
                                <div class="form-group float-label position-relative">
                                    <input class="form-control" id="password_confirmation" name="password_confirmation"
                                        type="password" autocomplete="new-password">
                                    <label class="form-control-label "><i class="las la-unlock-alt"></i> Confirm
                                        Password</label>
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-start" />
                                </div>
                                {{-- <div class="form-group float-label position-relative">
                                    <div class="custom-control custom-switch">
                                        <input class="custom-control-input" id="agree" name="agree" type="checkbox">
                                        <label class="custom-control-label" for="agree"> I agree with</label>
                                        <a class="text--base">Privacy and
                                            Policy</a>
                                        <a class="text--base" href="/privacy-policy">Payment Policy</a>
                                    </div>
                                </div> --}}
                                <button class="cmn-btn">SignUp Now</button>
                                <div class="my-4 row justify-content-center">
                                    <div class="text-center col">
                                        <p class="mb-1 text-white">Already have an account?</p>
                                        <a href="/login" class="pt-0 mb-3 text-white">
                                            <b>Sign In</b>
                                        </a>
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
