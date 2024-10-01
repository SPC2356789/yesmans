<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Grayscale - Start Bootstrap Theme</title>
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
<header class="">
    <!--    <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">-->
    <!--        <div class="d-flex justify-content-center">-->
    <!--            <div class="text-center">-->
    <!--                <h1 class="mx-auto my-0 text-uppercase">Grayscale</h1>-->
    <!--                <h2 class="text-white-50 mx-auto mt-2 mb-5">A free, responsive, one page Bootstrap theme created by-->
    <!--                    Start Bootstrap.</h2>-->
    <!--                <a class="btn btn-primary" href="#about">Get Started</a>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://via.placeholder.com/1900x1188" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/1901x1188" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/1902x1188" class="d-block w-100" alt="...">
            </div>
        </div>
    </div>
</header>
<div class=" pb-5 pt-5">
    <div id='calendar' class="">
    </div>
    {{--    <div id='calendar2' class="">--}}
    {{--    </div>--}}
</div>
<!--post-->
<section>
    <div class="container">
        <div class="row">
            <div class="post-area-index font-weight-bold ">

                <div class="img_box">
                    <img src="https://via.placeholder.com/1024x1024">
                    <div class="post-title">登山實用</div>
                </div>
                <div class="img_box">
                    <img src="https://via.placeholder.com/1024x1024">
                    <div class="post-title">帶團契機</div>
                </div>
                <div class="img_box">
                    <img src="https://via.placeholder.com/1024x1024">
                    <div class="post-title">山上救護分享</div>

                </div>
                <div class="img_box">
                    <img src="https://via.placeholder.com/1024x1024">
                    <div class="post-title">惡劣天氣判斷</div>
                </div>
            </div>
            <div class="mt-5"><a class="text-decoration-none read_more" href="#">看更多文章....</a></div>
        </div>
    </div>
</section>
<!-- About-->
<section class="about-section text-center" id="about">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8">
                <h2 class="text-white mb-4">Built with Bootstrap 5</h2>
                <p class="text-white-50">
                    Grayscale is a free Bootstrap theme created by Start Bootstrap. It can be yours right now, simply
                    download the template on
                    <a href="https://startbootstrap.com/theme/grayscale/">the preview page.</a>
                    The theme is open source, and you can use it for any purpose, personal or commercial.
                </p>
            </div>
        </div>
        <img class="img-fluid" src="assets/img/ipad.png" alt="..."/>
    </div>
</section>
<!-- Projects-->
<section class="projects-section bg-light" id="projects">
    <div class="container px-4 px-lg-5">
        <!-- Featured Project Row-->
        <div class="row gx-0 mb-4 mb-lg-5 align-items-center">
            <div class="col-xl-8 col-lg-7"><img class="img-fluid mb-3 mb-lg-0" src="assets/img/bg-masthead.jpg"
                                                alt="..."/></div>
            <div class="col-xl-4 col-lg-5">
                <div class="featured-text text-center text-lg-left">
                    <h4>Shoreline</h4>
                    <p class="text-black-50 mb-0">Grayscale is open source and MIT licensed. This means you can use it
                        for any project - even commercial projects! Download it, customize it, and publish your
                        website!</p>
                </div>
            </div>
        </div>
        <!-- Project One Row-->
        <div class="row gx-0 mb-5 mb-lg-0 justify-content-center">
            <div class="col-lg-6"><img class="img-fluid" src="assets/img/demo-image-01.jpg" alt="..."/></div>
            <div class="col-lg-6">
                <div class="bg-black text-center h-100 project">
                    <div class="d-flex h-100">
                        <div class="project-text w-100 my-auto text-center text-lg-left">
                            <h4 class="text-white">Misty</h4>
                            <p class="mb-0 text-white-50">An example of where you can put an image of a project, or
                                anything else, along with a description.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Project Two Row-->
        <div class="row gx-0 justify-content-center">
            <div class="col-lg-6"><img class="img-fluid" src="assets/img/demo-image-02.jpg" alt="..."/></div>
            <div class="col-lg-6 order-lg-first">
                <div class="bg-black text-center h-100 project">
                    <div class="d-flex h-100">
                        <div class="project-text w-100 my-auto text-center text-lg-right">
                            <h4 class="text-white">Mountains</h4>
                            <p class="mb-0 text-white-50">Another example of a project with its respective description.
                                These sections work well responsively as well!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Signup-->
