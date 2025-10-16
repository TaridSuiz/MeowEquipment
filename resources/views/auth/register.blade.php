<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>สมัครสมาชิก • MeowEquipment</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root{
      --brand:#e26b6b; --brand-2:#f4a9a8; --ink:#2a2a2a; --muted:#6b6f76;
      --bg:#fffaf9; --card:#ffffff; --ring: rgba(226,107,107,.28);
      --shadow: 0 .5rem 1.25rem rgba(0,0,0,.08); --shadow-lg: 0 1rem 2rem rgba(0,0,0,.12);
      --radius: 1rem;
    }
    body{
      min-height:100vh; background:
      radial-gradient(1200px 600px at -10% -10%, #ffe7e7 0, transparent 60%),
      radial-gradient(1200px 600px at 110% 110%, #ffe7e7 0, transparent 60%),
      var(--bg);
      color:var(--ink);
      display:flex; align-items:center;
    }
    .brand-link{ font-family:'Poppins',sans-serif; font-weight:800; color:#aa7c7a; text-decoration:none }
    .brand-link:hover{ color:#d06f6f }
    .auth-card{
      border:0; border-radius: calc(var(--radius) + .25rem);
      background: rgba(255,255,255,.86); backdrop-filter: blur(8px) saturate(130%);
      box-shadow: var(--shadow);
    }
    .form-control{ border-radius:.85rem; border:2px solid #f0d6d6; padding:.65rem .9rem; }
    .form-control:focus{ border-color:var(--brand); box-shadow:0 0 0 .25rem var(--ring); }
    .btn-brand{ background:var(--brand); border-color:var(--brand); color:#fff; border-width:2px; border-radius:.9rem; }
    .btn-brand:hover{ background:#d45d5d; border-color:#d45d5d; box-shadow:0 0 0 .25rem var(--ring) }
    .form-text a{ color:#b24f4f; text-decoration:none }
    .form-text a:hover{ text-decoration:underline }
    .invalid-feedback{ display:block }
    .toggle-eye{ cursor:pointer; user-select:none }
    .divider{ height:1px; background:linear-gradient(90deg,#ffe1e1,transparent) }
  </style>
</head>
<body>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-7 col-lg-6 col-xl-5">
      <div class="auth-card p-4 p-md-5">
        <div class="text-center mb-3">
          <a href="/dashboard" class="brand-link fs-3">MeowEquipment 🐾</a>
          <div class="text-muted">สมัครบัญชีใหม่ในไม่กี่ขั้นตอน</div>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger">
            <div class="fw-semibold mb-1">พบข้อผิดพลาด:</div>
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('register') }}" novalidate>
          @csrf

          <div class="mb-3">
            <label class="form-label">ชื่อที่แสดง</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   placeholder="เช่น มิวอีควิปเมนต์" value="{{ old('name') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">อีเมล</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   placeholder="you@example.com" value="{{ old('email') }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label d-flex justify-content-between">
              <span>รหัสผ่าน</span>
              <span class="toggle-eye small text-muted" onclick="togglePw('password')">แสดง/ซ่อน</span>
            </label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="อย่างน้อย 8 ตัวอักษร" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-4">
            <label class="form-label d-flex justify-content-between">
              <span>ยืนยันรหัสผ่าน</span>
              <span class="toggle-eye small text-muted" onclick="togglePw('password_confirmation')">แสดง/ซ่อน</span>
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control" placeholder="พิมพ์รหัสผ่านซ้ำ" required>
          </div>

          <button class="btn btn-brand w-100" type="submit">สมัครสมาชิก</button>
        </form>

        <div class="divider my-4"></div>

        <div class="text-center form-text">
          มีบัญชีแล้ว? <a href="{{ route('login') }}">เข้าสู่ระบบ</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function togglePw(id){
    const el = document.getElementById(id);
    el.type = (el.type === 'password') ? 'text' : 'password';
  }
</script>
</body>
</html>
