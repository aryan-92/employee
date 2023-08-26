@extends('layouts')

@section('section')
<input type="hidden" id="hid" name="hid">
@endsection
@section('js')
<script>
    $(document).ready(function() {
            var id = "{{Str::afterLast(Request::url(), '/')}}";
            $.ajax({
                method: "get",
                url: "/api/delete_emp/"+ id,
                // data: formData.serialize(),
                success: (result) => {
                    window.location.replace("{{url('/')}}");
                },
                error: (error) => {

                }
            });
        });
</script>
@endsection
