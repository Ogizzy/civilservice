<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{ asset('backend/assets/images/login-images/benue-logo.png') }}" type="image/png" />
	<!--plugins-->
	<link href="{{ asset('backend/assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
	<link href="{{ asset('backend/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
	<link href="{{ asset('backend/assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
	<!-- loader-->
	<link href="{{ asset('backend/assets/css/pace.min.css')}}" rel="stylesheet" />
	<script src="{{ asset('backend/assets/js/pace.min.js')}}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('backend/assets/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{ asset('backend/assets/css/bootstrap-extended.css')}}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{ asset('backend/assets/css/app.css')}}" rel="stylesheet">
	<link href="{{ asset('backend/assets/css/icons.css')}}" rel="stylesheet">
	<title>Benue State Civil Service Commission</title>
</head>

<body class="">
	<!--wrapper-->
	<div class="wrapper">
		<header class="login-header shadow">
			<nav class="navbar navbar-expand-lg navbar-light rounded-0 bg-white fixed-top rounded-0 shadow-none border-bottom">
				<div class="container-fluid">
					<a class="navbar-brand" href="{{ url('/')}}">
						<img src="{{ asset('backend/assets/images/login-images/benue-logo.png') }}" width="40" alt="" />
					</a>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent1">
						<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
							<li class="nav-item"> <a class="nav-link active" aria-current="page" href="{{ url('/')}}"><i class='bx bx-home-alt me-1'></i>Home</a>
							</li>
							<li class="nav-item"> <a class="nav-link" href="#"><i class='bx bx-user me-1'></i>About</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</header>
		
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-4">
            <div class="container">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="card my-5 my-lg-0 shadow-none border">
                            <div class="card-body">
                                <div class="p-4">
                                    <div class="text-center mb-4">
                                        <img src="{{ asset('backend/assets/images/icons/forgot-2.png') }}" width="100" alt="Forgot Password" />
                                    </div>
                                    <h4 class="font-weight-bold text-center">Forgot Password?</h4>
                                    <p class="text-muted text-center">Enter your registered Email Address to reset the password</p>
        
                                    <!-- Session Success Message -->
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif
        
                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf
        
                                        <!-- Email Input -->
                                        <div class="mb-4">
                                            <label class="form-label">Email Address</label>
                                            <input type="email" name="email" class="form-control" id="email" placeholder="terhide@gmail.com" value="{{ old('email') }}" required autofocus />
                                            
                                            @error('email')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
        
                                        <!-- Submit and Back Buttons -->
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                Send Reset Link
                                            </button>
                                            <a href="{{ route('login') }}" class="btn btn-light">
                                                <i class='bx bx-arrow-back me-1'></i> Back to Login
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End container -->
        </div>
        
        
		<footer class="bg-white shadow-none border-top p-2 text-center fixed-bottom">
			<p class="mb-0"><script>
                document.write(new Date().getFullYear())
            </script> © Benue State CSC. All right reserved.
             | Developed with <i class="fadeIn animated bx bx-heart text-danger"></i> By BDIC</p>
		</footer>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js')}}"></script>
	<!--plugins-->
	<script src="{{ asset('backend/assets/js/jquery.min.js')}}"></script>
	<script src="{{ asset('backend/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
	<script src="{{ asset('backend/assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
	<script src="{{ asset('backend/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	<!--app JS-->
	<script src="{{ asset('backend/assets/js/app.js')}}"></script>
</body>

</html>