<?php include 'includes/header.php'; ?>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Lanjutkan Pemesanan</h2>
            <p class="text-center text-muted">Isi formulir di bawah untuk mengirimkan pesanan via WhatsApp. Anda akan diarahkan langsung ke WhatsApp dengan pesan terisi otomatis.</p>

            <div class="row justify-content-center mt-4">
                <div class="col-lg-8">
                    <div class="card p-4">
                        <form id="orderForm">
                            <div class="mb-3">
                                <label for="product" class="form-label">Produk</label>
                                <input type="text" id="product" name="product" class="form-control" placeholder="Pilih produk atau masukkan nama produk" required>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Jumlah</label>
                                <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor WhatsApp</label>
                                <input type="tel" id="phone" name="phone" class="form-control" placeholder="0812xxxx" required>
                                <div class="form-text">Masukkan nomor tanpa + atau spasi. Contoh: 081234567890</div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat / Catatan</label>
                                <textarea id="address" name="address" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Dengan melanjutkan, Anda akan diarahkan ke WhatsApp.</small>
                                <button type="submit" class="btn btn-primary">Lanjutkan ke WhatsApp</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="assets/js/order.js"></script>

<?php include 'includes/footer.php'; ?>
