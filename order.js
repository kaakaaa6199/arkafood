// order.js — build WhatsApp link and redirect
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('orderForm');
  if (!form) return;

  // Nomor tujuan WhatsApp (diasumsikan). Jika ingin diganti, ubah di sini.
  // Nomor harus dalam format internasional tanpa tanda plus dan tanpa spasi.
  const WA_NUMBER = '6282116726900'; // asumsi: 082116726900 -> 6282116726900

  // Pre-fill product jika ada query param ?product=...
  const params = new URLSearchParams(window.location.search);
  const preProduct = params.get('product');
  if (preProduct) {
    const productInput = document.getElementById('product');
    if (productInput) productInput.value = decodeURIComponent(preProduct);
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const product = document.getElementById('product').value.trim();
    const quantity = document.getElementById('quantity').value.trim();
    const name = document.getElementById('name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const address = document.getElementById('address').value.trim();

    if (!product || !quantity || !name || !phone) {
      alert('Mohon isi semua field yang diperlukan.');
      return;
    }

    // Jika pengguna memasukkan nomor lokal (diawali 0), ubah ke format internasional 62
    let customerNumber = phone.replace(/[^0-9]/g, '');
    if (customerNumber.startsWith('0')) {
      customerNumber = '62' + customerNumber.slice(1);
    }

    // Bangun pesan
    let message = '*Pesanan dari Website Arka Food*%0A';
    message += '*Produk:* ' + encodeURIComponent(product) + '%0A';
    message += '*Jumlah:* ' + encodeURIComponent(quantity) + '%0A';
    message += '*Nama:* ' + encodeURIComponent(name) + '%0A';
    message += '*Nomor pelanggan:* ' + encodeURIComponent(phone) + '%0A';
    if (address) message += '*Alamat / Catatan:* ' + encodeURIComponent(address) + '%0A';
    message += '%0A' + encodeURIComponent('Mohon konfirmasi ketersediaan dan metode pembayaran. Terima kasih.');

    // Arahkan ke WhatsApp Business API (wa.me)
    const waUrl = `https://wa.me/${WA_NUMBER}?text=${message}`;

    // Buka di tab baru (atau sama) — gunakan location.href untuk membuka di tab yang sama
    window.open(waUrl, '_blank');
  });
});
