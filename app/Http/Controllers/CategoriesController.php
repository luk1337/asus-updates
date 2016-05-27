<?php

namespace App\Http\Controllers;

use App\Category;
use App\Device;
use App\Firmware;
use Illuminate\Http\Request;

use App\Http\Requests;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'getShow']);
    }

    public function getList()
    {
        return view('categories.list')->with('categories', Category::all());
    }

    public function getShow(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        return view('categories.show')
            ->with('firmwares', Firmware::all()->where('category.id', $category->id))
            ->with('devices', Device::all());
    }

    public function getAdd()
    {
        return view('categories.add');
    }

    public function postAdd(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories,deleted_at,NULL|max:120',
            'xpath' => 'required|max:255',
        ]);

        $category = new Category;
        $category->name = $request['name'];
        $category->xpath = $request['xpath'];
        $category->save();

        return redirect('/dashboard');
    }

    public function getEdit(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        return view('categories.edit')->with('category', $category);
    }

    public function postEdit(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:120',
            'xpath' => 'required|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request['name'];
        $category->xpath = $request['xpath'];
        $category->save();

        return redirect('/dashboard');
    }

    public function getDelete(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect('/dashboard');
    }
}
