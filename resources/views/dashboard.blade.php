@component('layouts.app')
<div class="pt-120 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="row mb-none-30">
                    <div class="col-xl-4 col-sm-6 mb-30">
                        <div class="d-widget d-flex flex-wrap">
                            <div class="col-8">
                                <span class="caption">Deposit Wallet Balance</span>
                                <h4 class="currency-amount">{{ config('app.currency') }}
                                    {{ (float) auth()->user()->wallet->deposit }}
                                </h4>
                            </div>
                            <div class="col-4">
                                <div class="icon ml-auto">
                                    <i class="las la-dollar-sign"></i>
                                </div>
                            </div>
                        </div><!-- d-widget-two end -->
                    </div>
                    <div class="col-xl-4 col-sm-6 mb-30">
                        <div class="d-widget d-flex flex-wrap">
                            <div class="col-8">
                                <span class="caption">Interest Wallet Balance</span>
                                <h4 class="currency-amount">{{ config('app.currency') }}
                                    {{ (float) auth()->user()->wallet->profits }}
                                </h4>
                            </div>
                            <div class="col-4">
                                <div class="icon ml-auto">
                                    <i class="las la-wallet"></i>
                                </div>
                            </div>
                        </div><!-- d-widget-two end -->
                    </div>
                    <div class="col-xl-4 col-sm-6 mb-30">
                        <div class="d-widget d-flex flex-wrap">
                            <div class="col-8">
                                <span class="caption">Total Invest</span>
                                <h4 class="currency-amount">{{ config('app.currency') }}
                                    {{ (float) $data['totalInvestments'] }}
                                </h4>
                            </div>
                            <div class="col-4">
                                <div class="icon ml-auto">
                                    <i class="las la-cubes "></i>
                                </div>
                            </div>
                        </div><!-- d-widget-two end -->
                    </div>
                    <div class="col-xl-4 col-sm-6 mb-30">
                        <div class="d-widget d-flex flex-wrap">
                            <div class="col-8">
                                <span class="caption">Total Deposit</span>
                                <h4 class="currency-amount">{{ config('app.currency') }}
                                    {{ (float) $data['totalDeposit'] }}
                                </h4>
                            </div>
                            <div class="col-4">
                                <div class="icon ml-auto">
                                    <i class="las la-credit-card"></i>
                                </div>
                            </div>
                        </div><!-- d-widget-two end -->
                    </div>
                    <div class="col-xl-4 col-sm-6 mb-30">
                        <div class="d-widget d-flex flex-wrap">
                            <div class="col-8">
                                <span class="caption">Total Withdraw</span>
                                <h4 class="currency-amount"> {{ config('app.currency') }}
                                    {{ (float) $data['totalWithdrawal'] }}
                                </h4>
                            </div>
                            <div class="col-4">
                                <div class="icon ml-auto">
                                    <i class="las la-cloud-download-alt"></i>
                                </div>
                            </div>
                        </div><!-- d-widget-two end -->
                    </div>
                    <div class="col-xl-4 col-sm-6 mb-30">
                        <div class="d-widget d-flex flex-wrap">
                            <div class="col-8">
                                <span class="caption">Referral Earnings</span>
                                <h4 class="currency-amount">{{ config('app.currency') }}
                                    {{ $data['totalReferralEarnings'] }}
                                </h4>
                            </div>
                            <div class="col-4">
                                <div class="icon ml-auto">
                                    <i class="las la-user-friends"></i>
                                </div>
                            </div>
                        </div><!-- d-widget-two end -->
                    </div>
                </div><!-- row end -->



                <div class="row mt-50 w-full">
                    <div class="col-lg-12 w-full rounded-md  ">
                        <div class="table-responsive--md p-0 w-full">

                            <div class=" w-full ">
                                <p>Your Referral Link :</p>
                                <div class="flex w-full gap-x-2 ">
                                    <input class=" form-control w-full" type="text"
                                        value="{{ auth()->user()->referral->link }}" id="myInput" readonly="readonly">

                                    <button onclick="myFunction()" class=" cmn-btn ">Copy </button>
                                </div>
                            </div>
                            <script>
                                function myFunction() {
                                    var copyText = document.getElementById("myInput");
                                    copyText.select();
                                    document.execCommand("copy");
                                }
                            </script>
                        </div>
                    </div>
                </div>

                {{-- <div class="row mt-50">
                    <div class="col-lg-12">
                        <div class="table-responsive--md p-0">
                            <div class=" ">

                                Lustre rewards
                            </div>
                            @if (count($data['tasks']) > 0)
                            <div class="flex flex-col gap-y-4">

                                @foreach ($data['tasks'] as $task)
                                <form action="/ranking/claim" method="POST">
                                    @csrf
                                    <div class="  rounded-md  bg-black flex px-2 py-3 flex-col gap-y-2 text-white ">
                                        <div class="
                                            inline-flex w-full justify-between ">
                                            <div> {{ $task['task_text'] }} </div>
                                            <div> {{ $data['activeReferrals'] }}/{{ (int) $task['task'] }}
                                            </div>
                                        </div>


                                        <input type="hidden" value="{{ $task['id'] }}" name="task">
                                        <div class=" w-full  flex gap-x-3">
                                            <progress class=" w-full" value="{{ $data['activeReferrals'] }}"
                                                max="{{ $task['task'] }}"></progress>
                                            <div class=" text-sm">
                                                {{ ($data['activeReferrals'] * 100) / (int) $task['task'] }}%
                                            </div>
                                        </div>

                                        <div class=" w-full ">
                                            <button type="submit"
                                                class="btn   disabled:border-primary rounded-sm btn-primary hover:bg-yellow-500 bg-yellow-500 border-0 btn-sm text-white">
                                                {{ $task['completed'] ? 'COMPLETED' : 'REDEEM' }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                @endforeach
                            </div>
                            @else
                            <div class=" rounded-md bg-black text-white p-2">
                                <div class="flex flex-col gap-y-3">
                                    <div class="text-red-600 text-center"> No tasks found </div>

                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div> --}}









                <div class="row mt-50">
                    <div class="col-lg-12">
                        <div class="table-responsive--md p-0">
                            <table class="table style--two white-space-nowrap">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Details</th>
                                        {{-- <th>Post Balance</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['transactions'] as $item)
                                        <tr>
                                            <td data-label="Date"> {{ $item['created_at'] }} </td>
                                            <td data-label="Transaction ID">
                                                <span class="base--color"> {{ $item['code'] }} </span>
                                            </td>
                                            <td data-label="Amount">
                                                <span class="text-success"> {{ (float) $item['amount'] }}
                                                    {{ $item['currency'] }}</span>
                                            </td>
                                            <td data-label="Wallet">
                                                <span class="badge base--bg"> {{ $item['status_text'] }} </span>
                                            </td>
                                            <td data-label="Details"> {{ $item['description'] }} </td>
                                            {{-- <td data-label="Post Balance">{{ config('app.currency') }}22991.9</td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- row end -->
            </div>
        </div>
    </div>
</div>




{{-- <div class="row justify-content-center">
    <div class="col-lg-6 text-center">
        <div class="section-header pt-7">
            <h2 class="section-title"><span class="font-weight-normal">Ranking</span> <b class="base--color"></b>
            </h2>
        </div>
    </div>
</div> --}}



<!-- dashboard section end -->
@endcomponent