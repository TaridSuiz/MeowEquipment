<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>สมัครสมาชิก</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-4 text-center">สมัครสมาชิก</h4>

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">ชื่อ</label>
              <input type="text" name="name" class="form-control" value="{{ old('name') }}" required minlength="3">
            </div>
            <div class="mb-3">
              <label class="form-label">อีเมล</label>
              <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label">รหัสผ่าน</label>
              <input type="password" name="password" class="form-control" required minlength="8">
              <div class="form-text">อย่างน้อย 8 ตัว และต้องมี a-z, A-Z และตัวเลข</div>
            </div>
            <div class="mb-3">
              <label class="form-label">ยืนยันรหัสผ่าน</label>
              <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button class="btn btn-success w-100" type="submit">สมัครสมาชิก</button>
          </form>

          <hr>
          <div class="text-center">
            มีบัญชีแล้ว? <a href="{{ route('login') }}">เข้าสู่ระบบ</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
