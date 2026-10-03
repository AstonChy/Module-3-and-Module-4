<?php

namespace App\Controllers;

use App\Models\TaskModel;

class Tasks extends BaseController
{
    private function requireLogin()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please log in to manage tasks.');
        }

        return null;
    }

    public function index()
    {
        $tasks = (new TaskModel())->where('is_archived', 0)->orderBy('task_date', 'ASC')->findAll();
        return view('tasks/index', ['tasks' => $tasks]);
    }

    public function new()
    {
        if ($redirect = $this->requireLogin()) return $redirect;
        return view('tasks/form', ['task' => null, 'formTitle' => 'New task', 'formAction' => 'tasks/create']);
    }

    public function create()
    {
        if ($redirect = $this->requireLogin()) return $redirect;

        $rules = [
            'title' => 'required|max_length[150]',
            'task_date' => 'required|valid_date[Y-m-d]',
            'description' => 'permit_empty|max_length[1000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new TaskModel())->insert([
            'title' => trim($this->request->getPost('title')),
            'description' => trim((string) $this->request->getPost('description')),
            'task_date' => $this->request->getPost('task_date'),
            'is_archived' => 0,
        ]);

        return redirect()->to('/tasks')->with('success', 'Task created successfully.');
    }

    public function edit($id)
    {
        if ($redirect = $this->requireLogin()) return $redirect;

        $task = (new TaskModel())->where('is_archived', 0)->find($id);
        if (! $task) return redirect()->to('/tasks')->with('error', 'Task not found.');

        return view('tasks/form', ['task' => $task, 'formTitle' => 'Edit task', 'formAction' => 'tasks/update/' . $id]);
    }

    public function update($id)
    {
        if ($redirect = $this->requireLogin()) return $redirect;

        $rules = [
            'title' => 'required|max_length[150]',
            'task_date' => 'required|valid_date[Y-m-d]',
            'description' => 'permit_empty|max_length[1000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new TaskModel();
        if (! $model->where('is_archived', 0)->find($id)) {
            return redirect()->to('/tasks')->with('error', 'Task not found.');
        }

        $model->update($id, [
            'title' => trim($this->request->getPost('title')),
            'description' => trim((string) $this->request->getPost('description')),
            'task_date' => $this->request->getPost('task_date'),
        ]);

        return redirect()->to('/tasks')->with('success', 'Task updated successfully.');
    }

    public function delete($id)
    {
        if ($redirect = $this->requireLogin()) return $redirect;

        $model = new TaskModel();
        if ($model->where('is_archived', 0)->find($id)) {
            $model->update($id, ['is_archived' => 1]);
            return redirect()->to('/tasks')->with('success', 'Task archived successfully.');
        }

        return redirect()->to('/tasks')->with('error', 'Task not found.');
    }
}
