@component('layouts.app')
    <div class="pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 pt-12">



                    <div class="card mb-0 mt-3">
                        <div class="card-header border-bottom">
                            <h6 class="mb-0">Referral Commission</h6>
                        </div>
                        <div class="card-body">
                            <div class="flex justify-center gap-x-10 ">

                                @foreach ($referralLevels as $item)
                                    <div class=" border  rounded-md font-semibold px-3 py-1.5 ">

                                        <div class=" text-center  font-bold text-sm  uppercase">Level {{ $item['level'] }}
                                        </div>
                                        <div class=" font-semibold rounded-full  ">
                                            {{ number_format($item['rate'] * 100) }} %</div>

                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>


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

                    <div class="row mt-50">
                        <div class="col-lg-12">
                            <div class="table-responsive--md p-0">
                                <table class="table style--two white-space-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Phone number</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($referrals as $item)
                                            <tr>
                                                <td data-label="Name"> {{ $item['user']['name'] }} </td>
                                                <td data-label="Phone number">
                                                    <span class="base--color"> {{ $item['user']['phone_number'] }} </span>
                                                </td>
                                                <td data-label="Date">
                                                    <span class="text-success"> {{ $item['created_at'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if (count($referrals) < 1)
                        <div class="card mb-0 mt-3">
                            {{-- <div class="card-header border-bottom">
                            <h6 class="mb-0">Referral Commission</h6>
                        </div> --}}

                            <div class="card-body">
                                <h6 class="mb-0 text-center">No team members found</h6>


                            </div>
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>
@endcomponent
