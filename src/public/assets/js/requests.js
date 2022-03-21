$(document).ready(function(){
    $("#signUp").click(function(){
       //console.log(validateSignup());
       if(validateSignup()){
           $.ajax({
            url:'http://localhost:8080/Signup/register',
            type:'POST',
            data:{
                'name':$("#name").val(),
                'email':$("#email").val(),
                'password':$("#password").val()
            },
            success:function(data){
                console.log(data);
                if(data){
                    window.location.replace("http://localhost:8080/login");
                }else{
                    $("#message").addClass('alert-danger');
                    $("#message").html("User already exist!");
                }
            }
           });
       }
    });
    $("#login").click(function(){
        // console.log(validateLogin());
        if(validateLogin()){
            $.ajax({
             url:'http://localhost:8080/Login/login',
             type:'POST',
             data:{
                 'email':$("#email").val(),
                 'password':$("#password").val()
             },
             success:function(data){
                 if(data==0){
                    $("#message").html('Error, invalid email or password!');
                    $("#message").addClass('alert-danger');
                 }else{
                    // $("#message").html('Logged In');
                    // $("#message").removeClass('alert-danger');
                    // $("#message").addClass('alert-success');
                    window.location.replace("http://localhost:8080");
                 }
             }
            });
        }
     });
});
function validateLogin(){
    var flag=false;
    var email=$("#email").val();
    var password=$("#password").val();
    if(email==''){
        $("#errorEmail").html('Email Should not be empty');
        flag=false;
    }else{
        $("#errorEmail").html('');
        flag=true;
    }
    if(password==''){
        $("#errorPassword").html('Password Should not be empty');
        flag=false;
    }else{
        $("#errorPassword").html('');
        flag=true;
    }
    return flag;
}
function validateSignup(){
    var flag=false;
    var name=$("#name").val();
    var email=$("#email").val();
    var password=$("#password").val();
    var confirmPassword=$("#confirmPassword").val();
    if(name==''){
        $("#errorName").html('Name Should not be empty');
        flag=false;
    }else{
        $("#errorName").html('');
        flag=true;
    }
    if(email==''){
        $("#errorEmail").html('Email Should not be empty');
        flag=false;
    }else{
        $("#errorEmail").html('');
        flag=true;
    }
    if(password==''){
        $("#errorPassword").html('Password Should not be empty');
        flag=false;
    }else{
        $("#errorPassword").html('');
        flag=true;
    }
    if(confirmPassword==''){
        $("#errorConfirmPassword").html('Should not be empty');
        flag=false;
    }else{
        if(password!=confirmPassword){
            $("#errorConfirmPassword").html('Password did not match');
            flag=false;
        }else{
            $("#errorConfirmPassword").html('');
            flag=true;
        }
    }
    return flag;
}