<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 12 Basic CRUD by devbanban.com 2025</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    @yield('css_before')

    <style>
      body{ padding-top: 88px; }

      .site-title{
        font-family:'Poppins',sans-serif; font-weight:700; font-size:2rem;
        color:#aa7c7a; text-transform:uppercase; letter-spacing:2px;
        text-shadow:2px 2px 4px rgba(0,0,0,.2);
        transition:transform .3s ease,color .3s ease;
      }
      .site-title:hover{ transform:scale(1.06); color:#aa7c7a; }
      .site-title::after{ content:" 🐾"; font-size:1.5rem; }

      .navbar-meow{
        --bg: rgba(251,203,204,.72);
        background:var(--bg)!important;
        backdrop-filter: blur(8px) saturate(160%);
        -webkit-backdrop-filter: blur(8px) saturate(160%);
        border-bottom:1px solid rgba(255,255,255,.5);
        box-shadow:0 .35rem 1rem rgba(0,0,0,.06);
        transition:box-shadow .2s ease, background .2s ease, padding .2s ease;
        padding:.45rem 0;
      }
      .navbar-meow.is-scrolled{
        --bg: rgba(251,203,204,.9);
        box-shadow:0 .6rem 1.4rem rgba(0,0,0,.12);
        padding:.25rem 0;
      }

      .navbar-meow .nav-link{
        position:relative; font-weight:600; color:#6b4040!important;
        padding:.45rem .8rem; border-radius:.6rem;
      }
      .navbar-meow .nav-link:hover{ color:#a14f4f!important; }
      .navbar-meow .nav-link::after{
        content:""; position:absolute; left:.8rem; right:.8rem; bottom:.25rem; height:3px;
        border-radius:3px; background:linear-gradient(90deg,#e26b6b,#f4a9a8);
        transform:scaleX(0); transform-origin:left; transition:transform .18s ease;
      }
      .navbar-meow .nav-link:hover::after,
      .navbar-meow .nav-link.active::after{ transform:scaleX(1); }

      .navbar-meow .form-control{ border-radius:.8rem; border:2px solid #f0d6d6; }
      .navbar-meow .form-control:focus{ border-color:#e26b6b; box-shadow:0 0 0 .25rem rgba(226,107,107,.25); }

      .btn-brand{ background:#e26b6b; border-color:#e26b6b; color:#fff; border-width:2px; border-radius:.8rem; }
      .btn-brand:hover{ background:#d45d5d; border-color:#d45d5d; box-shadow:0 0 0 .25rem rgba(226,107,107,.25); }

      .navbar-meow .dropdown-menu{
        border:0; border-radius:1rem; box-shadow:0 .75rem 1.5rem rgba(0,0,0,.12);
        padding:.35rem; overflow:hidden;
      }
      .navbar-meow .dropdown-item{ border-radius:.6rem; font-weight:600; color:#5a3d3d; }
      .navbar-meow .dropdown-item:hover{ background:#ffe7e7; color:#8f3e3e; }

      .navbar-meow.navbar-light .navbar-toggler{ border:0; }
      .navbar-meow.navbar-light .navbar-toggler-icon{
        filter: brightness(0) saturate(100%) invert(33%) sepia(25%) saturate(800%) hue-rotate(315deg) brightness(90%);
      }
    </style>
  </head>

  <body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-meow fixed-top">
      <div class="container">
        <a class="navbar-brand site-title" href="/dashboard">MeowEquipment</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            <li class="nav-item">
              <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" aria-current="page" href="/dashboard">Home</a>
            </li>

            {{-- เอาเมนู Login ชั้นบนสุดออก เพื่อย้ายไปไว้ใน dropdown --}}
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle {{ request()->routeIs('shop.*') || request()->routeIs('articles.*') ? 'active' : '' }}"
                 href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                BackOffice
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('shop.index') }}">สินค้า</a></li>
                <li><a class="dropdown-item" href="{{ route('articles.index') }}">บทความ</a></li>

                @if(Auth::check())
                  <li><a class="dropdown-item" href="{{ route('wishlist.index') }}">Wishlist</a></li>
                @else
                  <li><a class="dropdown-item" href="{{ route('login') }}">Wishlist</a></li>
                @endif

                <li><hr class="dropdown-divider"></li>

                {{-- 🔁 ตรงนี้แทนที่ "Something else here" ด้วย Login/Logout --}}
                @auth
                  <li>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                      @csrf
                      <button type="submit" class="dropdown-item text-danger">Logout</button>
                    </form>
                  </li>
                @else
                  <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                @endauth
              </ul>
            </li>

          </ul>

          <!-- Search -->
          <form action="/search" method="get" class="d-flex gap-2" role="search">
            <input class="form-control" type="text" name="keyword" placeholder="Search Product Name" aria-label="Search"
                   required value="{{ $keyword ?? ''}}">
            <button class="btn btn-brand" type="submit">Search</button>
          </form>
        </div>
      </div>
    </nav>
    <!-- /Navbar -->

    <div class="container mt-2">
      <div class="row">
        @yield('showProduct')
      </div>
    </div>

    <footer class="mt-5 mb-2">
      <p class="text-center">by devbanban.com @2025</p>
    </footer>

    @yield('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script>
      (function(){
        const nav = document.querySelector('.navbar-meow');
        const onScroll = () => nav && nav.classList.toggle('is-scrolled', window.scrollY > 8);
        window.addEventListener('scroll', onScroll, {passive:true});
        onScroll();
      })();
    </script>

    @yield('js_before')
  </body>
</html>
