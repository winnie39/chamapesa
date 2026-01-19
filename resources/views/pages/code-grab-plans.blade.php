@component('layouts.app')
    <section class="pt-120 pb-120">
        <div class="container">



            <div class="row justify-content-center mb-none-30">
                @foreach ($data['plans'] as $plan)
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-30">
                        <div class="text-center package-card bg_img" data-background="/assets/images/bg/bg-4.png">

                            <form class="profit-calculator" action="/codes/subscribe" method="post">
                                @csrf
                                <h4 class="mb-2 package-card__title base--color"> {{ $plan['name'] }} </h4>

                                @if (in_array($plan['id'], $data['activePlans']))
                                    <span class="badge base--bg text-dark"> ACTIVE </span>
                                @endif
                                <ul class="mt-4 package-card__features">
                                    <li> Win amount: {{ number_format($plan['winning_amount_per_code']) }}
                                        {{ config('app.currency') }} <span
                                            class="text-xs ">({{ number_format((float) $plan['winning_amount_per_code'] * 1.07 * $data['rates']['KES']) }}KES)</span>
                                    </li>
                                    <li> Period : {{ number_format($plan['period_in_hours'] / 24) }} Days
                                    </li>
                                </ul>

                                <input type="hidden" name="plan" value="{{ $plan['id'] }}">

                                <div class="mt-5 package-card__range base--color"> {{ (float) $plan['price'] }}
                                    {{ config('app.currency') }} <span
                                        class="text-xs ">({{ number_format((float) $plan['price'] * 1.07 * $data['rates']['KES']) }}KES)</span>
                                </div>


                                <button type="{{ in_array($plan['id'], $data['activePlans']) ? 'button' : 'submit' }}"
                                    class=" cmn-btn">

                                    @if (in_array($plan['id'], $data['activePlans']))
                                        <span class="badge base--bg text-dark"> ACTIVE </span>
                                    @else
                                        Invest Now
                                    @endif

                                </button>
                            </form>
                        </div><!-- package-card end -->
                    </div>
                @endforeach

            </div>
        </div>
    </section>
@endcomponent
