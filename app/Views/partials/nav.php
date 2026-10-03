<aside class="sidebar">
    <div class="brand"><div class="brand-mark">▣</div><div><strong>Pointly POS</strong><small>Management console</small></div></div>
    <p class="nav-label">Workspace</p>
    <nav class="nav-list">
        <a class="nav-link <?= ($active ?? '') === 'home' ? 'active' : '' ?>" href="<?= base_url('/') ?>"><span class="nav-icon">⌂</span><span>Dashboard</span></a>
        <a class="nav-link <?= ($active ?? '') === 'customers' ? 'active' : '' ?>" href="<?= base_url('customers') ?>"><span class="nav-icon">♙</span><span>Customers</span></a>
        <a class="nav-link <?= ($active ?? '') === 'products' ? 'active' : '' ?>" href="<?= base_url('products') ?>"><span class="nav-icon">▣</span><span>Products</span></a>
        <a class="nav-link <?= ($active ?? '') === 'sales' ? 'active' : '' ?>" href="<?= base_url('sales') ?>"><span class="nav-icon">₱</span><span>Sales history</span></a>
        <a class="nav-link <?= ($active ?? '') === 'users' ? 'active' : '' ?>" href="<?= base_url('users') ?>"><span class="nav-icon">♧</span><span>User accounts</span></a>
        <a class="nav-link <?= ($active ?? '') === 'about' ? 'active' : '' ?>" href="<?= base_url('about') ?>"><span class="nav-icon">ⓘ</span><span>About system</span></a>
    </nav>
    <div class="sidebar-note"><strong>Lab project</strong>Built with CodeIgniter 4 POS features.</div>
    <a class="nav-link" style="margin-top:16px" href="<?= base_url('logout') ?>"><span class="nav-icon">↪</span><span>Log out</span></a>
</aside>
