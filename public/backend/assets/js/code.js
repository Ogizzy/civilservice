$(document).on('click', '.delete-btn', function (e) {
  e.preventDefault();

  let form = $(this).closest('form'); // Get the closest form element

  Swal.fire({
      title: 'Are you sure?',
      text: "This action can not be undone. Do you want to proceed?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
      if (result.isConfirmed) {
          form.submit(); // Submit the form when confirmed
      }
  });
});
