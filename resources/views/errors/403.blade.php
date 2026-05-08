<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Ni lo intentes</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Black+Han+Sans&family=Space+Mono:wght@400;700&display=swap');

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg: #0a0a0a;
            --orange: #ff6a00;
            --yellow: #ffe600;
            --white: #f0f0f0;
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
            cursor: not-allowed;
        }

        /* Scanlines */
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

        /* Radial danger glow */
        .glow-bg {
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse 60% 50% at 50% 50%, rgba(255,106,0,0.07) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
            animation: pulse-glow 3s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        /* Warning stripes - top and bottom */
        .stripe {
            position: fixed;
            left: 0; right: 0;
            height: 8px;
            background: repeating-linear-gradient(
                90deg,
                var(--orange) 0px,
                var(--orange) 20px,
                #000 20px,
                #000 40px
            );
            z-index: 50;
            animation: stripe-scroll 1.5s linear infinite;
        }
        .stripe-top { top: 0; }
        .stripe-bottom { bottom: 0; }

        @keyframes stripe-scroll {
            from { background-position: 0 0; }
            to { background-position: 40px 0; }
        }

        .scene {
            position: relative;
            text-align: center;
            padding: 2rem;
            z-index: 10;
        }

        /* Lock icon */
        .lock-wrap {
            margin-bottom: -10px;
            position: relative;
            display: inline-block;
        }

        .lock {
            font-size: clamp(40px, 6vw, 70px);
            display: inline-block;
            animation: shake 4s ease-in-out infinite;
            filter: drop-shadow(0 0 18px var(--orange));
        }

        @keyframes shake {
            0%, 85%, 100% { transform: rotate(0deg); }
            87% { transform: rotate(-8deg); }
            89% { transform: rotate(8deg); }
            91% { transform: rotate(-5deg); }
            93% { transform: rotate(5deg); }
            95% { transform: rotate(0deg); }
        }

        /* Big 403 */
        .big-number {
            font-family: 'Black Han Sans', sans-serif;
            font-size: clamp(140px, 25vw, 280px);
            line-height: 0.85;
            color: transparent;
            -webkit-text-stroke: 3px var(--orange);
            letter-spacing: -8px;
            position: relative;
            display: inline-block;
            animation: glitch 4s infinite;
            user-select: none;
        }

        .big-number::before,
        .big-number::after {
            content: '403';
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            font-family: 'Black Han Sans', sans-serif;
            font-size: inherit;
            letter-spacing: inherit;
        }

        .big-number::before {
            color: var(--orange);
            opacity: 0.5;
            animation: glitch-1 4s infinite;
            clip-path: polygon(0 0, 100% 0, 100% 40%, 0 40%);
        }

        .big-number::after {
            color: var(--yellow);
            opacity: 0.35;
            animation: glitch-2 4s infinite;
            clip-path: polygon(0 60%, 100% 60%, 100% 100%, 0 100%);
        }

        @keyframes glitch {
            0%, 88%, 100% { transform: translate(0); }
            89% { transform: translate(-5px, 2px); }
            91% { transform: translate(5px, -2px); }
            93% { transform: translate(-3px, 0); }
            95% { transform: translate(3px, 1px); }
        }

        @keyframes glitch-1 {
            0%, 88%, 100% { transform: translate(0); opacity: 0; }
            89% { transform: translate(-10px, 0); opacity: 0.5; }
            91% { transform: translate(7px, 0); opacity: 0.5; }
            93% { opacity: 0; }
        }

        @keyframes glitch-2 {
            0%, 86%, 100% { transform: translate(0); opacity: 0; }
            87% { transform: translate(10px, 2px); opacity: 0.4; }
            89% { transform: translate(-5px, -1px); opacity: 0.4; }
            91% { opacity: 0; }
        }

        /* Tape */
        .tape {
            background: var(--orange);
            color: #000;
            font-family: 'Black Han Sans', sans-serif;
            font-size: clamp(13px, 2.2vw, 19px);
            letter-spacing: 6px;
            padding: 6px 32px;
            display: inline-block;
            transform: rotate(1.2deg);
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
            background: rgba(0,0,0,0.15);
        }
        .tape::before { left: 0; }
        .tape::after { right: 0; }

        /* Messages */
        .message-stack {
            margin: 28px 0 36px;
            display: flex;
            flex-direction: column;
            gap: 9px;
            align-items: center;
        }

        .msg {
            font-size: clamp(11px, 1.6vw, 14px);
            color: #777;
            letter-spacing: 1px;
        }

        .msg-highlight {
            font-size: clamp(14px, 2vw, 18px);
            color: var(--white);
            font-weight: 700;
        }

        .msg-orange {
            color: var(--orange);
            font-size: clamp(12px, 1.8vw, 15px);
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        /* Access denied blink */
        .access-denied {
            display: inline-block;
            border: 1px solid var(--orange);
            padding: 4px 16px;
            font-size: clamp(11px, 1.5vw, 13px);
            letter-spacing: 5px;
            color: var(--orange);
            text-transform: uppercase;
            animation: flicker 2.5s ease-in-out infinite;
        }

        @keyframes flicker {
            0%, 19%, 21%, 23%, 100% { opacity: 1; }
            20%, 22% { opacity: 0.2; }
        }

        /* Cursor blink */
        .cursor {
            display: inline-block;
            width: 10px;
            height: 1.1em;
            background: var(--orange);
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
            border: 2px solid var(--orange);
            color: var(--orange);
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            transition: color 0.2s;
        }

        .btn-home::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--orange);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.25s ease;
            z-index: -1;
        }

        .btn-home:hover { color: #000; cursor: pointer; }
        .btn-home:hover::before { transform: scaleX(1); }

        /* Corner brackets */
        .corner {
            position: fixed;
            width: 60px;
            height: 60px;
            border-color: var(--orange);
            border-style: solid;
            opacity: 0.25;
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
            color: var(--orange);
            opacity: 0.06;
            animation: fall linear infinite;
            white-space: nowrap;
        }

        @keyframes fall {
            from { transform: translateY(-60px); opacity: 0; }
            10% { opacity: 0.06; }
            90% { opacity: 0.06; }
            to { transform: translateY(110vh); opacity: 0; }
        }

        /* Bottom status */
        .error-line {
            font-size: 11px;
            color: #2a2a2a;
            letter-spacing: 2px;
            margin-top: 40px;
        }
        .error-line span { color: var(--orange); }
    </style>
</head>
<body>

    <div class="glow-bg"></div>
    <div class="stripe stripe-top"></div>
    <div class="stripe stripe-bottom"></div>

    <div class="corner corner-tl"></div>
    <div class="corner corner-tr"></div>
    <div class="corner corner-bl"></div>
    <div class="corner corner-br"></div>

    <div class="floaters" id="floaters"></div>

    <div class="scene">

        <div class="lock-wrap">
            <div class="lock">🔒</div>
        </div>

        <div class="big-number">403</div>

        <div class="tape">AQUÍ NO ENTRAS, AMIGO</div>

        <div class="message-stack">
            <div class="access-denied">⛔ &nbsp;ACCESO DENEGADO&nbsp; ⛔</div>
            <p class="msg" style="margin-top:8px">No tienes permisos, no tienes nivel,</p>
            <p class="msg-highlight">no tienes N A D A<span class="cursor"></span></p>
            <p class="msg">para estar aquí. Ni lo intentes de nuevo.</p>
            <p class="msg-orange">// forbidden.exe — y que quede claro</p>
        </div>

        <a href="{{ route('dashboard') }}" class="btn-home">← Regresa a donde sí te dejan</a>

        <p class="error-line">HTTP_STATUS: <span>403</span> &nbsp;|&nbsp; PERMISO: <span>NINGUNO</span> &nbsp;|&nbsp; INTENTO: <span>FALLIDO, COMO SIEMPRE</span></p>

    </div>

    <script>
        const chars = ['4','0','3','X','!','?','#','%','F','O','R','B','I','D','D','E','N'];
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

        // Glitch flicker
        const big = document.querySelector('.big-number');
        setInterval(() => {
            if (Math.random() > 0.65) {
                big.style.filter = 'hue-rotate(' + (Math.random() * 40) + 'deg)';
                setTimeout(() => big.style.filter = '', 100);
            }
        }, 2200);
    </script>
</body>
</html>