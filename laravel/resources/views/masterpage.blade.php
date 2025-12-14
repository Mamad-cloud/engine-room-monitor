<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل موتورخانه هوشمند</title>
    <!-- bootstrap  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
    <!-- bootstrap icons  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- aos animation  -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- css file link -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
</head>

<body>
    <!-- دکمه همبرگر -->
    <button class="hamburger-btn" id="hamburgerBtn">
        <i class="bi bi-list"></i>
    </button>

    <!-- overlay برای پس زمینه تیره هنگام باز بودن منو -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <section>
        <div class="container-fluid">
            <div class="row">
                <!-- sidebar -->
                <div class="col-12 col-lg-2 p-0">
                    <div class="sidebar-container py-4" id="sidebar">
                        <!-- title -->
                        <div class="px-3">
                            <h5 class="lalezar"><i class="bi bi-house me-2"></i> پنل موتورخانه هوشمند</h5>
                            <hr style="border-color: rgba(255,255,255,0.2);">
                        </div>

                        <!-- items  -->
                        <div class="sidebar-item-container sidebar-link-active">
                            <i class="bi bi-laptop fs-4 me-2"></i>
                            <a href="{{ route('mainpage') }}">داشبورد</a>
                        </div>

                        <div class="sidebar-item-container sidebar-link-active">
                            <i class="bi bi-people fs-4 me-2"></i>
                            <a href="{{ route('users') }}">کاربران</a>
                        </div>

                        <div class="sidebar-item-container sidebar-link-active">
                            <i class="bi bi-lightning fs-4 me-2"></i>
                            <a href="{{ route('subscriptions.index') }}">اشتراکها</a>
                        </div>

                        <div class="sidebar-item-container sidebar-link-active">
                            <i class="bi bi-lightning fs-4 me-2"></i>
                            <a href="{{ route('engine-rooms.index') }}">موتورخانه ها</a>
                        </div>

                        <div class="sidebar-item-container sidebar-link-active">
                            <i class="bi bi-lightning fs-4 me-2"></i>
                            <a href="{{ route('event-types') }}"> Event Types</a>
                        </div>

                        <div class="sidebar-item-container sidebar-link-active">
                            <i class="bi bi-lightning fs-4 me-2"></i>
                            <a href="{{ route('peripheral-modes') }}">Peripheral Modes</a>
                        </div>


                        <div class="sidebar-item-container">
                            <i class="bi bi-graph-up fs-4 me-2"></i>
                            <a href="{{ route('liveMonitoring') }}">مانیتورینگ زنده</a>
                        </div>

                        <div class="sidebar-item-container">
                            <i class="bi bi-bounding-box-circles fs-4 me-2"></i>
                            <a href="{{ route('equipment') }}">تجهیزات</a>
                        </div>

                        <div class="sidebar-item-container">
                            <i class="bi bi-hammer fs-4 me-2"></i>
                            <a href="{{ route('maintenance') }}">تعمیرات و نگهداری</a>
                        </div>

                        <div class="sidebar-item-container">
                            <i class="bi bi-envelope-paper fs-4 me-2"></i>
                            <a href="{{ route('notifaction') }}">هشدارها و اعلان ها</a>
                            <div class="bg-danger ms-3" style="font-size: 16px; padding: 7px 15px; border-radius: 50%;">5</div>
                        </div>

                        <div class="sidebar-item-container">
                            <i class="bi bi-newspaper fs-4 me-2"></i>
                            <a href="{{ route('reports') }}">سوابق و گزارشات</a>
                        </div>

                        <div class="sidebar-item-container">
                            <i class="bi bi-people fs-4 me-2"></i>
                            <a href="{{ route('user') }}">کاربران و مدیریت دسترسی</a>
                        </div>

                        <div class="sidebar-item-container">
                            <i class="bi bi-gear fs-4 me-2"></i>
                            <a href="{{ route('setting') }}">تنظیمات سیستم</a>
                        </div>
                        <div class="sidebar-item-container">
                            <i class="bi bi-box-arrow-right fs-4 me-2"></i>
                            <a href="./login.html">خروج / ورود</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-10 p-0">
                    <!-- page header  -->
                    <header style="background-color: white;">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 gy-4 py-3 d-flex justify-content-center">
                            <div class="col d-flex justify-content-center">
                                <!-- وضعیت اتصال  -->
                                <p class="fs-4 text-success">وضعیت ارتباط :
                                    <span>متصل</span>
                                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                                </p>
                            </div>
                            <div class="col d-flex justify-content-center">
                                <!-- activity  -->
                                <div class="modbus-active-style">
                                    <span>ModBus Active</span>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-center">
                                <!-- تاریخ  -->
                                <div class="datetime" id="datetime">--:--:-- | ۱۴۰۳/--/--</div>
                            </div>

                            <!-- پروفایل  -->
                            <div class="col d-flex justify-content-center">
                                <div class="user-info">
                                    <div class="user-details">
                                        <div>یاسر کاظمی</div>
                                        <div>مدیر فنی ارشد</div>
                                    </div>
                                    <div class="user-avatar">ی</div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 gy-4 py-3 mt-3"
                            style="border-top: 1px solid blue;">
                            <div class="col d-flex justify-content-center align-items-center">
                                <label for="">موتور خانه :</label>
                                <select name="" id="" class="selectbox-style">
                                    <option value="">همه موتورخانه ها</option>
                                    <option value="">موتور خانه A</option>
                                    <option value="">موتور خانه B</option>
                                </select>
                            </div>

                            <div class="col d-flex justify-content-center align-items-center">
                                <label for="">بازه زمانی :</label>
                                <select name="" id="" class="selectbox-style">
                                    <option value="">5 دقیقه گذشته</option>
                                    <option value="">15 دقیقه گذشته</option>
                                    <option value="">30 دقیقه گذشته</option>
                                </select>
                            </div>

                            <div class="col d-flex justify-content-center align-items-center">
                                <label for="">نوع پارامتر :</label>
                                <select name="" id="" class="selectbox-style">
                                    <option value="">همه پارامتر ها</option>
                                    <option value="">دما</option>
                                    <option value="">فشار</option>
                                    <option value="">جریان</option>
                                </select>
                            </div>
                        </div>
                    </header>

                    <!-- محتوای اصلی -->
                    <div class="main-content" id="mainContent">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- jQuery MUST be loaded before Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- bootstrap cdn  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- aos animation  -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // sidebar control code 
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const overlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
            }

            hamburgerBtn.addEventListener('click', toggleSidebar);

            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });

            // تابع به روزرسانی زمان
            function updateDateTime() {
                const now = new Date();
                const time = now.toLocaleTimeString('fa-IR');
                const date = now.toLocaleDateString('fa-IR');
                document.getElementById('datetime').textContent = `${time} | ${date}`;
            }

            updateDateTime();
            setInterval(updateDateTime, 1000);
        });
    </script>

    <script>
        AOS.init();
    </script>

</body>

</html>