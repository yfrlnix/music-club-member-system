<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'GreenBeat') ?> GreenBeat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="/css/app.css">
</head>

<body>

    <nav class="topnav">
        <div class="nav-brand">
            <i class="ti ti-music"></i> GreenBeat
        </div>
        <div class="nav-links">
            <a href="/forum" class="nav-link <?= uri_string() === 'forum' ? 'on' : '' ?>">Forum</a>
            <a href="/dashboard" class="nav-link <?= uri_string() === 'dashboard' ? 'on' : '' ?>">Dashboard</a>
            <a href="/members" class="nav-link <?= str_starts_with(uri_string(), 'members') ? 'on' : '' ?>">Members</a>
        </div>
        <div class="nav-right">
            <div class="lang-btn">
                <i class="ti ti-world"></i> English
            </div>
            <div class="nav-avatar" title="Your profile">AC</div>
            <button class="nav-icon" aria-label="Notifications"><i class="ti ti-bell"></i></button>
            <button class="nav-icon" aria-label="Alerts"><i class="ti ti-alert-triangle"></i></button>
            <button class="nav-icon" aria-label="Messages"><i class="ti ti-message"></i></button>
            <button class="nav-icon" aria-label="Search"><i class="ti ti-search"></i></button>
        </div>
    </nav>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success" role="alert">
            <i class="ti ti-circle-check"></i>
            <?= esc(session()->getFlashdata('success')) ?>
            <button class="alert-close" onclick="this.parentElement.remove()" aria-label="Dismiss">&times;</button>
        </div>
    <?php endif; ?>

    <?php if ($errors = session()->getFlashdata('errors')): ?>
        <div class="alert alert-error" role="alert">
            <i class="ti ti-alert-circle"></i>
            <ul class="alert-list">
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button class="alert-close" onclick="this.parentElement.remove()" aria-label="Dismiss">&times;</button>
        </div>
    <?php endif; ?>

    <main>
        <?= $content ?>
    </main>

    <script src="/js/app.js"></script>
</body>

</html>