@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit device</div>

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
                            <label for="name" class="col-sm-3 control-label">Category name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" name="name" placeholder="EMI and Safety" value="{{ $category->name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="xpath" class="col-sm-3 control-label">XPath</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="xpath" name="xpath" placeholder='//*[@id="div_type_38\"]//a' value="{{ $category->xpath }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
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
