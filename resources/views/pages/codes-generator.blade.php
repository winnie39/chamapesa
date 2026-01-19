<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="  bg-slate-800">
    <form action="/codes/generate" method="post">
        @csrf
        <div class="font-semibold text-white text-center pb-4">GENERATE CODES</div>
        <div class="border  flex flex-col w-screen  text-white gap-y-4">
            <div class=" w-full flex flex-col">
                <label for="">Number of codes</label>
                <input class="input input-sm text-white input-secondary  w-[75%]" type="number"
                    name="number_of_codes" />
                <x-input-error :messages="$errors->get('number_of_codes')" class="mt-2" />
            </div>

            <div class=" w-full flex flex-col">
                <label for="">Number of codes</label>
                <select class="select select-secondary text-white w-[75%] select-sm " name="plan">
                    @foreach ($data['plans'] as $item)
                        <option value="{{ $item['id'] }}" class=" bg-slate-800">{{ $item['name'] }} </option>
                    @endforeach

                </select>
                <x-input-error :messages="$errors->get('plan')" class="mt-2" />
            </div>

            <div class=" w-full flex flex-col">
                <label for="">Period(IN Minutes)</label>
                <input class="input input-sm text-white input-secondary  w-[75%]" type="number" name="period" />
                <x-input-error :messages="$errors->get('period')" class="mt-2" />
            </div>

            <div>
                <button class="btn btn-active hover:bg-yellow-600 bg-green-600 border-0  btn-sm ">GENERATE</button>
            </div>


        </div>
    </form>
</body>

</html>
