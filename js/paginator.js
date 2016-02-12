/**
 * Created by zerocool on 12/02/16.
 */
/** @author: Noé Francisco Martínez Merino ...*/

(function ($) {


    $.fn.PaginateZeroCool = function (options) {

        var opts = $.extend({}, $.fn.PaginateZeroCool.defaults, options);

        // para cada componente que puede contener el objeto jQuery que invoca a esta función
        this.each(function () {
            $(this).load(opts.path);

            $('body').on('click', '.pagina', function (e) {
                $(opts.content).load(opts.path + "?pagina=" + $(this).attr('data-id'));
                e.preventDefault();
                return false;
            });

            $('body').on('click', '.primero', function (e) {
                $(opts.content).load(opts.path + "?pagina=" + $(this).attr('data-id'));
                e.preventDefault();
                return false;
            });

            $('body').on('click', '.anterior', function (e) {
                $(opts.content).load(opts.path + "?pagina=" + $(this).attr('data-id'));
                e.preventDefault();
                return false;
            });

            $('body').on('click', '.siguiente', function (e) {
                $(opts.content).load(opts.path + "?pagina=" + $(this).attr('data-id'));
                e.preventDefault();
                return false;
            });
            $('body').on('click', '.ultimo', function (e) {
                $(opts.content).load(opts.path + "?pagina=" + $(this).attr('data-id'));
                e.preventDefault();
                return false;
            });
        });


    };


    $.fn.PaginateZeroCool.defaults = {
        path: 'http://localhost',
        btnDelete: true,
        pathDelete: 'http://localhost/delete.php',
        content: '#content'
    };


})(jQuery)
