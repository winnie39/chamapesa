<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @component('components.layouts.meta')
    @endcomponent
</head>

<body class=" p-2 bg-white text-black">
    <form action="/transaction-order" method="POST">
        @csrf
        <div class=" rounded-md p-2 py-4  border  flex flex-col gap-y-3 w-full ">
            <div class=" flex flex-col">
                <label for="">Phone number</label>
                <input type="text" name="code" placeholder="Enter sender's name"
                    class="input input-bordered w-full " />
                <x-input-error :messages="$errors->get('code')" class="text-start " />

            </div>

            <div class=" flex flex-col">
                <label for="">Amount</label>
                <input type="text" name="amount" placeholder="Enter amount" class="input input-bordered w-full " />
                <x-input-error :messages="$errors->get('phone_number')" class="text-start " />

            </div>
            <div class=" pt-4">
                <button type="submit"
                    class="btn hover:bg-blue-600  bg-blue-600 border-0 text-white  w-full btn-sm">Success</button>
            </div>
        </div>
    </form>

</body>
<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/js/popper.min.js"></script>
<script src="/js/bootstrap.min.js"></script>



<script src="/js/toastr.js"></script>
{!! Toastr::message() !!}

</html>
