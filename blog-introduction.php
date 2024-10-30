<?php
/*
Plugin Name: Blog Introduction
Plugin URI: http://jussi.ruokomaki.fi/tech/wp-blog-introduction/
Author: Jussi Ruokom&auml;ki
Version: 1.9.11
Author URI: http://jussi.ruokomaki.fi/
Description: Shows an introduction before posts without messing your theme files. <a href="options-general.php?page=blog-introduction.php">Configure the plugin and show your introduction.</a> 
*/

$blog_introduction_printed = false;
$loop_count = 0;

add_option('bintro_enabled', 0);
add_option('bintro_from', 'page');
add_option('bintro_settings_heading', 'Introduction');
add_option('bintro_settings_content', 'This is one fine blog you\'re reading.');
add_option('bintro_page_permalink', 'blog-introduction');
add_option('bintro_show_heading', 1);
add_option('bintro_show_content', 1);
add_option('bintro_show_for', 'all');
add_option('bintro_show_on_pages', 'all');
add_option('bintro_show_on_home', '1');
add_option('bintro_heading_tag', 'h2');

add_option('bintro_style_intro', '');

if (get_option('bintro_size_heading')) { // migrate from version below 1.6
  add_option('bintro_style_heading','size:' . get_option('bintro_size_heading') . ';');
  delete_option('bintro_size_heading');
} else {
  add_option('bintro_style_heading','');
}

if (get_option('bintro_size_content')) { // migrate from version below 1.6
  add_option('bintro_style_content', 'size:' . get_option('bintro_size_content') . ';');
  delete_option('bintro_size_content');
} else {
  add_option('bintro_style_content', '');
}

add_option('bintro_at_loop_count', 1);
add_option('bintro_debug', 0);


function bintro_admin_init() {
  register_setting('bintro-settings-group', 'bintro_enabled');
  register_setting('bintro-settings-group', 'bintro_from');
  register_setting('bintro-settings-group', 'bintro_settings_heading');
  register_setting('bintro-settings-group', 'bintro_settings_content');
  register_setting('bintro-settings-group', 'bintro_page_permalink');
  register_setting('bintro-settings-group', 'bintro_show_heading');
  register_setting('bintro-settings-group', 'bintro_show_content');
  register_setting('bintro-settings-group', 'bintro_heading_tag');
  register_setting('bintro-settings-group', 'bintro_show_for');
  register_setting('bintro-settings-group', 'bintro_show_on_pages');
  register_setting('bintro-settings-group', 'bintro_show_on_home');
  register_setting('bintro-settings-group', 'bintro_style_intro');
  register_setting('bintro-settings-group', 'bintro_style_heading');
  register_setting('bintro-settings-group', 'bintro_style_content');
  register_setting('bintro-settings-group', 'bintro_at_loop_count');
  register_setting('bintro-settings-group', 'bintro_debug');
}



////
// functions

function bintro_get() {
  if (get_option('bintro_show_for') != 'all') {
    if ((get_option('bintro_show_for') == 'users' && !is_user_logged_in()) ||
        (get_option('bintro_show_for') == 'visitors' && is_user_logged_in())) {
      return false;
    }
  }

  $intro_from_settings = false;
  
  if (is_archive()) {
    if (is_category( )) {
      $cat = get_query_var('cat');
      $yourcat = get_category($cat);
      $post_slug = $yourcat->slug;
      $page_name = ($post_slug?$post_slug:$cat) . '-cat-intro';
    } elseif (is_tag()) {
      $tag = get_query_var('tag');
      $yourtag = get_category($tag);
      $post_slug = $yourtag->slug;
      $page_name = ($post_slug?$post_slug:$tag) . '-tag-intro';
    } elseif (is_author()) {
      $author_obj = get_userdata(get_query_var('author'));
	  $author = $author_obj->user_nicename;
      $page_name = $author . '-author-intro';
    } 
    $page = get_page_by_path($page_name);
  } else {

    if (get_option('bintro_from') == 'page') {
      if (is_numeric(get_option('bintro_page_permalink'))) {
        $page = get_page(get_option('bintro_page_permalink'));
      } else {
        $page = get_page_by_path(get_option('bintro_page_permalink'));
      }
    } else {
      $intro_from_settings = true;
    }

  }

  if ($page || $intro_from_settings) {
    $str = '';

    if (get_option('bintro_debug')) { $str .= '<!--'; }

    $str  = '<div id="blog_introduction" class="' . ($page_name ? $page_name : (is_numeric(get_option('bintro_page_permalink'))?'':get_option('bintro_page_permalink'))). '-container" ' . (get_option('bintro_style_intro') != '' ? ' style="' . get_option('bintro_style_intro') . '"' : '') . '>' . "\n";
    if (get_option('bintro_show_heading')) {
      $str .= '  <div id="blog_introduction_heading" class="' . ($page_name ? $page_name : (is_numeric(get_option('bintro_page_permalink'))?'':get_option('bintro_page_permalink'))). '-heading">' . "\n";
      $str .= '      <' . get_option('bintro_heading_tag') . (get_option('bintro_style_heading') != '' ? ' style="' . get_option('bintro_style_heading') . '"' : '') . '><span>' .  (get_option('bintro_from') == 'page' ? $page->post_title : get_option('bintro_settings_heading')) . '</span></' . get_option('bintro_heading_tag') . '>' . "\n";
      $str .= '  </div>' . "\n";
    }
    if (get_option('bintro_show_content')) {
      $str .= '  <div id="blog_introduction_content"' . (get_option('bintro_style_content') != '' ? ' style="' . get_option('bintro_style_content') . '"' : '') . ' class="' . ($page_name ? $page_name : (is_numeric(get_option('bintro_page_permalink'))?'':get_option('bintro_page_permalink'))). '-content">' . "\n";
	  $my_content = wpautop((get_option('bintro_from') == 'page' ? $page->post_content : get_option('bintro_settings_content'))) . "\n";
      $str .= apply_filters('the_content', $my_content);
      $str .= '  </div>' . "\n";
    }
    $str .= '</div>' . "\n";

    if (get_option('bintro_debug')) { $str .= '-->'; }

    return $str;

  } else { // ends if $page
    return false;
  }

} // ends bintro_get


