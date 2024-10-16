    jQuery(document).ready(function ($) {
        let filterButtons = $('.filter-button');
        let loadMoreButton = $('.load-more-button');

        filterButtons.on('click', function () {

            filterButtons.removeClass('filter-active');

            $(this).addClass('filter-active');


            let filter = $(this).data('filter');
            loadProducts(filter);
            updateLoadMoreButton(filter);
        });

        function updateLoadMoreButton(filter) {
            let baseURL = '/shop';
            let newURL = `${baseURL}?filter=${filter}`;
            loadMoreButton.attr('data-url', newURL);
        }

        loadMoreButton.on('click', function () {
            let url = $(this).attr('data-url');
            window.location.href = window.location.href + url;
        });

        function loadProducts(filter) {
            $.ajax({
                url: 'wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'filter_products',
                    filter: filter
                },
                success: function (response) {
                    let slickSettings = $('.carousel-products2').slick('getSlick').options;
                    $('.carousel-products2').slick('unslick');

                    $('.carousel-products2').html(response);
                    $('.carousel-products2').slick(slickSettings);

                    $('.grid-products3').html(response);
                },
                error: function (xhr, status, error) {
                    console.log('AJAX Error: ' + error);
                }
            });
        }
    });
