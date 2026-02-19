<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::where('tenant_id', auth()->user()->tenant_id)->with('items')->get();
        return view('tenant.menus.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:100',
        ]);
        $validated['tenant_id'] = auth()->user()->tenant_id;
        Menu::create($validated);
        return response()->json(['success' => true, 'message' => 'Menu created', 'redirect' => route('tenant.menus.index')]);
    }

    public function update(Request $request, Menu $menu)
    {
        if ($menu->tenant_id !== auth()->user()->tenant_id) abort(403);
        $menu->update($request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:100',
        ]));
        return response()->json(['success' => true, 'message' => 'Menu updated']);
    }

    public function destroy(Menu $menu)
    {
        if ($menu->tenant_id !== auth()->user()->tenant_id) abort(403);
        $menu->delete();
        return response()->json(['success' => true, 'message' => 'Menu deleted']);
    }

    public function edit(Menu $menu)
    {
        if ($menu->tenant_id !== auth()->user()->tenant_id) abort(403);
        $menu->load('items.children');
        return view('tenant.menus.edit', compact('menu'));
    }

    public function storeItem(Request $request, Menu $menu)
    {
        if ($menu->tenant_id !== auth()->user()->tenant_id) abort(403);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:menu_items,id',
            'target' => 'nullable|in:_self,_blank',
            'sort_order' => 'nullable|integer',
        ]);
        $validated['menu_id'] = $menu->id;
        $validated['target'] = $validated['target'] ?? '_self';
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        MenuItem::create($validated);
        return redirect()->back()->with('success', 'Menu item added');
    }

    public function updateItem(Request $request, MenuItem $item)
    {
        if ($item->menu->tenant_id !== auth()->user()->tenant_id) abort(403);
        $item->update($request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'target' => 'nullable|in:_self,_blank',
            'sort_order' => 'nullable|integer',
        ]));
        return response()->json(['success' => true]);
    }

    public function destroyItem(MenuItem $item)
    {
        if ($item->menu->tenant_id !== auth()->user()->tenant_id) abort(403);
        $item->delete();
        return redirect()->back()->with('success', 'Item deleted');
    }
}
