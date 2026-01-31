<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - HYIP Investment HTML template</title>
    <link rel="icon" type="image/png" href="/assets/images/favicon.png" sizes="16x16">
    <!-- bootstrap 4  -->
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.min.css">
    <!-- fontawesome 5  -->
    <link rel="stylesheet" href="/assets/css/all.min.css">
    <!-- line-awesome webfont -->
    <link rel="stylesheet" href="/assets/css/line-awesome.min.css">
    <link rel="stylesheet" href="/assets/css/vendor/animate.min.css">
    <!-- slick slider css -->
    <link rel="stylesheet" href="/assets/css/vendor/slick.css">
    <link rel="stylesheet" href="/assets/css/vendor/dots.css">


    <link rel="stylesheet" href="/css/toastr.min.css">

    <link rel="stylesheet" href="/assets/css/main.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @component('components.whatsapp-meta')
    @endcomponent
</head>

<body>
    <div class="preloader">
        <div class="preloader-container">
            <span class="animated-preloader"></span>
        </div>
    </div>

    <!-- scroll-to-top start -->
    <div class="scroll-to-top">
        <span class="scroll-icon">
            <i class="fa fa-rocket" aria-hidden="true"></i>
        </span>
    </div>
    <!-- scroll-to-top end -->

    <div class="full-wh">
        <!-- STAR ANIMATION -->
        <div class="bg-animation">
            <div id='stars'></div>
            <div id='stars2'></div>
            <div id='stars3'></div>
            <div id='stars4'></div>
        </div><!-- / STAR ANIMATION -->
    </div>
    <div class="page-wrapper">
        <!-- header-section start  -->
        <header class="header">
            <div class="header__bottom">
                <div class="container">
                    <nav class="p-0 navbar navbar-expand-xl align-items-center">
                        <a class="site-logo site-title" href="/home">
                            <div class=" inline-flex  place-items-center  items-end">

                                <span class=" font-bold text-2xl   italic  text-yellow-600 font-serif">CHAMA</span>
                                <span class=" font-bold text-xl italic  text-yellow-700 font-serif">PESA</span>
                            </div>

                        </a>
                        <ul class="account-menu mobile-acc-menu">
                            <li class="icon">
                                <a href="/login"><i class="las la-user"></i></a>
                            </li>
                        </ul>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="menu-toggle"></span>
                        </button>
                        <div class="collapse navbar-collapse  overflow-y-scroll" id="navbarSupportedContent">
                            <ul class="w-full m-auto navbar-nav main-menu text-start">

                                <li><a href="/dashboard">Dashboard</a></li>
                                <li> <a href="/deposit">Deposit</a></li>
                                <li> <a href="/withdraw">Withdraw</a></li>
                                <li> <a href="/transactions">Transactions</a></li>
                                <li> <a href="/plans">Plans</a></li>
                                <li><a href="/team">My Team</a></li>
                                <li><a href="/my-plans">My plans</a></li>
                                {{-- <li><a href="/codes/plans">Promo withdrawals plans</a></li> --}}
                                {{-- <li><a href="/codes">Grab promo withdrawals</a></li> --}}
                                @auth

                                    <li><a href="/logout">Logout</a></li>
                                @endauth
                                {{-- <li><a href="/contact">Contact Us</a></li>
                                <li> <a href="/about">About Us</a></li>
                                <li> <a href="/logout">Logout</a></li> --}}


                                {{-- <li><a href="contact.html">Contact</a></li> --}}
                            </ul>
                            {{-- <div class="nav-right">
                                <ul class="ml-3 account-menu">
                                    <li class="icon">
                                        <a href="login.html"><i class="las la-user"></i></a>
                                    </li>
                                </ul>
                                <select class="w-auto select d-inline-block ml-xl-3">
                                    <option>Eng</option>
                                    <option>Ban</option>
                                    <option>Hin</option>
                                </select>
                            </div> --}}
                        </div>
                    </nav>
                </div>
            </div><!-- header__bottom end -->
        </header>

        <div class="pt-4 ">

            {{ $slot }}

            @component('components.socials')
            @endcomponent
        </div>


        <footer class="footer bg_img" data-background="/assets/images/bg/bg-7.jpg">
            <div class="footer__top">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="text-center col-lg-12">
                            <a href="#0" class="footer-logo"><img src="/assets/images/logo.png" alt="image"></a>
                            <ul class="flex-wrap mt-4 footer-short-menu d-flex justify-content-center">
                                <li><a href="#0">Home</a></li>
                                <li><a href="#0">Privacy & Policy</a></li>
                                <li><a href="#0">Terms & Conditions</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer__bottom">
                <div class="container">
                    <div class="row">
                        <div class="text-center col-md-6 text-md-left">
                            <p>© 2020 <a href="index.html" class="base--color">{{ config('app.name') }}</a>. All
                                rights
                                reserved</p>
                        </div>
                        <div class="col-md-6">
                            <ul class="flex-wrap social-link-list d-flex justify-content-md-end justify-content-center">
                                <li><a href="#0" data-toggle="tooltip" data-placement="top" title="facebook"><i
                                            class="lab la-facebook-f"></i></a></li>
                                <li><a href="#0" data-toggle="tooltip" data-placement="top" title="twitter"><i
                                            class="lab la-twitter"></i></a></li>
                                <li><a href="#0" data-toggle="tooltip" data-placement="top" title="pinterest"><i
                                            class="lab la-pinterest-p"></i></a></li>
                                <li><a href="#0" data-toggle="tooltip" data-placement="top" title="pinterest"><i
                                            class="lab la-pinterest-in"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- footer section end -->
    </div> <!-- page-wrapper end -->
    <!-- jQuery library -->
    <script src="/assets/js/vendor/jquery-3.5.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="/assets/js/vendor/bootstrap.bundle.min.js"></script>
    <!-- slick slider js -->
    <script src="/assets/js/vendor/slick.min.js"></script>
    <script src="/assets/js/vendor/wow.min.js"></script>
    <script src="/assets/js/contact.js"></script>
    <!-- dashboard custom js -->
    <script src="/assets/js/app.js"></script>
    <script src="/js/jquery.countdown.min.js"></script>

    <script src="/js/toastr.js"></script>
    {!! Toastr::message() !!}

    <!--Start of Tawk.to Script-->
    <!-- Start of LiveChat (www.livechat.com) code -->
    <script>
        window.__lc = window.__lc || {};
        window.__lc.license = 17371428;;
        (function (n, t, c) {
            function i(n) {
                return e._h ? e._h.apply(null, n) : e._q.push(n)
            }
            var e = {
                _q: [],
                _h: null,
                _v: "2.0",
                on: function () {
                    i(["on", c.call(arguments)])
                },
                once: function () {
                    i(["once", c.call(arguments)])
                },
                off: function () {
                    i(["off", c.call(arguments)])
                },
                get: function () {
                    if (!e._h) throw new Error("[LiveChatWidget] You can't use getters before load.");
                    return i(["get", c.call(arguments)])
                },
                call: function () {
                    i(["call", c.call(arguments)])
                },
                init: function () {
                    var n = t.createElement("script");
                    n.async = !0, n.type = "text/javascript", n.src = "https://cdn.livechatinc.com/tracking.js",
                        t.head.appendChild(n)
                }
            };
            !n.__lc.asyncInit && e.init(), n.LiveChatWidget = n.LiveChatWidget || e
        }(window, document, [].slice))
    </script>
    <noscript><a href="https://www.livechat.com/chat-with/17371428/" rel="nofollow">Chat with us</a>, powered by <a
            href="https://www.livechat.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript>
    <!-- End of LiveChat code -->

    <!--End of Tawk.to Script-->

</body>

</html>