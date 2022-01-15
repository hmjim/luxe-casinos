jQuery('#del').on('click', function(){
    var $this = jQuery(this);
    var post = jQuery('#su_ajaxdel_postid').val(); // get post id from hidded field
    var nonce = jQuery('input[name="ajaxsecurity"]').val(); // get nonce from hidded field
    jQuery.ajax({
        url: 'admin_ajax.php', // in backend you should pass the ajax url using this variable
        type: 'POST',
        data: { action : 'su_ajax_delete', postid: post, ajaxsecurity: nonce },
        success: function(data){
            console.log(data);
            $this.val('Deleted');
        }
    });
});