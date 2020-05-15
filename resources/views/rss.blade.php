<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
	<channel>
		<title>IdCoderBlog</title>
		<atom:link href="<?php echo action(config('app.frontend_namespace') . 'BlogController@rss'); ?>" rel="self" type="application/rss+xml" />
		<link><?php echo url(''); ?></link>
		<description>IdCoderBlog berisi artikel-artikel tentang pemrograman dan lainnya yang dibuat oleh penulis.</description>
		<lastBuildDate><?php echo date("D, d M Y H:i:s"); ?> GMT</lastBuildDate>
		<language>id-ID</language>
		<generator>Noteks v1.0</generator>
		<?php
		foreach( $posts as $post ){
			$url= url($post['url']);
			?>
		<item>
			<title><?php echo $post['title']; ?></title>
			<link><?php echo $url; ?></link>
			<description><![CDATA[<?php echo nl2br(get_word($post['content'], 40)); ?>]]></description>
		</item>
		<?php
		}
		?>
	</channel>
</rss>