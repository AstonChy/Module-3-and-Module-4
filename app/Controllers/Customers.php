<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    public function index()
    {
        return view('customers', [
            'customers' => (new CustomerModel())->orderBy('id', 'DESC')->findAll(),
            'active' => 'customers',
        ]);
    }

    public function new()
    {
        return view('customers/create', ['active' => 'customers']);
    }

    public function create()
    {
        $fullName = trim((string) $this->request->getPost('full_name'));
        $email = trim((string) $this->request->getPost('email'));
        $phone = trim((string) $this->request->getPost('phone'));

        if ($fullName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('error', 'Enter a full name and a valid email address.');
        }

        (new CustomerModel())->insert([
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/customers')->with('success', 'Customer added successfully.');
    }

    public function edit($id)
    {
        $customer = (new CustomerModel())->find($id);
        if (!$customer) {
            return redirect()->to('/customers')->with('error', 'Customer not found.');
        }

        return view('customers/edit', ['customer' => $customer, 'active' => 'customers']);
    }

    public function update($id)
    {
        $fullName = trim((string) $this->request->getPost('full_name'));
        $email = trim((string) $this->request->getPost('email'));
        $phone = trim((string) $this->request->getPost('phone'));

        if ($fullName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('error', 'Enter a full name and a valid email address.');
        }

        (new CustomerModel())->update($id, [
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
        ]);

        return redirect()->to('/customers')->with('success', 'Customer updated successfully.');
    }

    public function delete($id)
    {
        (new CustomerModel())->delete($id);
        return redirect()->to('/customers')->with('success', 'Customer deleted successfully.');
    }
}
