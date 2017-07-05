

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
            $(document).on('click', 'a.do-task', function(e) {
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

        $(document).on('click', 'a.check-task', function(e) {
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




    var Task = function(id) {

        this.id = id === undefined ? null : id;
        var _self = this;

        return _self;
    };


    var PopupManager = (function() {
        var instance,
            defaults = {
                width: 450,
                height: 380
            },
            popup,
            timer;

        return function Construct_PopupManager() {
            if ( instance ) {
                return instance;
            }
            if ( this && this.constructor === Construct_PopupManager ) {

                this.open = function(url, queryParams, options) {
                    var currentOptions = $.extend(defaults, options),
                        centerWidth = (window.screen.width - currentOptions.width) / 2,
                        centerHeight = (window.screen.height - currentOptions.height) / 2;

                    if ( popup && !popup.closed ) {
                        popup.close();
                        clearInterval(timer);
                        $(document).trigger($.Event('closed.PopupManager'));
                    }

                    popup = window.open(url, "Popup Manager",
                        "width=" + currentOptions.width +
                        ",height=" + currentOptions.width +
                        ",left=" + centerWidth +
                        ",top=" + centerHeight +
                        ",resizable=yes,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=yes");
                    popup.focus();

                    timer = setInterval(function() {
                        if ( popup.closed ) {
                            clearInterval(timer);
                            $(document).trigger($.Event('closed.PopupManager'));
                        }
                    }, 250);

                    $(document).trigger($.Event('opened.PopupManager'));
                };


                this.close = function() {
                    popup.close();
                };

                instance = this;
            } else {
                return new Construct_PopupManager();
            }
        }
    }());



    var BoostrapModalManager = (function() {

        var instance,
            stack = [],
            modalTemplate = '<div id="{{modalId}}" class="fade modal" role="dialog" tabindex="-1">' +
                                '<div class="modal-dialog modal-md">' +
                                    '<div class="modal-content">' +
                                        '<div class="modal-header">' +
                                            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
                                            '<h4 class="modal-title"></h4>' +
                                        '</div>' +
                                        '<div class="modal-body"></div>' +
                                        '<div class="modal-footer"></div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>';

        return function Construct_BoostrapModalManager() {
            if ( instance ) {
                return instance;
            } else if ( this && this.constructor === Construct_BoostrapModalManager ) {

                this.isOpen = function() {
                    if ( stack.length === 0 ) {
                        return false;
                    }
                    var modal = stack[stack.length - 1];
                    return modal.data('bs.modal').isShown;
                };

                this.getModal = function() {
                    if ( stack.length === 0 || this.isOpen() ) {
                        var modal = $(modalTemplate.replace('{{modalId}}', 'modal' + stack.length));
                        $('body').append(modal);
                        modal.modal({backdrop: 'static', show: false});
                        modal.on('hidden.bs.modal', function(e) {
                            stack.pop();
                            $(this).empty().remove();
                        });
                        stack.push(modal);
                    }
                    return stack[stack.length - 1];
                };

                this.open = function(src, title) {
                    var modal = this.getModal();
                    modal.find('.modal-body').load(src, function() {
                        modal.trigger('loaded.bs.modal');
                    });
                    modal.find('.modal-title').html(title);
                    modal.modal('show');
                    return modal;
                };

                this.close = function() {
                    if ( stack.length === 0 )
                        return;
                    var modal = stack[stack.length - 1];
                    modal.modal('hide');
                };

                $(document).on('show.bs.modal', '.modal', function (e) {
                    var zIndex = 1040 + (10 * $('.modal:visible').length);
                    $(this).css('z-index', zIndex);
                    setTimeout(function() {
                        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
                    }, 0);
                });

                instance = this;

            } else {
                return new Construct_BoostrapModalManager();
            }
        };

    }());



    $(document).ready(function() {

        var popupManager = PopupManager(),
            modalManager = BoostrapModalManager();

        $('.user-sidebar').mobilesidebar();

        var updateListView = function(id, filter) {

            var container = $('#' + id),
                filterForm = $('[data-list=' + id + ']'),
                modelClass = filterForm.data('model-class'),
                queryParams = {},
                location = window.location;

            // if (filter !== undefined) {
            //     for (var key in filter) {
            //         var value = filter[key];
            //         var input = $('input[name*=' + key + ']', filterForm);
            //         if ( input.is(':radio') ) {
            //             input.filter('[value="' + value + '"]').prop('selected', true);
            //         } else {
            //             input.val(value);
            //         }
            //         queryParams[modelClass + '[' + key + ']'] = value;
            //     }
            // } else {
            //     queryParams = filterForm.serialize();
            // }

            queryParams = filterForm.serialize();

            $.get(location, queryParams, function(res) {
                container.replaceWith(res);
            });
        };


        var updateUserCounters = function(counters) {
            if ( counters.points !== undefined ) {
                $('.user-points-container').text(counters.points);
            }
        };


        var saveTask = function(form) {
            var deferred = $.Deferred();
            $.post(form.attr('action'), form.serialize(), function(response) {
                if ( response.success ) {
                    updateUserCounters(response.userCounters);
                    updateListView('TaskList');
                    modalManager.close();
                    deferred.resolve(response);
                } else {
                    deferred.reject(response);
                }
            });
            return deferred.promise();
        };


        var checkAuth = function(serviceType) {
            return $.get('/user/social/is-auth', {
                serviceType: serviceType
            });
        };


        var goAuth = function(serviceType, serviceName, options) {
            var deferred = $.Deferred();
            popupManager.open('/auth/' + serviceName, {}, {
                width: options.popup.width,
                height: options.popup.height
            });

            $(document).on('closed.PopupManager', function() {
                checkAuth(serviceType).done(function() {
                    deferred.resolve();
                }).fail(function() {
                    deferred.reject();
                });
            });
            return deferred.promise();
        };


        var checkTaskTypeInputs = function() {

        };


        $(document).on('change', '.filter input', function(e) {
            var form = $(this).closest('form');
            updateListView(form.data('list'));
        });


        $(document).on('change', 'input[name*=service_type]', function(e) {
            var form = $(this).closest('form'),
                selectedServiceType = $(this).val(),
                associations = $(this).closest('.custom-radio-list').data('service-type-associations'),
                task_types = associations[selectedServiceType],
                taskTypeInputs = $('input[name*=task_type]', form);

            taskTypeInputs.closest('.custom-radio').hide();
            taskTypeInputs.filter(':checked').prop('checked', false);
            for ( var taskType in task_types ) {
                taskTypeInputs.filter('[value=' + taskType + ']').closest('.custom-radio').show();
            }
        });


        $(document).on('click', '.show-modal-btn', function(e) {
            e.preventDefault();
            var thisBtn = $(this),
                src = thisBtn.attr('value') ? thisBtn.attr('value') : thisBtn.attr('href'),
                title = thisBtn.attr('title') ? thisBtn.attr('title') : thisBtn.text(),
                modal = modalManager.open(src, title);

            modal.on('loaded.bs.modal', function(e) {
                var taskForm = $(e.target).find('form#user-task-form');
                if ( !taskForm.size() ) {
                    return;
                }

                taskForm.on('beforeSubmit', function(e) {
                    var serviceType = taskForm.find('input[name*=service_type]:checked').val();
                    checkAuth(serviceType).done(function(response) {
                        if ( response.authenticated ) {
                            saveTask(taskForm);
                        } else {
                            bootbox.confirm('Войти через соцсеть?', function(result) {
                                if ( !result ) return false;
                                goAuth(serviceType, response.serviceName, response.jsArguments).done(function() {
                                    saveTask(taskForm);
                                }).fail(function() {
                                    alert('WTF!!!');
                                });
                            });
                        }
                    }).fail(function(error) {
                        console.error(error);
                    });
                    return false;
                });
            });

            e.stopPropagation();
        });


        var likesManager = new LikesManager();
        likesManager.init();
    });



}) (jQuery);
