<?php
if ( class_exists( 'SU_Module' ) ) {

	class SU_Premium extends SU_Module {

		static function get_module_title() {
			return __( 'Premium Modules', 'seo-ultimate-pro' );
		}

		static function get_menu_title() {
			return __( 'Premium Modules', 'seo-ultimate-pro' );
		}

		static function get_menu_pos() {
			return 1000;
		}

		static function is_independent_module() {
			return true;
		}

		function admin_page_contents() {
			$su_premium = "";
			$su_premium = "
<style>
body {
background-image: url(\"" . plugins_url() . "/seoupro/modules/premium/background.jpg\");
background-repeat:no-repeat;
}
</style>
<div>
<div class=\"features-boxed\" style=\"opacity: .7;\">
    <div class=\"container\">
        <div class=\"intro\">
            <h2 class=\"text-center\" style=\"color: rgb(0,0,0);\">Premium Modules</h2>
            <p class=\"text-center\"><strong>SEO Ultimate Pro premium modules will help your site reach it&#39;s full potential. Check here frequently for new premium modules.</strong></p>
        </div>
        <div class=\"row features\">
            <div class=\"col-md-4 col-sm-6 item\" style=\"height: 300;\">
                <div class=\"box\" style=\"background-color: rgb(0,0,0);height: 300px;\"><i class=\"fa fa-question fa-4x faicon\"></i>
                    <h3 style=\"padding-top: 10px;\" class=\"name\">COMING SOON!</h3>
                    <p class=\"description\">This premium module will be coming soon!</p>
                    <i class=\"far fa-sun\" style=\"font-size: 42px;\"></i>
                </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\" style=\"height: 300;\">
                <div class=\"box\" style=\"background-color: rgb(0,0,0);height: 300px;\"><i class=\"fa fa-question fa-4x faicon\"></i>
                    <h3 style=\"padding-top: 10px;\" class=\"name\">COMING SOON!</h3>
                    <p class=\"description\">This premium module will be coming soon!</p>
                    <i class=\"far fa-sun\" style=\"font-size: 42px;\"></i>
                </div>
            </div>
                        <div class=\"col-md-4 col-sm-6 item\" style=\"height: 300;\">
                <div class=\"box\" style=\"background-color: rgb(0,0,0);height: 300px;\"><i class=\"fa fa-question fa-4x faicon\"></i>
                    <h3 style=\"padding-top: 10px;\" class=\"name\">COMING SOON!</h3>
                    <p class=\"description\">This premium module will be coming soon!</p>
                    <i class=\"far fa-sun\" style=\"font-size: 42px;\"></i>
                </div>
            </div>
                        <div class=\"col-md-4 col-sm-6 item\" style=\"height: 300;\">
                <div class=\"box\" style=\"background-color: rgb(0,0,0);height: 300px;\"><i class=\"fa fa-question fa-4x faicon\"></i>
                    <h3 style=\"padding-top: 10px;\" class=\"name\">COMING SOON!</h3>
                    <p class=\"description\">This premium module will be coming soon!</p>
                    <i class=\"far fa-sun\" style=\"font-size: 42px;\"></i>
                </div>
            </div>
                        <div class=\"col-md-4 col-sm-6 item\" style=\"height: 300;\">
                <div class=\"box\" style=\"background-color: rgb(0,0,0);height: 300px;\"><i class=\"fa fa-question fa-4x faicon\"></i>
                    <h3 style=\"padding-top: 10px;\" class=\"name\">COMING SOON!</h3>
                    <p class=\"description\">This premium module will be coming soon!</p>
                    <i class=\"far fa-sun\" style=\"font-size: 42px;\"></i>
                </div>
            </div>
                        <div class=\"col-md-4 col-sm-6 item\" style=\"height: 300;\">
                <div class=\"box\" style=\"background-color: rgb(0,0,0);height: 300px;\"><i class=\"fa fa-question fa-4x faicon\"></i>
                    <h3 style=\"padding-top: 10px;\" class=\"name\">COMING SOON!</h3>
                    <p class=\"description\">This premium module will be coming soon!</p>
                    <i class=\"far fa-sun\" style=\"font-size: 42px;\"></i>
                </div>
            </div>
        </div>
    </div>
</div>";

			echo $su_premium;

		}
	}
}
?>