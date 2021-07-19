<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>User Authincation</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
</head>
<body>
	<div class="container">
		<div class="row" style="margin-top: 45px;">
			<div class="col-md-4 offset-4">
				@if(Session::get('linkExpired'))
			<h3 class="text-danger">{{ Session::get('linkExpired') }}</h3>
			@endif
				<div class="col-md-12" style="">
				<label>Login</label>	<input class="form-check-input" checked="" type="radio" name="auth" id="login">
				<label>Register</label>	<input class="form-check-input" type="radio" name="auth" id="register">
			</div>
			
			<div id="registerForm" class="d-none">
				<h3 style="text-align:center;">Register</h3>
				<hr>
				<form method="post" action="" id="registerAuth">
					@csrf
					<div class="form-group">
						<label>Username</label>
						<input type="text" id="username" name="username" class="form-control" placeholder="Username">
						<span class="text-danger" id="usernameError"></span>
					</div>
					<div class="form-group">
						<label>Email</label>
						<input type="email" id="email" name="email" class="form-control" placeholder="Email">
						<span class="text-danger" id="emailError"></span>

					</div>
					<div class="form-group">
						<label>Password</label>
						<input type="password" id="password" name="password" class="form-control" placeholder="Password">
						<span class="text-danger" id="passwordError"></span>

					</div>
					<br>
					<div class="form-group">
						<button id="registerBTN" class="form-control btn btn-primary">SignUp</button>
					</div>
				</form>
				</div>
				<div id="loginForm" class="">
				<h3 style="text-align:center;">Login</h3>
				<hr>
				<form method="post"  id="loginAuth">
					@csrf
					<div class="form-group">
						<label>Email</label>
						<input type="email" id="loginEmail" name="email" class="form-control" placeholder="Email">
					</div>
					<div class="form-group">
						<label>Username</label>
						<input type="password" id="loginPassword" name="password" class="form-control" placeholder="Password">
					</div>
				
					<a href='{{route("reset.show")}}'>Forget Password</a>
					<br>
					<br>
					<div class="form-group">
						<button type="submit" id="loginBTN" class="form-control btn btn-primary">SignIn</button>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function(){
			$("#register").click(function() {
				 $("#loginForm").addClass('d-none');
				 $("#registerForm").removeClass('d-none');
			});
			$("#login").click(function() {
				 $("#registerForm").addClass('d-none');
				 $("#loginForm").removeClass('d-none');
			});


			$("#loginAuth").submit(function(e){
				
				e.preventDefault();  
				$.ajaxSetup({
			        headers: {
			            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			        }
			    });
				var data = {email:$("#loginEmail").val(),password:$("#loginPassword").val()}
			
				$.post('{{ route("login.post") }}', {data: data}, function(data, status) {
					swal(data.msg,data.desc,data.color);
						if(data.code==1){
							location.href="dashboard";
						}
					console.log(data)
				});

			});

		

			$("#registerAuth").submit(function(e) {
			$("#registerBTN").html("Registering...")
				e.preventDefault(e);
				$.ajax({
					url:"{{ route('register.post') }}",
					method:"post",
					data:new FormData(this),
					processData:false,
					dataType:"json",
					contentType:false,
					beforeSend:function(){
						$("#usernameError").html();
						$("#emailError").html();
						$("#passwordError").html();
					},
					success:function(data){
						
						if(data.code==1){
						$("#registerBTN").html("SignUp")
						swal(data.msg,data.desc,data.color);
						$("#registerAuth")[0].reset();
						$("#registerForm").addClass('d-none');
				 		$("#loginForm").removeClass('d-none');
					}
						if(data.code==0){
						$("#registerBTN").html("SignUp")
						$("#usernameError").html(data.error.username);
						$("#emailError").html(data.error.email);
						$("#passwordError").html(data.error.password);
					}
					},
					error:function(xhr,status,error){
						console.log(status+error)
					}
				});
			});
		});
			
	</script>
</body>
</html>