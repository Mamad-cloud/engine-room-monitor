@extends('masterpage')
@section('content')
<div class="row gy-5">
    <div class="col-12 col-lg-9">
        <div class="ai-prediction">
            <h4>🤖 پیش‌بینی هوشمند</h4>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>وضعیت</th>
                            <th>نام تجهیز</th>
                            <th>کد</th>
                            <th>نوع</th>
                            <th>سنسورها</th>
                            <th>آخرین سرویس</th>
                            <th>اقدامات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>✅ فعال</td>
                            <td>مشعل ۱</td>
                            <td>BL-1001</td>
                            <td>مشعل</td>
                            <td>دمای دودکش، فشار گاز</td>
                            <td>۲۰۲۴/۰۵/۱۵</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-outline-primary"><i class="bi bi-bar-chart"></i></button>
                                    <button class="btn btn-outline-warning"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn btn-outline-danger"><i class="bi bi-trash3"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>⚠️ خطا</td>
                            <td>پمپ ۱</td>
                            <td>PP-2001</td>
                            <td>پمپ آب</td>
                            <td>فشار ورودی، دمای موتور</td>
                            <td>۲۰۲۴/۰۴/۳۰</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-outline-primary"><i class="bi bi-bar-chart"></i></button>
                                    <button class="btn btn-outline-warning"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn btn-outline-danger"><i class="bi bi-trash3"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

    <div class="col-12 col-lg-3">
        <div class="ai-prediction">
            <p>📖 جزئیات تجهیز انتخاب شده</p>

            <div>
                <div class="information-row">
                    <span class="fs-5 text-secondary">نام :</span>
                    <span class="fs-5">دیگ اصلی</span>
                </div>
                <div class="information-row">
                    <span class="fs-5 text-secondary">کد :</span>
                    <span class="fs-5">B-001</span>
                </div>
                <div class="information-row">
                    <span class="fs-5 text-secondary">نوع :</span>
                    <span class="fs-5">دیگ فولادی</span>
                </div>
                <div class="information-row">
                    <span class="fs-5 text-secondary">ظرفیت :</span>
                    <span class="fs-5">کیلوکالری <span>۲۰۰,۰۰۰</span></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-9 mt-4">
        <div class="ai-prediction">
            <h4>🗺️ نقشه موقعیت تجهیزات</h4>

            <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 mt-4">
                <div class="equiment-place-status active-equiment-style">
                    <i class="bi bi-fire fs-2" style="color: orange;"></i>
                    <p class="fs-5">
                        مشعل <span>1</span>
                    </p>
                    <p class="text-success">فعال</p>
                </div>
                <div class="equiment-place-status error-equiment-style">
                    <i class="bi bi-fire fs-2" style="color: orange;"></i>
                    <p class="fs-5">
                        مشعل <span>2</span>
                    </p>
                    <p class="text-danger">خطا</p>
                </div>
                <div class="equiment-place-status active-equiment-style">
                    <i class="bi bi-droplet-half fs-2" style="color: blue;"></i>
                    <p class="fs-5">
                        پمپ <span>1</span>
                    </p>
                    <p class="text-success">فعال</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-3 mt-4">
        <div class="ai-prediction">
            <h4>⚡ اقدامات سریع</h4>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-outline-warning w-50">
                    <i class="bi bi-arrow-clockwise"></i>
                    ریست
                </button>
                <button class="btn btn-outline-primary w-50">
                    <i class="bi bi-graph-up"></i>
                    گزارش
                </button>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-outline-success w-50">
                    <i class="bi bi-tools"></i>
                    سرویس
                </button>
                <button class="btn btn-outline-danger w-50">
                    <i class="bi bi-pause-circle"></i>
                    توقف
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
