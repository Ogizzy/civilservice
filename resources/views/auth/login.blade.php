<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!--favicon-->
    <link rel="icon" href="{{ asset('backend/assets/images/login-images/benue-logo.png') }}" type="image/png" />
    <link href="{{ asset('backend/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
     <!-- Bootstrap CSS -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/css/icons.css') }}" rel="stylesheet">

    <title>Benue State CSC Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet"/>

    <link rel="stylesheet" href="{{ asset('backend/assets/login/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" />

    <!-- Recaptcha Script -->
    <script src="https://www.google.com/recaptcha/api.js"></script>
  </head>

  <body>
    <div class="login-container">
      <!-- Left: Form -->
      <div class="login-form">
        <div class="form-inner">
          <img src="{{ asset('backend/assets/login/benue-logo.png')}}" alt="Benue Logo" class="logo" width="100" />
          <h4><b>Benue State Civil Service Commission</b></h4>
          <p class="welcome-text" style="color: blue">
            Please Sign into your account
          </p>

          <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email / Subhead No -->
            <label for="login">Email / Subhead No</label>
            <div class="input-group">
              <input type="text" id="login" name="login" value="{{ old('login') }}" class="form-control @error('login') is-invalid @enderror" placeholder="Enter Your Email or Subhead No" />
              @error('login')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <!-- Password -->
            <label for="password">Password</label>
            <div class="input-group password-field" id="show_hide_password">
              <input type="password" name="password" id="password" class="form-control border-end-0 @error('password') is-invalid @enderror" placeholder="Enter Your Password" />
              <span class="toggle-password input-group-text bg-transparent"><i class="bx bx-hide"></i></span>
              @error('password')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
            <br>
            <!-- Recaptcha -->
            <div class="g-recaptcha" data-sitekey="6LdWMVUrAAAAAKjvTH4nZ2G3yvuEU_50jR-wRFQB"></div>
            @error('g-recaptcha-response')
            <span class="text-danger">{{ $message }}</span>
            @enderror

            <!-- Submit -->
            <button type="submit" class="btn-login" style="font-size: 140%"><b>Log in</b></button>

            <!-- Forgot Password -->
            <div class="forgot-password">
              <a href="{{ route('password.request') }}">Forgot Password?</a>
            </div>
          </form>
        </div>
      </div>

      <!-- Right: Image + Text -->
      <div class="login-image">
        <img src="{{ asset('backend/assets/login/people.jpg')}}" alt="People" class="hero-img" />
        <div class="image-text">
          <h2>Creating a Digital Benue</h2>
          <p>
            Modernizing Civil Service through innovative technology solutions,
            delivering excellence in public service management, and streamlining
            workforce management for greater government efficiency.
          </p>
        </div>
      </div>
    </div>

    <!-- jQuery (for password toggle) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Password show/hide -->
    <script>
      $(document).ready(function () {
        $("#show_hide_password .toggle-password").on("click", function (event) {
          event.preventDefault();
          var input = $("#show_hide_password input");
          var icon = $("#show_hide_password i");
          if (input.attr("type") === "text") {
            input.attr("type", "password");
            icon.addClass("bx-hide").removeClass("bx-show");
          } else {
            input.attr("type", "text");
            icon.removeClass("bx-hide").addClass("bx-show");
          }
        });
      });
    </script>

    <!-- Toastr -->
    <script
      type="text/javascript"
      src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
      @if (Session::has('message'))
          var type = "{{ Session::get('alert-type', 'info') }}";
          switch (type) {
              case 'info': toastr.info("{{ Session::get('message') }}"); break;
              case 'success': toastr.success("{{ Session::get('message') }}"); break;
              case 'warning': toastr.warning("{{ Session::get('message') }}"); break;
              case 'error': toastr.error("{{ Session::get('message') }}"); break;
          }
      @endif
    </script>

    {!! NoCaptcha::renderJs() !!}
  </body>
</html>
