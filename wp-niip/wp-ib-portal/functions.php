<?php
add_action('after_setup_theme', 'ib_theme_setup');
function ib_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}

add_action('init', 'ib_register_post_types');
function ib_register_post_types() {

    register_post_type('ib_news', [
        'labels' => [
            'name'               => 'Новости',
            'singular_name'      => 'Новость',
            'add_new'            => 'Добавить новость',
            'add_new_item'       => 'Добавить новую новость',
            'edit_item'          => 'Редактировать новость',
            'new_item'           => 'Новая новость',
            'view_item'          => 'Просмотреть новость',
            'search_items'       => 'Искать новости',
            'not_found'          => 'Новостей не найдено',
            'not_found_in_trash' => 'В корзине новостей не найдено',
            'menu_name'          => 'Новости ИБ',
        ],
        'public'             => true,
        'has_archive'        => false,
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon'          => 'dashicons-admin-site',
        'show_in_rest'       => true,
        'rewrite'            => ['slug' => 'ib-news'],
    ]);

    register_post_type('ib_doc', [
        'labels' => [
            'name'               => 'Документы',
            'singular_name'      => 'Документ',
            'add_new'            => 'Добавить документ',
            'add_new_item'       => 'Добавить новый документ',
            'edit_item'          => 'Редактировать документ',
            'new_item'           => 'Новый документ',
            'view_item'          => 'Просмотреть документ',
            'search_items'       => 'Искать документы',
            'not_found'          => 'Документов не найдено',
            'not_found_in_trash' => 'В корзине документов не найдено',
            'menu_name'          => 'Документы ИБ',
        ],
        'public'             => true,
        'has_archive'        => false,
        'supports'           => ['title', 'editor'],
        'menu_icon'          => 'dashicons-media-document',
        'show_in_rest'       => true,
        'rewrite'            => ['slug' => 'ib-doc'],
    ]);

    register_taxonomy('ib_doc_section', 'ib_doc', [
        'labels' => [
            'name'              => 'Разделы документов',
            'singular_name'     => 'Раздел',
            'search_items'      => 'Искать разделы',
            'all_items'         => 'Все разделы',
            'edit_item'         => 'Редактировать раздел',
            'update_item'       => 'Обновить раздел',
            'add_new_item'      => 'Добавить новый раздел',
            'new_item_name'     => 'Название нового раздела',
            'menu_name'         => 'Разделы',
        ],
        'hierarchical'      => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'doc-section'],
    ]);

    register_post_type('ib_test', [
        'labels' => [
            'name'               => 'Тесты',
            'singular_name'      => 'Тест',
            'add_new'            => 'Добавить тест',
            'add_new_item'       => 'Добавить новый тест',
            'edit_item'          => 'Редактировать тест',
            'new_item'           => 'Новый тест',
            'view_item'          => 'Просмотреть тест',
            'search_items'       => 'Искать тесты',
            'not_found'          => 'Тестов не найдено',
            'not_found_in_trash' => 'В корзине тестов не найдено',
            'menu_name'          => 'Тесты ИБ',
        ],
        'public'             => true,
        'has_archive'        => false,
        'supports'           => ['title', 'editor', 'thumbnail'],
        'menu_icon'          => 'dashicons-welcome-learn-more',
        'show_in_rest'       => true,
        'rewrite'            => ['slug' => 'ib-test'],
    ]);

    register_post_type('ib_result', [
        'labels' => [
            'name'               => 'Результаты тестов',
            'singular_name'      => 'Результат',
            'menu_name'          => 'Результаты',
        ],
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => 'edit.php?post_type=ib_test',
        'supports'           => ['title'],
        'menu_icon'          => 'dashicons-clipboard',
    ]);

    register_post_meta('ib_doc', 'ib_file_id', [
        'type'        => 'integer',
        'single'      => true,
        'show_in_rest'=> true,
    ]);

    register_post_meta('ib_doc', 'ib_file_url', [
        'type'        => 'string',
        'single'      => true,
        'show_in_rest'=> true,
    ]);

    register_post_meta('ib_test', 'ib_questions', [
        'type'        => 'string',
        'single'      => true,
        'show_in_rest'=> true,
    ]);

    register_post_meta('ib_result', 'ib_test_name', [
        'type'        => 'string',
        'single'      => true,
        'show_in_rest'=> false,
    ]);

    register_post_meta('ib_result', 'ib_score', [
        'type'        => 'string',
        'single'      => true,
        'show_in_rest'=> false,
    ]);

    register_post_meta('ib_news', 'ib_is_new', [
        'type'         => 'boolean',
        'single'       => true,
        'show_in_rest' => true,
        'auth_callback'=> function () { return current_user_can('edit_posts'); },
    ]);
}

