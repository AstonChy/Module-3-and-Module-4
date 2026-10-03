<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pointly POS | Login</title>
    <?= view('partials/styles') ?>
</head>
<body>
<main class="content" style="max-width:520px;margin:70px auto">
    <section class="panel">
        <p class="eyebrow">Pointly POS</p>
        <h1>Staff login</h1>
        <p class="subtitle">Sign in to manage the POS workspace.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="form-error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="form-error" style="background:#e7faf7;color:#0f9488"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post">
            <div class="field" style="margin-top:20px">
                <label for="username">Username</label>
                <input class="input" type="text" id="username" name="username" value="<?= old('username') ?>" required autofocus>
            </div>
            <div class="field" style="margin-top:18px">
                <label for="password">Password</label>
                <input class="input" type="password" id="password" name="password" required>
            </div>
            <div class="form-actions">
                <button class="button" type="submit">Log in</button>
            </div>
        </form>
    </section>
</main>
</body>
</html>
