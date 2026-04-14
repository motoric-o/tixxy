<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\ViewModels\CategoryCrudViewModel;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $search = request('search');
        $dateFrom = request('date_from');
        $dateTo = request('date_to');
        
        $sort = request('sort', 'id');
        $direction = request('direction', 'desc');

        $rows = Category::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->withCount('events');
            
        // Handle sorting for aggregated column
        if ($sort === 'events_count') {
             $rows = $rows->orderBy('events_count', $direction);
        } else {
             $rows = $rows->orderBy($sort, $direction);
        }

        $rows = $rows->paginate(10)->withQueryString();

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

        return redirect('/manage/categories')->with('success', 'Category created successfully.');
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

        return redirect('/manage/categories')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->events()->exists()) {
            return redirect('/manage/categories')->with('error', 'Category cannot be deleted because it has associated events.');
        }

        $category->delete();

        return redirect('/manage/categories')->with('success', 'Category deleted successfully.');
    }
}
