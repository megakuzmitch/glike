(function( $ ) {

    var settings = {
        'width': 250,
        'minimizeWidth': 50,
        'duration': 500
    };

    var methods = {
        init : function( options ) {

            settings = $.extend(settings, options);

            return this.each( function() {

                var $this = $(this),
                    data = $this.data('mobilesidebar');

                // Если плагин ещё не проинициализирован
                if ( ! data ) {

                    /*
                     * Тут выполняем инициализацию
                     */

                    // var toggleButton = $();

                    $(this).data('mobilesidebar', {
                        target : $this,
                        toggleButton: $('.toggle', $this).find().first()
                    });


                    $this.hammer().bind('swiperight.mobilesidebar', methods.swiperight);
                    $this.hammer().bind('swipeleft.mobilesidebar', methods.swipeleft);
                    // $this.bind('click.mobilesidebar', methods.click);

                    $(window).bind('resize.mobilesidebar', methods.reposition);

                }
            });
        },

        swiperight: function(e) {
            e.preventDefault();
            methods.off.apply(this);
            e.stopPropagation();
        },

        swipeleft: function(e) {
            e.preventDefault();
            methods.out.apply(this);
            e.stopPropagation();
        },

        click: function(e) {
            methods.toggle.apply(this);
        },

        off: function() {
            $(this).addClass('open').width(settings.width);
        },

        out: function() {
            $(this).removeClass('open').width(settings.minimizeWidth);
        },

        toggle: function(e) {
            if ( $(this).hasClass('open') ) {
                methods.out.apply(this);
            } else {
                methods.off.apply(this);
            }
        },

        destroy : function( ) {

            return this.each(function() {

                var $this = $(this),
                    data = $this.data('mobilesidebar');

                // пространства имён рулят!!11
                $(window).unbind('.mobilesidebar');
                $this.unbind('.mobilesidebar');
                $this.hammer().unbind('.mobilesidebar');
                $this.removeData('mobilesidebar');

            });

        },

        reposition: function () {

        }
    };


    $.fn.mobilesidebar = function( method ) {

        if ( methods[method] ) {
            return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Метод с именем ' +  method + ' не существует для jQuery.mobilesidebar' );
        }

    };


}) ( jQuery );