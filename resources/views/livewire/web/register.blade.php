<div>
    <!-- breadcrumb begin -->
    <div class="breadcrumb-oitila">
        <div class="container">
          <div class="row">
            <div class="col-xl-9 col-lg-9 col-8">
              <div class="part-txt">
                <h1>register</h1>
                <ul>
                  <li>home</li>
                  <li>register page</li>
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
      <!-- register begin -->
      <div class="register">
        <div class="container">
          <div class="reg-body">
            <form action="{{ url('/') }}/register/store" method="POST">
              @csrf
              <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                  <h4 class="sub-title">Personal Information</h4>
                  <input type="text" placeholder="Nama lengkap*" name="name">
                  <input type="email" placeholder="Email*" name="email">
                  <input type="text" placeholder="No. HP*" name="phone_number">
                  <input type="password" placeholder="Password*" name="password">
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 additional-info">
                  <h4 class="sub-title">Additional Information</h4>
                  <input type="text" placeholder="Kode Referral" name="code" value="{{ $kode }}" @if($kode != "") readonly @endif>
                  <select class="register-select" name="gender">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="1">Laki-laki</option>
                    <option value="2">Perempuan</option>
                  </select>
                  <textarea placeholder="Alamat" name="address" class="register-textarea"></textarea>
                </div>
              </div>
              <div class="term-condition">
                <h4 class="title">Terms and Conditions</h4>
                <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor. <br>
                  <br> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.
                </p>
              </div>
              <div class="row">
                <div class="col-xl-6 col-lg-6">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="exampleRadios" id="exampleRadios5" value="option2">
                    <label class="form-check-label" for="exampleRadios5"> I agree to the terms &amp; conditions. </label>
                    <p>(*) We will never share your personal information with third parties.</p>
                  </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                  <button class="def-btn btn-form" type="submit">Secure Sign Up <i class="fas fa-arrow-right"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- register end -->
</div>
