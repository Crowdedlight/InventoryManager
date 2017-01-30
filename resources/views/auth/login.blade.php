@extends('layout.master')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="jumbotron">
                <h1>Login</h1>


                <?php echo BootForm::open(['route' => 'auth.login']) ?>
                <?php echo BootForm::text('username', 'Username', null,
                        ['placeholder' => 'Enter your Username', 'rows' => 1]); ?>
                <?php echo BootForm::password('password', null,
                        ['placeholder' => 'Enter your Password', 'rows' => 1]); ?>
                <?php echo BootForm::checkbox('remember', null, 1, true); ?>
                <?php echo BootForm::submit('Login', ['class' => 'btn-block btn-primary btn']); ?>
                <?php echo BootForm::close(); ?>



                <?php if (count($errors->all()) > 0): ?>
                <div class="alert alert-danger" role="alert" style="margin-top:50px"><?php echo $errors->first(); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
@endsection
