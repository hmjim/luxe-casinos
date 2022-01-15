jQuery(document).ready(function ($) {
    //Добавление постав в избранное
    function add_fav_item(post_id) {
        let posts_storage = get_fav_items();
        posts_storage.push(post_id);
        save_fav_items(posts_storage);
    }

    //Сохранение избранного
    function save_fav_items(posts_ids) {
        let posts_storage = posts_ids.filter(onlyUnique);
        localStorage.setItem('favorites_posts', JSON.stringify(posts_storage));
    }

    //Удаление постов из изранного
    function remove_fav_item(post_id) {
        let posts_storage = get_fav_items();
        posts_storage = posts_storage.filter(function (e) {
            return e !== post_id;
        });
        save_fav_items(posts_storage);
    }

    //Получаем айдишники избранного
    function get_fav_items() {
        let posts_storage = localStorage.getItem('favorites_posts');
        return (posts_storage) ? JSON.parse(posts_storage) : [];
    }

    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }

    // Подготовим все для работы
    let $fav_wrapper = $('#scf_favorites');
    let $add_btn = $('.scf_add_fav_btn');
    let post_ids = get_fav_items();
    let data = {
        action: 'scf_get_favorites',
        posts: post_ids,
    };

    if (post_ids.indexOf($add_btn.attr('data-id')) !== -1) {
        let added_text = '<i class="fas fa-star"></i>';
        $add_btn.attr('data-id', 0).html(added_text);
    }

    //Отлавливаем события добавления в избранное
    $add_btn.click(function (e) {
        let post_id = $(this).attr('data-id');

        // Если еще не добавлено в избранное - добавляем
        if (post_id > 0) {
            add_fav_item(post_id);
            $(this).html('<i class="fas fa-star"></i>');
        } else {
            console.log('Ошибка, извинте')
        }

    });

    //Отлавливаем события удаления из избранного
    $fav_wrapper.on('click', '.scf_remove_fav_btn', function () {
        remove_fav_item($(this).attr('data-id'));
        console.log($(this).attr('data-id'));
        $(this).closest('.item').remove();
    });

    //Получим посты для юзера
    $.ajax({
        type: 'POST',
        url: scf_ajaxurl.ajaxurl,
        data: data,
    }).done(function (msg) {
        $fav_wrapper.html(msg);
    });

});
