<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pointly POS | Products</title>
    <?= view('partials/styles') ?>
</head>
<body>
<div class="app-shell">
    <?= view('partials/nav', ['active' => 'products']) ?>

    <main class="content">
        <header class="topbar">
            <div>
                <p class="eyebrow">Inventory</p>
                <h1>Product catalog</h1>
                <p class="subtitle">Manage products, prices, stock quantities, and images.</p>
            </div>
            <div class="profile"><div class="avatar">AD</div> Admin account</div>
        </header>

        <?php
            $productCount = count($products);
            $stockTotal = 0;
            $imageCount = 0;
            foreach ($products as $product) {
                $stockTotal += (int) ($product['stock_quantity'] ?? 0);
                if (!empty($product['image'])) {
                    $imageCount++;
                }
            }
        ?>

        <section class="stat-grid">
            <div class="stat">
                <div class="stat-icon">▣</div>
                <div><strong><?= $productCount ?></strong><span>Total products</span></div>
            </div>
            <div class="stat">
                <div class="stat-icon">▤</div>
                <div><strong><?= $stockTotal ?></strong><span>Units in stock</span></div>
            </div>
            <div class="stat">
                <div class="stat-icon">◈</div>
                <div><strong><?= $imageCount ?></strong><span>Images uploaded</span></div>
            </div>
        </section>

        <section class="panel">
            <div class="panel-heading">
                <div>
                    <h2>Product directory</h2>
                    <span><?= $productCount ?> records found</span>
                </div>
                <a class="button" href="<?= base_url('products/new') ?>">Add product +</a>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="form-error" style="background:#e7faf7;color:#0f9488">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="form-error">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <?php $stock = (int) ($product['stock_quantity'] ?? 0); ?>
                            <tr>
                                <td>
                                    <div class="person">
                                        <span class="person-avatar"><?= esc(strtoupper(substr($product['name'], 0, 1))) ?></span>
                                        <?= esc($product['name']) ?>
                                    </div>
                                </td>
                                <td>₱<?= number_format((float) $product['price'], 2) ?></td>
                                <td><?= $stock ?></td>
                                <td>
                                    <?php if (!empty($product['image'])): ?>
                                        <img class="current-image" src="<?= base_url('uploads/products/' . $product['image']) ?>" alt="Product image">
                                    <?php else: ?>
                                        <span>None</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge <?= $stock <= 0 ? 'out' : '' ?>"><?= $stock <= 0 ? 'Out of stock' : 'Available' ?></span></td>
                                <td>
                                    <a class="action-link" href="<?= base_url('products/edit/' . $product['id']) ?>">Edit</a>
                                    <a class="action-link danger" href="<?= base_url('products/delete/' . $product['id']) ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No products found. Click “Add product +” to create your first product.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
</body>
</html>
