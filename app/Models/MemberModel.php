<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table = 'members';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'first_name',
        'last_name',
        'email',
        'username',
        'role',
        'status',
        'avatar',
        'points',
        'reactions',
        'post_count',
        'joined_at',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'first_name' => 'required|min_length[2]|max_length[64]',
        'last_name' => 'required|min_length[2]|max_length[64]',
        'email' => 'required|valid_email',
        'username' => 'required|min_length[3]|max_length[32]',
        'role' => 'required|in_list[Administrator,Moderator,Member,Beginner]',
        'status' => 'required|in_list[active,inactive]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email address is already in use.',
        ],
        'username' => [
            'is_unique' => 'This username is already taken.',
        ],
    ];

    protected $skipValidation = false;

    public function getPaginatedMembers($perPage = 5, $search = null)
    {
        if ($search) {
            $this->groupStart()
                ->like('first_name', $search)
                ->orLike('last_name', $search)
                ->orLike('username', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }

        return $this->paginate($perPage);
    }

    public function getMembersWithFilters(array $params = []): array
    {
        $q = $params['q'] ?? '';
        $role = $params['role'] ?? '';
        $status = $params['status'] ?? '';
        $sort = $params['sort'] ?? 'points';
        $dir = $params['dir'] ?? 'desc';

        $allowed = ['points', 'post_count', 'reactions', 'joined_at', 'first_name'];
        if (!in_array($sort, $allowed)) {
            $sort = 'points';
        }
        $dir = strtolower($dir) === 'asc' ? 'ASC' : 'DESC';

        $builder = $this->db->table($this->table);

        if ($q !== '') {
            $builder->groupStart()
                ->like('first_name', $q)
                ->orLike('last_name', $q)
                ->orLike('username', $q)
                ->orLike('email', $q)
                ->groupEnd();
        }

        if ($role !== '') {
            $builder->where('role', $role);
        }

        if ($status !== '') {
            $builder->where('status', $status);
        }

        $builder->orderBy($sort, $dir);

        $total = (clone $builder)->countAllResults(false);

        $rows = $builder->get()->getResultArray();

        return [
            'members' => $rows,
            'total' => $total,
        ];
    }

    public function getNewest(int $limit = 4): array
    {
        return $this->orderBy('joined_at', 'DESC')->limit($limit)->findAll();
    }

    public function getMostActive(int $limit = 4): array
    {
        return $this->orderBy('points', 'DESC')->limit($limit)->findAll();
    }

    public function uploadAvatar(\CodeIgniter\HTTP\Files\UploadedFile $file): array
    {
        if (!$file->isValid() || $file->hasMoved()) {
            return ['error' => 'Invalid or already-moved file.'];
        }

        if ($file->getSize() > 2 * 1024 * 1024) {
            return ['error' => 'File size must be 2MB or less.'];
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return ['error' => 'Only JPG, PNG, WEBP, or GIF images are allowed.'];
        }

        if (strpos($file->getMimeType(), 'image/') !== 0) {
            return ['error' => 'File must be an image.'];
        }

        $uploadPath = FCPATH . 'uploads/avatars/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        return ['path' => 'uploads/avatars/' . $newName];
    }

    public function deleteAvatar(string $path): void
    {
        $full = FCPATH . $path;
        if (file_exists($full)) {
            unlink($full);
        }
    }

    public static function fullName(array $member): string
    {
        return trim(($member['first_name'] ?? '') . ' ' . ($member['last_name'] ?? ''));
    }

    public static function initials(array $member): string
    {
        return strtoupper(
            substr($member['first_name'] ?? '', 0, 1) .
            substr($member['last_name'] ?? '', 0, 1)
        );
    }
}