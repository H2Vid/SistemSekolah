<div class="header" id="header">
        <div class="bottom">
          <div class="container">
            <div class="row justify-content-between">
              <div class="col-xl-3 col-lg-2 d-xl-flex d-lg-flex d-block align-items-center">
                <div class="row">
                  <div class="col-6 d-xl-none d-lg-none d-block">
                    <button class="navbar-toggler" type="button">
                      <span class="dag"></span>
                      <span class="dag2"></span>
                      <span class="dag3"></span>
                    </button>
                  </div>
                  <div class="col-xl-12 col-lg-12 col-6 d-xl-block d-lg-block d-flex align-items-center justify-content-end">
                    <div class="logo">
                      <a href="/live/">
                        <img src="{{ asset('assets/fe') }}/images/logo.png" alt="">
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-9 col-lg-10">
                <div class="mainmenu">
                  <div class="d-xl-none d-lg-none d-block">
                    <div class="user-profile">
                      <div class="part-img">
                        <img src="{{ asset('assets/fe') }}/images/member-1.jpg" alt="">
                      </div>
                      <div class="user-info">
                        <span class="user-name">Sadwel Eunton</span>
                        <span class="user-balance">Bal : $202.25</span>
                      </div>
                      <div class="log-out-area">
                        <a href="#">Log out</a>
                      </div>
                    </div>
                  </div>
                  <nav class="navbar navbar-expand-lg">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                      <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                          <a class="nav-link" href="{{ url('/') }}/home">Home</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="{{ url('/') }}/product">Produk</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="{{ url('/') }}/task">Tugas</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="{{ url('/') }}/vip">VIP</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="{{ url('/') }}/contact">Kontak</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="{{ url('/') }}/login">Masuk</a>
                        </li>
                        <li class="nav-item join-now-btn">
                          <a class="nav-link" href="{{ url('/') }}/register">Daftar</a>
                        </li>
                        <li class="nav-item dropdown show">
                          <a class="nav-link dropdown-toggle" href="#" role="button" id="db-Dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Bahasa </a>
                          <div class="dropdown-menu" aria-labelledby="db-Dropdown">
                            <a class="dropdown-item" href="{{ url('/') }}/language/1"><img src="{{ asset('assets/fe') }}/images/indonesia.png" style="width: 30px;padding-right: 10px;">Indonesia</a>
                            <a class="dropdown-item" href="{{ url('/') }}/language/2"><img src="{{ asset('assets/fe') }}/images/united-kingdom.png" style="width: 30px;padding-right: 10px;">English</a>
                            <a class="dropdown-item" href="{{ url('/') }}/language/3"><img src="{{ asset('assets/fe') }}/images/rusia.png" style="width: 30px;padding-right: 10px;">Rusia</a>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>