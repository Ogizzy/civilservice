<!doctype html>
<html lang="en">

<head> 
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{ asset('backend/assets/images/login-images/benue-logo.png') }}" type="image/png"/>
 
	<!-- Place this in your <head> section -->
<link rel="icon" href="data:," type="image/x-icon">

	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="stylesheet" href="">
	
	<link href="backend/assets/plugins/input-tags/css/tagsinput.css'" rel="stylesheet" />
<!--tagsinput-->
	<link href="{{ asset('backend/assets/plugins/input-tags/css/tagsinput.css') }}" rel="stylesheet" />
<!--tagsinput-->

	<!--plugins-->
	<link href="{{ asset('backend/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet"/>
	<link href="{{ asset('backend/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ asset('backend/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ asset('backend/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet"/>
	
	{{-- My Custom Css Start --}}
	<link href="{{ asset('backend/assets/css/retirees.css') }}" rel="stylesheet"/>
	<link href="{{ asset('backend/assets/css/employee-form.css') }}" rel="stylesheet"/>
	<link href="{{ asset('backend/assets/css/service-account.css') }}" rel="stylesheet"/>
	<link href="{{ asset('backend/assets/css/mda.css') }}" rel="stylesheet"/>
	<link href="{{ asset('backend/assets/css/import-excel.css') }}" rel="stylesheet"/>


	<!-- loader-->
	{{-- {{-- <link href="{{ asset('backend/assets/css/pace.min.css') }}" rel="stylesheet"/> 
	{{-- <script src="{{ asset('backend/assets/js/pace.min.js') }}"></script> --}} 
	<!-- Bootstrap CSS -->
	<link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('backend/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{ asset('backend/assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('backend/assets/css/icons.css') }}" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{ asset('backend/assets/css/dark-theme.css') }}"/>
	<link rel="stylesheet" href="{{ asset('backend/assets/css/semi-dark.css') }}"/>
	<link rel="stylesheet" href="{{ asset('backend/assets/css/header-colors.css') }}"/>
	<!-- Datatable -->
	<link href="{{ asset('backend/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
	{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"> --}}
	{{-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css"> --}}

	<!-- End Datatable -->

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >

	<title>Benue State Civil Service Commission</title>
</head>

{{-- <body style="font-weight:bold"><div style="width:300px;margin:auto auto;"><img src="/resources/images/loading.gif" width="300px"/></div> --}}

	<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		@include('admin.body.sidebar')
		<!--end sidebar wrapper -->
		<!--start header -->
		@include('admin.body.header')
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			@yield('admin')
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		 <div class="overlay toggle-icon"></div>
		<!--end overlay-->
		
		<!--Start Back To Top Button-->
		  <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		@include('admin.body.footer')
	</div>
	<!--end wrapper-->
 
	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>
	<!--plugins-->
	<script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/chartjs/js/chart.js') }}"></script>
	<script src="{{ asset('backend/assets/js/index.js') }}"></script>
	
	{{-- MCustom JS --}}
	<script src="{{ asset('backend/assets/js/employee-form.js') }}"></script>
	<script src="{{ asset('backend/assets/js/service-account.js') }}"></script>
	<script src="{{ asset('backend/assets/js/import-excel.js') }}"></script>

	<!--tagsinput-->
	<script src="{{ asset('backend/assets/plugins/input-tags/js/tagsinput.js') }}"></script>
	<!--tagsinput-->

	<!--app JS-->
	<script src="{{ asset('backend/assets/js/app.js') }}"></script>

	<script src="{{ asset('backend/assets/js/validate.min.js') }}"></script>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script src="{{ asset('backend/assets/js/code.js') }}"></script>

	<script>
		new PerfectScrollbar(".app-container")
	</script>

	<!--Datatable-->
	<script src="{{ asset('backend/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
	
	{{-- <script>
		$(document).ready(function() {
			$('#example').DataTable();
		  } );
	</script> --}}
	<!--End Datatable-->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Include Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

<script>
 @if(Session::has('message'))
 var type = "{{ Session::get('alert-type','info') }}"
 switch(type){
    case 'info':
    toastr.info(" {{ Session::get('message') }} ");
    break;

    case 'success':
    toastr.success(" {{ Session::get('message') }} ");
    break;

    case 'warning':
    toastr.warning(" {{ Session::get('message') }} ");
    break;

    case 'error':
    toastr.error(" {{ Session::get('message') }} ");
    break; 
 }
 @endif
</script>


<script>
	$(document).ready(function() {
		$('#description').summernote({
			placeholder: 'Description...',
			tabsize: 2,
			height: 200,
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'italic', 'underline', 'clear']],
				['fontname', ['fontname']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'picture', 'video']],
				['view', ['fullscreen', 'codeview', 'help']]
			]
		});
	});
</script>

{{-- Hamburger for Mobile Script --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleBtn = document.getElementById("hamburger-toggle");
        const sidebar = document.getElementById("sidebar");

        toggleBtn.addEventListener("click", function () {
            sidebar.classList.toggle("sidebar-visible");
        });
    });
</script>
<style>
    @media (max-width: 991.98px) {
    .sidebar-wrapper {
        position: fixed;
        left: -260px;
        top: 0;
        height: 100%;
        width: 260px;
        background: #fff;
        z-index: 1040;
        transition: all 0.3s ease;
    }

    .sidebar-wrapper.sidebar-visible {
        left: 0;
    }

    .page-wrapper {
        margin-left: 0 !important;
    }
}

    </style>
{{-- End Hamburger for Mobile Script --}}
</body>
</html>