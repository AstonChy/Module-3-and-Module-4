<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        return view('users', [
            'users' => (new UserModel())->orderBy('id', 'DESC')->findAll(),
            'active' => 'users',
        ]);
    }

    public function new()
    {
        return view('users/create', ['active' => 'users']);
    }

    public function create()
    {
        $username = trim((string) $this->request->getPost('username'));
        $fullName = trim((string) $this->request->getPost('full_name'));
        $role = trim((string) $this->request->getPost('role')) ?: 'Staff';
        $password = (string) $this->request->getPost('password');

        if ($username === '' || $fullName === '' || strlen($password) < 6) {
            return redirect()->back()->withInput()->with('error', 'Complete the fields and use a password with at least 6 characters.');
        }

        $userModel = new UserModel();
        if ($userModel->where('username', $username)->first()) {
            return redirect()->back()->withInput()->with('error', 'That username is already in use.');
        }

        $avatarName = $this->storeAvatar();
        if ($avatarName === false) {
            return redirect()->back()->withInput()->with('error', 'Avatar must be a JPG or PNG image up to 2 MB.');
        }

        $userModel->insert([
            'username' => $username,
            'full_name' => $fullName,
            'role' => $role,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'avatar' => $avatarName,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/users')->with('success', 'Staff account added successfully.');
    }

    public function edit($id)
    {
        $user = (new UserModel())->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'Staff account not found.');
        }

        return view('users/edit', ['user' => $user, 'active' => 'users']);
    }

    public function update($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'Staff account not found.');
        }

        $username = trim((string) $this->request->getPost('username'));
        $fullName = trim((string) $this->request->getPost('full_name'));
        $role = trim((string) $this->request->getPost('role')) ?: 'Staff';
        $password = (string) $this->request->getPost('password');

        $duplicate = $userModel->where('username', $username)->where('id !=', $id)->first();
        if ($username === '' || $fullName === '' || $duplicate) {
            return redirect()->back()->withInput()->with('error', 'Enter valid details and use a unique username.');
        }

        $data = ['username' => $username, 'full_name' => $fullName, 'role' => $role];
        if ($password !== '') {
            if (strlen($password) < 6) {
                return redirect()->back()->withInput()->with('error', 'A new password must have at least 6 characters.');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $avatarName = $this->storeAvatar();
        if ($avatarName === false) {
            return redirect()->back()->withInput()->with('error', 'Avatar must be a JPG or PNG image up to 2 MB.');
        }
        if ($avatarName !== null) {
            $data['avatar'] = $avatarName;
            $oldAvatar = FCPATH . 'uploads/avatars/' . ($user['avatar'] ?? '');
            if (is_file($oldAvatar)) {
                unlink($oldAvatar);
            }
        }

        $userModel->update($id, $data);
        return redirect()->to('/users')->with('success', 'Staff account updated successfully.');
    }

    public function delete($id)
    {
        if ((int) session()->get('user_id') === (int) $id) {
            return redirect()->to('/users')->with('error', 'You cannot delete the account currently logged in.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);
        if ($user && !empty($user['avatar'])) {
            $avatarPath = FCPATH . 'uploads/avatars/' . $user['avatar'];
            if (is_file($avatarPath)) {
                unlink($avatarPath);
            }
        }
        $userModel->delete($id);
        return redirect()->to('/users')->with('success', 'Staff account deleted successfully.');
    }

    private function storeAvatar()
    {
        $avatar = $this->request->getFile('avatar');
        if (!$avatar || $avatar->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (!$avatar->isValid() || !in_array($avatar->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'], true) || $avatar->getSizeByUnit('mb') > 2) {
            return false;
        }

        $directory = FCPATH . 'uploads/avatars';
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        $name = $avatar->getRandomName();
        $avatar->move($directory, $name);
        return $name;
    }
}
