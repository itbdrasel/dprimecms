
<body>
<section style="background: #f3f3f3;padding: 15px;">
    <div style="max-width: 660px;margin: 20px auto; padding:20px; background: #fff;">

        <!-- confirmation message with user id password -->

        <div>
            <p>Name : {{$name}}</p>
            <p>Phone : {{$phone}}</p>
            <p>Email : {{$email}}</p>
            <p>Subject : {{$subject}}</p>
            <br>
            <p>{{$body_message}}</p>
        </div>


        <!-- Footer -->
        <div style="margin-top: 40px;text-align: center;">
            <h4 style="font-size: 18px;margin:0;">{{$appName}}</h4>
            <p style="font-size: 14px;margin: 0;">{{$appAddress}}</p>
        </div>
    </div>
</section>
</body>

