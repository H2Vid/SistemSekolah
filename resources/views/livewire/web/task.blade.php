<div>
      <!-- breadcrumb begin -->
  <div class="breadcrumb-oitila">
    <div class="container">
      <div class="row">
        <div class="col-xl-9 col-lg-9 col-8">
          <div class="part-txt">
            <h1>Tugas</h1>
            <ul>
              <li>home</li>
              <li>tugas</li>
            </ul>
          </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-4 d-flex align-items-center">
          <div class="part-img">
            <img src="{{ asset('assets/fe') }}/images/breadcrumb-img.png" alt="image">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- breadcrumb end -->
  <!-- blog begin -->
  <div class="blog-page">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12">
          <div class="blog">
            <div class="row justify-content-center justify-content-md-start">
              <div class="col-xl-12 col-lg-12 col-sm-12 col-md-12">
                @foreach($list as $l)
                  <div class="single-blog">
                      <div class="row">
                          <div class="col-xl-9 col-lg-9 col-sm-9 col-md-9">
                              <div class="part-text">
                                  <a href="{{ url('/') }}/product/detail" class="title">{{ $l->name }}</a>
                                  <p>{{ $l->description }}</p>
                              </div>
                          </div>
                          <div class="col-xl-3 col-lg-3 col-sm-3 col-md-3">
                              <a href="#" class="btn-hyipox-2" style="margin-top: 20px; text-align: right; float: right;">Mengajak</a>
                              <p style="text-align: right;margin-top: 80px; padding-right: 50px;">0 / {{ $l->total_member }}</p>
                          </div>
                      </div>
                  </div>
                @endforeach
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- blog end -->
</div>