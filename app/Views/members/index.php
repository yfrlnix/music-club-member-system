<?php
use App\Models\MemberModel;

$avColors = [
    ['bg' => '#ddeedd', 'fg' => '#002D2D'],
    ['bg' => '#d0ede6', 'fg' => '#002D2D'],
    ['bg' => '#eef6d6', 'fg' => '#002D2D'],
    ['bg' => '#003838', 'fg' => '#95BF47'],
    ['bg' => '#002D2D', 'fg' => '#95BF47'],
    ['bg' => '#5E8E3E', 'fg' => '#ffffff'],
];

$bannerClasses = ['banner-1', 'banner-2', 'banner-3', 'banner-4', 'banner-5', 'banner-6'];

function roleBadgeClass(string $role): string
{
    return match ($role) {
        'Administrator' => 'role-admin',
        'Moderator' => 'role-mod',
        'Member' => 'role-mem',
        default => 'role-beg',
    };
}

function buildQuery(array $base, array $overrides): string
{
    $merged = array_merge($base, $overrides);
    unset($merged['page']); 
    if (isset($overrides['page'])) {
        $merged['page'] = $overrides['page'];
    }
    return '?' . http_build_query(array_filter($merged, fn($v) => $v !== ''));
}

$q = esc($params['q']);
$role = $params['role'];
$sort = $params['sort'];
$dir = $params['dir'];
$maxPts = max(array_column($mostActive, 'points') ?: [1]);
?>