function bintro_print() { 
  global $blog_introduction_printed,$loop_count;
  
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  
  if (get_option('bintro_enabled') && 
      ((is_home() && get_option('bintro_show_on_home')) || is_archive()) && 
      ($paged == 1 || get_option('bintro_show_on_pages') == 'all')) {
    if (!$blog_introduction_printed && ++$loop_count == get_option('bintro_at_loop_count')) {
      echo bintro_get();
      $blog_introduction_printed = true;
    }
  } 
} // ends bintro_print


function bintro_add_options_page() {
  add_options_page('Blog Introduction', 'Blog Introduction', 8, basename(__FILE__), 'bintro_options_page');
} // ends bintro_add_options_page

function bintro_options_page() { 
  $oddeven = 0;
  $oddcolor = "#f8f8f8";
  
	?>
<div class="wrap">

  <h2><?php echo __('Blog Introduction Settings') ?></h2>
<?php if ($_GET['save']==1) { ?>
<div id="message" class="updated fade"><p><?php _e('Settings saved.') ?></p></div>
<?php } ?>

<p><?php echo __("Blog Introduction displays an introduction (heading and/or contents of certain page) before posts."); ?></p>

<p><?php echo __("An introduction can appear on your <b>home page</b> (from page whose URL - aka permalink aka slug - is, by default, 'blog-introduction'), <b>category</b> archive pages (from page 'yourcategoryurl-cat-intro', e.g. category has 'widgets' slug, then intro comes from page that has slug 'widgets-cat-intro'), <b>tag</b> archive pages ('yourtagurl-tag-intro', e.g. tag is 'blue-widgets', then use 'blue-widgets-tag-intro' as intro page slug), and <b>author</b> archive pages ('yourauthorurl-author-intro', e.g. author is 'jsmith', then use 'jsmith-author-intro' as intro page slug)."); ?></p>

<p><?php echo __("Hide these special pages by making them private."); ?></p>

  <!--<form method="post" action="options-general.php?page=blog-introduction.php&save=1">-->
  <form method="post" action="options.php">

    <?php wp_nonce_field('update-options'); ?>

    <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
    </p>

    <table border="0" style="vertical-align: top;" cellspacing="10">
      <tr>
       <td style="vertical-align: top;"><strong><?php echo __('Show intro:') ?></strong></td>
       <td style="vertical-align: top;">
         <input type="radio" name="bintro_enabled" value="1" <?php echo get_option('bintro_enabled') == '1' ? 'checked="checked"' : ''  ?> /> <span style="color: #060"><?php echo __('Yes') ?></span><br />
         <input type="radio" name="bintro_enabled" value="0" <?php echo get_option('bintro_enabled') == '0' ? 'checked="checked"' : ''  ?> /> <span style="color: #600"><?php echo __('No') ?></span><br />
       </td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __("Whether intro is shown. See preview to make sure all settings are correct, then show it to the world.") . '<br />' . __('Default') . ': ' . __('No') ?></span></td>
      </tr>

      <tr>
       <td style="vertical-align: top;"><strong><?php echo __('Get introduction text from:') ?></strong></td>
       <td style="vertical-align: top;">
         <input type="radio" name="bintro_from" value="page" <?php echo get_option('bintro_from') == 'page' ? 'checked="checked"' : ''  ?> /> <?php echo __('Page (define below which)') ?><br />
         <input type="radio" name="bintro_from" value="settings" <?php echo get_option('bintro_from') == 'settings' ? 'checked="checked"' : ''  ?> /> <?php echo __('These settings') ?><br />
       </td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __("Where the introduction is taken from, a page of your liking, or this settings page.") . '<br />' . __('Default') . ': ' . __('Page') ?></span></td>
      </tr>

      <tr id="bintro_page_id_row">
       <td style="vertical-align: top;" width="20%"><?php echo __('Page id/permalink:') ?></td>
       <td style="vertical-align: top;" width="30%"><input type="text" name="bintro_page_permalink" value="<?php echo get_option('bintro_page_permalink'); ?>" /></td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __('Enter page id (numeric, e.g. "8") or its permalink (string, e.g. "my-intro-page"). (Page id is shown in the url when you edit the page.)') . '<br />' . __('Default') . ': blog-introduction' ?></span></td>
      </tr>

      <tr>
       <td style="vertical-align: top;" width="20%"><?php echo __('Heading:') ?></td>
       <td style="vertical-align: top;" width="30%"><input type="text" name="bintro_settings_heading" value="<?php echo get_option('bintro_settings_heading'); ?>" /></td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __("Heading for home page's introduction if intro is taken from these settings (instead of page).") ?></span></td>
      </tr>

      <tr>
       <td style="vertical-align: top;" width="20%"><?php echo __('Content:') ?></td>
       <td style="vertical-align: top;" width="30%"><textarea rows="4" style="width:100%;" name="bintro_settings_content"><?php echo get_option('bintro_settings_content'); ?></textarea></td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __("Content for home page's introduction if intro is taken from these settings (instead of page).") ?></span></td>
      </tr>

      <tr>
       <td style="vertical-align: top;"><?php echo __('Show heading:') ?></td>
       <td style="vertical-align: top;">
         <input type="radio" name="bintro_show_heading" value="1" <?php echo get_option('bintro_show_heading') == '1' ? 'checked="checked"' : ''  ?> /> <?php echo __('Yes') ?><br />
         <input type="radio" name="bintro_show_heading" value="0" <?php echo get_option('bintro_show_heading') == '0' ? 'checked="checked"' : ''  ?> /> <?php echo __('No') ?><br />
       </td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __("Whether plugin should show heading (from chosen page).") . '<br />' . __('Default') . ': ' . __('Yes') ?></span></td>
      </tr>
      <tr>
       <td style="vertical-align: top;"><?php echo __('Show content:') ?></td>
       <td style="vertical-align: top;">
         <input type="radio" name="bintro_show_content" value="1" <?php echo get_option('bintro_show_content') == '1' ? 'checked="checked"' : ''  ?> /> <?php echo __('Yes') ?><br />
         <input type="radio" name="bintro_show_content" value="0" <?php echo get_option('bintro_show_content') == '0' ? 'checked="checked"' : ''  ?> /> <?php echo __('No') ?><br />
       </td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __("Whether plugin should show content (from chosen page).") . '<br />' . __('Default') . ': ' . __('Yes') ?></span></td>
      </tr>
      <tr>
       <td style="vertical-align: top;"><?php echo __('Wrapper tag for heading:') ?></td>
       <td style="vertical-align: top;"><input type="text" name="bintro_heading_tag" value="<?php echo get_option('bintro_heading_tag'); ?>" /></td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __('Enter tag without &lt;angle brackets&gt;.') . '<br />' . __('Default') . ': h2' ?></span></td>
      </tr>
      <tr>
       <td style="vertical-align: top;"><?php echo __('Show blog introduction for:') ?></td>
       <td style="vertical-align: top;">
         <input type="radio" name="bintro_show_for" value="all" <?php echo get_option('bintro_show_for') == 'all' ? 'checked="checked"' : ''  ?> /> <?php echo __('Everyone') ?><br />
         <input type="radio" name="bintro_show_for" value="users" <?php echo get_option('bintro_show_for') == 'users' ? 'checked="checked"' : ''  ?> /> <?php echo __('Authenticated users only') ?><br />
         <input type="radio" name="bintro_show_for" value="visitors" <?php echo get_option('bintro_show_for') == 'visitors' ? 'checked="checked"' : ''  ?> /> <?php echo __('Unauthenticated visitors only') ?>
       </td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __('To whom the introduction should be shown.') . '<br />' . __('Default') . ': ' . __('Everyone') ?></span></td>
      </tr>
      <tr>
       <td style="vertical-align: top;"><?php echo __('Show on all pages:') ?></td>
       <td style="vertical-align: top;">
         <input type="radio" name="bintro_show_on_pages" value="all" <?php echo get_option('bintro_show_on_pages') == 'all' ? 'checked="checked"' : ''  ?> /> <?php echo __('Yes (pages 1, 2, etc)') ?><br />
         <input type="radio" name="bintro_show_on_pages" value="first" <?php echo get_option('bintro_show_on_pages') == 'first' ? 'checked="checked"' : ''  ?> /> <?php echo __('No (first page only)') ?><br />
       </td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __("What front pages the introduction should be shown on.") . '<br />' . __('Default') . ': ' . __('Yes') ?></span></td>
      </tr>
      <tr>
       <td style="vertical-align: top;"><?php echo __('Show on home page:') ?></td>
       <td style="vertical-align: top;">
         <input type="radio" name="bintro_show_on_home" value="1" <?php echo get_option('bintro_show_on_home') == '1' ? 'checked="checked"' : ''  ?> /> <?php echo __('Yes') ?><br />
         <input type="radio" name="bintro_show_on_home" value="0" <?php echo get_option('bintro_show_on_home') == '0' ? 'checked="checked"' : ''  ?> /> <?php echo __('No') ?><br />
       </td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __("Whether intro is shown on home page. Archive pages show the intro regardless if corresponding page slug is found.") . '<br />' . __('Default') . ': ' . __('Yes') ?></span></td>
      </tr>
      <tr>
       <td style="vertical-align: top;"><?php echo __('Style for intro container:') ?></td>
       <td style="vertical-align: top;"><input type="text" name="bintro_style_intro" value="<?php echo get_option('bintro_style_intro'); ?>" /></td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __('Defines CSS style for the intro as a whole (which includes heading and content).') . '<br />' . __('Default') . ': ' . __('(empty)') ?></span></td>
      </tr>
      <tr>
       <td style="vertical-align: top;"><?php echo __('Style for heading:') ?></td>
       <td style="vertical-align: top;"><input type="text" name="bintro_style_heading" value="<?php echo get_option('bintro_style_heading'); ?>" /></td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __('Defines CSS style for the heading.') . '<br />' . __('Default') . ': ' . __('(empty)') ?></span></td>
      </tr>
      <tr>
       <td style="vertical-align: top;"><?php echo __('Style for content:') ?></td>
       <td style="vertical-align: top;"><input type="text" name="bintro_style_content" value="<?php echo get_option('bintro_style_content'); ?>" /></td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __('Defines CSS style for the content.') . '<br />' . __('Default') . ': ' . __('(empty)') ?></span></td>
      </tr>
      <tr>
       <td style="vertical-align: top;"><?php echo __('Intro before nth loop:') ?></td>
       <td style="vertical-align: top;"><input type="text" name="bintro_at_loop_count" value="<?php echo get_option('bintro_at_loop_count'); ?>" /></td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __('When used with several loops, which of them will trigger intro?') . '<br />' . __('Default') . ': 1 ' . __('(first article loop)') ?></span></td>
      </tr>
      <tr>
       <td style="vertical-align: top;"><?php echo __('Enable debugging mode:') ?></td>
       <td style="vertical-align: top;">
         <input type="radio" name="bintro_debug" value="1" <?php echo get_option('bintro_debug') == '1' ? 'checked="checked"' : ''  ?> /> <?php echo __('Yes') ?><br />
         <input type="radio" name="bintro_debug" value="0" <?php echo get_option('bintro_debug') == '0' ? 'checked="checked"' : ''  ?> /> <?php echo __('No') ?><br />
       </td>
       <td style="vertical-align: top;"><span style="color: #999"><?php echo __("Comments out the introduction with &lt;!--standard HTML commenting--&gt;.") . '<br />' . __('Default') . ': ' . __('No') ?></span></td>
      </tr>
    </table>

    <input type="hidden" name="action" value="update" />

    <?php settings_fields('bintro-settings-group'); ?>

    <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
    </p>

  </form>
<h3><?php echo __("Preview (home page)"); ?></h3>
<p>
<?php echo __("Remember to save changes to see the updated preview."); ?>
</p>
<div style="padding: 2em; border: 1px solid #ccc; overflow-x: scroll;"><code><pre>
<?php
$preview = bintro_get();
echo htmlentities($preview);
?>
</pre></code></div>

</div>

<?php
} // ends bintro_options_page


add_action('loop_start', 'bintro_print');

add_action('admin_menu', 'bintro_add_options_page');

add_action( 'admin_init', 'bintro_admin_init' );

