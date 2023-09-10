<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Activation</title>
</head>
<style>
    .body {
        width: 50%;
        margin: 0px auto;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        border: #000 solid 0.025rem;
        border-radius: 10px;
    }

    .body h3 {
        font-size: 38px;
        letter-spacing: 1px;
        margin: 0.5rem 0px;
    }

    .body .divider {
        width: 100%;
        height: 1px;
        background-color: #4d4848;
        margin: 0.5rem 0px;
    }

    .body .details {
        text-align: center;
        letter-spacing: 1px;
    }

    .body .btn {
        margin: 1.5rem 0px;
        padding: 0.5rem 1rem;
        background-color: #000;
        color: #fff;
        text-decoration: none;
        border-radius: 20px;
        letter-spacing: 1px;
    }

    .body .account {
        line-height: 2rem;
    }

    .body .account p {
        margin: 0px;
        letter-spacing: 1px;
    }

    .body .account .header {
        font-size: 24px;
        font-weight: 500;
    }

</style>
<body>
    
    <div class="body">
        <img style="width:250px;height:250px" src="{{ asset('/assets/img/cce.png') }}" alt="CCERMS Logo">
        <h3>CCERMS: Account activation</h3>

        <span class="divider"></span>

        <p class="details">
            Your email regestered on College of Computing Education Research Management System (CCERMS). 
            Activate your account, click the button to activate.
        </p>

        <a class="btn" href="{{ $url }}">Activate account</a>

        <div class="account">
            <p class="header">Login using this account.</p>
            <p>Email: {{ $user->email }} </p>
            <p>Password: password</p>
        </div>
    </div>
</body>
</html>