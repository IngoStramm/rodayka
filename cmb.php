<?php

function rk_render_notificaco_btn()
{
    echo
        '<div class="cmb-row">
		<div class="cmb-th">
			<label>' . __('Notificação', 'rk') . '</label>
		</div>
		<div class="cmb-td">
			<button class="button rk-btn rk-send-user-file-notification">
				<span class="rk-btn-text">' . __('Enviar notificação', 'rk') . '</span> 
				<div id="circularG" class="rk-load">
					<div id="circularG_1" class="circularG"></div>
					<div id="circularG_2" class="circularG"></div>
					<div id="circularG_3" class="circularG"></div>
					<div id="circularG_4" class="circularG"></div>
					<div id="circularG_5" class="circularG"></div>
					<div id="circularG_6" class="circularG"></div>
					<div id="circularG_7" class="circularG"></div>
					<div id="circularG_8" class="circularG"></div>
				</div>
			</button>
			<p class="cmb2-metabox-description">' . __('Notificar o usuário por e-mail, que existem arquivos disponíveis para download.', 'rk') . '</p>
		</div>
	</div>';
}

function rk_get_option($key = '', $default = false)
{
    if (function_exists('cmb2_get_option')) {
        // Use cmb2_get_option as it passes through some key filters.
        return cmb2_get_option('rk_options', $key, $default);
    }

    // Fallback to get_option if CMB2 is not loaded yet.
    $opts = get_option('rk_options', $default);

    $val = $default;

    if ('all' == $key) {
        $val = $opts;
    } elseif (is_array($opts) && array_key_exists($key, $opts) && false !== $opts[$key]) {
        $val = $opts[$key];
    }

    return $val;
}

add_action('cmb2_admin_init', 'rk_register_user_profile_metabox');

function rk_register_user_profile_metabox()
{

    /**
     * Metabox for the user profile screen
     */
    $cmb_user = new_cmb2_box(array(
        'id'               => 'rk_user_edit',
        'title'            => esc_html__('Opções Extras', 'rk'), // Doesn't output for user boxes
        'object_types'     => array('user'), // Tells CMB2 to use user_meta vs post_meta
        'show_names'       => true,
        'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
    ));

    $cmb_user->add_field(array(
        'name'         => esc_html__('Arquivos para Download', 'rk'),
        'id'           => 'rk_arquivos_title',
        'type'         => 'title',
    ));

    $cmb_user->add_field(array(
        'name'         => esc_html__('Etapa do Projeto', 'rk'),
        'id'           => 'rk_etapa',
        'desc'             => __('Será usado no assunto do e-mail.', 'rk'),
        'type'         => 'text',
        'attributes' => array(
            'required' => 'required',
        ),
        'default_cb'   => 'rk_default_etapa'
    ));

    $cmb_user->add_field(array(
        'name'         => esc_html__('Mensagem da notificação', 'rk'),
        'id'           => 'rk_mensagem',
        'desc'             => __('Os seguintes shortcodes estão disponíveis: <code>[rk_nome]</code> e <code>[rk_etapa]</code>.', 'rk'),
        'type'    => 'wysiwyg',
        'options' => array(
            'textarea_rows' => 5,
        ),
        'default_cb'   => 'rk_default_message'
    ));

    $cmb_user->add_field(array(
        'name'             => esc_html__('Arquivos', 'rk'),
        'desc'             => esc_html__('Faça o upload dos arquivos que devem estar disponíveis ao usuário.', 'rk'),
        'id'               => 'rk_arquivos',
        'type'             => 'file_list',
        'preview_size'     => array(100, 100), // Default: array( 50, 50 )
        'after_row'    => 'rk_render_notificaco_btn'
    ));
}

add_action('cmb2_admin_init', 'rk_product_metabox');
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function rk_product_metabox()
{
    /**
     * Sample metabox to demonstrate each field type included
     */
    $cmb_product = new_cmb2_box(array(
        'id'            => 'rk_product_metabox',
        'title'         => esc_html__('Opções Extras', 'rk'),
        'object_types'  => array('product'), // Post type
    ));

    $cmb_product->add_field(array(
        'name'    => esc_html__('Formulários', 'rk'),
        'desc'    => esc_html__('Selecione os formulários do produto', 'rk'),
        'id'      => 'rk_formularios',
        'type'    => 'multicheck',
        // 'multiple' => true, // Store values in individual rows
        'options_cb' => 'rk_return_product_formularios',
        // 'inline'  => true, // Toggles display to inline
    ));

    $cmb_product->add_field(array(
        'name' => esc_html__('Imagem de Fundo da Seção "Tabela de Preços"', 'rk'),
        'desc' => esc_html__('Upload an image or enter a URL.', 'rk'),
        'id'   => 'rk_img_tabela_precos',
        'type' => 'file',
    ));
}


add_action('cmb2_admin_init', 'rk_register_options_metabox');
/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function rk_register_options_metabox()
{

    /**
     * Registers options page menu item and form.
     */
    $cmb_options = new_cmb2_box(array(
        'id'           => 'rk_options_page',
        'title'        => esc_html__('Configurações Rodayka', 'rk'),
        'object_types' => array('options-page'),

        /*
		 * The following parameters are specific to the options-page box
		 * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
		 */

        'option_key'      => 'rk_options', // The option key and admin menu page slug.
        'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
        // 'menu_title'      => esc_html__( 'Options', 'rk' ), // Falls back to 'title' (above).
        'parent_slug'     => 'options-general.php', // Make options page a submenu item of the themes menu.
        // 'capability'      => 'manage_options', // Cap required to view options-page.
        // 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
        // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
        // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
        // 'save_button'     => esc_html__( 'Save Theme Options', 'rk' ), // The text for the options-page save button. Defaults to 'Save'.
        // 'disable_settings_errors' => true, // On settings pages (not options-general.php sub-pages), allows disabling.
        // 'message_cb'      => 'yourprefix_options_page_message_callback',
        // 'tab_group'       => '', // Tab-group identifier, enables options page tab navigation.
        // 'tab_title'       => null, // Falls back to 'title' (above).
        // 'autoload'        => false, // Defaults to true, the options-page option will be autloaded.
    ));

    /**
     * Options fields ids only need
     * to be unique within this box.
     * Prefix is not needed.
     */
    $cmb_options->add_field(array(
        'name'    => esc_html__('Página de Agendamento', 'rk'),
        'desc'    => esc_html__('Selecione a página Agendamento da Reunião de Briefing', 'rk'),
        'id'      => 'rk_appointment_page',
        'type'    => 'select',
        'show_option_none' => true,
        // 'multiple' => true, // Store values in individual rows
        'options_cb' => 'rk_return_pages',
        // 'inline'  => true, // Toggles display to inline
    ));
}
