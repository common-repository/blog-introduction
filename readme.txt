=== Blog Introduction ===
Contributors: jsruok
Tags: description, introduction, intro, purpose, meaning, tag line, home page, front page, sticky, archive pages, tag, author, category
Requires at least: 2.5
Tested up to: 3.2.1
Stable tag: trunk

Blog Introduction inserts a static intro before posts (on homepage or
archive pages). Introduction content is taken from a designated page.

== Description ==

Blog Introduction displays an introduction (heading and/or contents of certain 
page) before posts. Intro content comes from a designated page, please see below for examples.

An introduction can appear on your

*   **home page** (from page whose URL - aka permalink aka slug - is, by default, 'blog-introduction'),
*   **category** archive pages (intro from page 'yourcategoryurl-cat-intro', e.g. category has 'widgets' slug, then intro comes from page that has slug 'widgets-cat-intro'),
*   **tag** archive pages (intro from page 'yourtagurl-tag-intro', e.g. tag is 'blue-widgets', then use 'blue-widgets-tag-intro' as intro page slug), and
*   **author** archive pages (intro from page 'yourauthorurl-author-intro', e.g. author is 'jsmith', then use 'jsmith-author-intro' as intro page slug).

Hide these special pages by making them private.

Plugin options allow for you to:

*   Define who sees the introduction (everyone / logged in users only / visitors only).
*   Choose which parts (heading and/or content) you want to show.
*   Change the heading wrapper tag. 
*   Define style (CSS) for various parts of intro.
*   Change where the home page's intro is taken from, either by page id or its permalink. 
*   Define home page's the intro (instead of using a specific page).
*   Disable intro on home page and only use it on archive pages.

Default output (for home page):

    <div id='blog_introduction' class='blog-introduction-container'>
        <div id='blog_introduction_heading' class='blog-introduction-heading'>
            <h2><span><!-- page title is printed here --></span></h2>
        </div>
        <div id='blog_introduction_content' class='blog-introduction-content'>
            <!-- content is printed here -->
        </div>
    </div>


**If you use the plugin, please rate it at WP Plugin Directory. Thanks!**

== Installation ==

1. Upload `blog-introduction` folder to the `/wp-content/plugins/` directory.
1. Create a new page. Put 'blog-introduction' as its permalink. Set the page private.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Enter settings page and choose to 'Show intro' (and make other settings to your liking).

== FAQ ==

= My introduction looks funny/distorted, can I change it? =

Sure. Try changing CSS style of introduction elements, either in plugin settings or in your CSS file. 

= I did make changes in my CSS file, but it still looks funny/distorted! =

Please check that dashes and underscores in id's and class names in the CSS file match the ones used in HTML code.

= Intro appears in my sidebar, what can I do? =

You probably have another article loop appearing before the one you'd prefer. Change setting "Intro before nth loop" in Blog Introduction settings to "2". That will make intro appear before the second loop. (Value "3" will put intro before the third loop, and so on.)

= Can I have a different slug for category/tag/author intro page? =

No, unfortunately that's not possible. Managing the relations between the category/tag/author strings and intro page slugs would be too much of a hassle.

= How can I donate? =

To your local charity. Or better yet, take a homeless person into a store and buy him/her a bag of proper food. Veggies, too. Also, whether you liked the plugin or not, I'd appreciate if you rated it at WP Plugin Directory. Thanks!
 

== Screenshots ==

1. Blog introduction (baby blue background) appearing before the first post.
2. Settings (version 1.9.2)


== Changelog ==

= 1.9.11 =
* Broke in WP 3.2.1 with certain settings. Fixed.

= 1.9.10 =
* Documentation update.

= 1.9.9 =
* Documentation update (changelog, upgrade notice).

= 1.9.8 =
* Documentation update (better slug descriptions).


== Upgrade Notice ==

= 1.9.11 =
Upgrade if introduction content comes from plugin settings (i.e. not page).

= 1.9.10 =
No need to upgrade. No changes in functionality since 1.9.7. Documentation update only.

= 1.9.9 =
No need to upgrade. No changes in functionality since 1.9.7. Added documentation (changelog, upgrade notice).

= 1.9.8 =
No need to upgrade. No changes in functionality since 1.9.7. Added documentation (better slug description).
