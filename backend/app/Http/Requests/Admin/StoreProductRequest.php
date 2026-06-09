<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id'    => 'required|exists:categories,id',
            'code'           => 'required|string|max:50|unique:products,code' . ($this->method() === 'PUT' ? ',' . $this->route('id') : ''),
            'barcode'        => 'nullable|string|max:100',
            'name'           => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0|gte:purchase_price',
            'stock'          => 'required|integer|min:0',
            'min_stock'      => 'required|integer|min:0',
            'description'    => 'nullable|string|max:1000',
            'is_active'      => 'nullable|boolean'
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori produk harus dipilih',
            'category_id.exists' => 'Kategori yang dipilih tidak valid',
            'code.required' => 'Kode produk harus diisi',
            'code.unique' => 'Kode produk sudah digunakan',
            'name.required' => 'Nama produk harus diisi',
            'purchase_price.required' => 'Harga beli harus diisi',
            'purchase_price.min' => 'Harga beli tidak boleh kurang dari 0',
            'selling_price.required' => 'Harga jual harus diisi',
            'selling_price.gte' => 'Harga jual tidak boleh lebih kecil dari harga beli',
            'stock.required' => 'Stok harus diisi',
            'stock.min' => 'Stok tidak boleh kurang dari 0',
            'min_stock.required' => 'Batas minimum stok harus diisi',
            'min_stock.min' => 'Batas minimum stok tidak boleh kurang dari 0',
        ];
    }
}
