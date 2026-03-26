// news.js - manage news data and gallery navigation
document.addEventListener('DOMContentLoaded', function(){
  // News Database
  const newsDatabase = {
    '1': {
      id: 1,
      title: 'Peluncuran Lini Produk Terbaru',
      date: '9 November 2025',
      author: 'Arka Food',
      category: 'Produk Baru',
      content: `
        <p>Arka Food dengan bangga mengumumkan peluncuran lini produk terbaru yang menghadirkan inovasi dalam dunia kuliner. Produk-produk ini telah melalui serangkaian uji kualitas yang ketat untuk memastikan standar premium kami terpenuhi.</p>
        
        <p>Tim research and development kami telah bekerja keras selama berbulan-bulan untuk menciptakan produk yang tidak hanya lezat, tetapi juga sehat dan bergizi. Setiap produk dirancang dengan mempertimbangkan kebutuhan konsumen modern yang menginginkan kualitas terbaik.</p>
        
        <p>Inovasi terbaru kami menggabungkan bahan-bahan premium pilihan dengan proses produksi modern yang higienis. Kami percaya bahwa kualitas adalah prioritas utama, dan setiap produk yang kami luncurkan mencerminkan komitmen kami terhadap kepuasan pelanggan.</p>
        
        <p>Produk-produk baru ini akan tersedia di berbagai outlet dan platform e-commerce kami mulai bulan November ini. Kami mengundang semua pelanggan setia kami untuk mencoba pengalaman kuliner yang baru dan menakjubkan.</p>
      `,
      images: [
        'assets/images/news/newsproduk.png',
        'assets/images/news/newskk.png'
      ]
    },
    '2': {
      id: 2,
      title: 'Kolaborasi dengan Chef Ternama',
      date: '5 November 2025',
      author: 'Arka Food',
      category: 'Kolaborasi',
      content: `
        <p>Arka Food menjalin kerjasama strategis dengan chef terkenal dalam pengembangan produk baru yang inovatif. Kolaborasi ini merupakan langkah penting dalam meningkatkan kualitas dan kreativitas produk kami.</p>
        
        <p>Chef pemenang berbagai penghargaan internasional telah memberikan masukan berharga tentang cita rasa, tekstur, dan presentasi produk. Keahlian mereka dalam dunia kuliner akan membantu kami menciptakan produk yang lebih menarik dan berkualitas tinggi.</p>
        
        <p>Melalui kolaborasi ini, kami bertujuan untuk memperkenalkan Arka Food ke pasar yang lebih luas dan meningkatkan brand awareness di tingkat nasional. Produk hasil kolaborasi ini akan menampilkan signature style dari chef yang terlibat.</p>
        
        <p>Kami sangat optimis bahwa produk-produk baru hasil kolaborasi ini akan mendapat sambutan positif dari masyarakat dan menjadi bestseller di pasaran. Bersiaplah untuk pengalaman kuliner yang tak terlupakan!</p>
      `,
      images: [
        'assets/images/news/newskk.png',
        'assets/images/news/newskkplate.JPG'
      ]
    },
    '3': {
      id: 3,
      title: 'Promo Spesial Akhir Tahun',
      date: '1 November 2025',
      author: 'Arka Food',
      category: 'Promo',
      content: `
        <p>Dapatkan penawaran khusus untuk berbagai produk premium Arka Food selama periode akhir tahun. Diskon menarik dan hadiah spesial menanti untuk setiap pembelian Anda.</p>
        
        <p>Promo spesial ini adalah bentuk apresiasi kami kepada pelanggan setia yang telah mendukung Arka Food sejak awal. Dengan berbagai paket bundel hemat, Anda bisa menikmati produk favorit dengan harga yang lebih terjangkau.</p>
        
        <p>Selain diskon, kami juga menyediakan program loyalitas khusus di mana setiap pembelian memberikan poin reward yang bisa ditukarkan dengan produk gratis atau diskon tambahan untuk pembelian berikutnya.</p>
        
        <p>Jangan lewatkan kesempatan emas ini! Promo spesial akhir tahun berlaku hingga akhir Desember 2025. Hubungi kami melalui WhatsApp atau kunjungi outlet terdekat untuk mendapatkan informasi lengkap tentang penawaran terbaik kami.</p>
      `,
      images: [
        'assets/images/news/newskkplate.JPG',
        'assets/images/news/newsproduk.png'
      ]
    },
    '4': {
      id: 4,
      title: 'Event Kuliner Jakarta',
      date: '28 Oktober 2025',
      author: 'Arka Food',
      category: 'Event',
      content: `
        <p>Arka Food akan berpartisipasi dalam event kuliner terbesar di Jakarta minggu depan. Event ini akan menjadi kesempatan emas untuk bertemu langsung dengan ribuan penggemar kuliner dari seluruh nusantara.</p>
        
        <p>Di booth Arka Food, kami akan menampilkan seluruh lini produk unggulan dan memberikan kesempatan kepada pengunjung untuk mencoba semua produk kami secara gratis. Tim kami yang berpengalaman siap memberikan konsultasi tentang produk dan menjawab semua pertanyaan Anda.</p>
        
        <p>Selain itu, kami juga akan menghadirkan demo memasak langsung dengan chef profesional, di mana mereka akan menunjukkan berbagai cara kreatif menggunakan produk Arka Food dalam hidangan sehari-hari.</p>
        
        <p>Jangan lewatkan kesempatan untuk mendapatkan merchandise eksklusif Arka Food dan voucher belanja yang hanya tersedia di event ini. Kami tunggu kedatangan Anda di Jakarta Convention Center, 4-6 November 2025!</p>
      `,
      images: [
        'assets/images/news/newsproduk.png',
        'assets/images/news/newskk.png'
      ]
    }
  };

  // Get news ID from URL
  const params = new URLSearchParams(window.location.search);
  const newsId = params.get('id') || '1';
  const news = newsDatabase[newsId];

  // If it's the detail page, populate content
  if (document.getElementById('news-detail-container')) {
    if (!news) {
      document.getElementById('news-detail-container').innerHTML = '<p>Berita tidak ditemukan.</p>';
      return;
    }

    // Set title and meta
    document.getElementById('news-title').textContent = news.title;
    document.getElementById('news-date').textContent = news.date;
    document.getElementById('news-author').textContent = news.author;
    document.getElementById('news-category').textContent = news.category;
    document.getElementById('news-content').innerHTML = news.content;

    // Build gallery
    const galleryTrack = document.getElementById('news-gallery-track');
    const galleryDots = document.getElementById('news-gallery-dots');
    galleryTrack.innerHTML = '';
    galleryDots.innerHTML = '';

    news.images.forEach((src, idx) => {
      // Gallery items
      const item = document.createElement('div');
      item.className = 'news-gallery-item';
      const img = document.createElement('img');
      img.src = src;
      img.alt = news.title + ' ' + (idx + 1);
      item.appendChild(img);
      galleryTrack.appendChild(item);

      // Gallery dots
      const dot = document.createElement('span');
      dot.className = 'news-gallery-dot';
      if (idx === 0) dot.classList.add('active');
      dot.dataset.index = idx;
      dot.addEventListener('click', () => goToSlide(idx));
      galleryDots.appendChild(dot);
    });

    // Gallery navigation
    let currentSlide = 0;
    const totalSlides = news.images.length;

    function updateGallery() {
      galleryTrack.style.transform = `translateX(-${currentSlide * 100}%)`;
      Array.from(galleryDots.children).forEach((dot, idx) => {
        dot.classList.toggle('active', idx === currentSlide);
      });
    }

    window.goToSlide = function(idx) {
      if (idx < 0) currentSlide = 0;
      else if (idx >= totalSlides) currentSlide = totalSlides - 1;
      else currentSlide = idx;
      updateGallery();
    };

    document.getElementById('news-prev-btn').addEventListener('click', () => goToSlide(currentSlide - 1));
    document.getElementById('news-next-btn').addEventListener('click', () => goToSlide(currentSlide + 1));

    // Touch swipe support
    let startX = 0, deltaX = 0;
    galleryTrack.addEventListener('touchstart', e => { startX = e.touches[0].clientX; });
    galleryTrack.addEventListener('touchmove', e => { deltaX = e.touches[0].clientX - startX; });
    galleryTrack.addEventListener('touchend', () => {
      if (deltaX > 50) goToSlide(currentSlide - 1);
      else if (deltaX < -50) goToSlide(currentSlide + 1);
      startX = 0;
      deltaX = 0;
    });

    // Keyboard navigation
    document.addEventListener('keydown', e => {
      if (e.key === 'ArrowLeft') goToSlide(currentSlide - 1);
      if (e.key === 'ArrowRight') goToSlide(currentSlide + 1);
    });

    // Build sidebar with recent news
    const sidebar = document.getElementById('news-sidebar');
    const sidebarHTML = '<h5>Berita Terbaru</h5>';
    let newsItems = '';
    
    // Show other news (max 4 recent news)
    const otherNewsIds = Object.keys(newsDatabase)
      .filter(id => id !== newsId)
      .sort((a, b) => {
        const aDate = new Date(newsDatabase[a].date);
        const bDate = new Date(newsDatabase[b].date);
        return bDate - aDate;
      })
      .slice(0, 4);

    otherNewsIds.forEach(id => {
      const n = newsDatabase[id];
      newsItems += `
        <a href="news-detail.html?id=${n.id}">
          <div class="sidebar-news-item">
            <div class="sidebar-news-thumb">
              <img src="${n.images[0]}" alt="${n.title}">
            </div>
            <div class="sidebar-news-body">
              <p class="sidebar-news-title">${n.title}</p>
              <p class="sidebar-news-date">${n.date}</p>
            </div>
          </div>
        </a>
      `;
    });

    sidebar.innerHTML = sidebarHTML + newsItems;

    updateGallery();
  }
});
