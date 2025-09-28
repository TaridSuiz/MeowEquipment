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
      .sidebar .list-group-item.active {
        background-color:#0d6efd;
        border-color:#0d6efd;
      }
      .sidebar .list-group-item {
        display:flex; align-items:center; justify-content:space-between;
      }
      .user-chip {
        font-size:.95rem; font-weight:600;
      }
      .role-badge {
        font-size:.7rem;
      }
    </style>
  </head>
  <body>

    {{-- แถบหัว --}}
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="alert alert-success d-flex align-items-center justify-content-between mt-3" role="alert">
            <h4 class="m-0">Back Office || Laravel 12 || ยินดีต้อนรับ</h4>

            {{-- แสดงสถานะล็อกอิน + ปุ่มที่จำเป็น --}}
            <div class="d-flex align-items-center gap-2">
              @auth
                <span class="user-chip">
                  {{ auth()->user()->name }}
                  @if(auth()->user()->role === 'admin')
                    <span class="badge bg-danger role-badge ms-1">ADMIN</span>
                  @else
                    <span class="badge bg-secondary role-badge ms-1">USER</span>
                  @endif
                </span>

                {{-- ปุ่มไปหน้าโปรไฟล์ (ผู้ใช้แก้โปรไฟล์ตัวเอง) --}}
                <a href="{{ route('profile.edit') }}"
                   class="btn btn-outline-primary btn-sm">โปรไฟล์ของฉัน</a>

                {{-- ปุ่มไปหน้าร้าน/บทความ (ฝั่งดูข้อมูลสาธารณะ) --}}
                <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary btn-sm">ไปหน้าสินค้า</a>
                <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary btn-sm">บทความ</a>

                {{-- ปุ่มออกจากระบบ --}}
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                  @csrf
                  <button class="btn btn-outline-dark btn-sm">ออกจากระบบ</button>
                </form>
              @endauth

              @guest
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">เข้าสู่ระบบ</a>
              @endguest
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- header เฉพาะหน้า (ถ้ามี) --}}
    @yield('header')

    <div class="container">
      <div class="row">

        {{-- เมนูด้านซ้าย --}}
        <div class="col-md-3 sidebar">

          {{-- เมนูหลัก (ทุกคนเห็นได้) --}}
          <div class="list-group mb-3">
            <a href="{{ url('/') }}"
               class="list-group-item list-group-item-action {{ request()->is('/') ? 'active' : '' }}">
              Home
            </a>

            {{-- ฝั่ง public (ดูของหน้าเว็บ) --}}
            <a href="{{ route('shop.index') }}"
               class="list-group-item list-group-item-action {{ request()->is('shop*') ? 'active' : '' }}">
              สินค้า (หน้าเว็บ)
            </a>
            <a href="{{ route('articles.index') }}"
               class="list-group-item list-group-item-action {{ request()->is('articles*') ? 'active' : '' }}">
              บทความ (หน้าเว็บ)
            </a>

            @auth
              {{-- เมนูผู้ใช้ที่ล็อกอิน (wishlist/โปรไฟล์) --}}
              <a href="{{ route('wishlist.index') }}"
                 class="list-group-item list-group-item-action {{ request()->is('wishlist*') ? 'active' : '' }}">
                Wishlist
              </a>
              <a href="{{ route('profile.edit') }}"
                 class="list-group-item list-group-item-action {{ request()->is('profile') ? 'active' : '' }}">
                โปรไฟล์ของฉัน
              </a>

              {{-- ตาม requirement: เปรียบเทียบให้เฉพาะคนล็อกอินใช้ --}}
              <a href="{{ route('shop.compare', ['items[]'=>1,'items[]'=>2]) }}"
                 class="list-group-item list-group-item-action {{ request()->is('compare') ? 'active' : '' }}">
                เปรียบเทียบสินค้า (เลือก 2 รายการ)
              </a>
            @endauth
          </div>

          {{-- เมนูหลังบ้านสำหรับ ADMIN เท่านั้น --}}
          @auth
          @if(auth()->user()->role === 'admin')
            <div class="list-group">
              <div class="list-group-item bg-light fw-semibold">Admin Management</div>

              {{-- Users --}}
              <a href="{{ route('admin.users.index') }}"
                 class="list-group-item list-group-item-action {{ request()->is('admin/users*') ? 'active' : '' }}">
                Users
                <span>
                  <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-outline-primary">Add</a>
                </span>
              </a>

              {{-- Categories --}}
              <a href="{{ route('admin.categories.index') }}"
                 class="list-group-item list-group-item-action {{ request()->is('admin/categories*') ? 'active' : '' }}">
                Categories
                <span>
                  <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-outline-primary">Add</a>
                </span>
              </a>

              {{-- Merchandise --}}
              <a href="{{ route('admin.merchandise.index') }}"
                 class="list-group-item list-group-item-action {{ request()->is('admin/merchandise*') ? 'active' : '' }}">
                Merchandise
                <span>
                  <a href="{{ route('admin.merchandise.create') }}" class="btn btn-sm btn-outline-primary">Add</a>
                </span>
              </a>

              {{-- Articles (จัดการหลังบ้าน) --}}
              <a href="{{ route('admin.articles.index') }}"
                 class="list-group-item list-group-item-action {{ request()->is('admin/articles*') ? 'active' : '' }}">
                Articles
                <span>
                  <a href="{{ route('admin.articles.create') }}" class="btn btn-sm btn-outline-primary">Add</a>
                </span>
              </a>

              {{-- Review moderation (ลบ/ตรวจ) --}}
              <a href="{{ route('admin.dashboard') }}"
                 class="list-group-item list-group-item-action {{ request()->is('admin') ? 'active' : '' }}">
                Review Moderation
              </a>
            </div>
          @endif
          @endauth

          @yield('sidebarMenu')
        </div>

        {{-- เนื้อหาหลัก --}}
        <div class="col-md-9">
          @yield('content')
        </div>

      </div>
    </div>

    {{-- ท้ายหน้า --}}
    <footer class="mt-5 mb-2">
      <p class="text-center">by devbanban.com @2025</p>
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
