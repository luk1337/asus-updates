@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @foreach($devices as $device)
                @if(count($firmwares->where('device.id', $device->id)) > 0)
                    @set('num', 0)
                    <div class="panel panel-default">
                        <div class="panel-heading">{{ $device->name }}</div>
                        <div class="panel-body">
                            <table class="table table-responsive table-nomargin">
                                <thead>
                                    <th>Category</th>
                                    <th>Version</th>
                                    <th>Release date</th>
                                    <th>Description</th>
                                    <th>Download</th>
                                </thead>
                                <tbody>
                                    @foreach($firmwares->where('device.id', $device->id) as $firmware)
                                        @set('num', $num += 1)
                                        <tr>
                                            <td>{{ $firmware->category->name }}</td>
                                            <td>{{ $firmware->version }}</td>
                                            <td>{{ $firmware->release_date }}</td>
                                            <td><a href="javascript:showDescription('{{ base64_encode($firmware->description) }}')">Description</a></td>
                                            <td><a href="{{ $firmware->url }}">Download</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="pull-right">Last update: {{ Cache::get('last_update') }}</div>
</div>
@endsection
