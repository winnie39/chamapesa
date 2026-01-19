@component('layouts.app')
    <section class="  ">
        <div class="container">
            {{-- <div class="row justify-content-center pt-20">
                <div class="col-lg-6 text-center">
                    <div class="section-header pt-7">
                        <h2 class="section-title"><span class="font-weight-normal">Transactions</span> <b
                                class="base--color"></b></h2>
                    </div>
                </div>
            </div> --}}

            @php
                $plan = $data['plan'];
            @endphp

            <div class="row pt-28 ">
                <div class="col-lg-12">
                    <div class="table-responsive--md p-0">
                        <form action="/plans/subscribe" method="post">
                            @csrf

                            <input type="hidden" name="plan" value="{{ $data['plan']['id'] }}">

                            <div class=" rounded-md bg-black text-white p-2">
                                <div class="text-white pb-2 font-semibold text-md">
                                    Confirm buy plan

                                </div>
                                <div class="flex flex-col gap-y-3  text-center">
                                    <div>
                                        <div
                                            class=" flex  w-full justify-between    px-2 py-2  
                                             ">
                                            <div> Name </div>
                                            <div>
                                                {{ $plan['name'] }}
                                            </div>
                                        </div>

                                        <div
                                            class=" flex  w-full justify-between    px-2 py-2  
                                         ">
                                            <div> Capital </div>
                                            <div>
                                                {{ $plan['price'] }}
                                            </div>
                                        </div>


                                        <div
                                            class=" flex  w-full justify-between    px-2 py-2  
                                             ">
                                            <div> Return type </div>
                                            <div>
                                                Hourly
                                            </div>
                                        </div>

                                        <div
                                            class=" flex  w-full justify-between    px-2 py-2  
                                         ">
                                            <div> Hourly </div>
                                            <div>
                                                {{ number_format($plan['price'] * $plan['rate'], 2) }}
                                                {{ config('app.currency') }}
                                                <span class=" text-xs">

                                                    ({{ number_format($plan['price'] * $plan['rate'] * 1.07 * $data['rates']['KES']) }}KES)
                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class=" flex  w-full justify-between    px-2 py-2  
                                     ">
                                            <div> Daily </div>
                                            <div>
                                                {{ number_format($plan['price'] * $plan['rate'] * 24, 2) }}
                                                {{ config('app.currency') }}
                                                <span class=" text-xs">

                                                    ({{ number_format($plan['price'] * 1.07 * $plan['rate'] * 24 * $data['rates']['KES']) }}KES)
                                                </span>
                                            </div>
                                        </div>
                                        <div
                                            class=" flex  w-full justify-between    px-2 py-2  
                                 ">
                                            <div> Period </div>
                                            <div>
                                                {{ number_format((int) ($plan['period_in_hours'] / 24)) }} Days
                                            </div>
                                        </div>

                                        <div
                                            class=" flex  w-full justify-between    px-2 py-2  
                             ">
                                            <div> Total </div>
                                            <div>
                                                {{ number_format($plan['period_in_hours'] * $plan['price'] * $plan['rate'], 2) }}
                                                <span class="badge base--bg text-dark">{{ config('app.currency') }}</span>

                                                <span class=" text-xs">

                                                    ({{ number_format($plan['period_in_hours'] * $plan['price'] * $plan['rate'] * 1.07 * $data['rates']['KES']) }}KES)
                                                </span>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="  flex w-full justify-start">
                                        <button type="submit" class="  cmn-btn ">
                                            PROCEED </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endcomponent
