<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ekos - Salsabila</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <style>
    * { box-sizing: border-box; }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f4ff;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-card {
      background: #fff;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0,0,0,0.12);
      max-width: 900px;
      width: 100%;
    }

    /* ── Left Panel ── */
    .left-panel {
      background: #2451f5;
      color: #fff;
      padding: 50px 40px;
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-end;
      height: 100%;
      min-height: 420px;
    }

    /* Ensure Bootstrap col stretches full height */
    .row.g-0 { align-items: stretch; }
    .col-md-5 { display: flex; flex-direction: column; }
    .col-md-5 .left-panel { flex: 1; }

    /* Decorative blobs */
    .left-panel::before {
      content: '';
      position: absolute;
      width: 180px; height: 180px;
      background: rgba(255,255,255,0.08);
      border-radius: 50%;
      top: -40px; left: -40px;
    }
    .left-panel::after {
      content: '';
      position: absolute;
      width: 120px; height: 120px;
      background: rgba(255,255,255,0.06);
      border-radius: 50%;
      bottom: 60px; right: -30px;
    }

    /* Floating shapes */
    .dot-white {
      position: absolute;
      width: 18px; height: 18px;
      background: #fff;
      border-radius: 50%;
      top: 80px; left: 70px;
    }
    .drop-red {
      position: absolute;
      top: 60px; right: 110px;
    }
    .drop-red svg { width: 28px; }

    .circle-yellow-sm {
      position: absolute;
      width: 55px; height: 55px;
      background: #f5a623;
      border-radius: 50%;
      bottom: 130px; left: 50px;
    }
    .circle-yellow-lg {
      position: absolute;
      width: 75px; height: 75px;
      background: #f5c842;
      border-radius: 50%;
      bottom: 110px; right: 55px;
    }

    /* Card illustration */
    .card-illustration {
      position: relative;
      z-index: 2;
      background: #fff;
      border-radius: 18px;
      padding: 20px 24px;
      width: 220px;
      box-shadow: 0 12px 40px rgba(0,0,0,0.18);
      margin-bottom: 30px;
    }
    .illus-row {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
    }
    .avatar {
      width: 32px; height: 32px;
      border-radius: 50%;
      object-fit: cover;
      flex-shrink: 0;
    }
    .avatar-placeholder {
      width: 32px; height: 32px;
      border-radius: 50%;
      background: linear-gradient(135deg, #f5a623, #f5c842);
      flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; color: #fff; font-weight: 700;
    }
    .lines {
      flex: 1;
    }
    .line {
      height: 7px;
      background: #e8ecf0;
      border-radius: 4px;
      margin-bottom: 5px;
    }
    .line.short { width: 55%; }
    .line.long  { width: 85%; }

    .panel-text {
      position: relative;
      z-index: 2;
      text-align: center;
    }
    .panel-text h2 {
      font-weight: 700;
      font-size: 1.5rem;
      letter-spacing: 0.02em;
      margin-bottom: 8px;
    }
    .panel-text p {
      font-size: 0.88rem;
      color: rgba(255,255,255,0.8);
      line-height: 1.6;
    }

    /* ── Right Panel ── */
    .right-panel {
      padding: 50px 45px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .right-panel h1 {
      font-size: 2rem;
      font-weight: 700;
      color: #1a1a2e;
      margin-bottom: 6px;
    }
    .right-panel .subtitle {
      color: #6b7280;
      font-size: 0.92rem;
      margin-bottom: 32px;
    }

    .form-label {
      font-size: 0.82rem;
      font-weight: 600;
      color: #374151;
      margin-bottom: 6px;
    }

    .form-control {
      border: 1.5px solid #e5e7eb;
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 0.9rem;
      color: #1a1a2e;
      transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus {
      border-color: #2451f5;
      box-shadow: 0 0 0 3px rgba(36,81,245,0.12);
      outline: none;
    }

    .form-check-input:checked {
      background-color: #2451f5;
      border-color: #2451f5;
    }
    .form-check-label {
      font-size: 0.85rem;
      color: #374151;
    }
    .forgot-link {
      font-size: 0.85rem;
      color: #2451f5;
      text-decoration: none;
      font-weight: 600;
    }
    .forgot-link:hover { text-decoration: underline; }

    .btn-login {
      background: #2451f5;
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 13px;
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 0.02em;
      transition: background .2s, transform .1s, box-shadow .2s;
    }
    .btn-login:hover {
      background: #1a3de0;
      box-shadow: 0 6px 20px rgba(36,81,245,0.35);
      transform: translateY(-1px);
    }
    .btn-login:active { transform: translateY(0); }

    .btn-google {
      background: #fff;
      color: #374151;
      border: 1.5px solid #e5e7eb;
      border-radius: 10px;
      padding: 12px;
      font-size: 0.9rem;
      font-weight: 600;
      display: flex; align-items: center; justify-content: center; gap: 10px;
      transition: background .2s, box-shadow .2s;
      text-decoration: none;
    }
    .btn-google:hover {
      background: #f9fafb;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .google-icon {
      width: 20px; height: 20px;
    }

    .signup-text {
      font-size: 0.88rem;
      color: #6b7280;
      text-align: center;
      margin-top: 16px;
    }
    .signup-text a {
      color: #2451f5;
      font-weight: 600;
      text-decoration: none;
    }
    .signup-text a:hover { text-decoration: underline; }

    /* ── Responsive ── */
    @media (max-width: 767px) {
      .left-panel { min-height: 280px; padding: 40px 24px 32px; }
      .right-panel { padding: 36px 24px; }
      .card-illustration { width: 180px; padding: 14px 18px; }
      .right-panel h1 { font-size: 1.6rem; }
    }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="row g-0">

      <!-- LEFT PANEL -->
      <div class="col-md-5">
        <div class="left-panel">
          <!-- Decorative elements -->
          <div class="dot-white"></div>
          <div class="drop-red">
            <svg viewBox="0 0 24 30" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2C12 2 4 10.5 4 17a8 8 0 0016 0C20 10.5 12 2 12 2z" fill="#e74c3c"/>
              <path d="M12 6C12 6 7 13 7 17a5 5 0 0010 0C17 13 12 6 12 6z" fill="#c0392b" opacity="0.5"/>
            </svg>
          </div>
          <div class="circle-yellow-sm"></div>
          <div class="circle-yellow-lg"></div>

          <!-- Card illustration -->
          <div class="card-illustration">
            <div class="illus-row">
              <div class="avatar-placeholder">A</div>
              <div class="lines"><div class="line long"></div><div class="line short"></div></div>
            </div>
            <div class="illus-row">
              <div class="avatar-placeholder" style="background:linear-gradient(135deg,#667eea,#764ba2)">B</div>
              <div class="lines"><div class="line long"></div><div class="line short"></div></div>
            </div>
            <div class="illus-row mb-0">
              <div class="avatar-placeholder" style="background:linear-gradient(135deg,#f5a623,#f97316)">C</div>
              <div class="lines"><div class="line long"></div><div class="line short"></div></div>
            </div>
          </div>

          <!-- Text -->
          <div class="panel-text">
            <h2>E-Kos</h2>
            <p>Sistem Manajemen Kos-kosan Salsabila</p>
          </div>
        </div>
      </div>

      <!-- RIGHT PANEL -->
      <div class="col-md-7">
        <div class="right-panel">
          <h1>Hello, Again</h1>
          <p class="subtitle">We are happy to have you back.</p>

          <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="Masukan Email"
                    required
                />
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Masukkan password"
                    required
                />
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Login Button -->
            <div class="d-grid mt-3">
                <button type="submit" class="btn btn-login">
                    Login
                </button>
            </div>
            </form>
        </div>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
