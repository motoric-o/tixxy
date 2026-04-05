<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\ViewModels\CategoryCrudViewModel;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $search = request('search');
        $rows = Category::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        $viewModel = new CategoryCrudViewModel($rows);

        return view('admin.crud.index', $viewModel->toArray());
    }

    public function create()
    {
        $viewModel = new CategoryCrudViewModel(null, 'create');

        return view('admin.crud.form', $viewModel->toArray());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create(['name' => $request->name]);

        return redirect('/admin/categories')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $viewModel = new CategoryCrudViewModel($category, 'edit');

        return view('admin.crud.form', $viewModel->toArray());
    }

    public function update($id, Request $request)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category->update(['name' => $request->name]);

        return redirect('/admin/categories')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect('/admin/categories')->with('success', 'Category deleted successfully.');
    }
}
