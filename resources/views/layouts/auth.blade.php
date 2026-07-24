<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Notaris & PPAT Eka Sulistya</title>
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #4d0011 0%, #2e0108 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-container {
            width: 100%;
            max-width: 480px;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            padding: 2.5rem;
        }

        .auth-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-brand i {
            font-size: 2.5rem;
            color: #800020;
            margin-bottom: 0.5rem;
        }

        .auth-title {
            font-weight: 700;
            color: #0f172a;
            font-size: 1.5rem;
        }

        .form-control-premium {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control-premium:focus {
            border-color: #800020;
            box-shadow: 0 0 0 4px rgba(128, 0, 32, 0.1);
        }

        .btn-premium-primary {
            background: linear-gradient(135deg, #800020 0%, #5a0015 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(128, 0, 32, 0.2);
            transition: all 0.2s ease;
            width: 100%;
        }

        .btn-premium-primary:hover {
            background: linear-gradient(135deg, #b01c38 0%, #800020 100%);
            box-shadow: 0 6px 8px -1px rgba(128, 0, 32, 0.3);
            color: #ffffff;
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        .auth-footer a {
            color: #800020;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        @yield('content')
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
