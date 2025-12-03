@extends('masterpage')

@section('content')
<div class="row gy-4">
    <div class="col-12 col-lg-8 ">
        <div class="col-12">
            <div class="custom-card card mt-4">
                <div class="card-body">
                    <div class="">
                        <h5 class="card-title">🌡️ مدیریت پارامترهای مجاز</h5>

                        <div class="mt-4">
                            <p style="font-size: 17px;">دمای آسایش محیط (محدوده ۱۸-۲۵°C)</p>
                            <div class="d-flex justify-content-center align-items-center mt-2 mb-4 gap-4">
                                <button class="btn btn-outline-primary fs-5 px-3">-</button>
                                <span class="fs-4" style="direction: ltr;">
                                    55 <span>°C</span>
                                </span>
                                <button class="btn btn-outline-primary fs-5 px-3">+</button>
                            </div>
                        </div>

                        <div class="mt-5">
                            <p style="font-size: 17px;">دمای آبگرم مصرفی (محدوده ۴۵-۶۵°C)</p>
                            <div class="d-flex justify-content-center align-items-center mt-2 mb-4 gap-4">
                                <button class="btn btn-outline-primary fs-5 px-3">-</button>
                                <span class="fs-4" style="direction: ltr;">
                                    55 <span>°C</span>
                                </span>
                                <button class="btn btn-outline-primary fs-5 px-3">+</button>
                            </div>
                        </div>

                        <div class="mt-5">
                            <p style="font-size: 17px;">دمای آبگرم گرمایشی (محدوده ۶۰-۸۰°C)</p>
                            <div class="d-flex justify-content-center align-items-center mt-2 mb-4 gap-4">
                                <button class="btn btn-outline-primary fs-5 px-3">-</button>
                                <span class="fs-4" style="direction: ltr;">
                                    55 <span>°C</span>
                                </span>
                                <button class="btn btn-outline-primary fs-5 px-3">+</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="custom-card card mt-4">
                <div class="card-body">
                    <div class="">
                        <h5 class="card-title">📡 مدیریت سنسورها و رله‌ها</h5>

                        <div class="row row-cols-1 row-cols-lg-2 gy-4 mt-4">
                            
                            <div class="col">
                                <div class="equipment-card">
                                    <h4>🌡️ سنسور دما ۱</h4>
                                    <div class="status-badge">
                                        <div class="status-dot-small status-active"></div>
                                        <span>فعال</span>
                                    </div>
                                    <button class="btn btn-primary mt-3">کالیبره</button>
                                </div>
                            </div>
                            <div class="col">
                                <div class="equipment-card">
                                    <h4>🌡️ سنسور دما ۱</h4>
                                    <div class="status-badge">
                                        <div class="status-dot-small status-active"></div>
                                        <span>فعال</span>
                                    </div>
                                    <button class="btn btn-primary mt-3">کالیبره</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="custom-card card mt-4">
                <div class="card-body">
                    <div class="">
                        <h5 class="card-title">⏰ مدیریت زمان و تقویم</h5>
                        <p>برنامه‌ریزی فعالیت روزانه</p>

                        <div class="mt-4">
                            <select name="" id="" class="form-select">
                                <option value="">برنامه عادی (6 تا 10 صبح)</option>
                                <option value="">برنامه صرفه جویی</option>
                                <option value="">برنامه تعطیلات</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <p style="font-size: 17px;">زمان‌بندی دمای شبانه</p>
                            <div class="d-flex align-items-center mt-2 mb-4 gap-4">
                                <button class="btn btn-outline-primary fs-5 px-3">-</button>
                                <span class="fs-4" style="direction: ltr;">
                                    55 <span>°C</span>
                                </span>
                                <button class="btn btn-outline-primary fs-5 px-3">+</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="custom-card card mt-4">
            <div class="card-body">
                <div class="">
                    <h5 class="card-title">⚠️ تنظیمات هشدارها</h5>
                </div>
                <div class="mt-4">
                    <div class="alert-panel">
                        <div class="setting-item">
                            <span>هشدار دمای بالا</span>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="setting-item">
                            <span>هشدار فشار پایین</span>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="setting-item">
                            <span>اعلان‌های فوری</span>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-card card mt-4">
            <div class="card-body">
                <div class="">
                    <h5 class="card-title">🔗 وضعیت ارتباطات سیستم</h5>
                </div>
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="fs-5">
                            سنسور دما <span>1</span>
                        </p>
                        <p class="fs-5 text-success">متصل</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="fs-5">
                            رله مشعل<span>1</span>
                        </p>
                        <p class="fs-5 text-danger">قطع</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-card card mt-4">
            <div class="card-body">
                <div class="">
                    <h5 class="card-title">🔄 بازنشانی تنظیمات</h5>
                    <p class="text-muted">
                        تمام تنظیمات به حالت پیش‌فرض کارخانه بازگردانده می‌شود.
                    </p>
                </div>
                <div class="mt-4">
                    <button class="btn btn-outline-danger w-100 p-2">بازنشانی به پیش‌ فرض</button>
                </div>
            </div>
        </div>

        <div class="custom-card card mt-4">
            <div class="card-body">
                <div class="">
                    <h5>❓ راهنمای تنظیمات</h5>
                </div>
                <div class="mt-4">
                    <div class="py-3 px-1 rounded-2" style="font-size: 17px; background-color: #E3F2FD;">
                        <span class="fw-bold">📌 مدیریت پارامترهای مجاز:</span><br>
                        <span>
                            کاربر می‌تواند دما را فقط در محدوده تعیین شده تغییر دهد
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>




</div>

</div>

@endsection