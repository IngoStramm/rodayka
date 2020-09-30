<?php

add_action('init', 'rk_form_cpt', 1);

function rk_form_cpt()
{
    $form = new Rk_Post_Type(
        'Formulário', // Nome (Singular) do Post Type.
        'formulario' // Slug do Post Type.
    );

    $form->set_labels(
        array(
            'name'               => __('Formulários', 'rk'),
            'singular_name'      => __('Formulário', 'rk'),
            'view_item'          => __('Ver Formulário', 'rk'),
            'edit_item'          => __('Editar Formulário', 'rk'),
            'search_items'       => __('Pesquisar Formulário', 'rk'),
            'update_item'        => __('Atualizar Formulário', 'rk'),
            'parent_item_colon'  => __('Pai Formulário:', 'rk'),
            'menu_name'          => __('Formulários', 'rk'),
            'add_new'            => __('Adiionar Novo', 'rk'),
            'add_new_item'       => __('Adiionar Novo Formulário', 'rk'),
            'new_item'           => __('Novo Formulário', 'rk'),
            'all_items'          => __('Todos Formulários', 'rk'),
            'not_found'          => __('Nenhum Formulário encontrado', 'rk'),
            'not_found_in_trash' => __('Nenhum Formulário encontrado na Lixeira', 'rk')
        )
    );

    $form->set_arguments(
        array(
            'supports' => array('title', 'editor', 'page-attributes')
        )
    );
}