<div class="members-layout">

    <div class="members-main">

        <div class="page-hd">
            <h1>Members <span class="count-badge"><?= $total ?></span></h1>
            <a href="/members/create" class="btn btn-primary">
                <i class="ti ti-user-plus"></i> Add Member
            </a>
        </div>

        <form method="get" action="/members" class="toolbar" id="filter-form">
            <div class="sort-group">
                <i class="ti ti-arrows-sort"></i>
                <?php foreach (['points' => 'Points', 'post_count' => 'Posts', 'reactions' => 'Reactions'] as $key => $label): ?>
                    <button type="submit" name="sort" value="<?= $key ?>"
                        class="sort-btn <?= $sort === $key ? 'on' : '' ?>">
                        <?= $label ?>
                    </button>
                    <?php if ($key !== 'reactions'): ?>
                        <div class="sort-divider"></div><?php endif; ?>
                <?php endforeach; ?>
                <input type="hidden" name="q" value="<?= $q ?>">
                <input type="hidden" name="role" value="<?= esc($role) ?>">
                <input type="hidden" name="status" value="<?= esc($params['status']) ?>">
            </div>

            <div class="search-box">
                <i class="ti ti-search"></i>
                <input type="text" name="q" id="main-q" value="<?= $q ?>" placeholder="Search members…"
                    autocomplete="off">
                <?php if ($q): ?>
                    <a href="<?= buildQuery($params, ['q' => '']) ?>" class="clear-btn" aria-label="Clear search">
                        <i class="ti ti-x"></i>
                    </a>
                <?php endif; ?>
            </div>

            <div class="chip-group">
                <?php foreach (['' => 'All', 'Administrator' => 'Admin', 'Moderator' => 'Moderator', 'Member' => 'Member', 'Beginner' => 'Beginner'] as $val => $label): ?>
                    <a href="<?= buildQuery($params, ['role' => $val]) ?>"
                        class="fil-chip <?= $role === $val ? 'on' : '' ?>">
                        <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-filter">
                <i class="ti ti-adjustments-horizontal"></i> Filter
            </button>
        </form>

        <?php if (empty($members)): ?>
            <div class="empty-state">
                <i class="ti ti-users-group"></i>
                <p>No members found matching your search.</p>
                <a href="/members" class="btn btn-outline">Clear filters</a>
            </div>
        <?php else: ?>
            <div class="member-grid">
                <?php foreach ($members as $i => $member):
                    $ac = $avColors[$member['id'] % count($avColors)];
                    $bn = $bannerClasses[$i % count($bannerClasses)];
                    $ini = MemberModel::initials($member);
                    $name = MemberModel::fullName($member);
                    ?>
                    <article class="mcard" aria-label="<?= esc($name) ?>">
                        <div class="mcard-banner <?= $bn ?>"></div>

                        <div class="mcard-av-wrap">
                            <?php if (!empty($member['avatar'])): ?>
                                <img src="/<?= esc($member['avatar']) ?>" alt="<?= esc($name) ?>" class="mcard-av mcard-av-img">
                            <?php else: ?>
                                <div class="mcard-av" style="background:<?= $ac['bg'] ?>;color:<?= $ac['fg'] ?>">
                                    <?= $ini ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mcard-body">
                            <div class="mcard-name">
                                <a href="/members/<?= $member['id'] ?>"><?= esc($name) ?></a>
                            </div>

                            <div class="mcard-status">
                                <span
                                    class="status-badge <?= $member['status'] === 'active' ? 'status-active' : 'status-inactive' ?>">
                                    <?= ucfirst($member['status']) ?>
                                </span>
                            </div>

                            <div class="mcard-username">
                                <i class="ti ti-user"></i> @<?= esc($member['username']) ?>
                            </div>

                            <div class="mcard-email">
                                <i class="ti ti-mail"></i> <?= esc($member['email']) ?>
                            </div>

                            <div class="mcard-badges">
                                <span class="role-badge <?= roleBadgeClass($member['role']) ?>">
                                    <?= esc($member['role']) ?>
                                </span>


                                <div class="mcard-actions">
                                    <a href="/members/edit/<?= $member['id'] ?>" class="act-btn" title="Edit member">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <button class="act-btn" title="Message">
                                        <i class="ti ti-message"></i>
                                    </button>
                                    <button class="act-btn" title="Follow">
                                        <i class="ti ti-user-plus"></i>
                                    </button>
                                    <a href="/members/delete/<?= $member['id'] ?>"
                                        onclick="return confirm('Delete <?= esc($name) ?>? This cannot be undone.')"
                                        class="act-btn act-delete" title="Delete member">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                </div>

                                <div class="mcard-stats">
                                    <div class="stat-col">
                                        <span class="stat-num"><?= (int) $member['post_count'] ?></span>
                                        <span class="stat-lbl">Posts</span>
                                    </div>
                                    <div class="stat-col">
                                        <span class="stat-num"><?= (int) $member['reactions'] ?></span>
                                        <span class="stat-lbl">Reactions</span>
                                    </div>
                                    <div class="stat-col">
                                        <span class="stat-num"><?= (int) $member['points'] ?></span>
                                        <span class="stat-lbl">Points</span>
                                    </div>
                                </div>
                            </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($pager) && method_exists($pager, 'getPageCount') && $pager->getPageCount() > 1): ?>
            <nav class="pagination" aria-label="Member pages">
                <?php
                $currentPage = $pager->getCurrentPage();
                $pageCount = $pager->getPageCount();
                $firstNumber = (($currentPage - 1) * 5) + 1;
                $lastNumber = min($currentPage * 5, $total);
                ?>

                <?php if ($currentPage > 1): ?>
                    <a href="?page=<?= $currentPage - 1 ?>" class="pg-btn" aria-label="Previous page">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                <?php else: ?>
                    <span class="pg-btn disabled"><i class="ti ti-chevron-left"></i></span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $pageCount; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="pg-btn on"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>" class="pg-btn"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($currentPage < $pageCount): ?>
                    <a href="?page=<?= $currentPage + 1 ?>" class="pg-btn" aria-label="Next page">
                        <i class="ti ti-chevron-right"></i>
                    </a>
                <?php else: ?>
                    <span class="pg-btn disabled"><i class="ti ti-chevron-right"></i></span>
                <?php endif; ?>

                <span class="pg-info">
                    Showing <?= $firstNumber ?>–<?= $lastNumber ?> of <?= $total ?>
                </span>
            </nav>
        <?php endif; ?>

    </div>

    <aside class="members-sidebar">

        <div class="sib-section">
            <div class="sib-title">Search members</div>
            <form method="get" action="/members">
                <div class="sib-search">
                    <i class="ti ti-search"></i>
                    <input type="text" name="q" placeholder="Username…" value="<?= $q ?>">
                </div>
            </form>
        </div>

        <div class="sib-section">
            <div class="sib-title">Newest members</div>
            <?php foreach ($newest as $m):
                $ac = $avColors[$m['id'] % count($avColors)];
                $name = MemberModel::fullName($m);
                ?>
                <a href="/members/<?= $m['id'] ?>" class="sib-member-row">
                    <?php if (!empty($m['avatar'])): ?>
                        <img src="/<?= esc($m['avatar']) ?>" alt="<?= esc($name) ?>" class="sib-av sib-av-img">
                    <?php else: ?>
                        <div class="sib-av" style="background:<?= $ac['bg'] ?>;color:<?= $ac['fg'] ?>">
                            <?= MemberModel::initials($m) ?>
                        </div>
                    <?php endif; ?>
                    <div class="sib-info">
                        <div class="sib-name"><?= esc($name) ?></div>
                        <div class="sib-date"><?= date('M j, Y', strtotime($m['joined_at'] ?? $m['created_at'])) ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="sib-section">
            <div class="sib-title">Most active members</div>
            <?php foreach ($mostActive as $m):
                $ac = $avColors[$m['id'] % count($avColors)];
                $pct = $maxPts > 0 ? round($m['points'] / $maxPts * 100) : 0;
                $name = MemberModel::fullName($m);
                ?>
                <a href="/members/<?= $m['id'] ?>" class="sib-points-row">
                    <?php if (!empty($m['avatar'])): ?>
                        <img src="/<?= esc($m['avatar']) ?>" alt="<?= esc($name) ?>" class="sib-av sib-av-img sib-av-sm">
                    <?php else: ?>
                        <div class="sib-av sib-av-sm" style="background:<?= $ac['bg'] ?>;color:<?= $ac['fg'] ?>">
                            <?= MemberModel::initials($m) ?>
                        </div>
                    <?php endif; ?>
                    <div class="sib-pts-info">
                        <div class="sib-name"><?= esc($name) ?></div>
                        <div class="pts-bar-wrap">
                            <div class="pts-bar" style="width:<?= $pct ?>%"></div>
                        </div>
                        <div class="sib-date"><?= (int) $m['points'] ?> points</div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    </aside>

</div>