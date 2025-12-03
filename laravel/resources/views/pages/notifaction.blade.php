@extends('masterpage')

@section('content')
<div class="row g-0">
    <!-- 🚨 سیستم هشدار -->
    <div class="col-12 col-lg-7 p-3" style="height: min-content; margin-bottom: 0;">
        <div class="page-header d-flex flex-wrap justify-content-between align-items-center" data-aos="fade-down">
            <div>
                <h4 class="lalezar">🚨 سیستم هشدار هوشمند موتورخانه</h4>
                <p>مدیریت و پیگیری هشدارها در زمان واقعی</p>
            </div>
            <div class="d-flex">
                <span class="task-badge badge-pending">۴ هشدار</span>
                <span class="task-badge badge-overdue">۲ بحرانی</span>
                <span class="task-badge" style="background-color: blue;">۴ هشدار</span>
            </div>
        </div>

        <div class="system-alert-item d-flex flex-wrap critical m-0">
            <div class="alert-icon">🔥</div>
            <div class="alert-content">
                <div class="alert-title">دمای مشعل ۲ بیش از حد مجاز</div>
                <div class="alert-desc">دمای مشعل به ۱۸۰°C رسیده است. حد مجاز: ۱۶۰°C. خطر آسیب به نازل وجود دارد.</div>
                <div class="alert-meta">
                    <span>⏰ ۲ دقیقه پیش</span>
                    <span>🔧 مشعل ۲</span>
                    <span>📍 منطقه A</span>
                </div>
            </div>
            <div class="alert-actions">
                <button class="btn-ack">تأیید دریافت</button>
                <button class="btn-snooze">۵ دقیقه بعد</button>
            </div>
        </div>
    </div>
    

    <!-- 📝 فرم ثبت گزارش -->
    <div class="col-12 col-lg-5 p-3" style="margin-bottom: 0;">
        <div class="custom-card card m-0">
            <div class="card-body">
                <div class="">
                    <h5 class="card-title">🗺️ نقشه موقعیت خطاها</h5>
                </div>
                
                <div class="row row-cols-1 row-cols-lg-2 gy-4 mt-4">
                    <div class="col d-flex justify-content-center">
                        <div class="equiment-place-status active-equiment-style">
                            <i class="bi bi-fire fs-2" style="color: orange;"></i>
                            <p class="fs-5">
                                مشعل <span>1</span>
                            </p>
                            <p class="text-success">فعال</p>
                        </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                        <div class="equiment-place-status error-equiment-style">
                            <i class="bi bi-fire fs-2" style="color: orange;"></i>
                            <p class="fs-5">
                                مشعل <span>2</span>
                            </p>
                            <p class="text-danger">خطا</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4" style="height: 200px; background-color: red;">
                    <h5 class="card-title">📊 آمار هشدارها</h5>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection