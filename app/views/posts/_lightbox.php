<?php
// app/views/posts/_lightbox.php
// Include ONCE per page (feed, profile). Gives the photo grid its styles
// and the tap-to-view full screen photo viewer.
?>
<style>
    /* ===== Photo grid ===== */
    .neo-gallery {
        display: grid; gap: 4px;
        border-radius: 12px; overflow: hidden;
        border: 1px solid var(--glass-chrome-2);
    }
    .neo-g-item {
        position: relative; display: block; padding: 0; border: 0;
        background: var(--glass-bg-2); cursor: zoom-in; overflow: hidden;
        min-height: 0;
    }
    .neo-g-item img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .25s ease; }
    .neo-g-item:hover img { transform: scale(1.03); }
    .neo-gallery.g-1 { grid-template-columns: 1fr; }
    .neo-gallery.g-1 .neo-g-item img { height: auto; max-height: 500px; }
    .neo-gallery.g-2 { grid-template-columns: 1fr 1fr; height: 300px; }
    .neo-gallery.g-3 { grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; height: 380px; }
    .neo-gallery.g-3 .neo-g-item:first-child { grid-row: 1 / span 2; }
    .neo-gallery.g-4 { grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; height: 380px; }
    .neo-g-more {
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
        background: rgba(0,0,0,.55); color: #fff; font-size: 1.8rem; font-weight: 700;
    }
    img.neo-zoom { cursor: zoom-in; }
    @media (max-width: 575px) {
        .neo-gallery.g-2 { height: 210px; }
        .neo-gallery.g-3, .neo-gallery.g-4 { height: 280px; }
    }

    /* ===== Full screen photo viewer ===== */
    .neo-lb {
        position: fixed; inset: 0; z-index: 4000;
        display: flex; align-items: center; justify-content: center;
        background: rgba(0,0,0,.92);
    }
    .neo-lb[hidden] { display: none; }
    .neo-lb img {
        max-width: 92vw; max-height: 88vh; max-height: 88dvh;
        object-fit: contain; border-radius: 6px; user-select: none;
    }
    .neo-lb button {
        position: absolute; border: 0; color: #fff; cursor: pointer;
        background: rgba(255,255,255,.14); border-radius: 50%;
        width: 44px; height: 44px; font-size: 1.3rem;
        display: flex; align-items: center; justify-content: center;
    }
    .neo-lb button:hover { background: rgba(255,255,255,.28); }
    .neo-lb .lb-close { top: 16px; right: 16px; }
    .neo-lb .lb-prev  { left: 16px;  top: 50%; transform: translateY(-50%); }
    .neo-lb .lb-next  { right: 16px; top: 50%; transform: translateY(-50%); }
    .neo-lb .lb-count {
        position: absolute; bottom: 18px; left: 50%; transform: translateX(-50%);
        color: #fff; font-size: 14px; background: rgba(0,0,0,.5); padding: 4px 12px; border-radius: 20px;
    }
    @media (max-width: 575px) {
        .neo-lb .lb-prev, .neo-lb .lb-next { width: 38px; height: 38px; }
        .neo-lb .lb-prev { left: 8px; } .neo-lb .lb-next { right: 8px; }
    }
</style>

<div class="neo-lb" id="neoLightbox" role="dialog" aria-label="Photo viewer" hidden>
    <button type="button" class="lb-close" aria-label="Close"><i class="bi bi-x-lg"></i></button>
    <button type="button" class="lb-prev" aria-label="Previous photo"><i class="bi bi-chevron-left"></i></button>
    <img src="" alt="Photo">
    <button type="button" class="lb-next" aria-label="Next photo"><i class="bi bi-chevron-right"></i></button>
    <div class="lb-count"></div>
</div>

<script>
(function () {
    var lb = document.getElementById('neoLightbox');
    if (!lb) return;
    document.body.appendChild(lb);               // so nothing can trap it inside a card
    var img = lb.querySelector('img');
    var count = lb.querySelector('.lb-count');
    var prev = lb.querySelector('.lb-prev');
    var next = lb.querySelector('.lb-next');
    var list = [], idx = 0;

    function show() {
        img.src = list[idx];
        count.textContent = (idx + 1) + ' / ' + list.length;
        var many = list.length > 1;
        prev.style.display = next.style.display = count.style.display = many ? '' : 'none';
    }
    function openAt(images, i) {
        list = images; idx = i; show();
        lb.hidden = false;
        document.body.style.overflow = 'hidden';
    }
    function close() {
        lb.hidden = true; img.src = '';
        document.body.style.overflow = '';
    }
    function go(step) { idx = (idx + step + list.length) % list.length; show(); }

    document.addEventListener('click', function (e) {
        var item = e.target.closest('.neo-g-item');
        if (item) {
            var gal = item.closest('.neo-gallery');
            try { openAt(JSON.parse(gal.getAttribute('data-images')), parseInt(item.getAttribute('data-i'), 10) || 0); } catch (err) {}
            return;
        }
        var single = e.target.closest('img.neo-zoom');
        if (single) { openAt([single.getAttribute('src')], 0); }
    });

    lb.addEventListener('click', function (e) { if (e.target === lb) close(); });
    lb.querySelector('.lb-close').addEventListener('click', close);
    prev.addEventListener('click', function (e) { e.stopPropagation(); go(-1); });
    next.addEventListener('click', function (e) { e.stopPropagation(); go(1); });

    document.addEventListener('keydown', function (e) {
        if (lb.hidden) return;
        if (e.key === 'Escape') close();
        else if (e.key === 'ArrowLeft' && list.length > 1) go(-1);
        else if (e.key === 'ArrowRight' && list.length > 1) go(1);
    });

    // swipe on phones
    var startX = null;
    lb.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
    lb.addEventListener('touchend', function (e) {
        if (startX === null || list.length < 2) return;
        var dx = e.changedTouches[0].clientX - startX;
        if (Math.abs(dx) > 50) go(dx < 0 ? 1 : -1);
        startX = null;
    }, { passive: true });
})();
</script>