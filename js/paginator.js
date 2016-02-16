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


    $.fn.PaginateZeroCoolCrud = function (options) {

        var opts = $.extend({}, $.fn.PaginateZeroCoolCrud.defaults, options);

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

            $('body').on('click', '.eliminar', function (e) {
                var data = {
                    id: $(this).attr('data-id'),

                };

                var pagina = $(this).attr('data-pagina');
                bootbox.confirm("Deseas Eliminar este registro?", function (result) {
                    if (result == true) {
                        $.ajax({
                            url: opts.pathDelete,
                            data: data,
                            success: function (succes) {
                                bootbox.alert(succes);
                                $(opts.content).load(opts.path + "?pagina="+pagina);
                            }
                        });
                    }
                });
                e.preventDefault();
                return false;
            })
        });
    };


    $.fn.PaginateZeroCoolCrud.defaults = {
        btnDelete: true,
        path:'http://localhost',
        pathDelete: 'http://localhost/delete.php',
        content: '#content'
    };

})(jQuery)
