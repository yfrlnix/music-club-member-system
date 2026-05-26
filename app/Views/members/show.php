<?php
use App\Models\MemberModel;

$name = MemberModel::fullName($member);
$initials = MemberModel::initials($member);
?>

<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <a href="/members" class="btn-back">
                <i class="ti ti-arrow-left"></i> Back to Members
            </a>
        </div>

        <div class="profile-body">
            <div class="profile-avatar">
                <?php if (!empty($member['avatar'])): ?>
                    <img src="/<?= esc($member['avatar']) ?>" alt="<?= esc($name) ?>" class="profile-img">
                <?php else: ?>
                    <div class="profile-avatar-initials" style="background:#ddeedd;color:#002D2D">
                        <?= $initials ?>
                    </div>
                <?php endif; ?>
            </div>

            <h1 class="profile-name"><?= esc($name) ?></h1>

            <div class="profile-badges">
                <?php
                $roleClass = match ($member['role']) {
                    'Administrator' => 'role-admin',
                    'Moderator' => 'role-mod',
                    'Member' => 'role-mem',
                    default => 'role-beg'
                };
                ?>
                <span class="role-badge <?= $roleClass ?>">
                    <?= esc($member['role']) ?>
                </span>
            </div>

            <div class="profile-info">
                <div class="info-row">
                    <i class="ti ti-mail"></i>
                    <strong>Email:</strong>
                    <span><?= esc($member['email']) ?></span>
                </div>
                <div class="info-row">
                    <i class="ti ti-user"></i>
                    <strong>Username:</strong>
                    <span><?= esc($member['username']) ?></span>
                </div>
                <div class="info-row">
                    <i class="ti ti-calendar"></i>
                    <strong>Joined:</strong>
                    <span><?= date('F j, Y', strtotime($member['joined_at'] ?? $member['created_at'])) ?></span>
                </div>
            </div>

            <div class="profile-stats">
                <div class="stat">
                    <span class="stat-value"><?= (int) ($member['points'] ?? 0) ?></span>
                    <span class="stat-label">Points</span>
                </div>
                <div class="stat">
                    <span class="stat-value"><?= (int) ($member['reactions'] ?? 0) ?></span>
                    <span class="stat-label">Reactions</span>
                </div>
                <div class="stat">
                    <span class="stat-value"><?= (int) ($member['post_count'] ?? 0) ?></span>
                    <span class="stat-label">Posts</span>
                </div>
            </div>

            <div class="profile-actions">
                <a href="/members/edit/<?= $member['id'] ?>" class="btn-edit">
                    <i class="ti ti-edit"></i> Edit Profile
                </a>
                <form method="post" action="/members/delete/<?= $member['id'] ?>"
                    onsubmit="return confirm('Delete <?= esc($name) ?>? This cannot be undone.')"
                    style="display: inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-delete">
                        <i class="ti ti-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-container {
        max-width: 700px;
        margin: 2rem auto;
        padding: 1rem;
    }

    .profile-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.1);
    }

    .profile-header {
        padding: 1rem 1.5rem;
        background: #f9fbf5;
        border-bottom: 1px solid #eef6d6;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        color: #5E8E3E;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .btn-back:hover {
        color: #4a7232;
    }

    .profile-body {
        padding: 2rem;
        text-align: center;
    }

    .profile-avatar {
        margin-bottom: 1.5rem;
    }

    .profile-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .profile-avatar-initials {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: bold;
        margin: 0 auto;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .profile-name {
        font-size: 1.75rem;
        font-weight: 700;
        color: #002D2D;
        margin-bottom: 0.75rem;
    }

    .profile-badges {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        margin-bottom: 1.5rem;
        align-items: center;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 16px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        height: 28px;
        box-sizing: border-box;
    }

    .role-admin {
        background: #ff5722;
        color: white;
    }

    .role-mod {
        background: #ff9800;
        color: white;
    }

    .role-mem {
        background: #2196f3;
        color: white;
    }

    .role-beg {
        background: #9e9e9e;
        color: white;
    }

    .profile-info {
        text-align: left;
        background: #f9fbf5;
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #eef6d6;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row i {
        color: #5E8E3E;
        font-size: 1.125rem;
        margin-top: 0.125rem;
        min-width: 20px;
    }

    .info-row strong {
        width: 80px;
        color: #002D2D;
        font-size: 0.875rem;
    }

    .info-row span {
        flex: 1;
        color: #555;
        font-size: 0.875rem;
    }

    .profile-stats {
        display: flex;
        justify-content: space-around;
        padding: 1rem;
        background: #eef6d6;
        border-radius: 16px;
        margin-bottom: 1.5rem;
    }

    .stat {
        text-align: center;
    }

    .stat-value {
        display: block;
        font-size: 1.5rem;
        font-weight: 800;
        color: #002D2D;
    }

    .stat-label {
        font-size: 0.7rem;
        color: #5E8E3E;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .profile-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .btn-edit,
    .btn-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        font-family: inherit;
        min-width: 120px;
    }

    .btn-edit {
        background: #5E8E3E;
        color: white;
    }

    .btn-edit:hover {
        background: #4a7232;
    }

    .btn-delete {
        background: #f44336;
        color: white;
    }

    .btn-delete:hover {
        background: #d32f2f;
    }

    @media (max-width: 600px) {
        .profile-body {
            padding: 1.25rem;
        }

        .profile-name {
            font-size: 1.25rem;
        }

        .profile-img,
        .profile-avatar-initials {
            width: 90px;
            height: 90px;
            font-size: 2rem;
        }

        .info-row {
            flex-wrap: wrap;
        }

        .info-row strong {
            width: 100%;
            margin-left: 1.5rem;
        }

        .profile-actions {
            flex-direction: column;
            align-items: center;
        }

        .btn-edit,
        .btn-delete {
            min-width: 150px;
        }

        .profile-stats {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
    }
</style>