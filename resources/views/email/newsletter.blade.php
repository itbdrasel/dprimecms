<body>
<section style="background: #f3f3f3;padding: 15px;">
    <div style="max-width: 660px;margin: 20px auto; padding:20px;">
        <div>
{{--            <p style="background: #fff; padding: 20px; margin-bottom: -30px"><b>Subject : </b>{!! $subject !!}</p>--}}
            <table width="100%;" cellpadding="0" cellspacing="0" role="presentation">
                <tbody>

                @if (isset($articles) && count($articles) >0)
                @foreach($articles as $article)
                    <tr>
                        <td style="text-align: left; padding: 20px;  background: #fff; border-radius: 10px">
                            <p><strong style="font-size: 18px">{{$article->title}}</strong></p>
                            {!! strip_tags($article->introtext) !!}
                           <p style="margin-bottom: 30px"></p>
                            <a href="{{url($article->alias)}}" style="text-decoration: none;  background-color: #28a745; color: #fff; padding: 10px 25px; border-radius: 5px;" target="_blank"><strong>Read More</strong></a>
                            <br>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px"></td>
                    </tr>
                @endforeach
                @endif
            </table>
        </div>
    </div>
</section>
</body>
