@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit device</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="post">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-sm-3 control-label">Category name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" name="name" placeholder="EMI and Safety" value="{{ $category->name }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('xpath') ? ' has-error' : '' }}">
                            <label for="xpath" class="col-sm-3 control-label">XPath</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="xpath" name="xpath" placeholder='//*[@id="div_type_38\"]//a' value="{{ $category->xpath }}">

                                @if ($errors->has('xpath'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('xpath') }}</strong>
                                    </span>
                                @endif
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
