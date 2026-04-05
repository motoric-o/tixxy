<?php

namespace App\ViewModels;

use Illuminate\Contracts\Support\Arrayable;

class CategoryCrudViewModel implements Arrayable
{
    private $categories;
    private $action;

    public function __construct($categories, $action = 'index')
    {
        $this->categories = $categories;
        $this->action = $action;
    }

    public function toArray(): array
    {
        return [
            'title'     => $this->action == 'index' ? 'Categories' : ($this->action == 'create' ? 'Create Category' : 'Edit Category: ' . $this->categories->name),
            'columns'   => $this->columns(),
            'rows'      => $this->categories,
            'filters'   => [],
            'createUrl' => '/admin/categories/create',
            'editUrl'   => '/admin/categories',
            'backUrl'   => '/admin/categories',
            'action'    => $this->action,
            'item'      => $this->categories,
            'fields'    => $this->fields(),
            'detailFields' => $this->action === 'edit' ? $this->detailFields() : [],
        ];
    }

    private function detailFields(): array
    {
        $category = $this->categories;
        return [
            ['label' => 'Category ID',       'value' => '#' . $category->id],
            ['label' => 'Associated Events',  'value' => $category->events_count ?? $category->events()->count()],
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'created_at', 'label' => 'Created At'],
        ];
    }

    private function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Category Name', 'type' => 'text', 'placeholder' => 'e.g. Music, Sports, Tech', 'required' => true],
        ];
    }
}
