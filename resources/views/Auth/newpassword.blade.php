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
				<form method="post" action="{{route('change.password')}}" id="registerAuth">
					@csrf
					<input type="hidden" name="email" value="{{$data['email']}}">
					<input type="hidden" name="token" value="{{$data['token']}}">
					<div class="form-group">
						<label>Password</label>
						<input type="password" id="email" name="password" class="form-control" placeholder="Entere your new passwordd">
						<span class="text-danger" id=""></span>
					</div>
                    <div class="form-group">
						<label>Confirm Password</label>
						<input type="password" id="" name="password_confirmation" class="form-control" placeholder="Confirm your passwordd">
					
                        @error('password')
                        <span class="text-danger" id=""> <strong>{{ $message }}</strong></span>
                    @enderror
					</div>
				
					<br>
					<div class="form-group">
						<button id="registerBTN" class="form-control btn btn-primary">Update Password</button>
					</div>
				</form>
				</div>
				
			</div>
		</div>
	</div>

</body>
</html>