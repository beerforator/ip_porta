<?php
/**
 * Template Name: Новости
 */

get_header();
echo '<h1>' . get_the_title() . '</h1>';
?>

<div class="news-page-container" style="margin-top: var(--space-lg);">
    <?php
    $news_query = new WP_Query([
        'post_type'      => 'ib_news',
        'posts_per_page' => 9,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    if ($news_query->have_posts()) : ?>
        <div class="ib-news-grid">
            <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
                <div class="ib-news-card glass-panel">
                    <div class="card-image-wrap">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="card-image" style="background-image: url('<?php echo get_the_post_thumbnail_url(null, 'medium_large'); ?>');"></div>
                        <?php else : ?>
                            <div class="card-image fallback-image">
                                <span>ИБ</span>
                            </div>
                        <?php endif; ?>
                        <div class="card-tag">НОВОСТИ</div>
                    </div>
                    <div class="card-content">
                        <div class="card-meta">
                            <span class="meta-date"><?php echo get_the_date('d / m / Y'); ?></span>
                            <span class="meta-separator">&bull;</span>
                            <span class="meta-time"><?php echo get_the_time('H:i'); ?></span>
                        </div>
                        <h3 class="card-title"><?php the_title(); ?></h3>
                        <?php if (has_excerpt() || get_the_content()) : ?>
                            <div class="card-desc">
                                <?php echo mb_strimwidth(strip_tags(has_excerpt() ? get_the_excerpt() : get_the_content()), 0, 120, '...'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
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