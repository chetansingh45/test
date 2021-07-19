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