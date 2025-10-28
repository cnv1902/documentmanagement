<?php
namespace App\Http\Controllers;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
class PublisherController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->input('q');
        $publishers = Publisher::query()
            ->when($q, fn($builder) => $builder->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->paginate(10);
        return view('publishers.index', compact('publishers'));
    }
    public function create(): View
    {
        return view('publishers.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
        ]);

        $publisher = Publisher::create($validated);
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Tạo nhà xuất bản thành công.', 'data' => $publisher]);
        }
        return redirect()->route('publishers.index')->with('success', 'Tạo nhà xuất bản thành công.');
    }
    public function edit(Publisher $publisher): View
    {
        return view('publishers.edit', compact('publisher'));
    }
    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
        ]);

        $publisher->update($validated);
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Cập nhật nhà xuất bản thành công.', 'data' => $publisher]);
        }
        return redirect()->route('publishers.index')->with('success', 'Cập nhật nhà xuất bản thành công.');
    }
    public function destroy(Request $request, Publisher $publisher)
    {
        $publisher->delete();
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Xoá nhà xuất bản thành công.']);
        }
        return redirect()->route('publishers.index')->with('success', 'Xoá nhà xuất bản thành công.');
    }
}
