<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Reset Password</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="row" style="margin-top: 45px;">
		
			<div id="registerForm" class="col-md-4 offset-md-4">
				<h3 style="text-align:center;">Reset Password</h3>
				<hr>
                @if(Session::get('error'))
                <h3 class="text-danger">{{ Session::get("error") }}</h3>
                @endif
                @if(Session::get('success'))
                <h3 class="text-success">{{ Session::get("success") }}</h3>
                @endif
				<form method="post" action="{{route('reset.password')}}" id="registerAuth">
					@csrf
					
					<div class="form-group">
						<label>Email</label>
						<input type="email" id="email" name="email" class="form-control" placeholder="Entere your registered email">
						<span class="text-danger" id="emailError"></span>

					</div>
				
					<br>
					<div class="form-group">
						<button id="registerBTN" class="form-control btn btn-primary">Send Reset Link</button>
					</div>
				</form>
				</div>
				
			</div>
		</div>
	</div>

</body>
</html>