<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{config('app.name')}}</title>
    <style>
        :root {
            --bg: #050505;
            --t: #e2e8f0;
            --td: #94a3b8;
            --ac: #4ade80;
            --er: #f43f5e;
            --b: #1e293b;
        }

        body {
            background-color: var(--bg);
            color: var(--t);
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            max-width: 400px;
            width: 90%;

            padding: 2rem;
            text-align: center;
        }

        .code {
            color: var(--er);
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 0.5rem;
        }

        .title {
            text-transform: uppercase;
            font-weight: 900;
            letter-spacing: 0.1em;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .message {
            color: var(--td);
            font-size: 0.7rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            text-transform: uppercase;
        }

        .btn {
            display: inline-block;
            background: var(--ac);
            color: black;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            font-size: 0.7rem;
            font-weight: 900;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 2rem;
            font-size: 0.6rem;
            color: var(--td);
            opacity: 0.3;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="code">@yield('code')</div>
        <div class="title">@yield('title')</div>
        <div class="message">@yield('message')</div>
        <a href="/" class="btn">Return to Home page</a>

    </div>
</body>

</html>