<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kasir Fwbi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <style>
        :root { --primary: linear-gradient(135deg, #667eea, #764ba2); }
        body {
            background: radial-gradient(at 0% 0%, #121317 0, transparent 50%), 
                        radial-gradient(at 50% 0%, #2e3d61 0, transparent 50%), 
                        radial-gradient(at 100% 0%, #47273d 0, transparent 50%) #f8f9fa;
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.85); border-radius: 24px; backdrop-filter: blur(16px);
            width: 100%; max-width: 420px; padding: 3rem 2.5rem; transition: none;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        }
        .brand-logo {
            width: 65px; height: 65px; background: var(--primary); color: #fff;
            border-radius: 25px; display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; margin: 0 auto;
        }
        .input-group {
            background: #ffffff; border-radius: 15px; border: 1.5px solid #eee; transition: none;
            overflow: hidden;
        }
        .input-group:focus-within { border-color: #c2a4e0; box-shadow: 0 0 0 4px rgba(118,75,162,0.1); }
        .form-control { border: none; padding: 14px; font-size: 0.95rem; }
        .form-control:focus { box-shadow: none; }
        .btn-login {
            background: var(--primary); border: none; border-radius: 15px; padding: 14px;
            font-weight: 700; color: #ffffff; transition: none;
        }
        .toggle-password { cursor: pointer; padding-right: 1.2rem; color: #a0aec0; display: flex; align-items: center; }
        .alert-custom { background: #fff5f5; border-left: 4px solid #feb2b2; color: #c53030; border-radius: 12px; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-logo">
        <i class="fa-brands fa-optin-monster "></i>
    </div>

    <div class="text-center mb-4">
        <h2 class="fw-bold mb-1">KASIR FWBI</h2>
        <p class="text-muted small">Silakan Masuk dan Mulai Pekerjaan Anda</p>
    </div>

    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == "gagal"): ?>
        <div class="alert alert-custom small py-2">
            <i class="fas fa-circle-xmark me-2"></i> Username atau password salah.
        </div>
    <?php endif; ?>

    <form action="auth.php" method="POST">
        <div class="mb-3">
            <label class="form-label small fw-bold ms-1"> Username / Email</label>
            <div class="input-group">
                <span class="ps-3 text-muted"><i class="fa-regular fa-envelope"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold ms-1">Password</label>
            <div class="input-group">
                <span class="ps-3 text-muted"><i class="fa-solid fa-shield-halved"></i></span>
                <input type="password" name="password" id="pass" class="form-control" placeholder="••••••••" required>
                <div class="toggle-password" onclick="togglePass()">
                    <i class="fa-regular fa-eye" id="eye"></i>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-login w-100">
            MASUK KE DASHBOARD <i class="fa-solid fa-arrow-right ms-2"></i>
        </button>
    </form>

    <div class="text-center mt-5">
        <p class="text-muted mb-0" style="font-size: 11px;">
            &copy; 2026 <span style="color: #764ba2">Kasir Fwbi</span>. selamat bekerja.
        </p>
    </div>
</div>

<script>
    const togglePass = () => {
        const p = document.getElementById('pass'), i = document.getElementById('eye');
        p.type = p.type === 'password' ? 'text' : 'password';
        i.classList.toggle('fa-eye'); i.classList.toggle('fa-eye-slash');
    };
</script>
</body>
</html>