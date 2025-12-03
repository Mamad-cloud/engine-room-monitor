@extends('masterpage')
@section('content')

<!-- باکس های اطلاعات -->
<div class="row mt-3">

  <!-- box 1 -->
  <div class="col-md-6 col-lg-3 mb-4">
    <div class="custom-card card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title">مصرف ماهانه انرژی</h5>
          <i class="bi bi-pencil-square fs-3 text-primary"></i>
        </div>
        <div class="mt-4 text-center">
          <p class="fs-2">۱۲,۵۰۰</p>

          <p class="fs-5 mt-5">
            kMh <span>↘️</span> <span>8%</span>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- box 2  -->
  <div class="col-md-6 col-lg-3 mb-4">
    <div class="custom-card card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title">تجهیزات فعال</h5>
          <i class="bi bi-bookmark-check fs-3 text-success"></i>
        </div>
        <div class="mt-4 text-center">
          <p class="fs-2">۱۲,۵۰۰</p>

          <p class="fs-5 mt-5">
            kMh <span>↘️</span> <span>8%</span>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- box 3 -->
  <div class="col-md-6 col-lg-3 mb-4">
    <div class="custom-card card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title">هشدارهای امروز</h5>
          <i class="bi bi-hourglass-split fs-3 text-warning"></i>
        </div>
        <div class="mt-4 text-center">
          <p class="fs-2">۱۲,۵۰۰</p>

          <p class="fs-5 mt-5">
            kMh <span>↘️</span> <span>8%</span>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- box 4 -->
  <div class="col-md-6 col-lg-3 mb-4">
    <div class="custom-card card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title">خلاصه امروز</h5>
          <i class="bi bi-speedometer2 fs-3 text-info"></i>
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
  </div>
</div>

<div class="row">
  <div class="col-12 col-lg-6">
    <div class="chart-container" style="height: 300px; background-color: red;">
      <h4>📊 عملکرد ماهانه</h4>
      <div class="chart-wrapper">
        <canvas id="dashboardChart"></canvas>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="ai-prediction">
      <h4>🤖 پیش‌بینی هوشمند</h4>
      <div class="prediction-item">
        <div>
          <div>مشعل ۱</div>
          <small>احتمال خرابی در ۳۰ روز آینده</small>
        </div>
        <div class="prediction-progress">
          <div class="progress-bar progress-safe" style="width: 15%"></div>
        </div>
        <div>۱۵%</div>
      </div>
      <div class="prediction-item">
        <div>
          <div>پمپ ۱</div>
          <small>احتمال خرابی در ۳۰ روز آینده</small>
        </div>
        <div class="prediction-progress">
          <div class="progress-bar progress-warning" style="width: 65%"></div>
        </div>
        <div>۶۵%</div>
      </div>
    </div>
  </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection