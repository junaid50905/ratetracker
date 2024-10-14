@extends('ui.backend.layouts.default')

@section('title')
    Create user
@endsection



@section('main-panel')
    <div class="row">
        <div class="col-12">
            <a href="{{ route('users') }}" class="btn btn-sm btn-dark float-end mb-2"><span class="mdi mdi-keyboard-backspace"></span> Back</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="">
                        <div class="mb-2">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
