@extends('layouts')
@section('section')
    <div class="row">
        <div class="container mt-4 py-5 w">
            <h4 class="text-center ">Add Emplyoee</h4>
            <form class="p-md-5 border rounded-3 bg-body-tertiary" method="POST" id="submit-form">
                @csrf
                <!-- Name input -->
                <div class="form-floating mb-3">
                    <input class="form-control" name="name" id="name" type="text" />
                    <label class="form-label" for="name">Emplyoee Name</label>
                </div>
                <!-- Email input -->
                <div class="form-floating mb-3">
                    <input class="form-control" id="email" type="email" name="email" />
                    <label class="form-label" for="email">Email address</label>
                </div>
                <!-- jobtitle input -->
                <div class="form-floating mb-3">
                    <input class="form-control" id="job_title" type="text" name="job_title" />
                    <label class="form-label" for="job_title">Job Title</label>
                </div>
                <!-- department input -->
                <div class="form-floating mb-3">
                    <input class="form-control" id="department" type="text" name="department" />
                    <label class="form-label" for="department">Department</label>
                </div>
                <!-- hire_date input -->
                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="hire_date" name="hire_date">
                    <label class="form-label" for="hire_date">Date</label>
                </div>

                <!-- Submit button -->
                <button class="w-100 btn btn-lg btn-primary" type="submit" id="submit-button">Submit</button>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $("#submit-form").submit(function(e) {
            e.preventDefault();
            const formData = $(this);
            const submitButton = $("#submit-button");
            const post_id = $("#post-id-comment").val();
            submitButton.html('Saving....<i class="fa fa-spin fa-spinner" aria-hidden="true"></i>');
            $.ajax({
                method: "POST",
                url: "/api/store-employee",
                data: formData.serialize(),
                success: (result) => {
                    window.location.replace("{{url('/')}}");
                },
                error: (error) => {

                }
            });
        });
    </script>
@endsection
