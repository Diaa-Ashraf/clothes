<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ settings('site_name', 'دار الأناقة للأزياء') }} - @yield('title', 'تسجيل الدخول')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Alexandria:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'IBM Plex Sans Arabic', 'Alexandria', sans-serif;
            min-height: 100vh;
            background-color: #F3E9D2;
            color: #2B2118;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 950px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #FBF6EA;
            border: 1px solid #CDB78F;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px -10px rgba(43, 33, 24, 0.12);
        }

        .auth-banner {
            background-color: #382416;
            color: #FBF6EA;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
            border-inline-end: 1px solid #40372E;
        }

        .auth-banner .logo-icon {
            width: 72px;
            height: 72px;
            background: #25170D;
            border: 1px solid #CDB78F;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            font-size: 2rem;
        }

        .auth-banner h1 {
            color: #FBF6EA;
            font-size: 1.85rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .auth-banner p {
            color: #CDB78F;
            font-size: 0.95rem;
            line-height: 1.6;
            max-width: 300px;
        }

        .auth-form-section {
            padding: 50px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #FBF6EA;
        }

        .auth-form-header {
            margin-bottom: 28px;
        }

        .auth-form-header h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1A110A;
            margin-bottom: 6px;
        }

        .auth-form-header p {
            color: #6B5642;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            color: #2B2118;
            font-weight: 500;
            margin-bottom: 6px;
            font-size: 0.85rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #CDB78F;
            border-radius: 10px;
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.2s ease;
            background: #F3E9D2;
            color: #2B2118;
        }

        .form-control:focus {
            outline: none;
            border-color: #382416;
            background: #FBF6EA;
            box-shadow: 0 0 0 3px rgba(56, 36, 22, 0.12);
        }

        .form-control::placeholder {
            color: #8F7B63;
        }

        .form-control.is-invalid {
            border-color: #9E3B2E;
        }

        .invalid-feedback {
            color: #9E3B2E;
            font-size: 0.8rem;
            margin-top: 5px;
            display: block;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .form-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #382416;
            cursor: pointer;
        }

        .form-check label {
            color: #6B5642;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 36px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #382416;
            color: #FBF6EA;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #25170D;
            transform: translateY(-1px);
        }

        .auth-footer {
            margin-top: 22px;
            text-align: center;
        }

        .auth-footer p {
            color: #6B5642;
            font-size: 0.85rem;
        }

        .auth-footer a {
            color: #A9552F;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .auth-footer a:hover {
            color: #382416;
        }

        .forgot-password {
            text-align: left;
            margin-bottom: 20px;
        }

        .forgot-password a {
            color: #A9552F;
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-password a:hover {
            color: #382416;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-container {
                grid-template-columns: 1fr;
                max-width: 440px;
            }

            .auth-banner {
                display: none;
            }

            .auth-form-section {
                padding: 35px 25px;
            }
        }

        /* Alert Messages */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 18px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.85rem;
        }

        .alert-danger {
            background: #EADFC8;
            border: 1px solid #9E3B2E;
            color: #9E3B2E;
        }

        .alert-success {
            background: #EADFC8;
            border: 1px solid #5F6B3A;
            color: #5F6B3A;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #8F7B63;
            cursor: pointer;
            padding: 0;
        }

        .password-toggle:hover {
            color: #382416;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
