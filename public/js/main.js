

(function($) {


    var LikesManager = function() {

        var _self = this,
            _$user_points_container = $('.user-points-container'),
            _$tasks_container = $('.task-list'),
            _task_popup,
            _task_timer,
            _check_url = '/user/tasks/check';

        var _checkTask = function(task_id) {

            $.post(_check_url, {
                id: task_id
            }).done(function(data) {
                var notifyType = 'success';

                if ( data.done ) {
                    _$user_points_container.text(data.user_points);
                } else {
                    notifyType = 'danger';
                }

                $.notify({
                    message: data.message
                },{
                    type: notifyType
                });

            }).fail(function(error) {
                console.error(error);
            });
        };

        this.init = function() {
            _$tasks_container.on('click', 'a.do-task', function(e) {
                e.preventDefault();

                var $this = $(this),
                    popupWidth = 900,
                    popupHeight = 600,
                    centerWidth = (window.screen.width - popupWidth) / 2,
                    centerHeight = (window.screen.height - popupHeight) / 2;

                if ( _task_popup && !_task_popup.closed ) {
                    console.log('sssss');
                    clearInterval(_task_timer);
                    _checkTask($this.data('id'));
                    _task_popup.close();
                }

                _task_popup = window.open($this.attr('href'), "task_popup", "width=" + popupWidth + ",height=" + popupHeight + ",left=" + centerWidth + ",top=" + centerHeight + ",resizable=yes,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=yes");
                _task_popup.focus();

                _task_timer = setInterval(function() {
                    if ( _task_popup.closed ) {
                        clearInterval(_task_timer);
                        _checkTask($this.data('id'));
                    }
                }, 250);

                e.stopPropagation();
            });
        };

        return this;
    };



    $(document).ready(function() {
        $('.user-sidebar').mobilesidebar();

        var likesManager = new LikesManager();
        likesManager.init();
    });



}) (jQuery);
