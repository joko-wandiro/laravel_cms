<?php

if (!function_exists('settings')) {

    /**
     * Get settings by key
     * 
     * @param string $key
     * @return string
     */
    function settings($key)
    {
        return isset($GLOBALS['settings'][$key]) ? $GLOBALS['settings'][$key] : "";
    }

}

if (!function_exists('to_url_component')) {

    /**
     * Url component
     * 
     * @param string $value
     * 
     * @return string
     */
    function to_url_component($value)
    {
        return str_replace(" ", "-", strtolower($value));
    }

}

if (!function_exists('get_word')) {

    /**
     * Get word
     * 
     * @param string $words
     * @param int $length
     * 
     * @return string
     */
    function get_word($words, $length = 100)
    {
        $words = preg_split("/[ ]+/", strip_tags($words), $length + 1);
        unset($words[$length]);
        return implode(" ", $words) . "...";
    }

}

if (!function_exists('post_url')) {

    /**
     * Get url of specific post
     * 
     * @param string $title
     * 
     * @return string
     */
    function post_url($title)
    {
        return action(config("app.frontend_namespace") . "BlogController@single", array(to_url_component($title)));
    }

}

if (!function_exists('category_url')) {

    /**
     * Get url of specific category
     * 
     * @param string $category
     * 
     * @return string
     */
    function category_url($category)
    {
        return url('category/' . to_url_component($category));
    }

}

if (!function_exists('tag_url')) {

    /**
     * Get url of specific tag
     * 
     * @param string $tag
     * 
     * @return string
     */
    function tag_url($tag)
    {
        return url('tag/' . to_url_component($tag));
    }

}

if (!function_exists('get_gravatar')) {

    /**
       * Get either a Gravatar URL or complete image tag for a specified email address.
       *
       * @param string $email The email address
       * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
       * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
       * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
       * @param boole $img True to return a complete IMG tag False for just the URL
       * @param array $atts Optional, additional key/value attributes to include in the IMG tag
       * @return String containing either just a URL or a complete image tag
       * @source https://gravatar.com/site/implement/images/php/
        */
    function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array())
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        return $url;
    }

}

if (!function_exists('images_temporary_path')) {

    function images_temporary_path($filename = null)
    {
        $path = 'uploads' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
        $path = (!$filename) ? $path : $path . $filename;
        return public_path($path);
    }

}

if (!function_exists('images_path')) {

    function images_path($filename = null)
    {
        $path = 'uploads' . DIRECTORY_SEPARATOR;
        $path = (!$filename) ? $path : $path . $filename;
        return public_path($path);
    }

}

if (!function_exists('image_url')) {

    function image_url($filename = null)
    {
        return url('uploads/' . $filename);
    }

}

if (!function_exists('image_url_medium')) {

    function image_url_medium($filename = null)
    {
        $pathinfos = pathinfo($filename);
        $filename = $pathinfos['filename'] . '_medium.' . $pathinfos['extension'];
        return url('uploads/' . $filename);
    }

}

if (!function_exists('image_url_small')) {

    function image_url_small($filename = null)
    {
        $pathinfos = pathinfo($filename);
        $filename = $pathinfos['filename'] . '_small.' . $pathinfos['extension'];
        return url('uploads/' . $filename);
    }

}

if (!function_exists('get_unique_filename')) {

    /**
     * Get unique filename
     *
     * @return string
     */
    function get_unique_filename()
    {
        return time() . str_random(8);
    }

}

if (!function_exists('shortcode')) {

    function shortcode($content)
    {
        // Blog shortcode
        $pattern = '/\[\:blog\:\]/i';
        if (preg_match($pattern, $content)) {
            $replacement = get_blog();
            $content = preg_replace($pattern, $replacement, $content);
        }
        // Contact form shortcode
        $pattern = '/\[\:contact\-form\:\]/i';
        if (preg_match($pattern, $content)) {
            $replacement = get_contact_form();
            $content = preg_replace($pattern, $replacement, $content);
        }
        return $content;
    }

}

if (!function_exists('get_blog')) {

    function get_blog($page = 1)
    {
        $route_parameters = Route::current()->parameters();
        $page_number = (isset($route_parameters['number'])) ? (int) $route_parameters['number'] : 1;
        $Model = new App\Models\Posts;
        $Model = $Model->join('categories', 'categories.id', '=', 'posts.category', 'INNER');
        $Model = $Model->where('status', '=', 1)->where('published_at', '<=', date('Y-m-d H:i:s'));
        $Model = $Model->orderBy('published_at', 'DESC');
        $status = true;
        while ($status) {
            // Get records
            $posts = $Model->paginate(2, array('posts.id', 'title', 'url', 'categories.name AS category', 'content', 'published_at'), 'page', $page_number);
            // Page is valid
            $total = $posts->total();
            $lastPage = $posts->lastPage();
            $currentPage = $posts->currentPage();
            if ($currentPage > $lastPage && $total) {
                $page = $lastPage;
            } else {
                $status = false;
            }
        }
        $BlogController = new App\Http\Controllers\FrontEnd\BlogController;
        $parameters = $BlogController->getParameters();
        $parameters['posts'] = $posts;
        $parameters['baseUrl'] = url('');
        $parameters['replacementUrl'] = url('news/page');
        return view('frontend.themes.standard.posts', $parameters)->render();
    }

}

if (!function_exists('get_contact_form')) {

    function get_contact_form()
    {
        $httpVerb = request()->getMethod();
        $Scaffolding = new Scaffolding("messages");
        switch ($httpVerb) {
            case "POST":
//                // Modify request set default value of status parameter
//                $Scaffolding->addHooks("insertModifyRequest", "modifyRequest");
                // Hook Filter insertModifyResponse to modify response
                $Scaffolding->addHooks("insertModifyResponse", "addContactResponse");
                $Scaffolding->processInsert();
                break;
        }
        $Scaffolding->setTemplate('frontend.themes.standard.contact');
//        // Modify form layout
//        $Scaffolding->addHooks("modifyLayout", "modifyCommentFormLayout");
//        // Modify post form group
//        $Scaffolding->setFormGroup('post', 'getFormGroupPost');
        $commentForm = $Scaffolding->renderCreate();
        return $commentForm;
//        $parameters = $this->getParameters();
//        $parameters['post'] = $post;
//        $parameters['comments'] = $this->getComments($post);
//        $parameters['commentForm'] = $commentForm;
//        return view('frontend.themes.standard.single', $parameters);
    }

    /**
     * Add comment response
     * 
     * @return Illuminate\View\View
     */
    function addContactResponse($Response)
    {
        if (request()->ajax()) {
            // Send Response
            $JsonResponse = new \Illuminate\Http\JsonResponse(trans('main.alert.success.contact'), 200);
            $JsonResponse->send();
            exit;
        } else {
            return back()->with('alert_success_contact', trans('main.alert.success.contact'));
        }
    }

}