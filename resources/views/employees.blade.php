<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Employee Data</h1>
        <a class="btn btn-success" href="javascript:void(0)" id="createNewEmployee" style="float:right">Add</a>
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="employeeForm" name="employeeForm" class="form-horizontal">
                        <input type="hidden" name="employee_id" id="employee_id">
                        <div class="form-group">
                            Name: <br>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" required>
                        </div>
                        <div class="form-group">
                            Email: <br>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="" required>
                            @error('email')
                                 <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            Phone: <br>
                            <input type="number" class="form-control" id="phone" name="phone" placeholder="Enter phone" value="" required>
                        </div>
                        <div class="form-group">
                            Gender:<br>
                            <div class="col-sm-10">
                                <select name="gender" class="form-control" required id="gender">
                                    <option value="">--select-</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary btn-sm" id="saveBtn" value="create">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>


    <script type="text/javascript">
    $(function() {
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    var table = $('.data-table').DataTable({
        serverSide:true,
        processing:true,
        ajax:"{{route('employees.index')}}",
            columns:[
                {data:'DT_RowIndex',name:'DT_RowIndex'},
                {data:'name',name:'name'},
                {data:'email',name:'email'},
                {data:'phone',name:'phone'},
                {data:'gender',name:'gender'},
                {data:'action',name:'action'},
            ]
        });
    

        $("#createNewEmployee").click(function(){
            $("#employee_id").val('');
            $("#employeeForm").trigger("reset");
            $("#modalHeading").html("Add Employee");
            $('#ajaxModel').modal('show');
        });
        $("#saveBtn").click(function(e){

            //alert('test');
            e.preventDefault();
            $(this).html('Save');

            $.ajax({
                data:$("#employeeForm").serialize(),
                url:"{{route('employees.store')}}",
                type:"POST",
                datatype:'json',
                success:function(data){
                    $("#employeeForm").trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();
                },
                error:function(data){
                    console.log('Error:',data);
                    $("#saveBtn").html('Save');
                }
            });
        });
        $('body').on('click','.deleteEmployee',function(){
            var employee_id = $(this).data("id");
            confirm("Are you sure want to delete!");
            $.ajax({
                type:"DELETE",
                url:"{{route('employees.store')}}"+'/'+employee_id,
                success:function(data){
                    table.draw();
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        });
        $('body').on('click','.editEmployee',function(){
            var employee_id = $(this).data('id');
            $.get("{{route('employees.index')}}"+"/"+employee_id+"/edit",function(data){
                $("modalHeading").html("Edit Employee");
                $('#ajaxModel').modal('show');
                $("#employee_id").val(data.id);
                $("#name").val(data.name);
                $("#email").val(data.email);
                $("#phone").val(data.phone);
                $("#gender").val(data.gender);
                
            });
    });

    </script>
</body>
</html>