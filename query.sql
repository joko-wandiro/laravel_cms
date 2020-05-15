SELECT * FROM posts;

SELECT * FROM medias;

SELECT * FROM pages;

SELECT * FROM settings;

SELECT * FROM menus;

SELECT menus.page_id, menus.parent_id, menus.order, pages.title, pages.url FROM menus 
LEFT JOIN pages ON pages.id = menus.page_id;

SELECT * FROM menus 
LEFT JOIN pages ON pages.id = menus.page_id 
LEFT JOIN posts ON posts.id = menus.page_id 
LEFT JOIN categories ON categories.id = menus.page_id;

