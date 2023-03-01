
<body>
<section style="background: #f3f3f3;padding: 15px;">
    <div style="max-width: 660px;margin: 20px auto; padding:20px; background: #fff;">
        <div style="border: 1px solid #ddd; padding: 20px;text-align: center;">
            <h3 style="font-size: 25px;font-weight: bold;margin: 0;">Hello {{$name}}</h3>
        </div>
        <!-- confirmation message with user id password -->
        <div class="text-center">
            <p>Dear Mr/Mrs <span>{{$name}}</span>,</p>
            <p>You are receiving the email because we received a password reset request for you account</p>
            <a href="{{url($reset_url)}}" style="text-decoration: none;color: #fff;background:#B32D34; padding: 10px 20px; display: inline-block;margin-top: 15px;">Reset Password</a>
            <p>This password reset link will expire in 24 hours.</p>
            <p>if you did not request a password reset. no further action is required.</p>
        </div>
    </div>
</section>
</body>
