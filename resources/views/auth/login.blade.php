<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>เข้าสู่ระบบ</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-4 text-center">เข้าสู่ระบบ</h4>

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">อีเมล</label>
              <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-3">
              <label class="form-label">รหัสผ่าน</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="remember" id="remember">
              <label class="form-check-label" for="remember">จำฉันไว้</label>
            </div>
            <button class="btn btn-primary w-100" type="submit">เข้าสู่ระบบ</button>
          </form>

          <hr>
          <div class="text-center">
            ยังไม่มีบัญชี? <a href="{{ route('register') }}">สมัครสมาชิก</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
