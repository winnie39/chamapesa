@component('layouts.app')
<div class="container">
    <div class="pt-10 row justify-content-center">
        <div class="text-center col-lg-6">
            <div class="section-header pt-7">
                <h2 class="section-title"><span class="font-weight-normal">My investment plans </span> <b
                        class="base--color"></b>
                </h2>
            </div>
        </div>
    </div>

    @if (count($data['plans']))
        <div class="row mt-50">
            <div class="col-lg-12">
                <div class="p-0 table-responsive--md">
                    <table class="table style--two white-space-nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Remaining Days</th>
                                <th>Next payment</th>
                                <th>Next payment amount</th>

                                <th>Daily profits</th>
                                <th>Total received</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['plans'] as $item)
                                <tr>
                                    <td data-label="Name"> {{ $item['plan']['name'] }} </td>


                                    <td data-label="Remaining Days">
                                        <span class="text-success"> {{ $item['remaining_days'] }} Days</span>
                                    </td>
                                    <td data-label="Next payment">
                                        <span class="base--color" data-countdown="{{ $item['next_payment'] }}">
                                            {{ $item['next_payment'] }}
                                        </span>
                                    </td>

                                    <td data-label="Next payment amount">
                                        {{ number_format($item['plan']['price'] * $item['plan']['rate'], 2) }}
                                        {{ config('app.currency') }}
                                        <span class="text-xs ">

                                            ({{ number_format($item['plan']['price'] * 1.07 * $item['plan']['rate'] * $data['rates'][config('app.local-currency')]) . config('app.local-currency') }})
                                        </span>
                                    </td>

                                    <td data-label="Daily Profits">
                                        {{ number_format($item['plan']['price'] * $item['plan']['rate'] * 24, 2) }}
                                        {{ config('app.currency') }}
                                        <span class="text-xs ">

                                            ({{ number_format($item['plan']['price'] * 1.07 * $item['plan']['rate'] * 24 * $data['rates'][config('app.local-currency')]) . config('app.local-currency') }})
                                        </span>
                                    </td>



                                    <td data-label="Total received">
                                        <span class=""> {{ $item['total_received'] }} USD </span>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="p-2 text-white bg-black rounded-md ">
            <div class="flex flex-col gap-y-3">
                <div class="text-center text-red-600"> No plans found </div>
            </div>
        </div>
    @endif

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            $('[data-countdown]').each(function () {
                var $this = $(this),
                    finalDate = $(this).data('countdown');
                $this.countdown(finalDate, function (event) {
                    $this.html(event.strftime('%MMins %SSecs'));
                });
            });
        })
    </script>
</div>
</section>
@endcomponent