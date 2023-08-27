@extends('layouts')
@section('section')

    <div class="row mb-2 mt-4">

            <div class="col-md-4">

                    <select name="sname" id="sname" class="form-control">
                        <option value="">slelect Name</option>
                        @foreach ($emp as $item)
                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>

                {{-- <input type="text" name="sname" placeholder="name" id="sname" class="form-control"> --}}
            </div>

        <div class="col-md-4">
            <select name="sjob" id="job" class="form-control">
                <option value="">slelect Job</option>
                @foreach ($emp as $item)
                <option value="{{ $item->job_title }}">{{ $item->job_title }}</option>
                @endforeach
            </select>
            {{-- <input type="text" name="sjob" placeholder="job title" id="job" class="form-control"> --}}
        </div>
        <div class="col-md-4">
            {{-- <input type="text" name="sdepartment" id="department" placeholder="department" class="form-control"> --}}
            <select name="department" id="department" class="form-control">
                <option value="">slelect Department</option>
                @foreach ($emp as $item)
                <option value="{{ $item->department  }}">{{ $item->department }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <a class="btn btn-success" style="float:left;margin-right:20px;" href="{{ url('/add-employee') }}">Create Employee</a>
    <table  class="table table-striped table-bordered emp" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>EmpName</th>
                <th>Email</th>
                <th>JobTitle</th>
                <th>Department</th>
                <th>HireDate</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="emp_list">

        </tbody>
    </table>
@endsection
@section('js')
    <script>
        $(document).ready(function() {





            $(function() {
                var table = $('.emp').DataTable({
                    processing: true,
                    serverSide: true,
                    orderable: false,
                    ajax: {
                        url: "{{ url('api/list-employee') }}",
                        type: 'GET',
                        data: function(d) {
                            d.sname = $('#sname').val(),
                                d.job = $('#job').val(),
                                d.department = $('#department').val()
                            // d.search = $('input[type="search"]').val()
                        },
                    },
                    columns: [
                      /* {
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        }, */
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'job_title',
                            name: 'job_title'
                        },
                        {
                            data: 'department',
                            name: 'department'
                        },
                        {
                            data: 'hire_date',
                            name: 'hire_date'
                        },

                        {
                            data: 'action',
                            name: 'action',
                            render: function(data, type, row) {
                                let emp_id = row['id'];
                                let edit_url = "edit_emp/" + emp_id;
                                let delete_url = "/delete_emp/" + emp_id;
                                return "<a class='btn btn-sm btn-success waves-effect waves-themed' href='" +
                                edit_url + "'>Edit </a>"+"<button class='btn btn-sm btn-danger waves-effect waves-themed delEmp' id='delEmp' data-id='"+emp_id+"'>Delete</button>";
                                //return 'action';
                            }
                        },
                    ]
                });

                 $('#department').change(function() {
                    // alert('hi');
                    table.draw();
                });
                $('#job').change(function() {
                    // alert('hi');
                    table.draw();
                });
                $("#sname").change(function() {
                    table.draw();
                });

            });

             //alert('di');


  $('.emp').on('click', '.delEmp', function() {
            //alert('di');
             var id = $(this).attr('data-id');
             //alert(id);

            $.ajax({
                method: "get",
                url: "/api/delete_emp/",
                data: {
                _token: "{{ csrf_token() }}",
                id: id
                },
                success: (result) => {
                    console.log('success');
                    window.location.replace("{{url('/')}}");
                },
                error: (error) => {

                }
            });
        });
        });
    </script>
@endsection
