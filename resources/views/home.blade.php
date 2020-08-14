@extends('layouts.app')

@section('content')
    <div class="container">
        <nav class="navbar navbar-expand-md navbar-light bg-light">
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav">
                    <a href="#" class="nav-item nav-link active">Home</a>
                    <a href="wallet/add" class="nav-item nav-link">Add new wallet</a>
                </div>
            </div>
        </nav>
        <div class="row">
            <div class="col-md-4">
                <h6 class="text-muted"><?php echo e(__('List of your wallets')); ?></h6>
                <ul class="list-group">
                    @foreach ($wallets as $id => $wallet)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{$wallet['name']}}<a href="/wallet/{{$wallet['id']}}/add" >add</a> / <a href="/wallet/{{$wallet['id']}}/subtract" >subtract</a>
                        <span class="badge badge-primary badge-pill">{{$wallet['balance']}}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-8">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{$error}}
                        </div>
                    @endforeach
                @endif
                <h6 class="text-muted">List Group with Badges</h6>
                <div class="card">
                    <div class="card-header"><?php echo e(__('Dashboard')); ?></div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                        Your over all Balance is : {{$all}}
                        </li>
                    </ul>
                    <div class="card-body">
                        <?php if(session('status')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
