<?php 
function filter_products_ajax() {
    $filter = isset($_POST['filter']) ? sanitize_text_field($_POST['filter']) : '';

    $args = [
        'post_type' => 'product',
        'posts_per_page' => 8,
    ];

    if ($filter === 'on_sale') {
        $args['meta_query'] = [
            [
                'key' => '_sale_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'NUMERIC',
            ],
        ];
    } elseif ($filter === 'featured') {
        $args['tax_query'] = [
            [
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => 'IN',
            ],
        ];
    } elseif ($filter === 'best_selling') {
        $args['meta_key'] = 'total_sales';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
    } else {
        wp_send_json_error('No products found');
    }

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_filter_products', 'filter_products_ajax');
add_action('wp_ajax_nopriv_filter_products', 'filter_products_ajax');
