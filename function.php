<?php

function rk_debug($debug)
{
    echo '<pre>';
    var_dump($debug);
    echo '</pre>';
}

add_action('wp_mail_failed', 'rk_mail_error', 10, 1);

function rk_mail_error($wp_error)
{
    rk_debug($wp_error);
}

function rk_default_etapa()
{
    $etapa = 'Novos arquivos disponíveis para download!';
    return $etapa;
}

function rk_default_message()
{
    $msg = '<h3>Olá, [rk_nome]!</h3>
        <p>Seu projeto foi atualizado para a etapa: [rk_etapa].</p>
        <p>Existem arquivos disponíveis para download. Acesse <a href="' . get_site_url(null, '/arquivos-para-download/') . '" target="_BLANK">este link</a> para visualizá-los.</p>';
    return $msg;
}

add_shortcode('rk_nome', 'rk_user_name');

function rk_user_name()
{
    $user_id = isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : null;
    $user = get_user_by('id', $user_id);
    return $user->display_name;
}

add_shortcode('rk_etapa', 'rk_user_etapa');

function rk_user_etapa()
{
    $user_id = isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : null;
    $etapa = get_user_meta($user_id, 'rk_etapa', true);
    $etapa = empty($etapa) ? rk_default_etapa() : $etapa;
    return $etapa;
}

add_action('wp_ajax_rk_send_notification', 'rk_send_notification');

function rk_send_notification()
{

    $user_id = isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : null;
    $user = get_user_by('id', $user_id);
    $etapa = get_user_meta($user_id, 'rk_etapa', true);
    $etapa = empty($etapa) ? rk_default_etapa() : $etapa;
    $mensagem = get_user_meta($user_id, 'rk_mensagem', true);
    $mensagem = empty($mensagem) ? rk_default_message() : $mensagem;
    $mensagem = do_shortcode($mensagem);
    
    $to = $user->user_email;
    $subject = get_bloginfo('name') . ' | ' . esc_html__($etapa);
    $body = $mensagem;
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $headers[] = 'From: Rodayka <contato@rodaykasantana.com.br>';
    $headers[] .= 'Bcc: ingo@agencialaf.com';

    $send_email = wp_mail($to, $subject, $body, $headers);

    echo json_encode(array('status' => $send_email));

    wp_die();
}

add_action('woocommerce_order_details_after_order_table', 'rk_order', 10);

function rk_order($order)
{
    $products = $order->get_items();

    $output = '';
    $output .= '<ul class="rk-quiz-list">';
    $check_page_id = [];
    foreach ($products as $product) :
        $product_id = $product->get_product_id();
        $pages = get_post_meta($product_id, 'rk_formularios', true);
        // rk_debug( $forms );
        if ($pages) :

            foreach ($pages as $page_id) :

                if (!in_array($page_id, $check_page_id)) :

                    $output .= '<li><a class="btn button" target="_blank" href="' . get_post_permalink($page_id) . '?order=' . $order->get_id() . '">' . get_the_title($page_id) . '</a></li>';
                    $check_page_id[] = $page_id;
                endif;

            endforeach;

        endif;

    endforeach;
    $output .= '</ul>';

    echo $output;
}

function rk_return_formularios()
{
    $formularios = get_posts(
        array(
            'post_type'         => 'formulario',
            'orderby'           => 'menu_order',
            'order'             => 'ASC',
            'numberposts'       => -1
        )
    );
    $output = [];

    foreach ($formularios as $formulario) :
        $output[$formulario->ID] = $formulario->post_title;
    endforeach;

    return $output;
}

function rk_return_product_formularios()
{
    $output    = [];

    $args = [
        'post_type'         => 'formulario',
        'posts_per_page'    => -1,
        'order'             => 'ASC',
        'orderby'           => 'menu_order'
    ];

    $the_query = new WP_Query($args);

    if (
        $the_query->have_posts()
    ) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $output[get_the_ID()] = get_the_title();
        }
    }
    wp_reset_postdata();

    return $output;
}

add_action('admin_head', 'rk_test');

function rk_test()
{
    $user_id = isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : null;
    $user = get_user_by('id', $user_id);
    $etapa = get_user_meta($user_id, 'rk_etapa', true);
    $mensagem = get_user_meta($user_id, 'rk_mensagem', true);
    // rk_debug($mensagem);
}
