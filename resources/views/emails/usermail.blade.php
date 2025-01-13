<!DOCTYPE html>
<html>

<head>
    <title>New User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f7;
            color: #51545e;
            margin: 0;
            padding: 0;
        }

        .email-wrapper {
            width: 100%;
            background-color: #f4f4f7;
            padding: 20px;
        }

        .email-content {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 5px;
            overflow: hidden;
        }

        .email-header {
            background-color: #3869d4;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }

        .email-body {
            padding: 20px;
        }

        .email-body h1 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .email-body p {
            font-size: 16px;
            line-height: 1.5;
            margin: 0 0 20px;
        }

        .email-panel {
            background-color: #f4f4f7;
            padding: 15px;
            border: 1px solid #eaeaec;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .email-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3869d4;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #a8aaaf;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-content">
            <!-- Header -->
            <div class="email-header">
                Sena Web
            </div>

            <!-- Body -->
            <div class="email-body">
                <h1>Hello!</h1>
                <p>Selamat datang di Sena! Gunakan informasi berikut untuk login ke akun Sena Anda:</p>
                <div class="email-panel">
                    <p><strong>Username:</strong> {{ $data['username'] }}</p>
                    <p><strong>Email:</strong> {{ $data['email'] }}</p>
                    <p><strong>Password:</strong> {{ $data['password'] }}</p>
                </div>
                <a href="http://localhost:3000" class="email-button">Login Sekarang</a>
                <p>Mohon untuk tidak membagikan informasi tersebut kepada siapa pun.</p>
                <p>Anda dapat mengubah password dan informasi pengguna dalam sistem. Terimakasih.</p>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                © {{ date('Y') }} Sena. All rights reserved.
            </div>
        </div>
    </div>
</body>

</html>
