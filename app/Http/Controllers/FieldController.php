<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function index()
    {
        $fields = Field::latest()->get();

        return view('pages.dynamic_feilds.index', compact('fields'));
    }

    public function create()
    {
        return view('pages.dynamic_feilds.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        Field::create([
            'name' => $request->name,
            'type' => $request->type,
            'is_required' => $request->has('is_required'),
        ]);

        return redirect()->route('dynamicFeilds.index');
    }

    public function edit($field2)
    {
        $field = Field::findOrFail($field2);
        return view('pages.dynamic_feilds.edit', compact('field'));
    }

    public function update(Request $request, $field2)
    {
        $field = Field::findOrFail($field2);
        $field->update([
            'name' => $request->name,
            'type' => $request->type,
            'is_required' => $request->has('is_required'),
        ]);

        return redirect()->route('dynamicFeilds.index');
    }

    public function destroy(Request $request, Field $field)
    {
        Field::findOrFail($request->id)->delete();

        return redirect()->route('dynamicFeilds.index');
    }
}
