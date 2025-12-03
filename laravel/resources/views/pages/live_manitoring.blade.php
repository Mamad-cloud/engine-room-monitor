@extends('masterpage')
@section('content')
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 gy-4 mt-2">
    <div class="col">
        <div class="custom-card card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center">
                    <p class="fs-5 text-center">دمای آب رفت</p>
                </div>
                <div class="mt-4 text-center">
                    <div class="fs-2" style="direction: ltr;">
                        <span>75</span> °C
                    </div>

                    <div class="fs-5 mt-5" style="direction: ltr;">
                        <span>۲°</span> C+ <span>↘️</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="custom-card card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center">
                    <p class="fs-5 text-center">دمای آب برگشت</p>
                </div>
                <div class="mt-4 text-center">
                    <div class="fs-2" style="direction: ltr;">
                        <span>68</span> °C
                    </div>

                    <div class="fs-5 mt-5" style="direction: ltr;">
                        <span>5°</span> C+ <span>↘️</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="custom-card card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center">
                    <p class="fs-5 text-center">دمای آب مصرفی</p>
                </div>
                <div class="mt-4 text-center">
                    <div class="fs-2" style="direction: ltr;">
                        <span>55</span> °C
                    </div>

                    <p class="fs-5 mt-5">
                        <span>↘️</span> ثابت
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="custom-card card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center">
                    <p class="fs-5 text-center">🔥 مشعل ۱</p>
                </div>
                <div class="mt-4 text-center">
                    <p class="fs-2">
                        % <span>85</span>
                    </p>

                    <p class="fs-5 mt-5">
                        در حال کار
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="custom-card card h-100" style="background-color: #e74c3c;">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center">
                    <p class="fs-5 text-center">🔥 مشعل ۲</p>
                </div>
                <div class="mt-4 text-center">
                    <p class="fs-2">
                        % <span>85</span>
                    </p>

                    <p class="fs-5 mt-5">
                        خطا
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="custom-card card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center">
                    <p class="fs-5 text-center">فشار سیستم</p>
                </div>
                <div class="mt-4 text-center">
                    <p class="fs-2">
                        Bar <span>2</span>
                    </p>

                    <p class="fs-5 mt-5">
                        <span>↘️</span> نرمال
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="custom-card card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center">
                    <p class="fs-5 text-center">ولتاژ برق</p>
                </div>
                <div class="mt-4 text-center">
                    <p class="fs-2">
                        % <span>85</span>
                    </p>

                    <p class="fs-5 mt-5">
                        <span>↘️</span> ثابت
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="custom-card card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center">
                    <p class="fs-5 text-center">مصرف انرژی</p>
                </div>
                <div class="mt-4 text-center">
                    <p class="fs-2">
                        % <span>85</span>
                    </p>

                    <p class="fs-5 mt-5">
                        <span>↘️</span> <span>5% -</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="custom-card card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center">
                    <p class="fs-5 text-center">💧 پمپ ۱</p>
                </div>
                <div class="mt-4 text-center">
                    <p class="fs-2" style="direction: ltr;">
                        <span>4.2</span> A
                    </p>

                    <p class="fs-5 mt-5">
                        فعال
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="custom-card card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center">
                    <p class="fs-5 text-center">🏭 دیگ</p>
                </div>
                <div class="mt-4 text-center">
                    <p class="fs-2" style="direction: ltr;">
                        <span>72</span> °C
                    </p>

                    <p class="fs-5 mt-5">
                        دمای کاری
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12 col-lg-8">
        <div class="chart-container" style="height: 300px; background-color: red;">
            <h4>📊 عملکرد ماهانه</h4>
            <div class="chart-wrapper">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="custom-card card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h5 class="card-title">⚠️ هشدارهای زنده</h5>
                </div>
                <div class="mt-4">
                    <div>
                        <p style="font-size: 17px;">
                            ⚠️
                            <span>۱۰:۱۵ - امروز</span>
                            دمای برگشت در حال افزایش است
                        </p>
                    </div>
                    <hr>
                    <div>
                        <p style="font-size: 17px;">
                            🚨
                            <span>۱۰:۱۰ - امروز</span>
                            خطا در مشعل ۲ - نیاز به بررسی فوری
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12 col-lg-8">
        <div class="chart-container" style="height: 300px; background-color: red;">
            <h4>⚡ نمودار مصرف انرژی (زنده)</h4>
            <div class="chart-wrapper">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="custom-card card h-100">
            <div class="card-body">
                <div class="">
                    <h5 class="card-title">🎛️ کنترل‌های سریع</h5>
                </div>
                <div class="mt-4">
                    <span style="font-size: 17px;">دمای آب گرم مصرفی</span>
                    <div class="d-flex justify-content-around align-items-center mt-2 mb-4">
                        <button class="btn btn-outline-primary fs-5 px-3">-</button>
                        <span class="fs-4" style="direction: ltr;">
                            55 <span>°C</span>
                        </span>
                        <button class="btn btn-outline-primary fs-5 px-3">+</button>
                    </div>

                    <span style="font-size: 17px;">دمای گرمایش</span>
                    <div class="d-flex justify-content-around align-items-center mt-2">
                        <button class="btn btn-outline-primary fs-5 px-3">-</button>
                        <span class="fs-4" style="direction: ltr;">
                            55 <span>°C</span>
                        </span>
                        <button class="btn btn-outline-primary fs-5 px-3">+</button>
                    </div>

                    <button class="btn btn-danger w-100 fs-5 mt-5">
                        <i class="bi bi-sign-stop-fill"></i>
                        توقف اظطراری سیستم
                    </button>

                    <div class="text-center mt-3">
                        <span class="text-secondary">برای فعال‌سازی دوبار کلیک کنید</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection