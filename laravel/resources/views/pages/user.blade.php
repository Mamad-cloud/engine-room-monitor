@extends('masterpage')

@section('content')
<div class="row gy-4">
    <div class="col-12 col-lg-8 d-flex flex-wrap justify-content-between ">
        <div class="col-12">
            <!-- پروفایل کاربر -->
            <div class="user-profile-section">
                <div class="profile-header">
                    <div class="profile-avatar">ی</div>
                    <div class="profile-info">
                        <h3>یاسر کاظمی</h3>
                        <p>مدیر فنی ارشد</p>
                        <p>yas.kazemi@company.ir | ۰۹۱۲۳۴۵۶۷۸۹</p>
                        <span class="profile-badge">سطح دسترسی: مدیر سیستم</span>
                    </div>
                </div>

                <form action="">
                    <div class="mt-3">
                        <label>نام و نام خانوادگی</label>
                        <input type="text" class="form-control" value="یاسر کاظمی">
                    </div>
                    <div class="mt-3">
                        <label>پست الکترونیکی</label>
                        <input type="email" class="form-control" value="yas.kazemi@company.ir">
                    </div>
                    <div class="mt-3">
                        <label>شماره تماس</label>
                        <input type="tel" class="form-control" value="۰۹۱۲۳۴۵۶۷۸۹">
                    </div>
                    <div class="mt-3">
                        <label>سمت سازمانی</label>
                        <input type="text" class="form-control" value="مدیر فنی ارشد">
                    </div>
                    <div class="mt-3 full-width">
                        <label>درباره من</label>
                        <textarea class="form-control" rows="3">مدیر فنی با ۸ سال سابقه در زمینه مدیریت موتورخانه‌های هوشمند</textarea>
                    </div>
                    <br>
                    <div class="">
                        <button type="button" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> ذخیره تغییرات
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12">
            <div class="custom-card card mt-4">
                <div class="card-body">
                    <div class="">
                        <h5 class="card-title">🏭 موتورخانه‌های تحت مدیریت</h5>
                        <p>لیست موتورخانه‌هایی که شما به آن‌ها دسترسی دارید:</p>
                    </div>
                    <div class="row row-cols-1 row-cols-lg-3 justify-content-around gy-4 mt-4">
                        <div class="col">
                            <div class="equiment-place-status active-equiment-style w-100">
                            <i class="bi bi-fire fs-2" style="color: orange;"></i>
                            <p class="fs-5 fw-bold">
                                موتورخانه مرکزی <span>A</span>
                            </p>
                            <p>ساختمان مرکزی شرکت</p>
                            <p class="text-success">فعال</p>
                        </div>
                        </div>

                        <div class="col">
                            <div class="equiment-place-status active-equiment-style w-100">
                            <i class="bi bi-fire fs-2" style="color: orange;"></i>
                            <p class="fs-5 fw-bold">
                                موتورخانه مرکزی <span>A</span>
                            </p>
                            <p>ساختمان مرکزی شرکت</p>
                            <p class="text-success">فعال</p>
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
                        <h5 class="card-title">📝 فعالیت‌های اخیر</h5>
                        <p>تاریخچه آخرین فعالیت‌های شما در سیستم:</p>
                    </div>
                    <div class="mt-4">
                    <div>
                        <p style="font-size: 17px;">
                            <span class="text-muted">امروز - <span>10.30</span></span><br>
                            ورود به سیستم از دستگاه جدید
                        </p>
                    </div>
                    <hr>
                    <div>
                        <p style="font-size: 17px;">
                            <span class="text-muted">امروز - <span>10.30</span></span><br>
                            ورود به سیستم از دستگاه جدید
                        </p>
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
                    <h5 class="card-title">🔐 سطح دسترسی‌های شما</h5>
                    <p>محدودیت‌ها و مجوزهای دسترسی شما در سیستم:</p>
                </div>
                <div class="mt-4">
                    <div class="access-card">
                            <div class="access-header">
                                <div class="access-icon monitoring">📡</div>
                                <div class="access-status status-allowed text-success">
                                    <i class="fas fa-check-circle"></i>
                                    دسترسی کامل
                                </div>
                            </div>
                            <h5>مانیتورینگ زنده</h5>
                            <div class="access-description">
                                مشاهده تمام پارامترهای لحظه‌ای و کنترل تجهیزات
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <div class="custom-card card mt-4">
            <div class="card-body">
                <div class="">
                    <h5 class="card-title">📨 درخواست ارتقاء دسترسی</h5>
                    <p>در صورت نیاز به دسترسی بیشتر، درخواست خود را ارسال کنید:</p>
                </div>
                <div class="mt-4">
                    <div class="request-form">
                        <div class="form-group">
                            <label>نوع دسترسی درخواستی</label>
                            <div class="access-levels mt-2">
                                <div class="level-option">
                                    <div class="level-title">دسترسی گزارش‌گیری پیشرفته</div>
                                    <div class="level-description">امکان خروجی‌های تحلیلی پیشرفته</div>
                                </div>
                                <div class="level-option">
                                    <div class="level-title">دسترسی مدیریت کاربران</div>
                                    <div class="level-description">امکان مدیریت کاربران زیرمجموعه</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>دلیل درخواست</label>
                            <textarea class="form-control mt-2" rows="3" placeholder="علت نیاز به این سطح دسترسی را توضیح دهید..."></textarea>
                        </div>
                        
                        <button type="button" class="btn btn-outline-success">
                            <i class="fas fa-paper-plane"></i> ارسال درخواست به مدیر
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-card card mt-4">
            <div class="card-body">
                <div class="">
                    <h5 class="card-title">🔒 تغییر رمز عبور</h5>
                </div>
                <div class="mt-4">
                    <form action="">
                        <label for="">رمز عبور فعلی:</label>
                        <input type="text" placeholder="رمز عبور فعلی" class="form-control p-2 mt-2">

                        <label for="" class="mt-4">رمز عبور جدید:</label>
                        <input type="text" placeholder="رمز عبور فعلی" class="form-control p-2 mt-2">

                        <button class="btn btn-outline-primary w-100 mt-4">تغییر رمز عبور</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    

    
</div>

</div>

@endsection