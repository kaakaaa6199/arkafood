// product.js - load product data and initialize simple gallery
document.addEventListener('DOMContentLoaded', function(){
  const products = {
    'product1': {
      id: 'product1', title: 'Produk Premium 1', price: 'Rp 150.000',
      desc: 'Deskripsi singkat produk premium 1.',
      full: 'Deskripsi lengkap untuk Produk Premium 1. Bahan berkualitas, proses higienis, dan rasa terjamin.',
      images: ['assets/images/produk/kkOri/1.png','assets/images/produk/kkOri/2.png','assets/images/produk/kkOri/3.png']
    },
    'product2': {
      id: 'product2', title: 'Produk Premium 2', price: 'Rp 175.000',
      desc: 'Deskripsi singkat produk premium 2.', full: 'Deskripsi lengkap untuk Produk Premium 2.',
      images: ['assets/images/product2.jpg','assets/images/product2-2.jpg']
    },
    'product3': {
      id: 'product3', title: 'Produk Premium 3', price: 'Rp 200.000', desc:'Deskripsi singkat produk premium 3.', full:'Deskripsi lengkap untuk Produk Premium 3.', images:['assets/images/product3.jpg']
    },
    'product4': {
      id: 'product4', title: 'Jamur Krezi - Original', price: 'Rp 7.000',
      desc: 'Olahan jamur dengan varian rasa original yang lezat.',
      full: 'Jamur krezi original, packing 120gr. Cocok sebagai cemilan keluarga.',
      images: ['assets/images/product4.jpg','assets/images/product4-2.jpg']
    },
    'product5': {
      id: 'product5', title: 'Eggroll Original', price: 'Rp 14.000',
      desc: 'Olahan telur dengan varian rasa original yang lezat.',
      full: 'Eggroll original, renyah dan manis pas. Packing 80gr.',
      images: ['assets/images/product5.jpg']
    },
    'product6': {
      id: 'product6', title: 'Eggroll Coklat', price: 'Rp 14.000',
      desc: 'Olahan telur dengan varian rasa coklat yang lezat.',
      full: 'Eggroll coklat, favorit anak-anak. Packing 80gr.',
      images: ['assets/images/product6.jpg']
    },
    'jkoriginal': { id:'jkoriginal', title:'Jamur Krispy Tiram Original', price:'Rp 7.000', desc:'Jamur tiram crispy', full:'Jamur Tiram Pedas Krispy dengan menggunakan bahan baku jamur tiram berkualitas lalu menggunakan bumbu pedas yang menggugah selera, kemasan ini dengan berat bersih 70gr.', images:['assets/images/produk/jkOri/0.jpg','assets/images/produk/jkOri/1.jpg','assets/images/produk/jkOri/2.jpg','assets/images/produk/jkOri/3.jpg'] },
    'jkpedas': { id:'jkpedas', title:'Jamur Krispy Tiram Pedas', price:'Rp 7.000', desc:'Jamur tiram crispy pedas', full:'Jamur Tiram Pedas Krispy dengan menggunakan bahan baku jamur tiram berkualitas lalu menggunakan bumbu pedas yang menggugah selera, kemasan ini dengan berat bersih 70gr.', images:['assets/images/produk/jkPedas/0.jpg','assets/images/produk/jkPedas/1.jpg','assets/images/produk/jkPedas/2.jpg','assets/images/produk/jkPedas/3.jpg'] },
    'kkori': { id:'kkori', title:'Kulit Krezi Original', price:'Rp 7.000', desc:'Kulit krezi original', full:'Kulit Krezi ini terbuat dari bahan berkualitas tinggi dan diolah dengan resep khusus. Bahan baku utama yaitu Kulit Ayam dengan bumbu pilihan pada terigu, berat bersih 70gr', images:['assets/images/produk/kkOri/0.jpg','assets/images/produk/kkOri/1.jpg','assets/images/produk/kkOri/2.jpg','assets/images/produk/kkOri/3.jpg'] },
    'kkpedas': { id:'kkpedas', title:'Kulit Krezi Pedas', price:'Rp 7.000', desc:'Kulit krezi pedas', full:'Kulit Krezi ini terbuat dari bahan berkualitas tinggi dan diolah dengan resep khusus. Bahan baku utama yaitu Kulit Ayam dengan bumbu pilihan pada terigu, berat bersih 70gr', images:['assets/images/produk/kkPedas/0.jpg','assets/images/produk/kkPedas/1.jpg','assets/images/produk/kkPedas/2.jpg','assets/images/produk/kkPedas/3.jpg'] }
  };

  // read id or product query param
  const params = new URLSearchParams(window.location.search);
  let id = params.get('id') || params.get('product') || params.get('sku');
  // Normalize product query values used earlier (like 'Produk Premium 1' etc.) by mapping
  if (id && id.toLowerCase().startsWith('produk')) {
    // map Produk Premium 1 -> product1
    const match = id.toLowerCase().match(/produk\s*premium\s*(\d+)/);
    if (match){ id = 'product' + match[1]; }
  }
  // map some names
  if (id && id.toLowerCase().includes('jamur')) id = 'jk1';
  if (id && id.toLowerCase().includes('pedas') && id.toLowerCase().includes('tiram')) id = 'jk2';
  if (id && (id.toLowerCase().includes('kulit')|| id.toLowerCase().includes('krezi'))) id = 'kk1';

  // fallback to product1
  if (!id || !products[id]) id = 'product1';

  const p = products[id];
  document.getElementById('p-title').textContent = p.title;
  document.getElementById('p-desc').textContent = p.desc;
  document.getElementById('p-price').textContent = p.price;
  document.getElementById('p-full-desc').textContent = p.full;

  // set order link to order.html with product prefilled
  const orderLink = document.getElementById('order-link');
  if (orderLink){ orderLink.href = 'order.html?product=' + encodeURIComponent(p.title); }

  // build gallery
  const track = document.getElementById('galleryTrack');
  const dots = document.getElementById('galleryDots');
  track.innerHTML = '';
  dots.innerHTML = '';
  p.images.forEach((src, idx)=>{
    const item = document.createElement('div'); item.className='gallery-item';
    const img = document.createElement('img'); img.src = src; img.alt = p.title + ' ' + (idx+1);
    item.appendChild(img); track.appendChild(item);

    const dot = document.createElement('span'); dot.className='gallery-dot'; if(idx===0) dot.classList.add('active'); dot.dataset.index = idx;
    dot.addEventListener('click', ()=> goTo(idx)); dots.appendChild(dot);
  });

  let current = 0;
  const total = p.images.length;
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  function update(){
    const w = track.clientWidth; // not used since items are 100% width
    track.style.transform = `translateX(-${current * 100}%)`;
    Array.from(dots.children).forEach((d,i)=> d.classList.toggle('active', i===current));
  }
  function goTo(i){ if(i<0) i=0; if(i>=total) i=total-1; current=i; update(); }
  prevBtn.addEventListener('click', ()=> goTo(current-1));
  nextBtn.addEventListener('click', ()=> goTo(current+1));

  // touch support
  let startX=0, deltaX=0;
  track.addEventListener('touchstart', e=>{ startX = e.touches[0].clientX; });
  track.addEventListener('touchmove', e=>{ deltaX = e.touches[0].clientX - startX; });
  track.addEventListener('touchend', ()=>{ if(deltaX>50) goTo(current-1); else if(deltaX<-50) goTo(current+1); startX=0; deltaX=0; });

  // keyboard navigation
  document.addEventListener('keydown', e=>{ if(e.key==='ArrowLeft') goTo(current-1); if(e.key==='ArrowRight') goTo(current+1); });

  // initial update
  update();
});
