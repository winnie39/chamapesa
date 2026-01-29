@component('layouts.app')
<section class="pt-120 pb-120">
    <div class="container">



        <!-- profit calculator section start -->
        <section class="pt-120 pb-120">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="text-center section-header">
                            <h2 class="section-title"><span class="font-weight-normal">Profit</span> <b
                                    class="base--color">Calculator</b></h2>
                            <p>Check our calculator for precise investment calculations to avoid errors and ensure
                                accurate results. Trust in our calculations to guide your investment decisions reliably.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center" x-data="calculatorData" x-init="$watch('plan', () => {
                        selectedPlanDetails = investmentPlans.filter(item => item.id == plan)[0]
                    
                    });">
                    <div class="col-xl-8">
                        <div class="profit-calculator-wrapper">
                            <form class="profit-calculator">

                                @csrf

                                <div class="row mb-none-30">
                                    <div class="col-lg-6 mb-30">
                                        <label>Choose Plan</label>
                                        <select class="base--bg" x-model="plan">
                                            <option :value="null" selected> Please select </option>

                                            <template x-for="item,index in investmentPlans" :key="index">
                                                <option :value="item['id']" x-text="item['name']"> </option>
                                            </template>

                                        </select>
                                    </div>

                                    <div class="col-lg-6 mb-30">
                                        <label>Invest Amount</label>

                                        <template x-if="selectedPlanDetails">

                                            <input type="text" name="invest_amount" id="invest_amount"
                                                placeholder="0.00" :value="(selectedPlanDetails ? parseFloat(selectedPlanDetails['price']) :
                                                        0) +
                                                    'USD / ' + parseInt(selectedPlanDetails['price'] * rates[localCurrency] *
                                                        1.07) + localCurrency" readonly class="form-control base--bg">
                                        </template>


                                        <template x-if="!selectedPlanDetails">

                                            <input type="text" name="profit_amount" :value="'0 USD / 0 '+ localCurrency"
                                                id="profit_amount" placeholder="0.00" class="form-control base--bg"
                                                disabled>
                                        </template>
                                    </div>


                                    <div class="col-lg-12 mb-30">
                                        <label>Profit Amount</label>

                                        <template x-if="selectedPlanDetails">
                                            <input type="text" name="profit_amount" :value="parseFloat(selectedPlanDetails['price'] *
                                                        selectedPlanDetails['rate'] * selectedPlanDetails[
                                                            'period_in_hours']).toFixed(2) + 'USD / ' + parseInt(
                                                        selectedPlanDetails['price'] * rates[localCurrency] *
                                                        selectedPlanDetails[
                                                            'rate'] * 1.07 * selectedPlanDetails[
                                                            'period_in_hours']).toFixed(2) + localCurrency"
                                                id="profit_amount" placeholder="0.00" class="form-control base--bg"
                                                disabled>
                                        </template>

                                        <template x-if="!selectedPlanDetails">

                                            <input type="text" name="profit_amount" :value="'0 USD / 0 '+ localCurrency"
                                                id="profit_amount" placeholder="0.00" class="form-control base--bg"
                                                disabled>
                                        </template>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            const calculatorData = {
                investmentPlans: @json($data['plans']),
                plan: '',
                amount: 0,
                localCurrency: '{{ config("app.local-currency")}}',
                rates: @json($data['rates']),
                selectedPlanDetails: null
            }
        </script>
        <!-- profit calculator section end -->


        <div class="row justify-content-center">
            <div class="text-center col-lg-6">
                <div class="section-header pt-7">
                    <h2 class="section-title"><span class="font-weight-normal">Investment</span> <b
                            class="base--color">Plans</b></h2>
                    <p>Choose the best plan for your investment needs by understanding where your money is going.</p>
                </div>
            </div>
        </div>


        <div class="row justify-content-center mb-none-30">

            @foreach ($data['plans'] as $plan)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-30">
                    <div class="text-center package-card bg_img" data-background="/assets/images/bg/bg-4.png">

                        <form class="profit-calculator" action="/plans/subscribe" method="post">

                            @csrf


                            <h4 class="mb-2 package-card__title base--color"> {{ $plan['name'] }} </h4>

                            @if (in_array($plan['id'], $data['activePlans']))
                                <span class="badge base--bg text-dark"> ACTIVE </span>
                            @endif
                            <ul class="mt-4 package-card__features">
                                <li> Return type: Hourly</li>
                                <li> Hourly : {{ number_format($plan['price'] * $plan['rate'], 2) }}
                                    {{ config('app.currency') }}
                                    <span class="text-xs ">

                                        ({{ number_format($plan['price'] * $plan['rate'] * 1.07 * $data['rates'][config('app.local-currency')]) . config('app.local-currency') }})
                                    </span>
                                </li>
                                <li> Daily : {{ number_format($plan['price'] * $plan['rate'] * 24, 2) }}
                                    {{ config('app.currency') }}
                                    <span class="text-xs ">

                                        ({{ number_format($plan['price'] * 1.07 * $plan['rate'] * 24 * $data['rates'][config('app.local-currency')]) . config('app.local-currency') }})
                                    </span>
                                </li>
                                <li> Period: {{ number_format((int) ($plan['period_in_hours'] / 24)) }} Days</li>

                                <li>Total
                                    {{ number_format($plan['period_in_hours'] * $plan['price'] * $plan['rate'], 2) }}
                                    <span class="badge base--bg text-dark">{{ config('app.currency') }}</span>

                                    <span class="text-xs ">

                                        ({{ number_format($plan['period_in_hours'] * $plan['price'] * $plan['rate'] * 1.07 * $data['rates'][config('app.local-currency')]) . config('app.local-currency') . config('app.local-currency') }})
                                    </span>
                                </li>
                            </ul>

                            <input type="hidden" name="plan" value="{{ $plan['id'] }}">

                            <div class="mt-5 package-card__range base--color"> {{ (float) $plan['price'] }}
                                {{ config('app.currency') }} <span
                                    class="text-xs ">({{ number_format((float) $plan['price'] * 1.07 * $data['rates'][config('app.local-currency')]) . config('app.local-currency') }})</span>
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

        </div><!-- row end -->
    </div>
</section>
@endcomponent