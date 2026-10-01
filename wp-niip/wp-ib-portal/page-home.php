<?php
/**
 * Template Name: Главная страница
 */

get_header();
?>

<?php
$news_count = wp_count_posts('ib_news')->publish ?? 0;
$docs_count = wp_count_posts('ib_doc')->publish ?? 0;
$tests_count = wp_count_posts('ib_test')->publish ?? 0;
?>
<section class="ib-hero" aria-labelledby="ib-hero-title">
    <div class="ib-hero-copy">
        <h1 id="ib-hero-title">Безопасность<br><em>в ваших руках.</em></h1>
        <p>Актуальные инструкции, новости и короткие проверки знаний для спокойной работы с информацией каждый день.</p>
        <div class="ib-hero-actions">
            <a class="hero-primary" href="<?php echo esc_url(home_url('/documents/')); ?>">Открыть документы <span aria-hidden="true">↗</span></a>
            <a class="hero-secondary" href="<?php echo esc_url(home_url('/polls/')); ?>">Проверить знания <span aria-hidden="true">→</span></a>
        </div>
    </div>
    <div class="ib-hero-visual" aria-hidden="true">
        <div class="orbit orbit-one"></div><div class="orbit orbit-two"></div><div class="orbit orbit-three"></div>
        <div class="orbit-core"><svg viewBox="0 0 100 110" fill="none"><path d="M50 5 89 20v31c0 25-16 41-39 54C27 92 11 76 11 51V20L50 5Z" stroke="currentColor" stroke-width="2"/><path d="m32 54 12 12 25-28" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
    </div>
    <div class="ib-hero-stats">
        <div><strong><?php echo (int) $docs_count; ?></strong><span><?php echo esc_html(ib_count_label($docs_count, ['документ', 'документа', 'документов'])); ?></span></div>
        <div><strong><?php echo (int) $news_count; ?></strong><span><?php echo esc_html(ib_count_label($news_count, ['новость', 'новости', 'новостей'])); ?></span></div>
        <div><strong><?php echo (int) $tests_count; ?></strong><span><?php echo esc_html(ib_count_label($tests_count, ['тест', 'теста', 'тестов'])); ?></span></div>
    </div>
</section>

<div class="hero-links">
    <a href="<?php echo esc_url(home_url('/password/')); ?>" class="hero-link-card glass-panel">
        <div class="hero-link-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"/></svg></div>
        <div>
            <div class="hero-link-title">Генератор паролей</div>
            <div class="hero-link-description">Создать надежный пароль</div>
        </div>
    </a>
    <a href="<?php echo esc_url(home_url('/polls/')); ?>" class="hero-link-card glass-panel">
        <div class="hero-link-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h5m-5 4 2 2 4-4"/></svg></div>
        <div>
            <div class="hero-link-title">Тестирование</div>
            <div class="hero-link-description">Проверка знаний</div>
        </div>
    </a>
</div>

<section class="featured-news" aria-labelledby="featured-news-title">
    <div class="featured-news-heading">
        <h2 id="featured-news-title">Важное в фокусе</h2>
        <a href="<?php echo esc_url(home_url('/news/')); ?>">Все новости &rarr;</a>
    </div>

    <?php
    $featured_news_query = new WP_Query([
        'post_type'      => 'ib_news',
        'posts_per_page' => 4,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    if ($featured_news_query->have_posts()) : ?>
        <div class="featured-news-grid">
                <?php while ($featured_news_query->have_posts()) : $featured_news_query->the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="featured-news-card glass-panel">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="featured-news-image" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(null, 'medium')); ?>');"></div>
                        <?php else : ?>
                            <div class="featured-news-image fallback-image">ИБ</div>
                        <?php endif; ?>
                        <div class="featured-news-content">
                            <div class="featured-news-date"><?php echo esc_html(get_the_date('d.m.Y')); ?></div>
                            <div class="featured-news-title"><?php echo esc_html(get_the_title()); ?></div>
                        </div>
                    </a>
                <?php endwhile; ?>
        </div>
    <?php else : ?>
        <div class="glass-panel featured-news-empty">
            <p>Новостей пока нет.</p>
        </div>
    <?php endif;
    wp_reset_postdata();
    ?>
</section>

<?php get_footer(); ?>
