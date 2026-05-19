<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Verification</title>
    <style>
        body {
            background-color: #050505;
            color: #d1d5db;
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #0a0a0a;
            border: 1px solid #991b1b;
            border-radius: 2px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);
        }
        .header {
            background-color: #111;
            padding: 20px;
            border-b: 1px solid rgba(153, 27, 27, 0.4);
            text-align: center;
        }
        .header h1 {
            color: #ef4444;
            font-size: 16px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.25em;
            margin: 0;
        }
        .content {
            padding: 30px;
            line-height: 1.6;
        }
        .content p {
            font-size: 12px;
            margin: 0 0 20px;
        }
        .otp-container {
            background-color: #050505;
            border: 1px solid rgba(153, 27, 27, 0.2);
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-weight: 900;
            margin-bottom: 8px;
            display: block;
        }
        .otp-code {
            font-size: 28px;
            font-weight: 900;
            color: #ef4444;
            letter-spacing: 0.15em;
            font-family: monospace;
            margin: 0;
        }
        .footer {
            background-color: #070707;
            padding: 15px;
            text-align: center;
            border-top: 1px solid rgba(153, 27, 27, 0.1);
        }
        .footer p {
            font-size: 9px;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>System Connection Verification</h1>
        </div>
        <div class="content">
            <p>Greetings, <strong>{{ $username }}</strong>.</p>
            <p>You have requested account registration under the operational terminal. To finalize your access request and connect your node, you must verify your mail routing protocol using the security key below.</p>
            
            <div class="otp-container">
                <span class="otp-label">Security OTP Code</span>
                <h2 class="otp-code">{{ $otpCode }}</h2>
            </div>
            
            <p style="color: #6b7280; font-size: 10px; margin-top: 20px;">
                * Note: This dynamic key is valid for exactly 15 minutes. If this request was not authorized by your terminal, no further actions are necessary. All operational logs are fully encrypted.
            </p>
        </div>
        <div class="footer">
            <p>DoxMe System Node • Secure Encrypted Platform</p>
        </div>
    </div>
</body>
</html>
