@component('layouts.app')
<div id="depositModalBoday" class="modal-body" x-data="depositPage" x-init="$watch('paymentMethod', () => {
        if (paymentMethod) {
            paymentMethodDetails = methods.filter((item) => item.id.toString() == parseInt(paymentMethod.toString()))[0]
    
            console.log(paymentMethodDetails);
        } else {
            paymentMethodDetails = {}
        }
    
    });
    
    
    $watch('country', () => {
        paymentMethodDetails = {}
    
    
        selectedCountryPaymentMethods = allPaymentMethods.filter(method => method.countries.includes(country.toString()));
    
    
        if (selectedCountryPaymentMethods.length > 0) {
            paymentMethod = selectedCountryPaymentMethods[0]['id'].toString()
        } else {
            paymentMethod = null
        }
    
    });">
    <div class="main-container pt-28">
        <div class="container">
            <div>
                <div class="mb-3 input-group">
                    <label for="">Select country</label>
                    <select x-model="country">
                        @foreach ($data['countries'] as $country)
                            <option value="{{ $country['id'] }}">
                                {{ $country['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>


            <template x-if="!country">
                <div class="flex flex-col pt-2 ">
                    <div>
                        Deposit Rules:
                    </div>
                    <div class="pl-2 ">
                        <div>

                            1. Select your country accurately during the deposit process.
                        </div>

                        <div>

                            2. Choose from available deposit methods specific to your country.
                        </div>
                        <div>

                            3. Enter your deposit amount in USD; it will be automatically converted to your selected
                            country's
                            currency.
                        </div>
                        <div>

                            4. All payment gateways are fully automated for seamless transactions.
                        </div>
                        <div>

                            5. Follow the deposit instructions carefully; they are simple, transparent, and clear.
                        </div>
                        <div>

                            6. If you encounter any challenges, contact our support team immediately via live chat for
                            assistance.
                        </div>
                    </div>
                </div>
            </template>



            <form id="depositForm" action="/deposit" method="post" enctype="multipart/form-data">
                @csrf
                <template x-if="country">
                    <div class="flex flex-col justify-start w-full pb-4 text-start place-items-start">
                        <div class="flex flex-col justify-items-start place-items-start">
                            <div>Select payment method</div>


                            <template x-for="item,index in selectedCountryPaymentMethods" :key="index">

                                <div class="inline-flex justify-center place-items-center gap-x-2">
                                    <input type="radio" x-model="paymentMethod" name="method" :value="item['id']"
                                        :checked="paymentMethod == item['id']" class="" />


                                    <span class="" x-text="item['name']"> </span>
                                </div>
                            </template>

                            <x-input-error :messages="$errors->get('method')" class="mt-2 text-start" />

                        </div>
                    </div>

                </template>
                <template v-if="paymentMethod == null">
                    <div>
                        No valid payment method for this country
                    </div>
                </template>

                <template x-if="paymentMethod">
                    <div>
                        <div x-text="'Enter Amount to Deposit in {{ config('app.currency') }} '"></div>
                        <div class="mb-3 input-group">
                            <input type="number" step="any" x-model="amount" name="amount" class="form-control "
                                value="" autocomplete="off" placeholder="00.00" id="amount">
                        </div>
                        <x-input-error :messages="$errors->get('amount')" class="mt-2 text-start" />


                        <input type="hidden" name="method-parameter" :value="paymentMethodDetails['parameter']" />

                        <template x-if="paymentMethodDetails['parameter']  != 'crypto'">
                            <div class="flex w-full gap-x-2">
                                <div class="w-full ">
                                    <div> Phone number </div>
                                    <div class="input-group ">
                                        <input type="text" class="form-control " autocomplete="off" name="phone_numbar"
                                            placeholder="Enter phone number" id="phone_number">

                                    </div>
                                    <x-input-error :messages="$errors->get('phone_number')" class="text-start " />
                                </div>

                                {{-- <div class="w-full ">
                                    <div> Lastname</div>
                                    <div class="input-group ">
                                        <input type="text" step="any" class="form-control " autocomplete="off"
                                            name="lastname" placeholder="Enter lastname" id="transaction_id">

                                    </div>
                                    <x-input-error :messages="$errors->get('lastname')" class="text-start " />
                                </div> --}}
                            </div>


                        </template>
                        <p class="mb-4 text-center text-secondary">
                        </p>

                        <div class="my-2 card">
                            <div class="card-header">
                                <div class="row justify-content-center align-items-center">
                                    <div class="col-auto">
                                        <span class="material-icons">
                                    </div>

                                    <div class="pl-0 col">
                                        <h6 class="mb-0 subtitle"
                                            x-text="'Amount in '+ paymentMethodDetails['currency']">
                                        </h6>
                                    </div>
                                    <div class="col-7"
                                        x-text=" parseInt(rates[paymentMethodDetails['currency']] * amount *  (paymentMethodDetails['parameter']  != 'crypto'? 1.07:1))">

                                    </div>
                                </div>

                                <div class="row justify-content-center align-items-center">
                                    <div class="col-auto">
                                        <span class="material-icons">
                                    </div>

                                    <div class="pl-0 col">
                                        <h6 class="mb-0 subtitle" x-text="'Fee in '+ paymentMethodDetails['currency']">
                                        </h6>
                                    </div>
                                    <div class="col-7" x-text=" '0 '">

                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="pt-2 pb-3 my-2 card">
                            <div class="px-2 ">
                                <div class="" x-html="paymentMethodDetails.description ?? ''">
                                </div>
                            </div>

                        </div>



                        <div class="my-2 card">


                            <div class="m-4 my-1">
                                <button class="cmn-btn" type="submit">Deposit</button>
                            </div>
                            <script src="/js/qrcode.min.js"></script>
                        </div>

                    </div>
                </template>
            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        let errors = @json($errors->all())

        if (!errors.length) {
            localStorage.clear()
        }

        Alpine.data('depositPage', function () {
            return {
                paymentMethodDetails: this.$persist({}),
                amount: this.$persist(50),
                phone: this.$persist(''),
                currency: @json($data['methods'])[0]['currency'],
                paymentMethod: this.$persist(''),
                methods: @json($data['methods']),
                firstname: this.$persist(''),
                lastname: this.$persist(''),
                address: this.$persist(''),
                country: this.$persist(''),
                selectedCountryPaymentMethods: this.$persist([]),
                countries: @json($data['countries']),
                countryDetails: this.$persist({}),
                rates: @json($data['rates']),
                allPaymentMethods: @json($data['methods']),
            }


        })
    })
</script>
@endcomponent