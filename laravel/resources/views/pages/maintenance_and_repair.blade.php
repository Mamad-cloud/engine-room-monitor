@extends('masterpage')
@section('content')
<div class="row gy-4">
    <div class="col-12 col-lg-2">
        <div class="custom-card card">
            <div class="card-body">
                <div class="mt-4 text-center">
                    <div class="fs-2" style="direction: ltr;">
                        ۱۲
                    </div>

                    <div class="fs-5 mt-5" style="direction: ltr;">
                        <span>وظایف امروز</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-2">
        <div class="custom-card card">
            <div class="card-body">
                <div class="mt-4 text-center">
                    <div class="fs-2" style="direction: ltr;">
                        ۱۲
                    </div>

                    <div class="fs-5 mt-5" style="direction: ltr;">
                        <span>عقب افتاده</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-2">
        <div class="custom-card card">
            <div class="card-body">
                <div class="mt-4 text-center">
                    <div class="fs-2" style="direction: ltr;">
                        ۱۲
                    </div>

                    <div class="fs-5 mt-5" style="direction: ltr;">
                        <span>نرخ تکمیل</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="ai-prediction">
            <h4>🚨 هشدارهای فوری</h4>
            <div class="prediction-item">
                <div>
                    <div class="mb-2">سرویس مشعل ۲ عقب افتاده است</div>
                    <small style="font-size: 15px;">بیش از ۲۴ ساعت تأخیر</small>
                </div>

            </div>
            <div class="prediction-item">
                <div>
                    <div class="mb-2">۳ وظیفه برای امروز باقی مانده</div>
                    <small style="font-size: 15px;">مهلت: امروس ساعت <span>۱۸:۰۰</span></small>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row gy-4 mt-5">
    <div class="col-12 col-lg-4">
        <div class="task-card pending">
            <div class="task-header">
                <div class="task-title">بررسی فشار سیستم</div>
                <span class="task-badge badge-pending">در انتظار</span>
            </div>
            <div class="task-meta">
                <span>⏰ امروز - ۱۰:۰۰</span>
                <span>🔧 دیگ اصلی</span>
            </div>
            <div class="task-description">
                بررسی فشار دیگ و منابع انبساط - ثبت مقدار در گزارش
            </div>
            <div class="task-actions">
                <button class="btn btn-primary">📝 ثبت گزارش</button>
                <button class="btn btn-warning">⏰ به تعویق</button>
            </div>
        </div>


    </div>
    <div class="col-12 col-lg-4">
        <!-- Task 2 -->
        <div class="task-card overdue">
            <div class="task-header">
                <div class="task-title">شستشوی فیلترها</div>
                <span class="task-badge badge-overdue">عقب افتاده</span>
            </div>
            <div class="task-meta">
                <span>⏰ دیروز - ۱۴:۰۰</span>
                <span>🔧 مشعل ۱</span>
            </div>
            <div class="task-description">
                شستشوی فیلترهای هوا و سوخت - آپلود عکس پس از انجام
            </div>
            <div class="task-actions">
                <button class="btn btn-primary">📝 ثبت گزارش</button>
                <button class="btn btn-danger">🚨 فوری</button>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="custom-card card">
            <div class="card-body">
                <div class="">
                    <h5 class="card-title">📝 ثبت سریع گزارش</h5>
                </div>
                <div class="mt-4">
                    <form action="">
                        <div>
                            <label for="" class="fs-5 d-block">وظیفه :</label>
                            <select name="" id="" class="form-select mt-1">
                                <option value="">لطفا وظیفه را انتخاب نمایید</option>
                                <option value="">بررسی فشار سیستم</option>
                                <option value="">شستشوی فیلترها</option>
                                <option value="">کنترل دمای آب</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="" class="fs-5 d-block">توضیحات :</label>
                            <textarea name="" id="" class="form-control mt-1" style="min-height: 80px;">شرح اقدامات انجام شده ....</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-4">ذخیره گزارش</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <div class="ai-prediction">
            <h4>📋 چک‌لیست استاندارد سرویس مشعل</h4>
            <div class="mt-4">
                <form action="" class="fs-5">
                    <div>
                        <input type="checkbox" class="">
                        <label for="">بررسی شعله و احتراق</label>
                    </div>
                    <hr>
                    <div class="mt-2">
                        <input type="checkbox" class="">
                        <label for="">بررسی شعله و احتراق</label>
                    </div>
                    <hr>
                    <div class="mt-2">
                        <input type="checkbox" class="">
                        <label for="">بررسی شعله و احتراق</label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection