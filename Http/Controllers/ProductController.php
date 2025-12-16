<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get();

        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string',
            'price' => 'required',
            'description' => 'nullable|string',
            'stock' => 'integer|required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public'); // simpan di storage/app/public/images
            $validated['image'] = $filename; // simpan nama file ke database
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('sucess', 'produk berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $categories = Category::all(); // kalau memang mau ditampilkan
        return view('show', compact('product', 'categories'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('category');
        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'categories'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string',
            'price' => 'required',
            'description' => 'nullable|string',
            'stock' => 'integer|required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public'); // simpan di storage/app/public/images
            $validated['image'] = $filename; // simpan nama file ke database
        }


        $product->update($validated);

        return redirect()->route('products.index')->with('sucess', 'produk berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('sucess', 'produk berhasil dihapus');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category');

        $products = Product::when($search, function ($query, $search) {
            $query->where('product_name', 'like', "%{$search}%");
        })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->paginate(8)
            ->withQueryString();

        $categories = Category::all();

        return view('welcome', compact('products', 'search', 'categories', 'categoryId'));
    }
}