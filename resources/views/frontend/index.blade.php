
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('assets/backend/images/logo-fav.png') }}">

    <title>{{$page_name}}</title>
    
	<!-- Vendors Style-->
	<link rel="stylesheet" href="{{ asset('assets/frontend/css/vendors_css.css') }}">
	  
	<!-- Style-->  
	<link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/frontend/css/skin_color.css') }}">
	<style>
		.custom-img {
			max-width: 70px; /* Sesuaikan lebar maksimum sesuai kebutuhan Anda */
			width: 100%;
			height: auto;
		}
	</style>
	@stack('css')
	@livewireStyles
  </head>

<body class="theme-primary">
	
	<!-- The social media icon bar -->
	<!-- <div class="icon-bar-sticky">
	  <a href="#" class="waves-effect waves-light btn btn-social-icon btn-facebook"><i class="fa fa-facebook"></i></a>
	  <a href="#" class="waves-effect waves-light btn btn-social-icon btn-twitter"><i class="fa fa-twitter"></i></a>
	  <a href="#" class="waves-effect waves-light btn btn-social-icon btn-linkedin"><i class="fa fa-linkedin"></i></a>
	  <a href="#" class="waves-effect waves-light btn btn-social-icon btn-youtube"><i class="fa fa-youtube-play"></i></a>
	</div> -->

    <section class="bg-img pb-30" data-overlay="7" style="background-image: url({{ asset('assets/frontend/banner.jpg') }}); background-position: top center;">
		<div class="container">
			<div class="row">
			<div class="col-12">
				<div class="text-center mt-80">
					<img src="{{ asset('assets/backend/images/logo.png') }}" class="logo-img img-fluid custom-img" alt="logo">
					<h1 class="box-title text-white mb-30">Dashboard Realisasi Investasi</h1>	
				</div>
			</div>
			</div>
		</div>
	</section>	
	
	<section class="container my-5" id="pengaduan">
        <h2 class="text-center mb-4">Pengaduan Terbaru</h2>
        <div class="row">
          
        </div>
    </section>

	<footer class="footer_three">
		<div class="footer-top bg-dark3 pt-50">
            <div class="container">
                <div class="row">
					<div class="col-lg-9 col-12">
                        <div class="widget">
                            <h4 class="footer-title">About</h4>
							<hr class="bg-primary mb-10 mt-0 d-inline-block mx-auto w-60">
							<p class="text-capitalize mb-20">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis exercitation ullamco laboris<br><br>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.</p>
                        </div>
                    </div>											
					<div class="col-lg-3 col-12">
						<div class="widget">
							<h4 class="footer-title">Contact Info</h4>
							<hr class="bg-primary mb-10 mt-0 d-inline-block mx-auto w-60">
							<ul class="list list-unstyled mb-30">
								<li> <i class="fa fa-map-marker"></i> {{$alamat}} </li>
								<li> <i class="fa fa-phone"></i> <span>{{$nope}}<span></span></li>
								<li> <a href="mailto:{{$email}}"> <i class="fa fa-envelope"></i> <span>{{$email}} </span></a></li>
							</ul>
						</div>
					</div>	
                </div>				
            </div>
        </div>
		<div class="footer-bottom bg-dark3">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 col-12 text-md-start text-center"> © 2024 <span class="text-white">{{$app_name}}</span>  All Rights Reserved.</div>
                </div>
            </div>
        </div>
	</footer>
	
	
	<!-- Vendor JS -->
	<script src="{{ asset('assets/frontend/js/vendors.min.js') }}"></script>	
	<!-- Corenav Master JavaScript -->
    {{-- <script src="{{ asset('assets/frontend/corenav-master/coreNavigation-1.1.3.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/frontend/js/nav.js') }}"></script> --}}
	{{-- <script src="{{ asset('assets/frontend/vendor_components/OwlCarousel2/dist/owl.carousel.js') }}"></script>  --}}
	{{-- <script src="{{ asset('assets/frontend/vendor_components/bootstrap-select/dist/js/bootstrap-select.js') }}"></script> --}}
	
	<!-- EduAdmin front end -->
	<script src="{{asset('assets/backend/vendor_components/echarts/dist/echarts-en.min.js')}}"></script>
	<script src="{{ asset('assets/frontend/js/template.js') }}"></script>
	@stack('js')
	@livewireScripts
	
</body>
</html>
