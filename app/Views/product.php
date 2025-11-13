<?= $this->include('components/header') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Manajemen Produk</h3>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">+ Tambah Produk</button>
</div>

<!-- Table -->
<table class="table table-striped table-bordered align-middle">
  <thead class="table-dark text-center">
    <tr>
      <th>No</th>
      <th>Nama Produk</th>
      <th>Kategori</th>
      <th>Harga</th>
      <th width="160">Aksi</th>
    </tr>
  </thead>
  <tbody class="text-center">
    <?php if (!empty($products)): ?>
      <?php foreach ($products as $i => $p): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= esc($p['name']) ?></td>
          <td><?= esc($p['category_name']) ?></td>
          <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
          <td>
            <button 
              class="btn btn-warning btn-sm editBtn"
              data-id="<?= $p['id'] ?>"
              data-name="<?= esc($p['name']) ?>"
              data-category_id="<?= $p['category_id'] ?>"
              data-price="<?= $p['price'] ?>"
              data-bs-toggle="modal"
              data-bs-target="#editProductModal">
              Edit
            </button>
            <a href="<?= base_url('products/delete/'.$p['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="5" class="text-center">Belum ada produk</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<!-- ==============================
     Modal: Tambah Produk
=============================== -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="<?= base_url('products/store') ?>">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="addProductLabel">Tambah Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select" required>
              <option value="">-- Pilih Kategori --</option>
              <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="price" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ==============================
     Modal: Edit Produk
=============================== -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="<?= base_url('products/update') ?>">
      <input type="hidden" name="id" id="edit_id">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title" id="editProductLabel">Edit Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" id="edit_category" class="form-select" required>
              <option value="">-- Pilih Kategori --</option>
              <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="price" id="edit_price" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  // Isi modal edit otomatis saat tombol edit diklik
  document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('edit_id').value = btn.dataset.id;
      document.getElementById('edit_name').value = btn.dataset.name;
      document.getElementById('edit_category').value = btn.dataset.category_id;
      document.getElementById('edit_price').value = btn.dataset.price;
    });
  });
</script>

<?= $this->include('components/footer') ?>
