(function( $ ) {

    var methods = {
        init : function( options ) {

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


                    $this.bind('swiperight.mobilesidebar', methods.off);
                    $(window).bind('resize.mobilesidebar', methods.reposition);

                }
            });
        },

        off: function(e) {
            e.preventDefault();
            console.log(this);
        },

        out: function() {

        },

        destroy : function( ) {

            return this.each(function() {

                var $this = $(this),
                    data = $this.data('mobilesidebar');

                // пространства имён рулят!!11
                $(window).unbind('.mobilesidebar');
                $this.unbind('.mobilesidebar');
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