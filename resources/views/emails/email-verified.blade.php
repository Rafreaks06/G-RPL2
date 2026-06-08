<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - RPL IBSG</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.10);
            max-width: 460px;
            width: 100%;
            overflow: hidden;
            text-align: center;
        }

        .card-header {
            background-color: #1a3a6b;
            padding: 28px 32px 20px;
        }

        .card-header img {
            width: 52px;
            height: auto;
            display: block;
            margin: 0 auto 10px;
        }

        .card-header .kampus-name {
            font-size: 12px;
            font-weight: bold;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-header .kampus-sub {
            font-size: 15px;
            font-weight: bold;
            color: #e8b84b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-divider {
            border: none;
            border-top: 2px solid #e8b84b;
            margin: 14px 0 0;
        }

        .card-body {
            padding: 36px 36px 28px;
        }

        .icon {
            font-size: 52px;
            margin-bottom: 16px;
            line-height: 1;
        }

        .icon.success { color: #1a6b2f; }
        .icon.warning { color: #c0392b; }

        .card-body h2 {
            font-size: 18px;
            font-weight: bold;
            color: #1a3a6b;
            margin-bottom: 10px;
        }

        .card-body p {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 8px;
        }

        .btn {
            display: inline-block;
            margin-top: 24px;
            background-color: #1a3a6b;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            padding: 11px 32px;
            border-radius: 5px;
            letter-spacing: 0.5px;
        }

        .card-footer {
            background-color: #f4f7fb;
            border-top: 3px solid #1a3a6b;
            padding: 12px 20px;
        }

        .card-footer p {
            font-size: 11px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="card">

        <div class="card-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Global">
            <div class="kampus-name">Institut Teknologi &amp; Bisnis</div>
            <div class="kampus-sub">Bina Sarana Global</div>
            <hr class="header-divider">
        </div>

        <div class="card-body">
            <div class="icon {{ $type }}">{{ $icon }}</div>
            <h2>{{ $title }}</h2>
            <p>{{ $message }}</p>
            @if(isset($sub))
                <p>{{ $sub }}</p>
            @endif
            <a href="{{ url('/login') }}" class="btn">Masuk ke Akun</a>
        </div>

        <div class="card-footer">
            <p>Institut Teknologi &amp; Bisnis Bina Sarana Global &mdash; Sistem RPL</p>
        </div>

    </div>
</body>
</html>