<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCIC Patient Management System (PMS)</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Katibeh&display=swap" rel="stylesheet">
  <style>
    body {
      background: #eee url(http://subtlepatterns.com/patterns/sativa.png);
    }
    html, body {
      position: relative;
      height: 100%;
    }
    .login-container {
      position: relative;
      width: 420px;
      margin: 80px auto;
      padding: 20px 40px 40px;
      text-align: center;
      background: #fff;
      border: 1px solid #ccc;
    }
    #output {
      position: absolute;
      width: 420px;
      top: -75px;
      left: 0;
      color: #fff;
    }
    #output.alert-success {
      background: rgb(25, 204, 25);
    }
    #output.alert-danger {
      background: rgb(228, 105, 105);
    }
    .login-container::before,
    .login-container::after {
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      top: 3.5px;
      left: 0;
      background: #fff;
      z-index: -1;
      transform: rotateZ(4deg);
      border: 1px solid #ccc;
    }
    .login-container::after {
      top: 5px;
      z-index: -2;
      transform: rotateZ(-2deg);
    }
    .avatar {
      width: 100px;
      height: 100px;
      margin: 10px auto 30px;
      border-radius: 100%;
      border: 0px solid #aaa;
      background-size: cover;
      background-image: url('images/bcic_logo.png');
    }
    .form-box input {
      width: 100%;
      padding: 10px;
      text-align: center;
      height: 40px;
      border: 1px solid #ccc;
      background: #fafafa;
      transition: 0.2s ease-in-out;
    }
    .form-box input:focus {
      outline: 0;
      background: #eee;
    }
    .form-box input[type="text"] {
      border-radius: 5px 5px 0 0;
/*      text-transform: lowercase;*/
    }
    .form-box input[type="password"] {
      border-radius: 0 0 5px 5px;
      border-top: 0;
    }
    .form-box button.login {
      margin-top: 15px;
      padding: 10px 20px;
    }
    .animated {
      animation-duration: 1s;
      animation-fill-mode: both;
    }
    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .fadeInUp {
      animation-name: fadeInUp;
    }

      .katibeh-regular {
    font-family: "Katibeh", serif;
    font-weight: 400;
    font-style: normal;
  }
  </style>
</head>
<body>