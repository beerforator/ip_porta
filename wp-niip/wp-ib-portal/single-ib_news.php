<?php
get_header();
if (have_posts()) : while (have_posts()) : the_post();
?>
<article class="single-news">
    <a class="news-back" href="<?php echo esc_url(home_url('/news/')); ?>">← Все новости</a>
    <div class="single-news-heading">
        <h1><?php echo esc_html(get_the_title()); ?></h1>
        <div class="single-news-meta">
            <span><?php echo esc_html(get_the_date('d.m.Y')); ?></span>
            <span><?php echo esc_html(get_the_time('H:i')); ?></span>
            <span>Просмотров: <?php echo (int) get_post_meta(get_the_ID(), 'ib_views', true); ?></span>
        </div>
    </div>
    <?php if (has_post_thumbnail()) : ?>
        <figure class="single-news-image"><?php the_post_thumbnail('full', ['loading' => 'eager']); ?></figure>
    <?php endif; ?>
    <div class="single-news-body glass-panel"><?php the_content(); ?></div>
</article>
<?php endwhile; endif;
get_footer();
