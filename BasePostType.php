<?php  namespace App\Models;


use \WP_Query as WPQuery;

class BasePostType{

    protected $posttype;
    protected $args;
    protected $query;
    protected $posts;
    protected $pages;

    public function __construct()
    {
        $this->posts = [];
        $this->args['post_type'] = $this->posttype;
        $this->args['post_status'] = 'publish';

        return $this;
    }

    public function rawArgs($args)
    {
        $this->args = $args;

        return $this;
    }

    public function clear()
    {
        $this->args = [];
        $this->args['post_type'] = $this->posttype;
        $this->args['post_status'] = 'publish';

        return $this;
    }

    public function getPosts()
    {
        return $this->posts;
    }


    public function where($condition = null)
    {

        $this->args['post_type'] = $this->posttype;

        return $this;
    }

    public function postIn( array $list_id)
    {
        $this->args['post__in'] = $list_id;
        return $this;
    }


    public function numberPost($number = 1)
    {
        $this->args['posts_per_page'] = $number;
        return $this;
    }

    public function paged($p)
    {
        $this->args['paged'] = $p;
        return $this;
    }

    public function post__not_in( array $post__not_in)
    {
        $this->args['post__not_in'] = $post__not_in;
        return $this;
    }

    public function orderby($orderby = 'post_date')
    {
        $this->args['orderby'] = $orderby;
        return $this;
    }

    public function order($order)
    {
        $this->args['order'] = $order;
        return $this;
    }


    public function query()
    {
        $this->query = new WPQuery($this->args);
        $this->pages = $this->query->max_num_pages;

        $posts = [];

        if ($this->query->have_posts()) {
            while ($this->query->have_posts()) : $this->query->the_post();

                $data = $this->getData();
                $this->setPosts($posts,$data);
                
            endwhile;
        }

        $this->posts = array_merge($this->posts,$posts);

        wp_reset_postdata();

        return $this;
    }

    private function setPosts(&$posts,$data) : void
    {
        $posts[] = (object) $data;
    }

    protected function getData()
    {
        
        array_push($posts, [
            'id'      => get_the_ID(),
            'uri'     => get_permalink(get_the_ID()),
            'title'   => get_the_title(),
            'content' => get_the_content(),
            'image'   => get_the_post_thumbnail(),
      ]);
    }
    

    public function get()
    {
        return (object) [
            'posts' => $this->posts,
            'pages' => $this->pages,
        ];
    }


}
