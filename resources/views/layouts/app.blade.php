<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    {!! seo($SEOData) !!}

    <link rel="icon" type="image/x-icon" href="{{ asset('newLayout/assets/favicon.ico') }}"/>
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet"/>
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    {{--    @vite(['resources/css/app.css', 'resources/js/app.js','resources/newLayout/css/refer.css'])--}}
    <link href="{{ asset('newLayout/css/styles.css') }}" rel="stylesheet"/>
    <!--    自訂樣式-->
    <link href="{{ asset('newLayout/css/refer.css') }}" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <!--    font-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script type="application/ld+json">
       {
          "@context": "https://schema.org",
          "@type": "Product",
          "name": "產品名稱",
          "image": "https://www.example.com/product.jpg",
          "description": "產品描述"
        }
    </script>

</head>
<body id="page-top">
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="#page-top">Start Bootstrap</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <!--        <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse"-->
        <!--                data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"-->
        <!--                aria-label="Toggle navigation">-->
        <!--            Menu-->
        <!--            <i class="fas fa-bars"></i>-->
        <!--        </button>-->
        <div class="offcanvas offcanvas-start w-25" tabindex="-1" id="offcanvasExample"
             aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <button type="button" class="btn-close ms-auto text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#projects">Projects</a></li>
                    <li class="nav-item"><a class="nav-link" href="#signup">Contact</a></li>
                </ul>
            </div>
        </div>
        <!--        <div class="collapse navbar-collapse" id="navbarResponsive">-->
        <!--            <ul class="navbar-nav ms-auto">-->
        <!--                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>-->
        <!--                <li class="nav-item"><a class="nav-link" href="#projects">Projects</a></li>-->
        <!--                <li class="nav-item"><a class="nav-link" href="#signup">Contact</a></li>-->
        <!--            </ul>-->
        <!--        </div>-->
    </div>
</nav>
<!-- Masthead-->
@yield('content')

<!-- Footer-->
<footer class="footer bg-black small text-center text-white-50">
    <div class="container px-4 px-lg-5">Copyright &copy; Your Website 2023</div>
</footer>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->

<script src="{{ asset('newLayout/js/scripts.js') }}"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
{{--<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/multiMonthPlugin.global.min.js'></script>--}}
{{--@vite('resources/js/app.js')--}}
<script>

    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'UTC',
            editable: true,
            initialView: 'multiMonthTwoMonth',
            views: {
                multiMonthTwoMonth: {
                    type: 'multiMonth',
                    duration: { months: 2 }
                }},
            // multiMonthMinWidth:1,
            multiMonthMaxColumns: 2,
            events: 'https://fullcalendar.io/api/demo-feeds/events.json'

        });

        calendar.render();
        var calendarEl2 = document.getElementById('calendar2');
        var calendar2 = new FullCalendar.Calendar(calendarEl2, {
            timeZone: 'UTC',
            initialView: 'dayGridMonth',
            events: [
                {
                    title: '会议',
                    start: '2024-09-10T10:00:00',
                    end: '2024-09-10T12:00:00'
                },

                {
                    title: '会议',
                    start: '2024-09-10',
                    url: 'https://example.com/meeting' // 添加事件链接
                },
                {
                    title: '会议',
                    start: '2024-09-10',
                    url: 'https://example.com/meeting' // 添加事件链接
                },
                {
                    title: '会议',
                    start: '2024-09-10',
                    url: 'https://example.com/meeting' // 添加事件链接
                },
                {
                    title: '会议',
                    start: '2024-09-10T10:00:00',
                    end: '2024-09-10T12:00:00',
                    url: 'https://example.com/meeting' // 添加事件链接
                }, {
                    title: '午餐',
                    start: '2024-09-12T12:00:00',
                    end: '2024-09-12T13:00:00'
                }, {
                    title: '午餐',
                    start: '2024-09-12T12:00:00',
                    end: '2024-09-12T13:00:00'
                },
                {
                    title: '工作坊',
                    start: '2024-09-15T09:00:00',
                    end: '2024-09-15T17:00:00'
                }
            ],
            dayMaxEventRows: true, // for all non-TimeGrid views
            views: {
                timeGrid: {
                    dayMaxEventRows: 2 // adjust to 6 only for timeGridWeek/timeGridDay
                }
            }
        });

        calendar2.render();
    });


</script>
</body>
</html>
