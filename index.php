<?php include 'includes/header.php'; ?>

<section class="hero">
    <!-- Hero image (loads first, no layout shift) -->
    <picture>
        <!-- Next‑gen AVIF -->
        <source
            type="image/avif"
            srcset="
              /assets/images/hero_640.avif   640w,
              /assets/images/hero_1024.avif 1024w,
              /assets/images/hero_1536.avif 1536w,
              /assets/images/hero_2048.avif 2048w,
              /assets/images/hero_2560.avif 2560w"
            sizes="100vw">

        <!-- WebP fallback -->
        <source
            type="image/webp"
            srcset="
              /assets/images/hero_640.webp   640w,
              /assets/images/hero_1024.webp 1024w,
              /assets/images/hero_1536.webp 1536w,
              /assets/images/hero_2048.webp 2048w,
              /assets/images/hero_2560.webp 2560w"
            sizes="100vw">

        <!-- Safe JPEG fallback -->
        <img
            src="/assets/images/hero_2048.jpg"
            alt="The Farmstead at Sunrise."
            width="2048"
            height="726"
            loading="eager"
            fetchpriority="high">
    </picture>

    <!-- Existing copy & CTA -->
    <div class="hero-content">
        <h1>Welcome to The Gathering Light Farms</h1>
        <p><strong>We produce natural products, offer immersive experiences, and nurture the land. Explore our offerings and visit us soon!</strong></p>
        <a href="/store/index.php" class="button">Shop Now</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
