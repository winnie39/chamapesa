@component('layouts.app')
    <section class="pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="section-header pt-7">
                        <h2 class="section-title"><span class="font-weight-normal">

                                Lustre rewards
                            </span> <b class="base--color"></b>
                        </h2>
                    </div>
                </div>
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
                                    <div> {{ $data['activeReferrals'] }}/{{ (int) $task['task'] }} </div>
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
                <div class=" rounded-md bg-[#631ac6] text-white p-2">
                    <div class="flex flex-col gap-y-3">
                        <div class="text-red-600 text-center"> No tasks found </div>

                    </div>
                </div>
            @endif


        </div>
    </section>
@endcomponent
