<?php

namespace App\Http\Controllers;

use App\Console\Commands\UpdateFirmwares;
use App\Device;
use App\Helper;
use Illuminate\Http\Request;

use App\Http\Requests;

class DevicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getList()
    {
        return view('devices.list')->with('devices', Device::all());
    }

    public function getMoveUp($id) {
        $device = Device::findOrFail($id);
        $device_prev = Device::find(Device::where('id', '<', $device->id)->max('id'));

        if ($device_prev != null) {
            Helper::swapData($device, $device_prev, ['name', 'url']);
        }

        return redirect('/dashboard');
    }

    public function getMoveDown($id) {
        $device = Device::findOrFail($id);
        $device_next = Device::find(Device::where('id', '>', $device->id)->min('id'));

        if ($device_next != null) {
            Helper::swapData($device, $device_next, ['name', 'url']);
        }

        return redirect('/dashboard');
    }

    public function getAdd()
    {
        return view('devices.add');
    }

    public function postAdd(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:devices,deleted_at,NULL|max:120',
            'url' => 'required|max:255',
        ]);

        $device = new Device;
        $device->name = $request['name'];
        $device->url = $request['url'];
        $device->save();

        $this->dispatch(new UpdateFirmwares());

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
        $url_changed = $device->url != $request['url'];

        $device->name = $request['name'];
        $device->url = $request['url'];
        $device->save();

        if ($url_changed) {
            $this->dispatch(new UpdateFirmwares());
        }

        return redirect('/dashboard');
    }

    public function getDelete(Request $request, $id)
    {
        $device = Device::findOrFail($id);
        $device->delete();

        $this->dispatch(new UpdateFirmwares());

        return redirect('/dashboard');
    }
}
