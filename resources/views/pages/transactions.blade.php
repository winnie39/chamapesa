@component('layouts.app')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="section-header pt-7">
                    <h2 class="section-title"><span class="font-weight-normal">Transactions</span> <b class="base--color"></b>
                    </h2>
                </div>
            </div>
        </div>

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
                                            {{ config('app.currency') }}</span>
                                    </td>
                                    <td data-label="Wallet">
                                        <span class="badge base--bg"> {{ $item['status_text'] }} </span>
                                    </td>
                                    <td data-label="Details"> {{ $item['description'] }} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </section>
@endcomponent