<section class="signup-section" id="signup">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5">
            <div class="col-md-10 col-lg-8 mx-auto text-center">
                <i class="far fa-paper-plane fa-2x mb-2 text-white"></i>
                <h2 class="text-white mb-5">Subscribe to receive updates!</h2>
                <!-- * * * * * * * * * * * * * * *-->
                <!-- * * SB Forms Contact Form * *-->
                <!-- * * * * * * * * * * * * * * *-->
                <!-- This form is pre-integrated with SB Forms.-->
                <!-- To make this form functional, sign up at-->
                <!-- https://startbootstrap.com/solution/contact-forms-->
                <!-- to get an API token!-->
                <form class="form-signup" id="contactForm" data-sb-form-api-token="API_TOKEN">
                    <!-- Email address input-->
                    <div class="row input-group-newsletter">
                        <div class="col"><input class="form-control" id="emailAddress" type="email"
                                                placeholder="Enter email address..." aria-label="Enter email address..."
                                                data-sb-validations="required,email"/></div>
                        <div class="col-auto">
                            <button class="btn btn-primary disabled" id="submitButton" type="submit">Notify Me!</button>
                        </div>
                    </div>
                    <div class="invalid-feedback mt-2" data-sb-feedback="emailAddress:required">An email is required.
                    </div>
                    <div class="invalid-feedback mt-2" data-sb-feedback="emailAddress:email">Email is not valid.</div>
                    <!-- Submit success message-->
                    <!---->
                    <!-- This is what your users will see when the form-->
                    <!-- has successfully submitted-->
                    <div class="d-none" id="submitSuccessMessage">
                        <div class="text-center mb-3 mt-2 text-white">
                            <div class="fw-bolder">Form submission successful!</div>
                            To activate this form, sign up at
                            <br/>
                            <a href="https://startbootstrap.com/solution/contact-forms">https://startbootstrap.com/solution/contact-forms</a>
                        </div>
                    </div>
                    <!-- Submit error message-->
                    <!---->
                    <!-- This is what your users will see when there is-->
                    <!-- an error submitting the form-->
                    <div class="d-none" id="submitErrorMessage">
                        <div class="text-center text-danger mb-3 mt-2">Error sending message!</div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Contact-->
<section class="contact-section bg-black">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card py-4 h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-map-marked-alt text-primary mb-2"></i>
                        <h4 class="text-uppercase m-0">Address</h4>
                        <hr class="my-4 mx-auto"/>
                        <div class="small text-black-50">4923 Market Street, Orlando FL</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card py-4 h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-envelope text-primary mb-2"></i>
                        <h4 class="text-uppercase m-0">Email</h4>
                        <hr class="my-4 mx-auto"/>
                        <div class="small text-black-50"><a href="#!">hello@yourdomain.com</a></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card py-4 h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-mobile-alt text-primary mb-2"></i>
                        <h4 class="text-uppercase m-0">Phone</h4>
                        <hr class="my-4 mx-auto"/>
                        <div class="small text-black-50">+1 (555) 902-88</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="social d-flex justify-content-center">
            <a class="mx-2" href="#!"><i class="fab fa-twitter"></i></a>
            <a class="mx-2" href="#!"><i class="fab fa-facebook-f"></i></a>
            <a class="mx-2" href="#!"><i class="fab fa-github"></i></a>
        </div>
    </div>
</section>
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
