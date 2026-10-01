<?php
/**
 * Template Name: Новости
 */

get_header();
?>

<div class="page-heading">
    <h1><?php echo esc_html(get_the_title()); ?></h1>
    <label class="search-field"><span class="screen-reader-text">Поиск новостей</span><input id="news-search" type="search" placeholder="Найти новость…" autocomplete="off"></label>
</div>
<div class="news-page-container">
    <?php
    $news_query = new WP_Query([
        'post_type'      => 'ib_news',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    if ($news_query->have_posts()) : ?>
        <div class="ib-news-grid" id="news-list">
            <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="ib-news-card glass-panel" data-search="<?php echo esc_attr(get_the_title() . ' ' . wp_strip_all_tags(get_the_content())); ?>">
                    <div class="card-image-wrap">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="card-image" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(null, 'medium_large')); ?>');"></div>
                        <?php else : ?>
                            <div class="card-image fallback-image">
                                <span>ИБ</span>
                            </div>
                        <?php endif; ?>
                        <?php if (get_post_meta(get_the_ID(), 'ib_is_new', true)) : ?><div class="card-tag">НОВОЕ</div><?php endif; ?>
                    </div>
                    <div class="card-content">
                        <div class="card-meta">
                            <span class="meta-date"><?php echo get_the_date('d / m / Y'); ?></span>
                            <span class="meta-separator">&bull;</span>
                            <span class="meta-time"><?php echo get_the_time('H:i'); ?></span>
                            <span class="meta-separator">&bull;</span>
                            <span class="meta-views">Просмотров: <?php echo (int) get_post_meta(get_the_ID(), 'ib_views', true); ?></span>
                        </div>
                        <h3 class="card-title"><?php echo esc_html(get_the_title()); ?></h3>
                        <?php if (has_excerpt() || get_the_content()) : ?>
                            <div class="card-desc">
                                <?php echo esc_html(mb_strimwidth(wp_strip_all_tags(has_excerpt() ? get_the_excerpt() : get_the_content()), 0, 120, '...')); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
        <p class="search-empty" id="news-empty" hidden>По запросу ничего не найдено.</p>
    <?php else : ?>
        <div class="glass-panel" style="padding: var(--space-lg); text-align: center;">
            <p>Новостей пока нет.</p>
        </div>
    <?php endif;
    wp_reset_postdata();
    ?>
</div>

<?php get_footer(); ?>
