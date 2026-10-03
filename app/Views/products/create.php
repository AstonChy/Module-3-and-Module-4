<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pointly POS | Add Product</title>
    <?= view('partials/styles') ?>
</head>
<body>
<div class="app-shell">
    <?= view('partials/nav', ['active' => 'products']) ?>
    <main class="content">
        <header class="topbar">
            <div>
                <p class="eyebrow">Inventory</p>
                <h1>Add product</h1>
                <p class="subtitle">Create a product record for your POS catalog.</p>
            </div>
            <div class="profile"><div class="avatar">AD</div> Admin account</div>
        </header>

        <section class="panel">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="form-error"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <form action="<?= base_url('products/create') ?>" method="post" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="field full">
                        <label for="name">Product name</label>
                        <input class="input" type="text" id="name" name="name" value="<?= old('name') ?>" required>
                    </div>
                    <div class="field">
                        <label for="price">Price</label>
                        <input class="input" type="number" id="price" name="price" step="0.01" min="0" value="<?= old('price') ?>" required>
                    </div>
                    <div class="field">
                        <label for="stock_quantity">Stock quantity</label>
                        <input class="input" type="number" id="stock_quantity" name="stock_quantity" min="0" value="<?= old('stock_quantity') ?>" required>
                    </div>
                    <div class="field full">
                        <label for="image">Product image <span style="font-weight:400;color:var(--muted)">(JPG or PNG, maximum 2 MB)</span></label>
                        <input class="input" type="file" id="image" name="image" accept=".jpg,.jpeg,.png">
                    </div>
                </div>
                <div class="form-actions">
                    <button class="button" type="submit">Save product</button>
                    <a class="button secondary" href="<?= base_url('products') ?>">Cancel</a>
                </div>
            </form>
        </section>
    </main>
</div>
</body>
</html>
