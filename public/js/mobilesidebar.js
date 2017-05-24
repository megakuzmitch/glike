(function( $ ) {

    var settings = {
        width: 250,
        minimizeWidth: 50,
        duration: 500,
        canvasEl: '.canvas',
        toggleBtn: '.sidebar-toggle'
    };

    var methods = {
        init : function( options ) {

            settings = $.extend(settings, options);

            return this.each( function() {

                var $this = $(this),
                    data = $this.data('mobilesidebar');

                if ( ! data ) {

                    var $toggleButton = $(settings.toggleBtn).first(),
                        $canvas = $(settings.canvasEl).first();

                    $(this).data('mobilesidebar', {
                        target : $this,
                        toggleButton: $toggleButton,
                        canvas: $canvas
                    });


                    $this.hammer().bind('swiperight.mobilesidebar', methods.swiperight);
                    $this.hammer().bind('swipeleft.mobilesidebar', methods.swipeleft);

                    $toggleButton.bind('click.mobilesidebar', $this.data('mobilesidebar'), methods.toggle);


                    // $this.bind('click.mobilesidebar', methods.click);

                    // $(window).bind('resize.mobilesidebar', methods.reposition);

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
            e.preventDefault();
            methods.toggle.apply(this);
            e.stopPropagation();
        },

        off: function() {
            var data = $(this).data('mobilesidebar');

            data.target.removeClass('minimize').addClass('full');//.width(settings.width);
            // if ( $(window).width() >= 768 ) {
            //     data.canvas.css('paddingLeft', settings.width);
            // }
        },

        out: function() {
            var data = $(this).data('mobilesidebar');

            data.target.removeClass('full').addClass('minimize');//.width(settings.minimizeWidth);
            // if ( $(window).width() >= 768 ) {
            //     data.canvas.css('paddingLeft', settings.minimizeWidth);
            // }
        },

        toggle: function(e) {

            var data = e.data;

            if ( data.target.hasClass('full') ) {
                methods.out.apply(data.target);
            } else {
                methods.off.apply(data.target);
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

        reposition: function (e) {
            if ( $(window).width > 768 ) {
                methods.off.apply(this);
            } else {
                methods.out.apply(this);
            }
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