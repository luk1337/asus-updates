@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Add category</div>

                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="form-horizontal" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Category name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" placeholder="EMI and Safety">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="xpath" class="col-sm-2 control-label">XPath</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="xpath" name="xpath" placeholder='//*[@id="div_type_38\"]//a'>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Add category</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
