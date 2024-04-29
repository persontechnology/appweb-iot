
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Global stylesheets -->
	<link href="{{ asset('assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/ltr/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{ asset('assets/demo/demo_configurator.js') }}"></script>
	<script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="{{ asset('assets/js/app.js') }}"></script>
	<!-- /theme JS files -->

</head>

<body class="bg-dark">

	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Inner content -->
			<div class="content-inner">

				<!-- Content area -->
				<div class="content d-flex justify-content-center align-items-center">

					<!-- Login card -->
					
						<div class="card mb-0 login-form">
							<div class="card-body">
								<div class="text-center mb-3">
									<div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
										<img src="{{ asset('assets/images/logo_icon.svg') }}" class="h-48px" alt="">
									</div>
									<h5 class="mb-0">
                                        @yield('code')
                                    </h5>
									<span class="d-block text-muted">
                                        @yield('message')
                                    </span>
								</div>

								<div class="mb-3">

                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary w-100 my-2">
                                        <i class="ph ph-house me-2"></i>
                                        Inicio
                                    </a>
                                    
									<form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a href="{{ route('logout') }}" class="btn btn-danger w-100" onclick="event.preventDefault(); this.closest('form').submit();">
                                            <i class="ph-sign-out me-2"></i>
                                            Cerrar sesión
                                        </a>
                                    </form>
								</div>

                                
							</div>
						</div>
                    
					
					<!-- /login card -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /inner content -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->


	<!-- Demo config -->
	@include('layouts.demo-config')
	<!-- /demo config -->

</body>
</html>
