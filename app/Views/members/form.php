<?php
use App\Models\MemberModel;
$isEdit = !empty($member);
$title = $isEdit ? 'Edit Member' : 'Add New Member';
$action = $isEdit ? "/members/update/{$member['id']}" : '/members/store';

$old = fn(string $k, $fallback = '') => old($k, $isEdit ? ($member[$k] ?? $fallback) : $fallback);
?>

<div class="form-layout">
    <div class="form-panel">

        <div class="form-panel-hd">
            <a href="/members" class="btn btn-ghost btn-sm">
                <i class="ti ti-arrow-left"></i> Back
            </a>
            <h1 class="form-panel-title"><?= $title ?></h1>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error" role="alert">
                <i class="ti ti-alert-circle"></i>
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul class="alert-list">
                        <?php foreach ($errors as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="ti ti-circle-check"></i> <?= esc($success) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= $action ?>" enctype="multipart/form-data" novalidate id="member-form">
            <?= csrf_field() ?>
            <?php if ($isEdit): ?>
            <?php endif; ?>

            <div class="form-section">
                <div class="form-section-title">Profile photo</div>

                <div class="avatar-upload-row">
                    <div class="av-preview-wrap">
                        <?php if ($isEdit && !empty($member['avatar'])): ?>
                            <img id="av-preview-img" src="/<?= esc($member['avatar']) ?>" alt="Current avatar"
                                class="av-preview av-preview-img">
                        <?php else: ?>
                            <div id="av-preview-placeholder" class="av-preview av-preview-initials"
                                style="background:#ddeedd;color:#002D2D">
                                <?= $isEdit ? MemberModel::initials($member) : '?' ?>
                            </div>
                            <img id="av-preview-img" class="av-preview av-preview-img" style="display:none"
                                alt="Avatar preview">
                        <?php endif; ?>
                    </div>

                    <div class="av-upload-controls">
                        <label class="btn btn-outline btn-sm" for="avatar-input">
                            <i class="ti ti-upload"></i> Choose image
                        </label>
                        <input type="file" id="avatar-input" name="avatar"
                            accept="image/jpeg,image/png,image/webp,image/gif" class="sr-only"
                            onchange="previewAvatar(this)">

                        <?php if ($isEdit && !empty($member['avatar'])): ?>
                            <label class="btn btn-ghost btn-sm btn-danger" style="display:none" id="remove-av-btn">
                                <input type="checkbox" name="remove_avatar" value="1" id="remove-av-chk"
                                    style="display:none">
                                <i class="ti ti-trash"></i> Remove photo
                            </label>
                        <?php endif; ?>

                        <div class="form-hint">
                            PNG, JPG, WEBP, GIF · max 2 MB · min 100×100 px
                        </div>

                        <div id="val-checklist" class="val-checklist" style="display:none">
                            <div class="val-item" id="v-type"><i class="ti ti-circle-dashed"></i> MIME type valid</div>
                            <div class="val-item" id="v-size"><i class="ti ti-circle-dashed"></i> Size ≤ 2 MB</div>
                            <div class="val-item" id="v-ext"><i class="ti ti-circle-dashed"></i> Extension allowed</div>
                        </div>
                        <?php if (isset($errors['avatar'])): ?>
                            <div class="field-error"><i class="ti ti-alert-circle"></i> <?= esc($errors['avatar']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Personal information</div>
                <div class="form-grid-2">

                    <div class="form-group <?= isset($errors['first_name']) ? 'has-error' : '' ?>">
                        <label class="form-label" for="first_name">First name <span class="req">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="<?= esc($old('first_name')) ?>"
                            placeholder="Maria" required autocomplete="given-name">
                        <?php if (isset($errors['first_name'])): ?>
                            <div class="field-error"><i class="ti ti-alert-circle"></i> <?= esc($errors['first_name']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group <?= isset($errors['last_name']) ? 'has-error' : '' ?>">
                        <label class="form-label" for="last_name">Last name <span class="req">*</span></label>
                        <input type="text" id="last_name" name="last_name" value="<?= esc($old('last_name')) ?>"
                            placeholder="Santos" required autocomplete="family-name">
                        <?php if (isset($errors['last_name'])): ?>
                            <div class="field-error"><i class="ti ti-alert-circle"></i> <?= esc($errors['last_name']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
                        <label class="form-label" for="email">Email address <span class="req">*</span></label>
                        <input type="email" id="email" name="email" value="<?= esc($old('email')) ?>"
                            placeholder="maria@example.com" required autocomplete="email">
                        <?php if (isset($errors['email'])): ?>
                            <div class="field-error"><i class="ti ti-alert-circle"></i> <?= esc($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group <?= isset($errors['username']) ? 'has-error' : '' ?>">
                        <label class="form-label" for="username">Username <span class="req">*</span></label>
                        <input type="text" id="username" name="username" value="<?= esc($old('username')) ?>"
                            placeholder="maria_santos" required autocomplete="username">
                        <?php if (isset($errors['username'])): ?>
                            <div class="field-error"><i class="ti ti-alert-circle"></i> <?= esc($errors['username']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Role &amp; status</div>
                <div class="form-grid-2">

                    <div class="form-group <?= isset($errors['role']) ? 'has-error' : '' ?>">
                        <label class="form-label" for="role">Role <span class="req">*</span></label>
                        <select id="role" name="role" required>
                            <?php foreach (['Beginner', 'Member', 'Moderator', 'Administrator'] as $r): ?>
                                <option value="<?= $r ?>" <?= $old('role', 'Member') === $r ? 'selected' : '' ?>>
                                    <?= $r ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['role'])): ?>
                            <div class="field-error"><i class="ti ti-alert-circle"></i> <?= esc($errors['role']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group <?= isset($errors['status']) ? 'has-error' : '' ?>">
                        <label class="form-label" for="status">Status <span class="req">*</span></label>
                        <select id="status" name="status" required>
                            <option value="active" <?= $old('status', 'active') === 'active' ? 'selected' : '' ?>>Active
                            </option>
                            <option value="inactive" <?= $old('status', 'active') === 'inactive' ? 'selected' : '' ?>>
                                Inactive</option>
                        </select>
                        <?php if (isset($errors['status'])): ?>
                            <div class="field-error"><i class="ti ti-alert-circle"></i> <?= esc($errors['status']) ?></div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <i class="ti ti-chart-bar"></i> Member Stats
                </div>
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="form-label" for="points">
                            <i class="ti ti-star"></i> Points
                        </label>
                        <input type="number" id="points" name="points" value="<?= esc($old('points', 0)) ?>"
                            placeholder="0" min="0">
                        <small class="form-hint">Total points earned</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="reactions">
                            <i class="ti ti-heart"></i> Reactions
                        </label>
                        <input type="number" id="reactions" name="reactions" value="<?= esc($old('reactions', 0)) ?>"
                            placeholder="0" min="0">
                        <small class="form-hint">Total reactions received</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="post_count">
                            <i class="ti ti-message"></i> Post Count
                        </label>
                        <input type="number" id="post_count" name="post_count" value="<?= esc($old('post_count', 0)) ?>"
                            placeholder="0" min="0">
                        <small class="form-hint">Total posts created</small>
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <a href="/members" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-check"></i>
                    <?= $isEdit ? 'Save changes' : 'Create member' ?>
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    function previewAvatar(input) {
        const file = input.files[0];
        if (!file) return;

        const allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        const okExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        const ext = file.name.split('.').pop().toLowerCase();

        const mimeOk = allowed.includes(file.type);
        const sizeOk = file.size <= 2 * 1024 * 1024;
        const extOk = okExt.includes(ext);

        document.getElementById('val-checklist').style.display = 'grid';
        setVal('v-type', mimeOk);
        setVal('v-size', sizeOk);
        setVal('v-ext', extOk);

        if (mimeOk) {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById('av-preview-img');
                const placeholder = document.getElementById('av-preview-placeholder');
                img.src = e.target.result;
                img.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }

        const removeBtn = document.getElementById('remove-av-btn');
        if (removeBtn) removeBtn.style.display = 'none';
    }

    function setVal(id, ok) {
        const el = document.getElementById(id);
        if (!el) return;
        el.className = 'val-item ' + (ok ? 'ok' : 'fail');
        const icon = ok ? 'ti-circle-check' : 'ti-circle-x';
        el.innerHTML = `<i class="ti ${icon}"></i> ` + el.textContent.trim();
    }

    const removeBtn = document.getElementById('remove-av-btn');
    if (removeBtn) {
        removeBtn.addEventListener('click', function () {
            const chk = document.getElementById('remove-av-chk');
            chk.checked = !chk.checked;
            const img = document.getElementById('av-preview-img');
            if (chk.checked) {
                img.style.opacity = '0.3';
                removeBtn.classList.add('active');
            } else {
                img.style.opacity = '1';
                removeBtn.classList.remove('active');
            }
        });
    }
</script>