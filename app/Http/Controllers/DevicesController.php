<?php

namespace App\Http\Controllers;

use App\Device;
use Illuminate\Http\Request;

use App\Http\Requests;

class DevicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAdd()
    {
        return view('devices.add');
    }

    public function postAdd(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:devices|max:120',
            'url' => 'required|max:255',
        ]);

        $device = new Device;
        $device->name = $request['name'];
        $device->url = $request['url'];
        $device->save();

        return redirect('/dashboard');
    }

    public function getEdit(Request $request, $id)
    {
        $device = Device::findOrFail($id);

        return view('devices.edit')->with('device', $device);
    }

    public function postEdit(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:120',
            'url' => 'required|max:255',
        ]);

        $device = Device::findOrFail($id);
        $device->name = $request['name'];
        $device->url = $request['url'];
        $device->save();

        return redirect('/dashboard');
    }

    public function getDelete(Request $request, $id)
    {
        $device = Device::findOrFail($id);
        $device->delete();

        return redirect('/dashboard');
    }
}
