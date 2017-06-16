

(function($) {


    var LikesManager = function() {

        var _self = this,
            _$user_points_container = $('.user-points-container'),
            _$tasks_container = $('.task-list'),
            _task_popup,
            _task_timer,
            _check_url = '/user/tasks/check';

        var _checkTask = function(task_id, successCallback, errorCallback) {

            $.post(_check_url, {
                id: task_id
            }).done(function(data) {
                var notifyType = 'success';

                if ( data.done ) {
                    _$user_points_container.text(data.user_points);

                    if ( successCallback ) {
                        successCallback.apply(_self, [data]);
                    }

                } else {
                    notifyType = 'danger';

                    if ( errorCallback ) {
                        errorCallback.call(null, data);
                    }
                }

                $.notify({
                    message: data.message
                },{
                    type: notifyType
                });

            }).fail(function(error) {
                console.error(error);
                if ( errorCallback ) {
                    errorCallback.call(null, data);
                }
            });
        };

        this.init = function() {
            _$tasks_container.on('click', 'a.do-task', function(e) {
                e.preventDefault();

                var $this = $(this),
                    $taskItem = $this.closest('.task-item'),
                    $doButton = $('[type=button].do-task', $taskItem),
                    $checkButton = $('[type=button].check-task', $taskItem),
                    taskId = $taskItem.data('id'),
                    popupWidth = 900,
                    popupHeight = 600,
                    centerWidth = (window.screen.width - popupWidth) / 2,
                    centerHeight = (window.screen.height - popupHeight) / 2;

                var checkDone = function(data) {
                    $taskItem.closest('[data-key]').delay(500).fadeOut(500);
                };

                var checkFail = function(data) {
                    if ( device.mobile() ) {
                        $doButton.css({display: 'none'});
                        $checkButton.css({display: 'inline-block'});
                    }
                };

                if ( _task_popup && !_task_popup.closed ) {
                    clearInterval(_task_timer);
                    _checkTask(taskId, checkDone, checkFail);
                    _task_popup.close();
                }

                _task_popup = window.open($this.attr('href'), "task_popup", "width=" + popupWidth + ",height=" + popupHeight + ",left=" + centerWidth + ",top=" + centerHeight + ",resizable=yes,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=yes");
                _task_popup.focus();

                _task_timer = setInterval(function() {
                    if ( _task_popup.closed ) {
                        clearInterval(_task_timer);
                        _checkTask(taskId, checkDone, checkFail);
                    }
                }, 250);

                e.stopPropagation();
            });
        };

        _$tasks_container.on('click', 'a.check-task', function(e) {
            e.preventDefault();

            var $this = $(this),
                $taskItem = $this.closest('.task-item'),
                $doButton = $('[type=button].do-task', $taskItem),
                $checkButton = $('[type=button].check-task', $taskItem),
                taskId = $taskItem.data('id');

            var checkDone = checkFail = function(data) {
                if ( device.mobile() ) {
                    $checkButton.css({display: 'none'});
                    $doButton.css({display: 'inline-block'});
                }
            };

            _checkTask(taskId, checkDone, checkFail);

            e.stopPropagation();
        });

        return this;
    };



    $(document).ready(function() {
        $('.user-sidebar').mobilesidebar();

        var likesManager = new LikesManager();
        likesManager.init();
    });



}) (jQuery);
