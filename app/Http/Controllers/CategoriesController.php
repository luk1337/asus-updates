<?php

namespace App\Http\Controllers;

use App\Category;
use App\Console\Commands\UpdateFirmwares;
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

    public function getMoveUp($id) {
        $category = Category::findOrFail($id);
        $category_prev = Category::find(Category::where('id', '<', $category->id)->min('id'));

        if ($category_prev != null) {
            // Cache current categories
            $category_1 = clone $category;
            $category_2 = clone $category_prev;

            // Swap name and XPath
            $category_1->name = $category_prev->name;
            $category_1->xpath = $category_prev->xpath;
            $category_2->name = $category->name;
            $category_2->xpath = $category->xpath;

            // Save both categories
            $category_1->save();
            $category_2->save();
        }

        return redirect('/dashboard');
    }

    public function getMoveDown($id) {
        $category = Category::findOrFail($id);
        $category_next = Category::find(Category::where('id', '>', $category->id)->min('id'));

        if ($category_next != null) {
            // Cache current categories
            $category_1 = clone $category;
            $category_2 = clone $category_next;

            // Swap name and XPath
            $category_1->name = $category_next->name;
            $category_1->xpath = $category_next->xpath;
            $category_2->name = $category->name;
            $category_2->xpath = $category->xpath;

            // Save both categories
            $category_1->save();
            $category_2->save();
        }

        return redirect('/dashboard');
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

        $this->dispatch(new UpdateFirmwares());

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
        $xpath_changed = $category->xpath != $request['xpath'];

        $category->name = $request['name'];
        $category->xpath = $request['xpath'];
        $category->save();

        if ($xpath_changed) {
            $this->dispatch(new UpdateFirmwares());
        }
        return redirect('/dashboard');
    }

    public function getDelete(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        $this->dispatch(new UpdateFirmwares());

        return redirect('/dashboard');
    }
}
