
<body>
<section style="background: #f3f3f3;padding: 15px;">
    <div style="max-width: 660px;margin: 20px auto; padding:20px; background: #fff;">
        <div style="border: 1px solid #ddd; padding: 20px;text-align: center;">
            <h2 style="font-size: 30px; color: #3053a6; margin: 0;padding-bottom: 10px;">Thank You for Subscribing!</h2>
        </div>
        <!-- confirmation message with user id password -->
        <div>
            <p>Hi {{$subscriberName}},</p>
            <p>Thank you for signing up for {{$appName}} Newsletter! Please click the link below to verify your E-mail address.
            </p>
            <a href="{{url($verificationUrl)}}" style="text-decoration: none;color: #fff;background:#3053a6; padding: 10px 20px; display: inline-block;margin-top: 15px;">Verify E-mail Address</a>
        </div>


        <!-- Footer -->
        <div style="margin-top: 40px;text-align: center;">            
            <h4 style="font-size: 18px;margin:0;">{{$appName}}</h4>
            <p style="font-size: 14px;margin: 0;">{{$appAddress}}</p>
        </div>
    </div>
</section>
</body>