add_action('wp_enqueue_scripts', 'ib_enqueue_assets');
function ib_enqueue_assets() {
    wp_enqueue_style('ib-style', get_stylesheet_uri(), [], filemtime(get_stylesheet_directory() . '/style.css'));
    wp_enqueue_script('ib-main', get_template_directory_uri() . '/js/main.js', [], filemtime(get_template_directory() . '/js/main.js'), true);
    wp_localize_script('ib-main', 'ibAjax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('ib_poll_nonce'),
    ]);
}

add_action('wp_ajax_ib_save_poll_result', 'ib_save_poll_result');
add_action('wp_ajax_nopriv_ib_save_poll_result', 'ib_save_poll_result');
function ib_save_poll_result() {
    check_ajax_referer('ib_poll_nonce', 'nonce');

    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $test_id = absint($_POST['testId'] ?? 0);
    $submitted = json_decode(wp_unslash($_POST['answers'] ?? ''), true);
    $test = get_post($test_id);
    $questions = $test && $test->post_type === 'ib_test' && $test->post_status === 'publish'
        ? json_decode(get_post_meta($test_id, 'ib_questions', true), true) : null;

    if (!$name || mb_strlen($name) > 120 || !is_array($questions) || !$questions || !is_array($submitted) || count($submitted) !== count($questions)) {
        wp_send_json_error(['message' => 'Некорректные данные теста.'], 400);
    }

    $details = [];
    $correct_count = 0;
    foreach ($questions as $index => $question) {
        $selected = $submitted[$index] ?? null;
        if (!isset($question['answers'], $question['correct']) || !is_array($question['answers'])
            || !is_int($selected) || !array_key_exists($selected, $question['answers'])) {
            wp_send_json_error(['message' => 'Некорректный ответ.'], 400);
        }
        $correct = (int) $question['correct'];
        $is_correct = $selected === $correct;
        $correct_count += $is_correct ? 1 : 0;
        $details[] = [
            'question' => sanitize_text_field($question['question'] ?? ''),
            'selected' => sanitize_text_field($question['answers'][$selected]),
            'correct' => sanitize_text_field($question['answers'][$correct] ?? ''),
            'is_correct' => $is_correct,
        ];
    }

    $total = count($questions);
    $ip = filter_var($_SERVER['REMOTE_ADDR'] ?? '', FILTER_VALIDATE_IP) ?: '';
    $user = wp_get_current_user();
    $post_id = wp_insert_post([
        'post_type'   => 'ib_result',
        'post_title'  => $name . ' (' . wp_date('d.m.Y H:i') . ')',
        'post_status' => 'publish',
        'meta_input'  => [
            'ib_test_id'    => $test_id,
            'ib_test_name'  => get_the_title($test_id),
            'ib_score'      => $correct_count . ' из ' . $total,
            'ib_score_value'=> $correct_count,
            'ib_total'      => $total,
            'ib_answers'    => $details,
            'ib_ip'         => $ip,
            'ib_wp_user'    => $user->exists() ? $user->user_login : '',
            'ib_network_user' => sanitize_text_field(wp_unslash($_SERVER['REMOTE_USER'] ?? '')),
            'ib_user_agent' => sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'] ?? '')),
        ],
    ]);

    if ($post_id && !is_wp_error($post_id)) {
        wp_send_json_success(['id' => $post_id, 'score' => $correct_count, 'total' => $total]);
    }
    wp_send_json_error(['message' => 'Не удалось сохранить результат.'], 500);
}

add_action('add_meta_boxes', function () {
    add_meta_box('ib_result_details', 'Итог и ответы', 'ib_render_result_details', 'ib_result', 'normal', 'high');
    add_meta_box('ib_news_settings', 'Метка новости', 'ib_render_news_settings', 'ib_news', 'side');
    add_meta_box('ib_news_documents', 'Ссылки на документы', 'ib_render_news_documents', 'ib_news', 'normal');
});

