@extends('masterpage')

@section('content')
<div class="row gy-4">
    <div class="col-12 col-lg-8 d-flex flex-wrap justify-content-between ">
        <div class="col-12 col-lg-6 px-0 px-lg-1">
            <div class="report-card">
                <div class="report-header">
                    <h4>📊 گزارش مصرف انرژی</h4>
                    <div class="report-icon energy">⚡</div>
                </div>
                <p>گزارش کامل مصرف انرژی ماهانه با تحلیل روند و مقایسه با دوره‌های قبلی</p>
                <div class="report-actions">
                    <button class="btn btn-primary">مشاهده</button>
                    <button class="btn text-dark" style="background-color: lightgray;">دانلود PDF</button>
                    <button class="btn text-dark" style="background-color: lightgray;">خروجی Excel</button>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 px-0 px-lg-1 mt-3 mt-lg-0">
            <div class="report-card">
                <div class="report-header">
                    <h4>💧 گزارش مصرف آب</h4>
                    <div class="report-icon water">💧</div>
                </div>
                <p>تحلیل مصرف آب و شناسایی نقاط بهینه‌سازی در سیستم توزیع</p>
                <div class="report-actions">
                    <button class="btn btn-primary">مشاهده</button>
                    <button class="btn text-dark" style="background-color: lightgray;">دانلود PDF</button>
                    <button class="btn text-dark" style="background-color: lightgray;">خروجی Excel</button>
                </div>
            </div>
        </div>

        <div class="col-12 bg-danger mt-3" style="height: 200px;">

        </div>

    </div>
    <div class="col-12 col-lg-4">
        <div class="custom-card card">
            <div class="card-body">
                <div class="">
                    <h5 class="card-title">📅 گزارش‌های برنامه‌ریزی شده</h5>
                </div>
                <div class="mt-4">
                    <div>
                        <p style="font-size: 17px;">
                            ✅
                            <span>۸۵%</span>
                            تجهیزات در حال کار عادی
                        </p>
                    </div>
                    <hr>
                    <div>
                        <p style="font-size: 17px;">
                            ⚠️
                            <span>2</span>
                            هشدار فعال نیاز به بررسی
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-card card mt-4">
            <div class="card-body">
                <div class="">
                    <h5 class="card-title">نوع گزارش:</h5>
                </div>
                <div class="mt-4">
                    <form action="">
                        <div>
                            <label for="" class="fs-5 d-block">وظیفه :</label>
                            <select name="" id="" class="form-select mt-1">
                                <option value="">گزارش مصرف انرژی</option>
                                <option value="">گزارش هشدارها</option>
                                <option value="">گزارش عملکرد تجهیزات</option>
                                <option value="">گزارش سرویس و نگهداری</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="" class="fs-5 d-block">بازه زمانی:</label>
                            <select name="" id="" class="form-select mt-1">
                                <option value="">امروز</option>
                                <option value="">هفته جاری</option>
                                <option value="">ماه جاری</option>
                                <option value="">سه ماهه</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-4">تولید فوری گزارش</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection