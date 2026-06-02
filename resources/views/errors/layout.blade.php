<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;700;800&family=Syne+Mono:wght@400;700&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg: #05080f;
            --blue: #4facfe;
            --cyan: #00f2fe;
            --gold: #ffd200;
            --orange: #f7971e;
            --muted: #4a6080;
            --text: #b8cce0;
        }

        html,
        body {
            min-height: 100vh;
            background: var(--bg);
            font-family: 'Space Grotesk', sans-serif;
            color: var(--text);
            overflow: hidden;
        }

        
        .nebula {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .nebula.a {
            width: 520px;
            height: 420px;
            top: -8%;
            left: -8%;
            background: radial-gradient(circle, rgba(79, 172, 254, .13), transparent 70%);
            animation: neb 22s ease-in-out infinite alternate;
        }

        .nebula.b {
            width: 460px;
            height: 460px;
            bottom: -10%;
            right: -8%;
            background: radial-gradient(circle, rgba(157, 80, 187, .1), transparent 70%);
            animation: neb 17s ease-in-out infinite alternate-reverse;
        }

        .nebula.c {
            width: 310px;
            height: 310px;
            top: 45%;
            left: 55%;
            background: radial-gradient(circle, rgba(247, 151, 30, .07), transparent 70%);
            animation: neb 26s ease-in-out infinite alternate;
        }

        @keyframes neb {
            from {
                transform: scale(1) translate(0, 0)
            }

            to {
                transform: scale(1.1) translate(22px, 14px)
            }
        }

        
        .stars {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .stars::before,
        .stars::after,
        .stars i {
            content: '';
            position: absolute;
            inset: -50%;
            background-image:
                radial-gradient(1px 1px at 8% 14%, rgba(232, 242, 255, .9) 0%, transparent 100%),
                radial-gradient(1px 1px at 21% 38%, rgba(232, 242, 255, .7) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 35% 6%, rgba(232, 242, 255, .95) 0%, transparent 100%),
                radial-gradient(1px 1px at 49% 58%, rgba(232, 242, 255, .6) 0%, transparent 100%),
                radial-gradient(1px 1px at 63% 22%, rgba(232, 242, 255, .8) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 72% 75%, rgba(232, 242, 255, .9) 0%, transparent 100%),
                radial-gradient(1px 1px at 85% 32%, rgba(232, 242, 255, .7) 0%, transparent 100%),
                radial-gradient(1px 1px at 91% 53%, rgba(232, 242, 255, .85) 0%, transparent 100%),
                radial-gradient(1px 1px at 4% 80%, rgba(232, 242, 255, .6) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 17% 89%, rgba(232, 242, 255, .75) 0%, transparent 100%),
                radial-gradient(1px 1px at 30% 70%, rgba(232, 242, 255, .5) 0%, transparent 100%),
                radial-gradient(1px 1px at 45% 85%, rgba(232, 242, 255, .8) 0%, transparent 100%),
                radial-gradient(1px 1px at 59% 43%, rgba(232, 242, 255, .65) 0%, transparent 100%),
                radial-gradient(1px 1px at 77% 11%, rgba(232, 242, 255, .9) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 83% 94%, rgba(232, 242, 255, .7) 0%, transparent 100%),
                radial-gradient(1px 1px at 13% 52%, rgba(232, 242, 255, .55) 0%, transparent 100%),
                radial-gradient(1px 1px at 40% 27%, rgba(232, 242, 255, .8) 0%, transparent 100%),
                radial-gradient(1px 1px at 54% 65%, rgba(232, 242, 255, .6) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 68% 48%, rgba(232, 242, 255, .85) 0%, transparent 100%),
                radial-gradient(1px 1px at 95% 78%, rgba(232, 242, 255, .75) 0%, transparent 100%);
            background-size: 280px 280px;
            animation: twinkle1 9s ease-in-out infinite alternate;
        }

        .stars::after {
            background-image:
                radial-gradient(1px 1px at 6% 27%, rgba(179, 220, 255, .8) 0%, transparent 100%),
                radial-gradient(1px 1px at 20% 62%, rgba(179, 220, 255, .6) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 34% 18%, rgba(179, 220, 255, .9) 0%, transparent 100%),
                radial-gradient(1px 1px at 47% 71%, rgba(179, 220, 255, .5) 0%, transparent 100%),
                radial-gradient(1px 1px at 61% 36%, rgba(179, 220, 255, .75) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 75% 82%, rgba(179, 220, 255, .65) 0%, transparent 100%),
                radial-gradient(1px 1px at 89% 16%, rgba(179, 220, 255, .85) 0%, transparent 100%),
                radial-gradient(1px 1px at 2% 45%, rgba(179, 220, 255, .7) 0%, transparent 100%),
                radial-gradient(1px 1px at 15% 74%, rgba(179, 220, 255, .55) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 28% 91%, rgba(179, 220, 255, .8) 0%, transparent 100%),
                radial-gradient(1px 1px at 53% 50%, rgba(179, 220, 255, .65) 0%, transparent 100%),
                radial-gradient(1px 1px at 66% 5%, rgba(179, 220, 255, .9) 0%, transparent 100%),
                radial-gradient(1px 1px at 80% 59%, rgba(179, 220, 255, .6) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 96% 40%, rgba(179, 220, 255, .75) 0%, transparent 100%);
            background-size: 380px 380px;
            animation: twinkle2 13s ease-in-out infinite alternate;
        }

        .stars i {
            background-image:
                radial-gradient(2px 2px at 16% 31%, rgba(255, 255, 200, .7) 0%, transparent 100%),
                radial-gradient(2px 2px at 43% 76%, rgba(255, 255, 200, .5) 0%, transparent 100%),
                radial-gradient(2px 2px at 69% 13%, rgba(255, 255, 200, .8) 0%, transparent 100%),
                radial-gradient(2px 2px at 87% 65%, rgba(255, 255, 200, .6) 0%, transparent 100%),
                radial-gradient(2px 2px at 53% 88%, rgba(255, 255, 200, .75) 0%, transparent 100%);
            background-size: 480px 480px;
            animation: twinkle3 16s ease-in-out infinite alternate;
        }

        @keyframes twinkle1 {
            from {
                opacity: .55;
                transform: translateY(0)
            }

            to {
                opacity: 1;
                transform: translateY(-5px)
            }
        }

        @keyframes twinkle2 {
            from {
                opacity: .7;
                transform: translateX(0)
            }

            to {
                opacity: .4;
                transform: translateX(6px)
            }
        }

        @keyframes twinkle3 {
            from {
                opacity: .35;
                transform: scale(1)
            }

            to {
                opacity: .9;
                transform: scale(1.03)
            }
        }

        
        .shoots {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .s {
            position: absolute;
            height: 1.5px;
            border-radius: 2px;
            background: linear-gradient(90deg, #fff, transparent);
            opacity: 0;
            animation: shoot linear infinite;
        }

        .s:nth-child(1) {
            width: 110px;
            top: 10%;
            left: 8%;
            animation-duration: 7s;
            animation-delay: 0s;
            transform: rotate(-15deg);
        }

        .s:nth-child(2) {
            width: 75px;
            top: 26%;
            left: 52%;
            animation-duration: 10s;
            animation-delay: 3s;
            transform: rotate(-20deg);
        }

        .s:nth-child(3) {
            width: 140px;
            top: 7%;
            left: 68%;
            animation-duration: 8s;
            animation-delay: 5.5s;
            transform: rotate(-12deg);
        }

        .s:nth-child(4) {
            width: 90px;
            top: 43%;
            left: 22%;
            animation-duration: 12s;
            animation-delay: 2s;
            transform: rotate(-18deg);
        }

        @keyframes shoot {
            0% {
                opacity: 0;
                transform: translateX(0) rotate(-15deg);
            }

            5% {
                opacity: 1;
            }

            30% {
                opacity: 0;
                transform: translateX(280px) rotate(-15deg);
            }

            100% {
                opacity: 0;
                transform: translateX(280px) rotate(-15deg);
            }
        }

        
        .planet-wrap {
            position: fixed;
            right: -80px;
            bottom: -80px;
            z-index: 0;
            pointer-events: none;
            animation: planet-rot 60s linear infinite;
        }

        .planet {
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: conic-gradient(from 160deg, #0d2040, #153060, #1a3a6a, #0a1830, #153050, #0d2040);
            position: relative;
            overflow: hidden;
        }

        .planet::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: repeating-linear-gradient(8deg, transparent, transparent 18px, rgba(79, 172, 254, .06) 18px, rgba(79, 172, 254, .06) 20px);
        }

        .planet::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, rgba(79, 172, 254, .08), transparent 60%), radial-gradient(circle at 75% 70%, rgba(0, 0, 0, .5), transparent 50%);
        }

        .ring {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotateX(78deg);
            width: 460px;
            height: 460px;
            border-radius: 50%;
            border: 16px solid transparent;
            border-top-color: rgba(79, 172, 254, .18);
            border-bottom-color: rgba(79, 172, 254, .18);
        }

        @keyframes planet-rot {
            to {
                transform: rotate(360deg)
            }
        }

        
        .debris-wrap {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .db {
            position: absolute;
            bottom: -20px;
            font-family: 'Syne Mono', monospace;
            font-size: .7rem;
            color: rgba(79, 172, 254, .16);
            animation: rise linear infinite;
        }

        .db:nth-child(1) {
            left: 5%;
            animation-duration: 14s;
            animation-delay: 0s;
        }

        .db:nth-child(2) {
            left: 12%;
            animation-duration: 18s;
            animation-delay: -5s;
            font-size: .6rem;
        }

        .db:nth-child(3) {
            left: 22%;
            animation-duration: 11s;
            animation-delay: -2s;
        }

        .db:nth-child(4) {
            left: 34%;
            animation-duration: 21s;
            animation-delay: -9s;
            font-size: .75rem;
        }

        .db:nth-child(5) {
            left: 44%;
            animation-duration: 15s;
            animation-delay: -6s;
        }

        .db:nth-child(6) {
            left: 56%;
            animation-duration: 17s;
            animation-delay: -1s;
            font-size: .6rem;
        }

        .db:nth-child(7) {
            left: 65%;
            animation-duration: 13s;
            animation-delay: -8s;
        }

        .db:nth-child(8) {
            left: 74%;
            animation-duration: 23s;
            animation-delay: -3s;
            font-size: .65rem;
        }

        .db:nth-child(9) {
            left: 83%;
            animation-duration: 16s;
            animation-delay: -12s;
        }

        .db:nth-child(10) {
            left: 93%;
            animation-duration: 19s;
            animation-delay: -4s;
            font-size: .7rem;
        }

        @keyframes rise {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0;
            }

            7% {
                opacity: 1;
            }

            93% {
                opacity: .7;
            }

            100% {
                transform: translateY(-110vh) rotate(380deg);
                opacity: 0;
            }
        }

        
        .scanlines {
            position: fixed;
            inset: 0;
            z-index: 2;
            pointer-events: none;
            background: repeating-linear-gradient(0deg, transparent, transparent 3px, rgba(0, 0, 0, .04) 3px, rgba(0, 0, 0, .04) 4px);
        }

        
        .scene {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
            text-align: center;
        }

        
        .astro-wrap {
            margin-bottom: 1.2rem;
            filter: drop-shadow(0 0 26px rgba(79, 172, 254, .38));
            animation: float 5s ease-in-out infinite, fadein .8s .1s both;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(-2deg)
            }

            50% {
                transform: translateY(-18px) rotate(3deg)
            }
        }

        
        .glitch-wrap {
            position: relative;
            line-height: 1;
            margin-bottom: .6rem;
            animation: fadein .6s both;
        }

        .glitch {
            font-size: clamp(6rem, 18vw, 12rem);
            font-weight: 800;
            font-family: 'Syne Mono', monospace;
            letter-spacing: -4px;
            color: #4facfe;
            position: relative;
            display: inline-block;
            animation: glitch-main 5s steps(1) infinite;
        }

        .glitch::before,
        .glitch::after {
            content: '@yield(' code')';
            position: absolute;
            top: 0;
            left: 0;
            font-size: inherit;
            font-weight: inherit;
            font-family: inherit;
            letter-spacing: inherit;
        }

        .glitch::before {
            color: #00f2fe;
            clip-path: polygon(0 0, 100% 0, 100% 38%, 0 38%);
            animation: glitch-t 5s steps(1) infinite;
        }

        .glitch::after {
            color: #f7971e;
            clip-path: polygon(0 62%, 100% 62%, 100% 100%, 0 100%);
            animation: glitch-b 5s steps(1) infinite;
        }

        @keyframes glitch-main {

            0%,
            88%,
            100% {
                transform: none
            }

            89% {
                transform: translateX(-4px) skewX(-3deg)
            }

            91% {
                transform: translateX(4px) skewX(3deg)
            }

            93% {
                transform: translateX(-2px)
            }

            95% {
                transform: none
            }

            97% {
                transform: skewX(-6deg)
            }

            99% {
                transform: skewX(6deg)
            }
        }

        @keyframes glitch-t {

            0%,
            88%,
            100% {
                transform: none;
                opacity: 0
            }

            89% {
                transform: translate(-8px, -2px);
                opacity: .75
            }

            91% {
                transform: translate(8px, 2px);
                opacity: .6
            }

            93% {
                opacity: 0
            }
        }

        @keyframes glitch-b {

            0%,
            88%,
            100% {
                transform: none;
                opacity: 0
            }

            90% {
                transform: translate(6px, 3px);
                opacity: .65
            }

            92% {
                transform: translate(-6px, -1px);
                opacity: .5
            }

            94% {
                opacity: 0
            }
        }

        
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--gold);
            background: rgba(255, 210, 0, .08);
            border: 1px solid rgba(255, 210, 0, .22);
            padding: 5px 16px;
            border-radius: 100px;
            margin-bottom: .9rem;
            animation: fadein .6s .2s both;
        }

        .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--orange);
            flex-shrink: 0;
            animation: blink 1.4s ease-in-out infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .15
            }
        }

        
        .headline {
            font-size: clamp(1.4rem, 4vw, 2.1rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: .7rem;
            animation: fadein .6s .3s both;
        }

        .subtext {
            font-size: clamp(.88rem, 2vw, 1rem);
            color: var(--muted);
            max-width: 400px;
            line-height: 1.75;
            margin: 0 auto 2rem;
            animation: fadein .6s .4s both;
        }

        .subtext em {
            color: var(--blue);
            font-style: normal;
            font-weight: 600;
        }

        
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            animation: fadein .6s .5s both;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--blue);
            color: #030e1a;
            font-weight: 800;
            font-size: 1rem;
            padding: 14px 32px;
            border-radius: 14px;
            text-decoration: none;
            transition: transform .2s, box-shadow .2s;
            position: relative;
            overflow: hidden;
        }

        .btn-home::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, .3), transparent 60%);
            opacity: 0;
            transition: opacity .2s;
        }

        .btn-home:hover {
            transform: translateY(-3px) scale(1.04);
            box-shadow: 0 14px 40px rgba(79, 172, 254, .45);
        }

        .btn-home:hover::before {
            opacity: 1;
        }

        .btn-home:active {
            transform: scale(.97);
        }

        .rocket-icon {
            display: inline-block;
            transition: transform .3s;
        }

        .btn-home:hover .rocket-icon {
            transform: translateX(6px) translateY(-6px) rotate(-35deg);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            font-size: .95rem;
            font-weight: 600;
            padding: 14px 24px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .1);
            background: rgba(255, 255, 255, .04);
            text-decoration: none;
            transition: all .2s;
        }

        .btn-back:hover {
            color: var(--text);
            background: rgba(255, 255, 255, .08);
            border-color: rgba(255, 255, 255, .2);
        }

        
        .hud {
            position: fixed;
            bottom: 24px;
            left: 24px;
            font-family: 'Syne Mono', monospace;
            font-size: 10px;
            color: rgba(79, 172, 254, .28);
            line-height: 2;
            pointer-events: none;
            z-index: 1;
            animation: fadein 1s 1.2s both;
        }

        .hud-right {
            position: fixed;
            bottom: 24px;
            right: 24px;
            text-align: right;
            font-family: 'Syne Mono', monospace;
            font-size: 10px;
            color: rgba(79, 172, 254, .28);
            line-height: 2;
            pointer-events: none;
            z-index: 1;
            animation: fadein 1s 1.4s both;
        }

        @keyframes fadein {
            from {
                opacity: 0;
                transform: translateY(14px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @media (max-width:600px) {

            .planet-wrap,
            .hud,
            .hud-right {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="nebula a"></div>
    <div class="nebula b"></div>
    <div class="nebula c"></div>
    <div class="stars"><i></i></div>
    <div class="shoots">
        <div class="s"></div>
        <div class="s"></div>
        <div class="s"></div>
        <div class="s"></div>
    </div>
    <div class="scanlines"></div>

    <div class="planet-wrap" aria-hidden="true">
        <div class="planet">
            <div class="ring"></div>
        </div>
    </div>

    <div class="debris-wrap" aria-hidden="true">
        <div class="db">ERR_@yield('code')</div>
        <div class="db">undefined</div>
        <div class="db">null</div>
        <div class="db">{ }</div>
        <div class="db">NaN</div>
        <div class="db">VOID</div>
        <div class="db">0x@yield('code')</div>
        <div class="db">???</div>
        <div class="db">LOST</div>
        <div class="db">∅</div>
    </div>

    <div class="hud" aria-hidden="true">LAT 40.4° N<br>LON 74.0° W<br>ALT ∞</div>
    <div class="hud-right" aria-hidden="true">STATUS: LOST<br>SIGNAL: 0.000%<br>HELP: ????</div>

    <main class="scene">

        <div class="astro-wrap" aria-hidden="true">
            <svg width="150" height="192" viewBox="0 0 150 192" fill="none" xmlns="http://www.w3.org/2000/svg">
                <line x1="75" y1="28" x2="75" y2="10" stroke="#4facfe" stroke-width="2" stroke-linecap="round" />
                <circle cx="75" cy="7" r="5" fill="#ffd200" />
                <circle cx="75" cy="7" r="8" fill="#ffd200" opacity="0">
                    <animate attributeName="opacity" values="0;.45;0" dur="1.4s" repeatCount="indefinite" />
                    <animate attributeName="r" values="5;10;5" dur="1.4s" repeatCount="indefinite" />
                </circle>
                <ellipse cx="75" cy="70" rx="38" ry="40" fill="#1a2a4a" stroke="#4facfe" stroke-width="2.5" />
                <ellipse cx="75" cy="68" rx="27" ry="25" fill="#080f1e" />
                <ellipse cx="75" cy="68" rx="27" ry="25" fill="#4facfe" opacity=".06" />
                <ellipse cx="63" cy="57" rx="9" ry="6" fill="#4facfe" opacity=".12" transform="rotate(-20,63,57)" />
                <circle cx="63" cy="65" r="5" fill="#4facfe" opacity=".9" />
                <circle cx="87" cy="65" r="5" fill="#4facfe" opacity=".9" />
                <circle cx="64" cy="66" r="2.2" fill="#fff" />
                <circle cx="88" cy="66" r="2.2" fill="#fff" />
                <path d="M65 78 Q75 88 85 78" stroke="#4facfe" stroke-width="2.2" stroke-linecap="round" fill="none" />
                <ellipse cx="97" cy="57" rx="3.5" ry="5" fill="#00c6fb" opacity=".55" transform="rotate(12,97,57)" />
                <rect x="40" y="104" width="70" height="58" rx="22" fill="#1a2a4a" stroke="#4facfe" stroke-width="2" />
                <rect x="57" y="101" width="36" height="11" rx="5.5" fill="#233552" />
                <rect x="58" y="118" width="34" height="18" rx="6" fill="#080f1e" stroke="#4facfe" stroke-width="1" />
                <text x="75" y="131" fill="#4facfe" font-size="8.5" font-family="Syne Mono,monospace"
                    text-anchor="middle" font-weight="700">@yield('code')</text>
                <rect x="14" y="104" width="27" height="14" rx="7" fill="#1a2a4a" stroke="#4facfe" stroke-width="2"
                    transform="rotate(-35,28,111)" />
                <ellipse cx="8" cy="119" rx="9.5" ry="9.5" fill="#233552" stroke="#4facfe" stroke-width="1.5" />
                <rect x="109" y="106" width="27" height="14" rx="7" fill="#1a2a4a" stroke="#4facfe" stroke-width="2"
                    transform="rotate(20,122,113)" />
                <ellipse cx="141" cy="118" rx="9.5" ry="9.5" fill="#233552" stroke="#4facfe" stroke-width="1.5" />
                <rect x="48" y="154" width="21" height="32" rx="10.5" fill="#1a2a4a" stroke="#4facfe" stroke-width="2"
                    transform="rotate(-6,58,170)" />
                <rect x="81" y="154" width="21" height="32" rx="10.5" fill="#1a2a4a" stroke="#4facfe" stroke-width="2"
                    transform="rotate(6,92,170)" />
                <rect x="39" y="178" width="28" height="11" rx="5.5" fill="#233552" stroke="#4facfe"
                    stroke-width="1.5" />
                <rect x="83" y="178" width="28" height="11" rx="5.5" fill="#233552" stroke="#4facfe"
                    stroke-width="1.5" />
                <path d="M141 118 Q158 90 152 55 Q148 28 162 8" stroke="#4facfe" stroke-width="1.2"
                    stroke-dasharray="4 4" fill="none" opacity=".4" />
            </svg>
        </div>

        <div class="glitch-wrap" aria-label="404 error">
            <div class="glitch" aria-hidden="true"> @yield('code') </div>
        </div>

        <div class="badge"><span class="dot"></span>Houston, we have a problem</div>

        <h1 class="headline">@yield('title') 🌌</h1>

        <p class="subtext">@yield('message')<br><br>@if(trim($__env->yieldContent('code')) == '404') The page you're
            looking for drifted into a <em>black hole</em>.<br>Our astronaut tried to find it but got distracted
            floating around.<br><br>Maybe it never existed — or it's hiding behind Saturn's rings. @else Something went
            wrong in our orbital station.<br>Our engineers have been alerted and are trying to fix the
            coordinates.<br><br>Please try again shortly. @endif</p>

        <div class="actions">
            <a href="/" class="btn-home"><span class="rocket-icon">🚀</span> Take me Home</a>
            <a href="javascript:history.back()" class="btn-back">← Go Back</a>
        </div>

    </main>
</body>

</html>