<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Ni modo</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Black+Han+Sans&family=Space+Mono:wght@400;700&display=swap');

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg: #0a0a0a;
            --red: #ff2d2d;
            --yellow: #ffe600;
            --white: #f0f0f0;
            --gray: #1a1a1a;
        }

        body {
            background: var(--bg);
            color: var(--white);
            font-family: 'Space Mono', monospace;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            cursor: crosshair;
        }

        /* Scanline overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(0,0,0,0.07) 2px,
                rgba(0,0,0,0.07) 4px
            );
            pointer-events: none;
            z-index: 100;
        }

        /* Noise texture */
        body::after {
            content: '';
            position: fixed;
            inset: -50%;
            width: 200%;
            height: 200%;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            opacity: 0.4;
            z-index: 99;
        }

        .scene {
            position: relative;
            text-align: center;
            padding: 2rem;
            z-index: 10;
        }

        /* Big 404 */
        .big-number {
            font-family: 'Black Han Sans', sans-serif;
            font-size: clamp(140px, 25vw, 280px);
            line-height: 0.85;
            color: transparent;
            -webkit-text-stroke: 3px var(--red);
            letter-spacing: -8px;
            position: relative;
            display: inline-block;
            animation: glitch 3.5s infinite;
            user-select: none;
        }

        .big-number::before,
        .big-number::after {
            content: '404';
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            font-family: 'Black Han Sans', sans-serif;
            font-size: inherit;
            letter-spacing: inherit;
        }

        .big-number::before {
            color: var(--red);
            opacity: 0.6;
            animation: glitch-1 3.5s infinite;
            clip-path: polygon(0 0, 100% 0, 100% 35%, 0 35%);
        }

        .big-number::after {
            color: var(--yellow);
            opacity: 0.4;
            animation: glitch-2 3.5s infinite;
            clip-path: polygon(0 65%, 100% 65%, 100% 100%, 0 100%);
        }

        @keyframes glitch {
            0%, 90%, 100% { transform: translate(0); }
            91% { transform: translate(-4px, 2px); }
            93% { transform: translate(4px, -2px); }
            95% { transform: translate(-2px, 0); }
            97% { transform: translate(2px, 1px); }
        }

        @keyframes glitch-1 {
            0%, 90%, 100% { transform: translate(0); opacity: 0; }
            91% { transform: translate(-8px, 0); opacity: 0.6; }
            93% { transform: translate(6px, 0); opacity: 0.6; }
            95%, 96% { transform: translate(0); opacity: 0; }
        }

        @keyframes glitch-2 {
            0%, 88%, 100% { transform: translate(0); opacity: 0; }
            89% { transform: translate(8px, 2px); opacity: 0.5; }
            91% { transform: translate(-4px, -1px); opacity: 0.5; }
            93% { transform: translate(0); opacity: 0; }
        }

        /* Tape label */
        .tape {
            background: var(--yellow);
            color: #000;
            font-family: 'Black Han Sans', sans-serif;
            font-size: clamp(14px, 2.5vw, 20px);
            letter-spacing: 6px;
            padding: 6px 32px;
            display: inline-block;
            transform: rotate(-1.5deg);
            margin: -10px 0 24px;
            position: relative;
            z-index: 2;
            text-transform: uppercase;
        }

        .tape::before, .tape::after {
            content: '';
            position: absolute;
            top: 0; bottom: 0;
            width: 18px;
            background: rgba(0,0,0,0.12);
        }
        .tape::before { left: 0; }
        .tape::after { right: 0; }

        /* Messages */
        .message-stack {
            margin: 28px 0 36px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: center;
        }

        .msg {
            font-size: clamp(11px, 1.6vw, 14px);
            color: #888;
            letter-spacing: 1px;
        }

        .msg-highlight {
            font-size: clamp(14px, 2vw, 18px);
            color: var(--white);
            font-weight: 700;
        }

        .msg-red {
            color: var(--red);
            font-size: clamp(12px, 1.8vw, 16px);
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        /* Terminal blink */
        .cursor {
            display: inline-block;
            width: 10px;
            height: 1.1em;
            background: var(--red);
            vertical-align: text-bottom;
            animation: blink 1s step-end infinite;
            margin-left: 3px;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        /* Button */
        .btn-home {
            display: inline-block;
            padding: 14px 40px;
            background: transparent;
            border: 2px solid var(--red);
            color: var(--red);
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            transition: color 0.2s;
            cursor: pointer;
        }

        .btn-home::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--red);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.25s ease;
            z-index: -1;
        }

        .btn-home:hover { color: #000; }
        .btn-home:hover::before { transform: scaleX(1); }

        /* Corner decorations */
        .corner {
            position: fixed;
            width: 60px;
            height: 60px;
            border-color: var(--red);
            border-style: solid;
            opacity: 0.3;
        }
        .corner-tl { top: 20px; left: 20px; border-width: 2px 0 0 2px; }
        .corner-tr { top: 20px; right: 20px; border-width: 2px 2px 0 0; }
        .corner-bl { bottom: 20px; left: 20px; border-width: 0 0 2px 2px; }
        .corner-br { bottom: 20px; right: 20px; border-width: 0 2px 2px 0; }

        /* Floating chars */
        .floaters {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 1;
        }

        .floater {
            position: absolute;
            font-family: 'Space Mono', monospace;
            font-size: 11px;
            color: var(--red);
            opacity: 0.08;
            animation: fall linear infinite;
            white-space: nowrap;
        }

        @keyframes fall {
            from { transform: translateY(-60px); opacity: 0; }
            10% { opacity: 0.08; }
            90% { opacity: 0.08; }
            to { transform: translateY(110vh); opacity: 0; }
        }

        /* Error code line */
        .error-line {
            font-size: 11px;
            color: #333;
            letter-spacing: 2px;
            margin-top: 40px;
            font-family: 'Space Mono', monospace;
        }

        .error-line span {
            color: var(--red);
        }
    </style>
</head>
<body>

    <!-- Corner brackets -->
    <div class="corner corner-tl"></div>
    <div class="corner corner-tr"></div>
    <div class="corner corner-bl"></div>
    <div class="corner corner-br"></div>

    <!-- Floating random chars -->
    <div class="floaters" id="floaters"></div>

    <div class="scene">
        <div class="big-number">404</div>

        <div class="tape">NO EXISTE, CAUSA</div>

        <div class="message-stack">
            <p class="msg">Buscaste con todo y no encontramos absolutamente</p>
            <p class="msg-highlight">N A D A&nbsp;&nbsp;D E&nbsp;&nbsp;N A D A<span class="cursor"></span></p>
            <p class="msg">ni el rastro, ni las cenizas, ni el recuerdo de lo que buscabas.</p>
            <p class="msg-red">// página.exe ha dejado de existir</p>
        </div>

        <a href="{{ route('dashboard') }}" class="btn-home">← Regresar a donde sí hay algo</a>

        <p class="error-line">HTTP_STATUS: <span>404</span> &nbsp;|&nbsp; RESULTADO: <span>NADA WEY</span> &nbsp;|&nbsp; SUGERENCIA: <span>NO VOLVER A INTENTAR</span></p>
    </div>

    <script>
        // Floating chars
        const chars = ['0','1','4','N','U','L','L','E','R','R','/','\\','?','!','%','#'];
        const container = document.getElementById('floaters');
        for (let i = 0; i < 30; i++) {
            const el = document.createElement('div');
            el.className = 'floater';
            el.textContent = Array.from({length: Math.floor(Math.random()*6)+3}, () => chars[Math.floor(Math.random()*chars.length)]).join(' ');
            el.style.left = Math.random() * 100 + 'vw';
            el.style.animationDuration = (Math.random() * 15 + 10) + 's';
            el.style.animationDelay = (Math.random() * 15) + 's';
            el.style.fontSize = (Math.random() * 6 + 9) + 'px';
            container.appendChild(el);
        }

        // Random glitch flicker on 404
        const big = document.querySelector('.big-number');
        setInterval(() => {
            if (Math.random() > 0.7) {
                big.style.filter = 'hue-rotate(' + (Math.random()*60) + 'deg)';
                setTimeout(() => big.style.filter = '', 80);
            }
        }, 2000);
    </script>
</body>
</html>