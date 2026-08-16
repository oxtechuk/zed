<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" dir="{{ App::getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('الصفحة غير موجودة') }} — Zad Capital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Cairo', sans-serif;
            background: #0a0a0a; color: #fff;
            min-height: 100vh; display: flex;
            align-items: center; justify-content: center;
            overflow-x: hidden; position: relative;
        }

        #particles-canvas { position: fixed; inset: 0; z-index: 0; pointer-events: none; }
        .orb {
            position: fixed; border-radius: 50%; filter: blur(90px); opacity: 0.18; pointer-events: none;
            animation: orbFloat 10s ease-in-out infinite;
        }
        .orb--1 {
            width: 600px; height: 600px;
            background: radial-gradient(circle, #ED1C24, transparent);
            top: -200px; {{ App::getLocale() == 'ar' ? 'left' : 'right' }}: -100px;
            animation-delay: 0s;
        }
        .orb--2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, #8A1217, transparent);
            bottom: -100px; {{ App::getLocale() == 'ar' ? 'right' : 'left' }}: -50px;
            animation-delay: 5s;
        }
        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.05); }
        }

        .err404-inner {
            position: relative; z-index: 1;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 40px 24px; text-align: center; max-width: 640px; width: 100%;
            animation: fadeUp 0.8s ease-out;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .error-code {
            font-size: clamp(90px, 16vw, 160px);
            font-weight: 900; line-height: 1; letter-spacing: -6px;
            display: inline-block; margin-bottom: 8px;
        }
        .error-code__digit {
            display: inline-block;
            animation: digitBounce 2.5s ease-in-out infinite;
        }
        .error-code__digit:nth-child(1) { animation-delay: 0s; }
        .error-code__digit:nth-child(2) {
            background: linear-gradient(135deg, #ED1C24, #ff6b6b, #ED1C24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 30px rgba(237,28,36,0.8));
            animation-delay: 0.2s;
        }
        .error-code__digit:nth-child(3) { animation-delay: 0.4s; }
        @keyframes digitBounce {
            0%, 100% { transform: translateY(0); }
            40% { transform: translateY(-14px); }
            60% { transform: translateY(-7px); }
        }

        .err-divider {
            width: 60px; height: 3px;
            background: linear-gradient(90deg, #ED1C24, #8A1217);
            border-radius: 2px; margin: 16px auto 20px;
        }

        .error-title {
            font-size: clamp(20px, 4vw, 32px);
            font-weight: 900; color: #fff; margin-bottom: 12px; line-height: 1.35;
        }
        .error-title span {
            background: linear-gradient(135deg, #ED1C24, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .error-subtitle {
            font-size: clamp(14px, 2vw, 16px);
            color: rgba(255,255,255,0.6);
            max-width: 480px; margin: 0 auto 28px; line-height: 1.8;
        }

        .err-btn-group {
            display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; margin-bottom: 24px;
        }
        .btn-404 {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 13px 28px; border-radius: 50px;
            font-family: inherit; font-size: 14px; font-weight: 800;
            text-decoration: none; transition: all 0.3s ease; cursor: pointer;
        }
        .btn-404--primary {
            background: linear-gradient(135deg, #ED1C24, #8A1217);
            color: #fff; box-shadow: 0 8px 24px rgba(237,28,36,0.35); border: none;
        }
        .btn-404--primary:hover {
            color: #fff; transform: translateY(-2px); box-shadow: 0 12px 32px rgba(237,28,36,0.5);
        }
        .btn-404--ghost {
            background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.85);
            border: 1.5px solid rgba(255,255,255,0.15); backdrop-filter: blur(8px);
        }
        .btn-404--ghost:hover {
            color: #fff; background: rgba(255,255,255,0.13);
            border-color: rgba(255,255,255,0.3); transform: translateY(-2px);
        }

        .quick-links {
            display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;
        }
        .quick-link {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 18px; border-radius: 50px; font-size: 13px; font-weight: 700;
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.65); text-decoration: none; transition: all 0.25s ease;
        }
        .quick-link:hover {
            background: rgba(237,28,36,0.15); border-color: rgba(237,28,36,0.4); color: #fff;
        }
        .quick-link i { font-size: 14px; color: #ED1C24; }
    </style>
</head>
<body>
    <div class="orb orb--1"></div>
    <div class="orb orb--2"></div>
    <canvas id="particles-canvas"></canvas>

    <div class="err404-inner">
        <div class="error-code" aria-label="404">
            <span class="error-code__digit">4</span>
            <span class="error-code__digit">0</span>
            <span class="error-code__digit">4</span>
        </div>

        <div class="err-divider"></div>

        <h1 class="error-title">
            {{ __('عُذراً! هذه الصفحة') }} <span>{{ __('غير موجودة') }}</span>
        </h1>

        <p class="error-subtitle">
            {{ __('يبدو أن هذه الصفحة انطلقت بسرعة كبيرة ولم نتمكن من العثور عليها. يمكنك العودة للصفحة الرئيسية أو تصفح سياراتنا المتاحة.') }}
        </p>

        <div class="err-btn-group">
            <a href="{{ route('store.home') }}" class="btn-404 btn-404--primary">
                <i class="bi bi-house-door-fill"></i>
                {{ __('الصفحة الرئيسية') }}
            </a>
            <a href="{{ route('store.cars') }}" class="btn-404 btn-404--ghost">
                <i class="bi bi-car-front-fill"></i>
                {{ __('تصفح السيارات') }}
            </a>
        </div>

        <div class="quick-links">
            <a href="{{ url('/offers') }}" class="quick-link">
                <i class="bi bi-tag-fill"></i> {{ __('العروض') }}
            </a>
            <a href="{{ url('/blog') }}" class="quick-link">
                <i class="bi bi-journal-text"></i> {{ __('المقالات') }}
            </a>
            <a href="{{ url('/about') }}" class="quick-link">
                <i class="bi bi-info-circle-fill"></i> {{ __('من نحن') }}
            </a>
            <a href="{{ url('/finance-calculator') }}" class="quick-link">
                <i class="bi bi-calculator-fill"></i> {{ __('الحاسبة') }}
            </a>
        </div>
    </div>

    <script>
    (function () {
        const canvas = document.getElementById('particles-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let W, H, particles = [];
        function resize() {
            W = canvas.width = window.innerWidth;
            H = canvas.height = window.innerHeight;
        }
        function rand(a, b) { return a + Math.random() * (b - a); }
        function mk() {
            return {
                x: rand(0, W), y: rand(0, H),
                r: rand(1, 3), dx: rand(-0.4, 0.4), dy: rand(-0.8, -0.2),
                alpha: rand(0.1, 0.45),
                color: Math.random() > 0.6 ? '#ED1C24' : '#ffffff',
            };
        }
        function init() {
            resize(); particles = [];
            const n = Math.min(80, Math.floor(W * H / 14000));
            for (let i = 0; i < n; i++) particles.push(mk());
        }
        function tick() {
            ctx.clearRect(0, 0, W, H);
            for (const p of particles) {
                ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = p.color; ctx.globalAlpha = p.alpha;
                ctx.fill(); p.x += p.dx; p.y += p.dy; p.alpha -= 0.0012;
                if (p.alpha <= 0 || p.y < -10) {
                    Object.assign(p, mk()); p.y = H + 5; p.alpha = rand(0.1, 0.45);
                }
            }
            ctx.globalAlpha = 1;
            requestAnimationFrame(tick);
        }
        window.addEventListener('resize', init);
        init(); tick();
    })();
    </script>
</body>
</html>
