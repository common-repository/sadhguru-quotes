<?php
/*
  Plugin Name: Sadhguru Quotes
  Description: When activated you will see today's quote from Sadhguru, in a dashboard widget.
  Author: Valle Plugins
  Version: 2.0.0
*/

add_action('wp_dashboard_setup', 'sadhguru_quotes_add_dashboard_widget');

function sadhguru_quotes_add_dashboard_widget(){

  wp_enqueue_style("sadhguru-quotes.css", plugins_url("/_inc/styles.css", __FILE__));
  wp_enqueue_script(
    'sadhguru-quotes.js', 
    plugins_url('/_inc/sadhguru-quotes.js', __FILE__),
    array('jquery'),
    '1.0.0',
    true
  );

  $cached_quotes = get_transient("sadhguru_quotes");
  if(false === $cached_quotes){
    $api_url = 'https://iso-facade.sadhguru.org/content/fetchcsr/content?';
    $query_params = http_build_query(array(
      'format' => 'json',
      'sitesection' => 'wisdom',
      'slug' => 'wisdom',
      'region' => 'us',
      'lang' => 'en',
      'topic' => '',
      'start' => 0,
      'limit' => 12,
      'contentType' => 'quotes',
      'sortby' => 'newest'
    ));

    $response = wp_remote_get($api_url . $query_params);
    $body = json_decode(wp_remote_retrieve_body($response));

    set_transient("sadhguru_quotes", $body->posts->cards, HOUR_IN_SECONDS);
  }

  wp_localize_script( 'sadhguru-quotes.js', 'SadhguruQuotes', array(
    'nonce' => wp_create_nonce('sadhguru_quotes_nonce'),
    'quotes' => get_transient("sadhguru_quotes")
  ));

  wp_add_dashboard_widget(
    'sadhguru_quotes', 
    __('Sadhguru Daily Quote'),
    'sadhguru_quotes_dashboard_widget_render'
  );
}

function sadhguru_quotes_dashboard_widget_render(){
  printf('
    <div id="sadhguru-quotes">
      <p>
        <span class="screen-reader-text">%s</span>
        <span id="sadhguru-quotes--content"><span>
      </p>
      <div id="sadhguru-quotes--date"></div>
      <button class="button btn-prev">Day after</button>
      <button class="button btn-next">Day before</button>
    </div>',
    __('Daily quote from Sadhguru')
  );
}

add_action('wp_ajax_sadhguru_quotes_next', 'sadhguru_quotes_next');
function sadhguru_quotes_next(){
  if(!empty($_POST['next_quote_index']) && current_user_can('edit_dashboard') && check_admin_referer('sadhguru_quotes_nonce')){
    $next_quote_index = intval( $_POST['next_quote_index'] );
    $api_url = 'https://iso-facade.sadhguru.org/content/fetchcsr/content?';
    $query_params = http_build_query(array(
      'format' => 'json',
      'sitesection' => 'wisdom',
      'slug' => 'wisdom',
      'region' => 'us',
      'lang' => 'en',
      'topic' => '',
      'start' => $next_quote_index,
      'limit' => 12,
      'contentType' => 'quotes',
      'sortby' => 'newest'
    ));

    $response = wp_remote_get($api_url . $query_params);
    $body = json_decode(wp_remote_retrieve_body($response));
    $current_quotes = get_transient('sadhguru_quotes');
    $new_quotes = array_merge($current_quotes, $body->posts->cards);

    delete_transient('sadhguru_quotes');
    set_transient("sadhguru_quotes", $new_quotes, HOUR_IN_SECONDS);

    wp_send_json_success($new_quotes);
  }
}

add_action('wp_ajax_sadhguru_quotes_clear', 'sadhguru_quotes_clear');
function sadhguru_quotes_clear(){
  delete_transient('sadhguru_quotes');
  wp_send_json_success("Cleared");
}

/**
 * when plugin is deactivated remove data
 */
register_deactivation_hook(__FILE__, 'sadhguru_quotes_deactivation');
function sadhguru_quotes_deactivation() {
  delete_transient('sadhguru_quotes');
}
