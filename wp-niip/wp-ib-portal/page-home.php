<?php
/**
 * Template Name: Главная страница
 */

get_header();
?>

<div class="glass-panel" style="padding: var(--space-xl) var(--space-lg); text-align: center; margin-top: var(--space-lg);">
    <h1 style="margin-top: 0; font-size: 36px; background: linear-gradient(135deg, #fff 0%, #a5b4fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">На страже ваших данных</h1>
    <p style="color: var(--text-muted); font-size: 18px; max-width: 600px; margin: 0 auto; line-height: 1.6;">
        Добро пожаловать на корпоративный портал отдела ИБ. Здесь вы найдете актуальные регламенты, обучающие материалы и инструменты для безопасной работы в сети компании.
    </p>
</div>

<div class="hero-links">
    <a href="<?php echo home_url('/password/'); ?>" class="hero-link-card glass-panel">
        <div class="hero-link-icon">🔑</div>
        <div>
            <div style="font-weight: bold; font-size: 16px;">Генератор паролей</div>
            <div style="font-size: 12px; color: var(--text-muted);">Создать надежный пароль</div>
        </div>
    </a>
    <a href="<?php echo home_url('/memo/'); ?>" class="hero-link-card glass-panel">
        <div class="hero-link-icon">🛡️</div>
        <div>
            <div style="font-weight: bold; font-size: 16px;">Памятка ИБ</div>
            <div style="font-size: 12px; color: var(--text-muted);">Базовые правила защиты</div>
        </div>
    </a>
    <a href="<?php echo home_url('/polls/'); ?>" class="hero-link-card glass-panel">
        <div class="hero-link-icon">📝</div>
        <div>
            <div style="font-weight: bold; font-size: 16px;">Тестирование</div>
            <div style="font-size: 12px; color: var(--text-muted);">Проверка знаний</div>
        </div>
    </a>
</div>

<div style="margin-top: var(--space-xl);">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: var(--space-md);">
        <h2 style="margin: 0;">Важное в фокусе</h2>
        <a href="<?php echo home_url('/news/'); ?>" style="color: #4facfe; text-decoration: none; font-size: 14px;">Все новости &rarr;</a>
    </div>

    <?php
    $slider_query = new WP_Query([
        'post_type'      => 'ib_news',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    if ($slider_query->have_posts()) : ?>
        <div class="ib-slider-wrapper">
            <button class="slider-btn prev-btn" id="slider-prev">&larr;</button>
            <div class="ib-slider-track" id="slider-track">
                <?php while ($slider_query->have_posts()) : $slider_query->the_post(); ?>
                    <a href="<?php echo home_url('/news/'); ?>" class="ib-slide glass-panel">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="slide-img" style="background-image: url('<?php echo get_the_post_thumbnail_url(null, 'medium'); ?>');"></div>
                        <?php else : ?>
                            <div class="slide-img fallback-image">ИБ</div>
                        <?php endif; ?>
                        <div class="slide-content">
                            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;"><?php echo get_the_date('d.m.Y'); ?></div>
                            <div class="slide-title"><?php the_title(); ?></div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
            <button class="slider-btn next-btn" id="slider-next">&rarr;</button>
        </div>
    <?php else : ?>
        <div class="glass-panel" style="padding: var(--space-lg); text-align: center;">
            <p>Новостей пока нет. Добавьте их через админ-панель (Новости ИБ).</p>
        </div>
    <?php endif;
    wp_reset_postdata();
    ?>
</div>

<?php get_footer(); ?>