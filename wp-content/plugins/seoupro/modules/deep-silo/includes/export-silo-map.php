<?php
$su_path = preg_replace('/wp-content(?!.*wp-content).*/','',__DIR__);

include($su_path . "wp-load.php");

$nonce = $_REQUEST["_wpnonce"];
if(wp_verify_nonce($nonce, "export-silo")){
    $with_links = isset($_REQUEST["with-links"]);
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename=Deep_Silo_Map_".str_replace(" ", "_", get_bloginfo("name")).".doc");

    $args=array(
        'post_type' => 'page',
        'meta_key' => '_wpu_silo_dws',
        "post_status" => array("publish","draft"),
        'posts_per_page'=>-1,
        'orderby' => 'menu_order',
        'order'=>'ASC'
    );
    $silo_pages = new WP_Query($args);

    echo "<html>";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
    echo "<body>";
	$listing_code = "";
    $listing_code .= "<h1 style='text-align: center;'>SILO SITE STRUCTURE MAP</h1>";
    $listing_code .= "<h2 style='text-align: center;'><a href='".get_bloginfo("url")."'>".get_bloginfo("name")."</a></h2>";
    $listing_code .="<br><br><br>";
    $listing_code .="<ol>";

    while ($silo_pages->have_posts()): $silo_pages->the_post();
        $tmp_id = get_the_ID();
        $parent_id = get_post()->post_parent;
        if($parent_id  == 0 ) {
            $silo_page_title = get_the_title();
            $listing_code .="<li style='list-style-type: decimal;'>";
            $listing_code .="<h4>";
            $draftStr = (get_post_status($tmp_id) == "draft") ? " - <b>draft</b>" : "" ;
            $listing_code .= ($with_links) ? "<a href='".get_permalink()."'>".$silo_page_title.$draftStr."</a>" : $silo_page_title.$draftStr;
            $listing_code .="</h4>";

            if($art_cat = get_post_meta($tmp_id,'_wpu_catmatch_dws', true)) {
                $silo_cat_id = $art_cat;
            } else {
				continue;
			}

            $categories = get_categories("child_of=$silo_cat_id&hide_empty=0");
            if(!empty($categories)) {
                $listing_code .="<ol>";
                foreach ($categories as $cat)
                {
					$current_cat_id = $cat->cat_ID;
                    $listing_code .="<li style='list-style-type: decimal;'>";
					
					$matching_page_id = get_term_meta ($current_cat_id, '_wpu_pagematch_dws', true);
                    $draftStr = (get_post_status($matching_page_id) == "draft") ? " - <b>draft</b>" : " ";
					$category_page_title = get_the_title($matching_page_id);
                    query_posts("cat=$current_cat_id&posts_per_page=-1&orderby=menu_order&order=ASC&post_status=publish,draft");
                    $listing_code .= $category_page_title.$draftStr;
                    if(have_posts()) {
                        $listing_code .="<ol class=\"c\">";
                        while (have_posts()) {
							the_post();
                            $draftStr = (get_post_status(get_the_ID()) == "draft") ? " - <b>draft</b>" : "" ;
                            $listing_code .="<li style='list-style-type: decimal;'>".get_the_title().$draftStr."</li>";
                        }
                        $listing_code .="</ol>";
                    }
                    $listing_code .="</li>";
                }
                $listing_code .="</ol>";
            }
            $listing_code .="</li>";
        }
    endwhile;
    $listing_code .="</ol>";
    $listing_code .= "</div>";
    echo $listing_code;
    echo "</body>";
    echo "</html>";
}
?>