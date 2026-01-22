@component('layouts.guest')
    <!-- account section start -->
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
                            <h3 class="text-center">Login</h3>
                            <form class="mt-4" method="POST" action="/login">
                                @csrf
                                <div class="form-group">
                                    <label>Phone number</label>
                                    <input type="text" class="form-control" placeholder="Enter phone number"
                                        name="phone_number">
                                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />

                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" placeholder="Enter password"
                                        name="password">
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />

                                </div>
                                <div class="form-row">
                                    <div class="col-sm-6">
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                            <label class="form-check-label" for="exampleCheck1">Remmber me</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-sm-right">
                                        <p class="f-size-14">Haven't an account? <a href="/register"
                                                class="base--color">Sign Up</a></p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="cmn-btn">Login Now</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- account section end -->
@endcomponent
