<div>
    <!-- breadcrumb begin -->
    <div class="breadcrumb-oitila">
        <div class="container">
          <div class="row">
            <div class="col-xl-9 col-lg-9 col-8">
              <div class="part-txt">
                <h1>login</h1>
                <ul>
                  <li>home</li>
                  <li>login page</li>
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
      <!-- login begin -->
      <div class="login">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10 col-md-7 col-sm-9">
              @if(session()->has('success'))
                <div class="alert alert-success">
                  {{ session()->get('success') }}
                </div>
              @elseif(session()->has('error'))
                <div class="alert alert-danger">
                  {{ session()->get('error') }}
                </div>
              @endif
              <div class="form-area">
                <div class="row no-gutters">
                  <div class="col-xl-6 col-lg-6">
                    <div class="login-form">
                      <form action="{{ url('/') }}/login/doLogin" method="POST">
                      @csrf
                        <div class="form-group">
                          <label for="exampleInputEmail1">Email address</label>
                          <input type="email" class="form-control" id="exampleInputEmail1" name="email">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputPassword1">Password</label>
                          <input type="password" class="form-control" id="exampleInputPassword1" name="password">
                        </div>
                        <div class="form-group form-check">
                          <input type="checkbox" class="form-check-input" id="exampleCheck1">
                          <label class="form-check-label" for="exampleCheck1">remember me</label>
                          <button class="btn-form" type="submit">Submit</button>
                        </div>
                      </form>
                      <div class="other-option">
                        <a href="{{ url('/') }}/login">Daftar</a>
                        <a href="#">Lupa Password?</a>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 d-xl-block d-lg-block d-none">
                    <div class="blank-space"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- login end -->
</div>