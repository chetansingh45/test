<html>
    <head>
        <title>Reset Password</title>
    </head>
    <body>
        <h3>Reset password link from BLOG</h3>
        <hr>
            <p>
                Please click on button to change your password:<br>
                    <h3><a href="{{ URL::to('/checkReset'); }}/{{$data['email']}}/{{$data['token']}}"><button>Click Here</button></a></h3> 
            </p>
        <hr>
        <h5>Thankyou</h5>
    </body>
</html>