
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
    	<title><?php echo (!empty($title)) ? 'Title-'.$title: 'Look Me Cosmetic || Feel the Beauty'; ?></title>
    	<link rel="icon" type="image/png" href="{{ asset('/front-assets/images/logo.png') }}">		
    	<!-- Google Font: Source Sans Pro -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="{{asset('admin-assets/plugins/fontawesome-free/css/all.min.css')}}">
		<!-- Theme style -->
		<link rel="stylesheet" href="{{asset('admin-assets/css/adminlte.min.css')}}">
		<link rel="stylesheet" href="{{asset('admin-assets/css/custom.css')}}">
	</head>
	<body class="hold-transition login-page">

        	<div class="login-box">
			<!-- /.login-logo -->
            @include('admin.message')
			<div class="card card-outline card-primary">
			  	<div class="card-header text-center">
					<a href="{{ route('admin.dashboard') }}" class="h3">Administrative Panel</a>
			  	</div>
			  	<div class="card-body">
					<p class="login-box-msg">Reset Your Password</p>

		  			<p class="mb-1 mt-3">
		  			    <form action="{{ route('admin.resetPassword') }}" method="post">
                            @csrf
                            <h4 class="modal-title">Reset Password</h4>
            
                            <div class="form-group">
                                <input type="password" class="form-control" @error('password') is-invalid @enderror placeholder="Password" required="required" name="password" id="password">
                                @error('password')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" @error('password_confirmation') is-invalid @enderror placeholder="Password Confirmation" required="required" name="password_confirmation" id="password_confirmation">
                                @error('password_confirmation')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="text" readOnly class="form-control" @error('phone_number') is-invalid @enderror placeholder="Phone" required="required" name="phone_number" id="phone_number" value="{{ Session::get('phone_number') }}">
                                @error('phone_number')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-5" >Reset</button>
                        </form>
                    <div class="text-center small">Remember your password? <a href="{{ route('account.login') }}">Login</a></div>
					</p>
			  	</div>
			  	<!-- /.card-body -->
			</div>
			<!-- /.card -->
		</div>
        
        


		<!-- jQuery -->
		<script src="{{asset('admin-assets/plugins/jquery/jquery.min.js')}}"></script>
		<!-- Bootstrap 4 -->
		<script src="{{asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
		<!-- AdminLTE App -->
		<script src="{{asset('admin-assets/js/adminlte.min.js')}}"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="{{asset('admin-assets/js/demo.js')}}"></script>
	</body>
</html>


