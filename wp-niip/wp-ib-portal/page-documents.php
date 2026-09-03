<?php
/**
 * Template Name: Документы
 */

get_header();
echo '<h1>' . get_the_title() . '</h1>';
?>

<div class="documents-page" style="margin-top: var(--space-lg);">
    <?php
    $sections = get_terms([
        'taxonomy'   => 'ib_doc_section',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ]);

    $uncategorized_query = new WP_Query([
        'post_type'      => 'ib_doc',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'tax_query'      => [[
            'taxonomy' => 'ib_doc_section',
            'operator' => 'NOT EXISTS',
        ]],
    ]);

    $all_sections = [];
    foreach ($sections as $section) {
        $all_sections[$section->term_id] = [
            'name'  => $section->name,
            'items' => [],
        ];
    }

    if ($uncategorized_query->have_posts()) {
        $all_sections[0] = ['name' => 'Прочие документы', 'items' => []];
        while ($uncategorized_query->have_posts()) {
            $uncategorized_query->the_post();
            $all_sections[0]['items'][] = [
                'title' => get_the_title(),
                'date'  => get_the_date('d.m.Y'),
                'id'    => get_the_ID(),
            ];
        }
        wp_reset_postdata();
    }

    foreach ($sections as $section) {
        $section_query = new WP_Query([
            'post_type'      => 'ib_doc',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'tax_query'      => [[
                'taxonomy' => 'ib_doc_section',
                'field'    => 'term_id',
                'terms'    => $section->term_id,
            ]],
        ]);

        while ($section_query->have_posts()) {
            $section_query->the_post();
            $all_sections[$section->term_id]['items'][] = [
                'title' => get_the_title(),
                'date'  => get_the_date('d.m.Y'),
                'id'    => get_the_ID(),
            ];
        }
        wp_reset_postdata();
    }

    $all_sections = array_filter($all_sections, function($s) { return !empty($s['items']); });

    if (!empty($all_sections)) : ?>
        <div class="ib-accordion-list">
            <?php foreach ($all_sections as $section) : ?>
                <div class="accordion-item glass-panel">
                    <div class="accordion-header">
                        <h3 class="accordion-title"><?php echo esc_html($section['name']); ?></h3>
                        <button class="accordion-toggle-btn">
                            <span class="icon-plus"></span>
                        </button>
                    </div>
                    <div class="accordion-content-wrapper">
                        <div class="accordion-content">
                            <div class="docs-list">
                                <?php foreach ($section['items'] as $item) :
                                    $file_id  = get_post_meta($item['id'], 'ib_file_id', true);
                                    $file_url = get_post_meta($item['id'], 'ib_file_url', true);
                                    $file_size = '0 КБ';

                                    if ($file_id) {
                                        $attach_url = wp_get_attachment_url((int)$file_id);
                                        if ($attach_url) {
                                            $file_url = $attach_url;
                                            $attach_path = get_attached_file((int)$file_id);
                                            if ($attach_path && file_exists($attach_path)) {
                                                $file_size = ib_format_bytes(filesize($attach_path));
                                            }
                                        }
                                    }
                                ?>
                                    <div class="doc-row">
                                        <div class="doc-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8b8d98" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                        </div>
                                        <div class="doc-info">
                                            <a href="<?php echo esc_url($file_url ?: '#'); ?>" target="_blank" class="doc-name"><?php echo esc_html($item['title']); ?></a>
                                            <div class="doc-meta"><?php echo esc_html($item['date']); ?> &bull; <?php echo esc_html($file_size); ?></div>
                                        </div>
                                        <a href="<?php echo esc_url($file_url ?: '#'); ?>" download class="doc-download" title="Скачать">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4facfe" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="glass-panel" style="padding: var(--space-lg); text-align: center;">
            <p>Документов пока нет. Добавьте их через админ-панель (Документы ИБ).</p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>