<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0 4px 0 4px;
            background-color: #ebebeb;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        p {
            word-wrap: break-word;
        }

        .container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 22px rgba(0, 0, 0, .1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 28px;
        }

        .content {
            margin-bottom: 20px;
        }

        .content h4 {
            margin: 0 0 -16px 0;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            color: #666;
            font-size: 11px;
        }

        .footer-text-primary {
            text-align: left;
            font-size: 14px;
            color: #353535;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('Reset Password') }}</h1>
        </div>
        <div class="content">
            <h4>{{ __('Hello!') }} {{ $user->first_name }},</h4>
            <p>{{ __('To confirm your identity, we need you to click the button below so you can reset your password.') }}
            </p>
            <a href="{{ env('FRONTEND_URL') }}/reset_password/{{ $user->id }}?token={{ $token }}"
                class="button">
                {{ __('Reset Password') }}
            </a>
            <p>
                {{ __("If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\ninto your web browser:", ['actionText' => __('Reset Password')]) }}
            </p>
            <p>
                <a href="{{ env('FRONTEND_URL') }}/email_verification?token={{ $token }}">
                    {{ env('FRONTEND_URL') }}/reset_password/{{ $user->id }}?token={{ $token }}
                </a>
            </p>
            <p>{{ __('If you have not requested to recover your password, please disregard this email.') }}</p>
        </div>
        <div class="footer">
            <p class="footer-text-primary">{{ __('Thanks') }},<br>{{ config('app.name') }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}.
                {{ __('All rights reserved.') }}
            </p>
        </div>
    </div>
</body>

</html>
