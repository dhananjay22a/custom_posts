This widget will show custom posts "news" types 5 most recent posts which are not older than 30 days.

------------------------------------------------
			Description
------------------------------------------------

This widget checks database for "news" custom post type posts and fetches 5 recent records which are not older than 30 days.

You can change it to work for any custom post type. Currently it is set to "news". e.g. if you want it to work for "books" custom post type then change the value of post_type to books.

$args =  array( 
						'posts_per_page' => '5', 
						'post_type'      => 'news',
						'post__not_in'	=> array($currentID),
						'orderby' => 'date',
						'order'   => 'DESC'
			); 



------------------------------------------------
                    INSTALLATION
------------------------------------------------
Just upload the custom_posts folder in your plugin folder and activate from admin section.


------------------------------------------------
			     How to use
------------------------------------------------
After installation, in admin section, go to Appearance -> Widgets. You can find Custom Post Widget under Available Widgets section. Just click on it and and then click on Add Widget button. And that's all. This widget now will be displayed on side bar of pages.