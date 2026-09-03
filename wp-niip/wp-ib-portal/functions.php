<?php
/**
 * ИБ Портал — функции темы
 */

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
}

add_action('wp_enqueue_scripts', 'ib_enqueue_assets');
function ib_enqueue_assets() {
    wp_enqueue_style('ib-style', get_stylesheet_uri(), [], '1.0');
    wp_enqueue_script('ib-main', get_template_directory_uri() . '/js/main.js', [], '1.0', true);
    wp_localize_script('ib-main', 'ibAjax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('ib_poll_nonce'),
    ]);
}

add_action('wp_ajax_ib_save_poll_result', 'ib_save_poll_result');
add_action('wp_ajax_nopriv_ib_save_poll_result', 'ib_save_poll_result');
function ib_save_poll_result() {
    check_ajax_referer('ib_poll_nonce', 'nonce');

    $name     = sanitize_text_field($_POST['name'] ?? '');
    $testName = sanitize_text_field($_POST['testName'] ?? '');
    $score    = sanitize_text_field($_POST['score'] ?? '');

    if (empty($name)) {
        wp_die('Error: empty name');
    }

    $post_id = wp_insert_post([
        'post_type'   => 'ib_result',
        'post_title'  => $name . ' (' . date('d.m.Y H:i') . ')',
        'post_status' => 'publish',
        'meta_input'  => [
            'ib_test_name' => $testName,
            'ib_score'     => $score,
        ],
    ]);

    if ($post_id && !is_wp_error($post_id)) {
        echo 'OK';
    } else {
        echo 'Error: ' . (is_wp_error($post_id) ? $post_id->get_error_message() : 'unknown');
    }

    wp_die();
}

function ib_format_bytes($bytes) {
    $units = ['Б', 'КБ', 'МБ', 'ГБ'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    return round($bytes / pow(1024, $pow), 2) . ' ' . $units[$pow];
}