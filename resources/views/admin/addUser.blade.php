
<x-header />
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<div class="container">
    <!-- <div class="row"> -->
    <h1 style="text-align: center;">
    Add  User</h1>
    @if(Session::get('success'))
    <h3 class="text-success">{{ Session::get("success") }}</h3>
    @endif
   
    @if(Session::get('error'))
    <h3 class="text-danger">{{ Session::get("error") }}</h3>
    @endif
   
    <form  id="adduser" class="user" method="post" >
        @csrf
        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="text" class="form-control " id="" value="" placeholder="username" name="username">
          
                <span class="text-danger" id="usernameError"></span>    
          
                
            </div>
            <div class="col-sm-6 mb-3 mb-sm-0">
              <input type="email" class="form-control " id="" name="email" placeholder="email">
          
              <span class="text-danger" id="emailError"></span>    
          
            </div>
        </div>
        <div class="col-sm-6">
            <input type="password" name="password" class="form-control " placeholder="Password">
          
            <span class="text-danger" id="passwordError"></span>    
          
        </div>
        <br>
        <button type="submit" id="addbtn" class="btn btn-primary btn-user btn-block">
            Add user
        </button>
        <hr>
    </form>
    <hr>
    <!-- </div> -->
</div>
<script>
    $(document).ready(function(){

        $("#adduser").submit(function(e) {
            e.preventDefault(e);
            $("#addbtn").html("adding user....");
            $.ajax({
                url:"{{ route('register.post') }}",
                method:"post",
                data:new FormData(this),
                processData:false,
                dataType:"json",
                contentType:false,
              
                success:function(data){
                    
                    if(data.code==1){
                $("#addbtn").html("Add User");
                    swal(data.msg,data.desc,data.color);
                    $("#adduser")[0].reset();
                }
                    if(data.code==0){
                    $("#usernameError").html(data.error.username);
                    $("#emailError").html(data.error.email);
                    $("#passwordError").html(data.error.password);
                }
                },
                error:function(xhr,status,error){
                $("#addbtn").html("Add User");
                    console.log(status+error)
                }
            });

        });
    });
        
</script>
<x-footer />