function ib_render_result_details($post) {
    $score = get_post_meta($post->ID, 'ib_score', true);
    $details = get_post_meta($post->ID, 'ib_answers', true);
    echo '<p><strong>Тест:</strong> ' . esc_html(get_post_meta($post->ID, 'ib_test_name', true)) . '</p>';
    echo '<p><strong>Результат:</strong> ' . esc_html($score ?: 'Не сохранён') . '</p>';
    echo '<p><strong>IP:</strong> ' . esc_html(get_post_meta($post->ID, 'ib_ip', true) ?: 'Нет данных') . '</p>';
    echo '<p><strong>Учётная запись WordPress:</strong> ' . esc_html(get_post_meta($post->ID, 'ib_wp_user', true) ?: 'Гость') . '</p>';
    echo '<p><strong>Сетевая учётная запись:</strong> ' . esc_html(get_post_meta($post->ID, 'ib_network_user', true) ?: 'Не передана сервером') . '</p>';
    echo '<p><strong>Браузер:</strong> ' . esc_html(get_post_meta($post->ID, 'ib_user_agent', true) ?: 'Нет данных') . '</p>';
    if (!$details || !is_array($details)) {
        echo '<p>Для старых результатов подробные ответы не сохранялись.</p>';
        return;
    }
    echo '<ol>';
    foreach ($details as $detail) {
        echo '<li><strong>' . esc_html($detail['question']) . '</strong><br>Ответ: ' . esc_html($detail['selected']);
        if (empty($detail['is_correct'])) {
            echo ' — <strong>ошибка</strong>; правильный ответ: ' . esc_html($detail['correct']);
        } else {
            echo ' — верно';
        }
        echo '</li>';
    }
    echo '</ol>';
}

add_filter('manage_ib_result_posts_columns', function ($columns) {
    return ['cb' => $columns['cb'], 'title' => 'Участник', 'ib_test' => 'Тест', 'ib_score' => 'Результат', 'ib_ip' => 'IP', 'date' => 'Дата'];
});
add_action('manage_ib_result_posts_custom_column', function ($column, $post_id) {
    $meta = ['ib_test' => 'ib_test_name', 'ib_score' => 'ib_score', 'ib_ip' => 'ib_ip'];
    if (isset($meta[$column])) {
        echo esc_html(get_post_meta($post_id, $meta[$column], true) ?: '—');
    }
}, 10, 2);

function ib_render_news_settings($post) {
    wp_nonce_field('ib_save_news_settings', 'ib_news_settings_nonce');
    echo '<label><input type="checkbox" name="ib_is_new" value="1" ' . checked((bool) get_post_meta($post->ID, 'ib_is_new', true), true, false) . '> Показывать метку «Новое»</label>';
}

add_action('save_post_ib_news', function ($post_id) {
    if (!isset($_POST['ib_news_settings_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ib_news_settings_nonce'])), 'ib_save_news_settings')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
        return;
    }
    update_post_meta($post_id, 'ib_is_new', isset($_POST['ib_is_new']) ? 1 : 0);
});

add_filter('manage_ib_news_posts_columns', function ($columns) {
    $columns['ib_is_new'] = 'Новое';
    $columns['ib_views'] = 'Просмотры';
    return $columns;
});
add_action('manage_ib_news_posts_custom_column', function ($column, $post_id) {
    if ($column === 'ib_is_new') echo get_post_meta($post_id, 'ib_is_new', true) ? 'Да' : '—';
    if ($column === 'ib_views') echo (int) get_post_meta($post_id, 'ib_views', true);
}, 10, 2);

function ib_document_url($id) {
    $post = get_post($id);
    return $post && $post->post_type === 'ib_doc' && $post->post_status === 'publish'
        ? add_query_arg('doc', $id, home_url('/documents/')) : '';
}

function ib_render_news_documents() {
    echo '<p>Вставьте ссылку на нужный документ в текст новости. Она откроет раздел «Документы» с выбранным файлом.</p>';
    $documents = get_posts(['post_type' => 'ib_doc', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC']);
    foreach ($documents as $document) {
        echo '<p><label><strong>' . esc_html($document->post_title) . '</strong><br><input readonly onclick="this.select()" style="width:100%" value="' . esc_attr(ib_document_url($document->ID)) . '"></label></p>';
    }
}

add_shortcode('ib_document', function ($atts) {
    $atts = shortcode_atts(['id' => 0, 'title' => ''], $atts);
    $url = ib_document_url(absint($atts['id']));
    $title = $atts['title'] ?: get_the_title(absint($atts['id']));
    return $url ? '<a href="' . esc_url($url) . '">' . esc_html($title) . '</a>' : '';
});

add_action('template_redirect', function () {
    if (is_singular('ib_news') && !is_preview() && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
        $id = get_queried_object_id();
        update_post_meta($id, 'ib_views', (int) get_post_meta($id, 'ib_views', true) + 1);
    }
});

function ib_format_bytes($bytes) {
    $units = ['Б', 'КБ', 'МБ', 'ГБ'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    return round($bytes / pow(1024, $pow), 2) . ' ' . $units[$pow];
}

function ib_count_label($count, $forms) {
    $last_two = $count % 100;
    $last = $count % 10;
    if ($last_two >= 11 && $last_two <= 14) return $forms[2];
    if ($last === 1) return $forms[0];
    if ($last >= 2 && $last <= 4) return $forms[1];
    return $forms[2];
}

add_filter('acf/settings/remove_wp_meta_box', '__return_false');
