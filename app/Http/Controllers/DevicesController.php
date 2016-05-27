<?php

namespace App\Http\Controllers;

use App\Console\Commands\UpdateFirmwares;
use App\Device;
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
        $device_prev = Device::find(Device::where('id', '<', $device->id)->min('id'));

        if ($device_prev != null) {
            // Cache current devices
            $device_1 = clone $device;
            $device_2 = clone $device_prev;

            // Swap name and URL
            $device_1->name = $device_prev->name;
            $device_1->url = $device_prev->url;
            $device_2->name = $device->name;
            $device_2->url = $device->url;

            // Save both devices
            $device_1->save();
            $device_2->save();
        }

        return redirect('/dashboard');
    }

    public function getMoveDown($id) {
        $device = Device::findOrFail($id);
        $device_next = Device::find(Device::where('id', '>', $device->id)->min('id'));

        if ($device_next != null) {
            // Cache current devices
            $device_1 = clone $device;
            $device_2 = clone $device_next;

            // Swap name and URL
            $device_1->name = $device_next->name;
            $device_1->url = $device_next->url;
            $device_2->name = $device->name;
            $device_2->url = $device->url;

            // Save both devices
            $device_1->save();
            $device_2->save();
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
        $device->name = $request['name'];
        $device->url = $request['url'];
        $device->save();

        $this->dispatch(new UpdateFirmwares());

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
