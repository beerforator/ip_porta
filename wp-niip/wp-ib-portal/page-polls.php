<?php
/**
 * Template Name: Тестирование
 */

get_header();

$tests_query = new WP_Query([
    'post_type'      => 'ib_test',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

$testsData = [];

while ($tests_query->have_posts()) {
    $tests_query->the_post();
    $test_id = get_the_ID();
    $questions_json = get_post_meta($test_id, 'ib_questions', true);
    $questions = [];

    if ($questions_json) {
        $decoded = json_decode($questions_json, true);
        if (is_array($decoded)) {
            $questions = $decoded;
        }
    }

    if (!empty($questions)) {
        $public_questions = array_map(function ($question) {
            return [
                'question' => $question['question'] ?? '',
                'answers' => $question['answers'] ?? [],
            ];
        }, $questions);
        $testsData[] = [
            'id'          => $test_id,
            'name'        => get_the_title(),
            'description' => get_the_content() ?: 'Пройдите тестирование для проверки знаний.',
            'questions'   => $public_questions,
        ];
    }
}
wp_reset_postdata();
?>

<div class="page-heading">
    <h1><?php echo esc_html(get_the_title()); ?></h1>
    <label class="search-field"><span class="screen-reader-text">Поиск тестов</span><input id="polls-search" type="search" placeholder="Найти тест…" autocomplete="off"></label>
</div>
<div class="polls-grid" id="polls-container"></div>
<p class="search-empty" id="polls-empty" hidden>По запросу ничего не найдено.</p>

<div class="modal-overlay" id="quiz-overlay">
    <div class="poll-modal glass-panel">
        <button type="button" class="modal-close" id="quiz-close" aria-label="Закрыть тест">&times;</button>
        <div id="quiz-content"></div>
    </div>
</div>

<script>
const testsData = <?php echo wp_json_encode($testsData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
</script>

<?php get_footer(); ?>
