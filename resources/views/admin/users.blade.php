<x-header />
<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="{{asset('admin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Users List </h6>
                          
                        </div>
                       
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                   
                                    <tr id="addnewUserRow">
                                        <form id="adduser">
                                            @csrf

                                            <td><input type="text" name="username" placeholder="Username" class="form-control"><span id="usernameError"></span></td>
                                            <td><input type="text" name="email" class="form-control" placeholder="email"><span id="emailError"></span></td>
                                            <td><input type="text" name="password" class="form-control" placeholder="password"><span id="PasswordError"></span></td>
                                            <td><button id="addUserBtn" class="btn btn-success">Add User</button></td>
                                        </form>
                                    </tr>
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <tr>
                                            <th>id</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                        </tr>
                                    </tfoot>
                                    <tbody id="tbody">
                                        <?php //print_r($posts); ?>
                                       @foreach($users as $user)
                                        <tr id="userRow{{$user->id}}">
                                            <td>{{ $user->id}}</td>
                                            <td>{{ $user->username}}</td>
                                            <td>{{ $user->email}}</td>
                                            <td>
                                                <button class='btn btn-danger btn-circle' onclick="deleteUser({{$user->id}})" ><i class="fas fa-trash"></i></button>
                                                <button class='btn btn-success btn-circle' onclick="editUser({{$user->id}})"  data-toggle="modal" data-target="#logoutModal"  ><i class="fas fa-pen"></i></button>
                                            </td>
                                           
                                        </tr>
                                        @endforeach
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update User</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post" id="updateForm">
            <div class="modal-body" id="modelBody">
                    @csrf
                <label>Username</label><input class="form-control" type="text" id="username" name="username">
                <label>Email</label><input type="email" class="form-control" id="email" name="email">
                <label>password</label><input class="form-control" type="password" id="password" name="password">
                <input id="userId" name="userId" type="hidden" >
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="submit">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    function deleteUser(id){

            swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this user again!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
            })
            .then((willDelete) => {
                    if (willDelete) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        }
                        });
                        var data ={id:id};
                    $.post("{{route('user.delete')}}",{data:data},function(data,ststus){
                        if(data.code==1){
                        $("#userRow"+id).fadeOut("slow"); 
                        swal("Poof! Your user has been deleted!", {
                        icon: "success",
                        });
                        }else{
                            swal("Error",data.msg,"error");
                        }
                    });
                    } else {
                        swal("Your user is safe!");
                    }
            });
    }

     function editUser(id){
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
            });
            var data ={id:id};
        $.post("{{route('user.edit')}}",{data:data},(data,status)=>{
            $("#username").val(data.user.username);
            $("#email").val(data.user.email);
            $("#password").val(data.user.password);
            $("#userId").val(data.user.id);
        })
     }
     $(document).ready(function(){
      
     $("#updateForm").submit(function(e) {
				e.preventDefault(e);
				$.ajax({
					url:"{{ route('user.update') }}",
					method:"post",
					data:new FormData(this),
					processData:false,
					dataType:"json",
					contentType:false,
					success:function(data){
						console.log(data)
					 	if(data.code==1){
						swal(data.msg,data.desc,data.color);
						$("#updateForm")[0].reset();
                         var row="<td>"+data.data.userId+"</td>";
                         row+="<td>"+data.data.username+"</td>";
                         row+="<td>"+data.data.email+"</td>";
                         row+="<td> <button class='btn btn-danger btn-circle' onclick='deleteUser({{$user->id}})' ><i class='fas fa-trash'></i></button>";
                         row+="<button class='btn btn-success btn-circle' onclick='editUser({{$user->id}})'  data-toggle='modal' data-target='#logoutModal'  ><i class='fas fa-pen'></i></button></td>";
                            $("#userRow"+data.data.userId).html(row)
					}
						if(data.code==0){
                        swal(data.msg,data.desc,data.color);
					}
					},
					error:function(xhr,status,error){
						console.log(status+error)
					}
				});
			});

            $("#adduser").submit(function(e) {
            e.preventDefault(e);
            $("#addUserBtn").html("adding user....");
            $.ajax({
                url:"{{ route('register.post') }}",
                method:"post",
                data:new FormData(this),
                processData:false,
                dataType:"json",
                contentType:false,
              
                success:function(data){
                    
                    if(data.code==1){
                $("#addUserBtn").html("Add User");
                    swal(data.msg,'Users added successfully',data.color);
                    $("#adduser")[0].reset();
                    var row='<tr>';
                            row+='<td>'+data.user.id+'</td>';
                            row+='<td>'+data.user.username+'</td>';
                            row+='<td>'+data.user.email+'</td>';
                            row+="<td> <button class='btn btn-danger btn-circle' onclick='deleteUser("+data.user.id+")' ><i class='fas fa-trash'></i></button>";
                            row+="<button class='btn btn-success btn-circle' onclick='editUser("+data.user.id+")'  data-toggle='modal' data-target='#logoutModal'  ><i class='fas fa-pen'></i></button></td>";
                            row+='</tr>';
                    $("#tbody").append(row);
                }
                    if(data.code==0){
                        $("#addUserBtn").html("Add User");
                    $("#usernameError").html(data.error.username);
                    $("#emailError").html(data.error.email);
                    $("#passwordError").html(data.error.password);
                }
                },
                error:function(xhr,status,error){
                $("#addUserBtn").html("Add User");
                    console.log(status+error)
                }
            });

        });
		});
        
</script>
 <x-footer />