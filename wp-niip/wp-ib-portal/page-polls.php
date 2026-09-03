<?php
/**
 * Template Name: Тестирование
 */

get_header();
echo '<h1>' . get_the_title() . '</h1>';

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
        $testsData[] = [
            'id'          => $test_id,
            'name'        => get_the_title(),
            'description' => get_the_content() ?: 'Пройдите тестирование для проверки знаний.',
            'questions'   => $questions,
        ];
    }
}
wp_reset_postdata();
?>

<div class="polls-grid" id="polls-container"></div>

<div class="modal-overlay" id="quiz-overlay">
    <div class="poll-modal glass-panel">
        <button class="modal-close" id="quiz-close">&times;</button>
        <div id="quiz-content"></div>
    </div>
</div>

<script>
const testsData = <?php echo json_encode($testsData); ?>;
</script>

<?php get_footer(); ?>