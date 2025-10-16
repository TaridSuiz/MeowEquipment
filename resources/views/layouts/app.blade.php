<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Back Office | Laravel')</title>
  {{-- ใส่ asset ของโครงการคุณตามที่มี --}}
  {{-- ตัวอย่าง: @vite(['resources/css/app.css','resources/js/app.js']) --}}
</head>
<body>
  {{-- ถ้ามีเมนูแยกเป็น partials ก็ include ได้ --}}
  {{-- @includeIf('partials.nav') --}}

  <main class="container py-4">
    @yield('content')
  </main>
</body>
</html>
