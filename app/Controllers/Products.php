<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Products extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();

        $data = [
            'products' => $productModel->orderBy('id', 'DESC')->findAll(),
            'active' => 'products'
        ];

        return view('products/index', $data);
    }

    public function new()
    {
        return view('products/create', ['active' => 'products']);
    }

    public function create()
    {
        $productModel = new ProductModel();

        $name = $this->request->getPost('name');
        $price = $this->request->getPost('price');
        $stockQuantity = $this->request->getPost('stock_quantity');

        if (empty($name) || empty($price) || $stockQuantity === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please complete all required fields.');
        }

        $imageName = null;
        $image = $this->request->getFile('image');

        if ($image && $image->getError() !== UPLOAD_ERR_NO_FILE) {
            if (!$image->isValid() || $image->hasMoved()) {
                return redirect()->back()->withInput()->with('error', 'The uploaded product image is invalid.');
            }
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            if (!in_array($image->getMimeType(), $allowedTypes)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Only JPG and PNG images are allowed.');
            }

            if ($image->getSizeByUnit('mb') > 2) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'The image must not exceed 2 MB.');
            }

            $uploadDirectory = FCPATH . 'uploads/products';
            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0755, true);
            }

            $imageName = $image->getRandomName();
            $image->move($uploadDirectory, $imageName);
        }

        $productModel->insert([
            'name' => $name,
            'price' => $price,
            'stock_quantity' => $stockQuantity,
            'image' => $imageName,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/products')
            ->with('success', 'Product added successfully.');
    }

    public function edit($id)
    {
        $productModel = new ProductModel();

        $data = [
            'product' => $productModel->find($id),
            'active' => 'products'
        ];

        if (!$data['product']) {
            return redirect()->to('/products')
                ->with('error', 'Product not found.');
        }

        return view('products/edit', $data);
    }

    public function update($id)
    {
        $productModel = new ProductModel();
        $product = $productModel->find($id);

        if (!$product) {
            return redirect()->to('/products')
                ->with('error', 'Product not found.');
        }

        $imageName = $product['image'];
        $image = $this->request->getFile('image');

        if ($image && $image->getError() !== UPLOAD_ERR_NO_FILE) {
            if (!$image->isValid() || $image->hasMoved()) {
                return redirect()->back()->withInput()->with('error', 'The uploaded product image is invalid.');
            }
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            if (!in_array($image->getMimeType(), $allowedTypes)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Only JPG and PNG images are allowed.');
            }

            $uploadDirectory = FCPATH . 'uploads/products';
            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0755, true);
            }

            $oldImageName = $imageName;
            $imageName = $image->getRandomName();
            $image->move($uploadDirectory, $imageName);

            if (!empty($oldImageName)) {
                $oldImagePath = $uploadDirectory . DIRECTORY_SEPARATOR . $oldImageName;
                if (is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }

        $productModel->update($id, [
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'image' => $imageName
        ]);

        return redirect()->to('/products')
            ->with('success', 'Product updated successfully.');
    }

    public function delete($id)
    {
        $productModel = new ProductModel();

        $product = $productModel->find($id);

        if ($product && !empty($product['image'])) {
            $imagePath = FCPATH . 'uploads/products/' . $product['image'];

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $productModel->delete($id);

        return redirect()->to('/products')
            ->with('success', 'Product deleted successfully.');
    }
}
