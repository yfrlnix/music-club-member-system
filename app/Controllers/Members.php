<?php

namespace App\Controllers;

use App\Models\MemberModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse;

class Members extends BaseController
{
    protected MemberModel $model;

    public function __construct()
    {
        $this->model = new MemberModel();
    }

    public function index(): string
    {
        $search = $this->request->getGet('q') ?? '';

        $params = [
            'q' => $search,
            'role' => $this->request->getGet('role') ?? '',
            'status' => $this->request->getGet('status') ?? '',
            'sort' => $this->request->getGet('sort') ?? 'points',
            'dir' => $this->request->getGet('dir') ?? 'desc',
        ];

        $members = $this->model->getPaginatedMembers(5, $search);

        $filteredResult = $this->model->getMembersWithFilters($params);

        $data = [
            'title' => 'Members',
            'members' => $members,                
            'pager' => $this->model->pager,       
            'total' => $filteredResult['total'],  
            'page' => $this->request->getGet('page_members') ?? 1,
            'per_page' => 5,
            'params' => $params,
            'newest' => $this->model->getNewest(5),
            'mostActive' => $this->model->getMostActive(4),
            'errors' => session()->getFlashdata('errors') ?? [],
            'success' => session()->getFlashdata('success') ?? '',
        ];

        return view('layouts/main', [
            'content' => view('members/index', $data),
            'title' => 'Members',
        ]);
    }

    public function show(int $id): string
    {
        $member = $this->model->find($id);
        if (!$member) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                "Member #{$id} not found."
            );
        }

        return view('layouts/main', [
            'content' => view('members/show', ['member' => $member]),
            'title' => MemberModel::fullName($member),
        ]);
    }

    public function create(): string
    {
        return view('layouts/main', [
            'content' => view('members/form', [
                'member' => null,
                'errors' => session()->getFlashdata('errors') ?? [],
                'success' => session()->getFlashdata('success') ?? '',
            ]),
            'title' => 'Add Member',
        ]);
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[64]',
            'last_name' => 'required|min_length[2]|max_length[64]',
            'email' => 'required|valid_email|is_unique[members.email]',
            'username' => 'required|min_length[3]|max_length[32]|is_unique[members.username]',
            'role' => 'required|in_list[Administrator,Moderator,Member,Beginner]',
            'status' => 'required|in_list[active,inactive]',
            'avatar' => 'uploaded[avatar]|max_size[avatar,2048]|is_image[avatar]|mime_in[avatar,image/jpeg,image/png,image/webp]'
        ];

        $messages = [
            'avatar' => [
                'uploaded' => 'Please select an avatar image.',
                'max_size' => 'Avatar must be 2MB or less.',
                'is_image' => 'File must be an image.',
                'mime_in' => 'Only JPG, PNG, WEBP, or GIF images are allowed.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status'),
            'points' => $this->request->getPost('points') ?? 0,
            'reactions' => $this->request->getPost('reactions') ?? 0,
            'post_count' => $this->request->getPost('post_count') ?? 0, 
            'joined_at' => date('Y-m-d H:i:s'),
        ];

        $avatarFile = $this->request->getFile('avatar');
        if ($avatarFile && $avatarFile->isValid() && !$avatarFile->hasMoved()) {
            $result = $this->model->uploadAvatar($avatarFile);
            if (isset($result['error'])) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', ['avatar' => $result['error']]);
            }
            $data['avatar'] = $result['path'];
        }

        $this->model->insert($data);

        return redirect()->to('/members')
            ->with('success', 'Member added successfully.');
    }

    public function edit(int $id): string
    {
        $member = $this->model->find($id);
        if (!$member) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('layouts/main', [
            'content' => view('members/form', [
                'member' => $member,
                'errors' => session()->getFlashdata('errors') ?? [],
                'success' => session()->getFlashdata('success') ?? '',
            ]),
            'title' => 'Edit Member',
        ]);
    }

    public function update($id): RedirectResponse
    {
        $member = $this->model->find($id);
        if (!$member) {
            return redirect()->to('/members')->with('error', 'Member not found.');
        }

        $rules = [
            'first_name' => 'required|min_length[2]|max_length[64]',
            'last_name' => 'required|min_length[2]|max_length[64]',
            'email' => "required|valid_email|is_unique[members.email,id,{$id}]",
            'username' => "required|min_length[3]|max_length[32]|is_unique[members.username,id,{$id}]",
            'role' => 'required|in_list[Administrator,Moderator,Member,Beginner]',
            'status' => 'required|in_list[active,inactive]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'role' => $this->request->getPost('role'),
            'status' => $this->request->getPost('status'),
            'points' => $this->request->getPost('points') ?? 0,
            'reactions' => $this->request->getPost('reactions') ?? 0,
            'post_count' => $this->request->getPost('post_count') ?? 0,
        ];

        $avatarFile = $this->request->getFile('avatar');
        if ($avatarFile && $avatarFile->isValid() && !$avatarFile->hasMoved()) {
            $result = $this->model->uploadAvatar($avatarFile);
            if (isset($result['error'])) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', ['avatar' => $result['error']]);
            }
            if (!empty($member['avatar'])) {
                $this->model->deleteAvatar($member['avatar']);
            }
            $data['avatar'] = $result['path'];
        }

        if ($this->request->getPost('remove_avatar') === '1' && !empty($member['avatar'])) {
            $this->model->deleteAvatar($member['avatar']);
            $data['avatar'] = null;
        }

        if ($this->model->update($id, $data)) {
            return redirect()->to('/members/' . $id)
                ->with('success', 'Member updated successfully!');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->model->errors());
    }

    public function delete(int $id): RedirectResponse
    {
        $member = $this->model->find($id);
        if ($member) {
            if (!empty($member['avatar'])) {
                $this->model->deleteAvatar($member['avatar']);
            }
            $this->model->delete($id);
        }

        return redirect()->to('/members')
            ->with('success', 'Member deleted.');
    }
}