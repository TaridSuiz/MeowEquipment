<!doctype html>
<html lang="th">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Back Office | Laravel 12</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
          crossorigin="anonymous">

    {{-- จุดเผื่อใส่ CSS เฉพาะหน้า --}}
    @yield('css_before')

    <style>
      /* ===== Brand & Base ===== */
      :root{
        --brand:#aa7c7a;
        --accent:#d60000;
        --soft:#fbcbcc;
        --ink:#333;
      }
      html,body{ background:#f8f9fa; color:var(--ink); }

      /* ===== Header ===== */
      .site-title{
        font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,"Helvetica Neue",Arial,"Noto Sans Thai",sans-serif;
        font-weight:700;
        font-size:clamp(1.25rem, 2.2vw + .6rem, 1.8rem);
        color:var(--brand);
        text-shadow:2px 2px 4px rgba(0,0,0,.08);
        line-height:1.2;
        transition:transform .25s ease, color .25s ease;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
      }
      .site-title:hover{ transform:scale(1.02); }
      .site-title::after{ content:" 🐾"; font-size:1.2em }

      /* ===== Sidebar (desktop) ===== */
      .sidebar{
        padding:12px;
        background:#fff;
        border-radius:.75rem;
        box-shadow:0 0 1rem rgba(0,0,0,.06);
      }
      .sidebar .list-group-item{
        background:#fff;
        border-radius:.5rem;
        margin-bottom:.75rem;
        padding:.6rem .9rem;
        font-weight:600;
        display:flex; align-items:center; justify-content:space-between;
        transition:background-color .2s ease, transform .12s ease;
      }
      .sidebar .list-group-item:hover{ background:#f7f7f7 }
      .sidebar .list-group-item-action.active{
        background-color:var(--accent); color:#fff; border-color:var(--accent);
      }
      .sidebar .list-group-item.active{
        background-color:#f8f9fa; color:var(--accent);
      }
      .role-badge{ font-size:.75rem; font-weight:700 }

      /* ===== Buttons ===== */
      .btn-outline-primary{
        color:var(--accent); border-color:var(--accent);
      }
      .btn-outline-primary:hover{
        background:var(--accent); color:#fff;
      }

      /* ===== Cards / Alerts ===== */
      .alert{ border-radius:1rem; box-shadow:0 .5rem 1rem rgba(0,0,0,.08) }

      /* ===== Offcanvas (mobile sidebar) ===== */
      .offcanvas-sidebar .list-group-item{ margin-bottom:.5rem }

      /* ===== Responsive touch targets ===== */
      @media (max-width: 575.98px){
        .list-group-item{ padding:.85rem 1rem }
        .btn, .btn-sm{ padding:.6rem .9rem; font-size:.95rem }
      }

      /* iPad landscape tweaks */
      @media (min-width:768px) and (max-width:1024px){
        .sidebar .list-group-item{ padding:.65rem 1rem }
      }

      /* Make content cards breathe a bit */
      .content-wrap{ padding-block:1rem }
    </style>
  </head>
  <body>

    {{-- Top bar (sticky on mobile) --}}
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="alert d-flex align-items-center justify-content-between mt-3 sticky-top"
               style="top:12px; background-color: var(--soft);" role="alert">
            <div class="d-flex align-items-center gap-2">
              {{-- Sidebar toggle appears on < md --}}
              <button class="btn btn-dark d-md-none me-1"
                      type="button"
                      data-bs-toggle="offcanvas"
                      data-bs-target="#mobileSidebar"
                      aria-controls="mobileSidebar"
                      aria-label="เปิดเมนู">
                ☰
              </button>
              <h4 class="m-0 site-title">MeowEquipment || ยินดีต้อนรับ</h4>
            </div>

            <div class="d-flex align-items-center gap-2 flex-shrink-0">
              @auth
                <span class="user-chip d-none d-sm-inline">
                  {{ auth()->user()->name }}
                  @if(auth()->user()->role === 'admin')
                    <span class="badge bg-danger role-badge ms-1">ADMIN</span>
                  @else
                    <span class="badge bg-secondary role-badge ms-1">USER</span>
                  @endif
                </span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                  @csrf
                  <button class="btn btn-outline-dark btn-sm">ออกจากระบบ</button>
                </form>
              @endauth

              @guest
                <a href="{{ route('login') }}" class="btn btn-success btn-sm">เข้าสู่ระบบ</a>
              @endguest
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- header เฉพาะหน้า (ถ้ามี) --}}
    @yield('header')

    <div class="container content-wrap">
      <div class="row g-3">
        {{-- Sidebar column (desktop/tablet) --}}
        <div class="col-md-3 d-none d-md-block">
          <aside class="sidebar">

            {{-- เมนูหลัก (ทุกคนเห็นได้) --}}
            <div class="list-group mb-3">
              <a href="/dashboard"
                class="list-group-item list-group-item-action {{ request()->routeIs('home.index') ? 'active' : '' }}">
                Home
              </a>

              <a href="{{ route('shop.index') }}"
                 class="list-group-item list-group-item-action {{ request()->is('shop*') ? 'active' : '' }}">
                Merchandise
              </a>
              <a href="{{ route('articles.index') }}"
                 class="list-group-item list-group-item-action {{ request()->is('articles*') ? 'active' : '' }}">
                Article
              </a>

              @auth
                <a href="{{ route('wishlist.index') }}"
                   class="list-group-item list-group-item-action {{ request()->is('wishlist*') ? 'active' : '' }}">
                  Wishlist
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="list-group-item list-group-item-action {{ request()->is('profile') ? 'active' : '' }}">
                  Profile
                </a>
              @endauth
            </div>

            {{-- เมนูหลังบ้านสำหรับ ADMIN เท่านั้น --}}
            @auth
            @if(auth()->user()->role === 'admin')
              <div class="list-group mb-3">
                <div class="list-group-item active">Admin Management</div>

                <div class="list-group-item">
                  <span>Admin Users</span>
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.users.create') }}">Edit</a>
                </div>

                <div class="list-group-item">
                  <span>Categories</span>
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.categories.index') }}">Edit</a>
                </div>

                <div class="list-group-item">
                  <span>Merchandise</span>
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.merchandise.index') }}">Edit</a>
                </div>

                <div class="list-group-item">
                  <span>Articles</span>
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.articles.index') }}">Edit</a>
                </div>

                <div class="list-group-item">
                  <span>Dashboard</span>
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.dashboard') }}">View</a>
                </div>
              </div>
            @endif
            @endauth

            @yield('sidebarMenu')
          </aside>
        </div>

        {{-- Mobile Offcanvas Sidebar --}}
        <div class="offcanvas offcanvas-start offcanvas-sidebar d-md-none" tabindex="-1" id="mobileSidebar"
             aria-labelledby="mobileSidebarLabel">
          <div class="offcanvas-header" style="background:var(--soft)">
            <h5 class="offcanvas-title" id="mobileSidebarLabel">เมนู</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="ปิด"></button>
          </div>
          <div class="offcanvas-body">
            <div class="list-group mb-3">
              <a href="/dashboard"
                 class="list-group-item list-group-item-action {{ request()->routeIs('home.index') ? 'active' : '' }}"
                 data-bs-dismiss="offcanvas">Home</a>

              <a href="{{ route('shop.index') }}"
                 class="list-group-item list-group-item-action {{ request()->is('shop*') ? 'active' : '' }}"
                 data-bs-dismiss="offcanvas">Merchandise</a>

              <a href="{{ route('articles.index') }}"
                 class="list-group-item list-group-item-action {{ request()->is('articles*') ? 'active' : '' }}"
                 data-bs-dismiss="offcanvas">Article</a>

              @auth
                <a href="{{ route('wishlist.index') }}"
                   class="list-group-item list-group-item-action {{ request()->is('wishlist*') ? 'active' : '' }}"
                   data-bs-dismiss="offcanvas">Wishlist</a>

                <a href="{{ route('profile.edit') }}"
                   class="list-group-item list-group-item-action {{ request()->is('profile') ? 'active' : '' }}"
                   data-bs-dismiss="offcanvas">Profile</a>
              @endauth
            </div>

            @auth
            @if(auth()->user()->role === 'admin')
              <div class="list-group mb-3">
                <div class="list-group-item active">Admin Management</div>
                <a class="list-group-item d-flex justify-content-between align-items-center"
                   href="{{ route('admin.users.create') }}" data-bs-dismiss="offcanvas">
                   <span>Admin Users</span><span class="btn btn-sm btn-outline-primary">Edit</span>
                </a>
                <a class="list-group-item d-flex justify-content-between align-items-center"
                   href="{{ route('admin.categories.index') }}" data-bs-dismiss="offcanvas">
                   <span>Categories</span><span class="btn btn-sm btn-outline-primary">Edit</span>
                </a>
                <a class="list-group-item d-flex justify-content-between align-items-center"
                   href="{{ route('admin.merchandise.index') }}" data-bs-dismiss="offcanvas">
                   <span>Merchandise</span><span class="btn btn-sm btn-outline-primary">Edit</span>
                </a>
                <a class="list-group-item d-flex justify-content-between align-items-center"
                   href="{{ route('admin.articles.index') }}" data-bs-dismiss="offcanvas">
                   <span>Articles</span><span class="btn btn-sm btn-outline-primary">Edit</span>
                </a>
                <a class="list-group-item d-flex justify-content-between align-items-center"
                   href="{{ route('admin.dashboard') }}" data-bs-dismiss="offcanvas">
                   <span>Dashboard</span><span class="btn btn-sm btn-outline-primary">View</span>
                </a>
              </div>
            @endif
            @endauth

            @yield('sidebarMenu')
          </div>
        </div>

        {{-- Main content --}}
        <div class="col-12 col-md-9">
          @yield('content')
        </div>
      </div>
    </div>

    {{-- Footer --}}
    <footer class="mt-5 mb-2">
      <p class="text-center m-0 small text-muted">by devbanban.com @2025</p>
    </footer>

    @yield('footer')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
            crossorigin="anonymous"></script>

    {{-- จุดเผื่อใส่สคริปต์เฉพาะหน้า --}}
    @yield('js_before')

    {{-- SweetAlert --}}
    @include('sweetalert::alert')
  </body>
</html>
