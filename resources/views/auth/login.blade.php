@extends('layout.master')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="jumbotron">
                <h1>Login</h1>


                <?php echo BootForm::open()->post()->action(route('auth.login')); ?>
                <?php echo BootForm::text('Username', 'username')->placeholder('Enter your Username'); ?>
                <?php echo BootForm::password('Password', 'password')->placeholder('Enter your Password'); ?>
                <?php echo BootForm::checkbox('remember', null, 1, true); ?>
                <?php echo BootForm::submit('Login')->addClass('btn-block btn-primary btn'); ?>
                <?php echo BootForm::close(); ?>

                <?php if (count($errors->all()) > 0): ?>
                <div class="alert alert-danger" role="alert" style="margin-top:50px"><?php echo $errors->first(); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
@endsection
