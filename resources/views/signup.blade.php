<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>

<style>
    /* body {
        display: flex;
        justify-content: center;
    }
     */
    .form-bg{
        height: 100vh;
        background: #222;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .form-container{
    background: linear-gradient(150deg,#1B394D 33%,#2D9DA7 34%,#2D9DA7 66%,#EC5F20 67%);
    font-family: 'Raleway', sans-serif;
    text-align: center;
    padding: 30px 20px 50px;
}
.form-container .title{
    color: #fff;
    font-size: 23px;
    text-transform: capitalize;
    letter-spacing: 1px;
}
.form-container .form-horizontal{
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
}
.form-horizontal .form-icon{
    color: #fff;
    background-color: #1B394D;
    font-size: 75px;
    line-height: 92px;
    height: 90px;
    width: 90px;
    margin: -65px auto 10px;
    border-radius: 50%;
}
.form-horizontal .form-group{
    margin: 0 0 10px;
    position: relative;
}
/* .form-horizontal .form-group:nth-child(3){ margin-bottom: 30px; } */
.form-horizontal .form-group .input-icon{
    color: #e7e7e7;
    font-size: 23px;
    position: absolute;
    left: 0;
    top: 10px;
}
.form-horizontal .form-control{
    color: #000;
    font-size: 16px;
    font-weight: 600;
    height: 50px;
    padding: 10px 10px 10px 40px;
    margin: 0 0 5px;
    border: none;
    border-bottom: 2px solid #e7e7e7;
    border-radius: 0px;
    box-shadow: none;
}
.form-horizontal .form-control:focus{
    box-shadow: none;
    border-bottom-color: #EC5F20;
}
.form-horizontal .form-control::placeholder{
    color: #000;
    font-size: 16px;
    font-weight: 600;
}
.form-horizontal .forgot{
    font-size: 13px;
    font-weight: 600;
    text-align: right;
    display: block;
}
.form-horizontal .forgot a{
    color: #777;
    transition: all 0.3s ease 0s;
}
.form-horizontal .forgot a:hover{
    color: #777;
    text-decoration:  underline;
}
.form-horizontal .signin{
    color: #fff;
    background-color: #EC5F20;
    font-size: 17px;
    text-transform: capitalize;
    letter-spacing: 2px;
    width: 100%;
    padding: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
    transition: all 0.4s ease 0s;
}
.form-horizontal .signin:hover,
.form-horizontal .signin:focus{
    font-weight: 600;
    letter-spacing: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.3) inset;
}
</style>

<body>

        
        <div class="form-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6" style="margin: 0 auto;">
                        <div class="form-container">
                            <h3 class="title">Đăng kí</h3>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                        @foreach ($errors->all() as $error)
                                            <p class="m-0">{{ $error }}</p>
                                        @endforeach
                                </div>
                            @endif
                            @if (isset($success))
                                <div class="alert alert-sucess">
                                    <p>{{ $success }}</p>
                                </div>
                            @endif
                            <form method="POST" action="{{route("postSignup")}}" class="form-horizontal">
                                @csrf
                                <div class="form-icon">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <div class="form-group">
                                    <span class="input-icon"><i class="fa fa-user"></i></span>
                                    <input name="email" type="email" class="form-control" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <span class="input-icon"><i class="fa fa-user"></i></span>
                                    <input name="name" type="text" class="form-control" placeholder="Name">
                                </div>
                                <div class="form-group">
                                    <span class="input-icon"><i class="fa fa-lock"></i></span>
                                    <input name="password" type="password" class="form-control" placeholder="Password">
                                    <span class="forgot"><a href="">Quên mật khẩu?</a></span>
                                </div>
                                <button type="submit" class="btn signin">Đăng kí</button>
                            </form>
                            <a style="display: inline-block; margin-top: 10px; text-decoration: none; color: rgb(232, 224, 224);" href="/dang_nhap">Quay lại trang đăng nhập</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
    crossorigin="anonymous"></script>
</body>

</html>