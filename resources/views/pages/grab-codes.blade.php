@component('layouts.app')
    <section class=" pt-20   pb-20 px-3 ">
        <div class=" relative  mb-20 " x-data="pageData" x-init="window.Echo.channel('promo-code')
            .listen('.promo-code-message', (event) => {
        
                codes = event.filter((item) => userCodePlans.includes(item['code_plan_id']))
            });">

            <section class="dashboard-section">
                <div class="dashboard-body-part">
                    <div class="table-responsive text-white relative ">
                        <div class=" pb-1 font-semibold ">Grab codes</div>
                        <template x-if="codes.length>0">
                            <div class=" flex flex-col gap-y-2">
                                <template x-for="item,index in codes" :key="index">
                                    <div class="  bg-slate-800 rounded-sm p-2 flex justify-between">
                                        <div :id="item.code" x-text="item.code">
                                        </div>
                                        <button class="btn btn-xs  btn-warning rounded-sm"
                                            x-on:click="copyCode(item.code)">COPY</button>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    <div class="    ">
                        <template x-if="codes.length<1">
                            <div>
                                <div>

                                    Sorry, there are not any codes at this time.
                                </div>
                                {{-- 
                                <ol>
                                    <li>
                                        1. Deposit 1200 KES or more on MobTenWeb3.

                                    </li>
                                    <li>
                                        2. Get a promo code.

                                    </li>
                                    <li>

                                        3. Promo code grants a free prize of 50 KES to 2000 KES.

                                    </li>
                                    <li>

                                        4. Copy and paste promo code on the site.

                                    </li>

                                    <li>

                                        5. Receive free prize upon submission.

                                    </li>

                                    <li>

                                        6. Larger deposits may yield higher-value promo codes.
                                    </li>
                                </ol> --}}
                            </div>

                        </template>
                    </div>

                    <form action="/codes/submit" method="post">
                        <div class="">
                            @csrf
                            <div class=" md:justify-end flex w-full gap-x-2   pt-4   ">
                                <div>
                                    <button type="submit" class="btn btn-sm  btn-warning rounded-sm">SUBMIT</button>
                                </div>
                                <div class=" w-full flex justify-end">
                                    <input type="text" placeholder="Enter code" name="code"
                                        class="input   input-bordered rounded-sm input-sm input-warning w-full  text-white md:max-w-xs  " />
                                </div>
                            </div>
                        </div>
                    </form>

            </section>


            {{-- <template x-if="codes.length>0"> --}}


            {{-- </template> --}}
        </div>
        </div>
    </section>

    <script>
        const copyCode = (id) => {
            var textToCopy = document.getElementById(id).innerText;

            var tempInput = document.createElement("textarea");
            tempInput.value = textToCopy;

            document.body.appendChild(tempInput);

            tempInput.select();
            tempInput.setSelectionRange(0, 99999);

            document.execCommand("copy");

            document.body.removeChild(tempInput);
            toastr.info('Copied ' + id);
        }
    </script>


    <script>
        const pageData = {
            codes: @json($data['codes']),
            userCodePlans: @json($data['userCodePlans']),

        }

        const updatePromoCodes = () => {

        }
    </script>
@endcomponent
