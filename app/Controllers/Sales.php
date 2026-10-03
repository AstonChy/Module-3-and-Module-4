<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\SaleModel;

class Sales extends BaseController
{
    public function index()
    {
        return view('sales/index', [
            'sales' => (new SaleModel())->history(),
            'active' => 'sales',
        ]);
    }

    public function new()
    {
        return view('sales/create', [
            'products' => (new ProductModel())->where('stock_quantity >', 0)->orderBy('name')->findAll(),
            'customers' => (new CustomerModel())->orderBy('full_name')->findAll(),
            'active' => 'sales',
        ]);
    }

    public function create()
    {
        $productId = (int) $this->request->getPost('product_id');
        $customerId = $this->request->getPost('customer_id') ?: null;
        $quantity = (int) $this->request->getPost('quantity');

        if ($productId < 1 || $quantity < 1) {
            return redirect()->back()->withInput()->with('error', 'Select a product and enter a valid quantity.');
        }

        $db = db_connect();
        $productModel = new ProductModel();
        $saleModel = new SaleModel();

        $db->transStart();
        $product = $productModel->find($productId);

        if (!$product) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'The selected product was not found.');
        }

        if ($quantity > (int) $product['stock_quantity']) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Sale rejected: requested quantity exceeds available stock.');
        }

        $productModel->update($productId, [
            'stock_quantity' => (int) $product['stock_quantity'] - $quantity,
        ]);

        $saleModel->insert([
            'product_id' => $productId,
            'customer_id' => $customerId,
            'sold_by' => session()->get('user_id'),
            'quantity' => $quantity,
            'total_price' => (float) $product['price'] * $quantity,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'The sale could not be recorded.');
        }

        return redirect()->to('/sales')->with('success', 'Sale recorded and stock updated successfully.');
    }
}
