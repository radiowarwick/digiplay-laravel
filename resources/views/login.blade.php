<!DOCTYPE html>
<html>
	<head>
		<title>RAW Digiplay - Login</title>

		<meta lang="en">
		<link rel="stylesheet" type="text/css" href="/css/app.css">
	</head>
	<body class="bg-light">
		<div class="container">
			<div class="row">
				<div class="login-absolute-center bg-dark text-white">
					<div class="logo-login">
						@include('layouts.logo')
					</div>
					<h2 class="text-white text-center">Members&apos; Area</h2>
					<form>
						<div class="form-group">
							<div class="input-group mb-2">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-user" aria-hidden="true"></i>
									</div>
								</div>
								<input type="text" class="form-control" id="username" placeholder="Username">
							</div>
						</div>
						<div class="form-group">
							<div class="input-group mb-2">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fa fa-lock" aria-hidden="true"></i>
									</div>
								</div>
								<input type="password" class="form-control" id="password" placeholder="Password">
							</div>
						</div>
						<div class="form-group">
							<button class="btn btn-warning text-white" type="submit">Login</button>
						</div>
					</form>
					<p>Forgotten your password? <a href="#" class="text-warning">Click here!</a>
				</div>
			</div>
		</div>
	</body>
</html>