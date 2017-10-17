

(function($) {

    var LikesManager = function() {



        this.init = function() {
            $(document).on('click', 'a.do-task', function(e) {
                e.preventDefault();



                e.stopPropagation();
            });
        };

        $(document).on('click', 'a.check-task', function(e) {
            e.preventDefault();
            var $this = $(this),
                $taskItem = $this.closest('.task-item'),
                $doButton = $('[type=button].do-task', $taskItem),
                $checkButton = $('[type=button].check-task', $taskItem),
                taskId = $taskItem.data('id'),
                serviceType = $taskItem.data('service_type');

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




    var Task = function(id, service_type, source_url) {

        var _self = this,
            _check_url = '/user/tasks/check',
            _popupOptions = {
                width: 900,
                height: 600
            },
            _popupManager = PopupManager();

        this.id = id;
        this.serviceType = service_type;
        this.url = source_url;
        this.taskContainer = $('.task-item[data-id=' + this.id +']');
        this.doButton = $('[type=button].do-task', this.taskContainer);
        this.checkButton = $('[type=button].check-task', this.taskContainer);

        this.check = function() {

            var checkDone = function() {
                _self.taskContainer.closest('[data-key]').delay(500).fadeOut(500);
            };

            var checkFail = function() {
                if ( device.mobile() ) {
                    _self.doButton.css({display: 'none'});
                    _self.checkButton.css({display: 'inline-block'});
                }
            };

            if ( ! _popupManager.isClosed() ) {
                _popupManager.close();
            }


            _popupManager.open(_self.url, {}, _popupOptions);
            _popupManager.on('closed.PopupManager', function(e) {
                _popupManager.off('closed.PopupManager');

                $.post(_check_url, {id: _self.id}).done(function(data) {
                    console.log(data);
                });
            });


            // _checkTask(taskId, checkDone, checkFail);
            // $.post(_check_url, {
            //     id: _self.id
            // }).done(function(data) {
            //     var notifyType = 'success';
            //     if ( data.done ) {
            //         updateUserCounters({points: data.user_points});
            //     } else {
            //         notifyType = 'danger';
            //         if ( errorCallback ) {
            //             errorCallback.call(null, data);
            //         }
            //     }
            //     $.notify({
            //         message: data.message
            //     },{
            //         type: notifyType
            //     });
            // }).fail(function(error) {
            //     console.error(error);
            // });
            //
            // _task_popup = window.open($this.attr('href'), "task_popup", "width=" + popupWidth + ",height=" + popupHeight + ",left=" + centerWidth + ",top=" + centerHeight + ",resizable=yes,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=yes");
            // _task_popup.focus();
            //
            // _task_timer = setInterval(function() {
            //     if ( _task_popup.closed ) {
            //         clearInterval(_task_timer);
            //         _checkTask(taskId, checkDone, checkFail);
            //     }
            // }, 250);
        };


        return this;
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
                        $(document).trigger($.Event('closed.PopupManager'), [popup]);
                    }

                    popup = window.open(url, "Popup Manager",
                        "width=" + currentOptions.width +
                        ",height=" + currentOptions.height +
                        ",left=" + centerWidth +
                        ",top=" + centerHeight +
                        ",resizable=yes,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=yes");

                    timer = setInterval(function() {
                        if ( popup.closed ) {
                            clearInterval(timer);
                            $(document).trigger($.Event('closed.PopupManager'), [popup]);
                        }
                    }, 250);

                    $(document).trigger($.Event('opened.PopupManager'), [popup]);
                    popup.focus();
                };


                this.close = function() {
                    popup.close();
                };


                this.isClosed = function() {
                    if ( popup ) return popup.closed;
                    return true;
                };


                this.on = function(event, handler) {
                    $(document).on(event, handler);
                };

                this.off = function(event) {
                    $(document).off(event);
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


    var updateListView = function(id, filter) {
        var container = $('.list-view-container').first(),
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

        return $.get(location, queryParams, function(res) {
            container.html(res);
        });
    };


    var updateProfileData = function() {
        $.get('/user/profile/current', function(res) {
            var account = $('.user-sidebar .account');
            $('.avatar', account).attr('src', res.avatar);
            $('.name', account).text(res.name);
        });
    };


    var updateUserCounters = function(counters) {
        if ( counters.points !== undefined ) {
            $('.user-points-container').text(counters.points);
        }
    };


    var saveTask = function(form) {
        var deferred = $.Deferred(),
            submitButton = $('[type=submit]', form);

        submitButton.button('loading');
        $.post(form.attr('action'), form.serialize(), function(response) {
            if ( response.success ) {
                updateUserCounters(response.userCounters);
                updateListView('TaskList');
                deferred.resolve(response);
                form.trigger('reset');
                submitButton.button('reset');
            } else {
                deferred.reject(response);
                submitButton.button('reset');
            }
        });
        return deferred.promise();
    };


    var checkAuth = function(serviceType) {

        console.log(this);

        var deferred = $.Deferred();
        $.get('/user/social/is-auth', {
            serviceType: serviceType
        }).done(function(response) {
            deferred.resolve(response);
        }).fail(function(e) {
            deferred.reject(e);
        });
        return deferred.promise();
    };


    var goAuth = function(serviceType, serviceName, options) {
        var deferred = $.Deferred(),
            popupManager = PopupManager();

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


    var prepareSignedAction = function(serviceType) {

        var deferred = $.Deferred();

        checkAuth(serviceType).done(function(response) {
            if ( response.authenticated ) {
                deferred.resolve();
            } else {
                bootbox.confirm('Войти через соцсеть?', function(result) {
                    if ( !result ) {
                        deferred.reject();
                        return false;
                    }
                    goAuth(serviceType, response.serviceName, response.jsArguments).done(function() {
                        deferred.resolve();
                    }).fail(function() {
                        deferred.reject();
                    });
                });
            }
        }).fail(function(error) {
            console.error(error);
            deferred.reject();
        });

        return deferred.promise();

    };


    $(document).ready(function() {


        $("#social-carousel").Cloud9Carousel( {
            bringToFront: true,
            speed: 1,
            autoPlay: true,
            autoPlayDelay: 4000,
            mirror: {
                gap: 12,     /* 12 pixel gap between item and reflection */
                height: 0.2, /* 20% of item height */
                opacity: 0.4 /* 40% opacity at the top */
            }
        } );


        $('.user-sidebar').mobilesidebar();

        $(document).on('change', '.filter input', function(e) {
            var form = $(this).closest('form');
            updateListView(form.data('list')).done(function() {
                updateProfileData();
            });
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


        $('#user-task-form').on('beforeSubmit', function(e) {
            var taskForm = $(this),
                serviceType = taskForm.find('input[name*=service_type]:checked').val();

            checkAuth(serviceType).done(function(response) {
                if ( response.authenticated ) {
                    saveTask(taskForm);
                } else {
                    $('#modal-auth').modal();
                }
            }).fail(function(error) {
                console.error(error);
            });
            return false;
        });


        // $(document).on('click', '.show-modal-btn', function(e) {
        //     e.preventDefault();
        //     var thisBtn = $(this),
        //         src = thisBtn.attr('value') ? thisBtn.attr('value') : thisBtn.attr('href'),
        //         title = thisBtn.attr('title') ? thisBtn.attr('title') : thisBtn.text(),
        //         modal = modalManager.open(src, title);
        //
        //     // modal.on('loaded.bs.modal', function(e) {
        //     //     var taskForm = $(e.target).find('form#user-task-form');
        //     //     if ( !taskForm.size() ) {
        //     //         return;
        //     //     }
        //     //
        //     //     taskForm.on('beforeSubmit', function(e) {
        //     //         var serviceType = taskForm.find('input[name*=service_type]:checked').val();
        //     //         checkAuth(serviceType).done(function(response) {
        //     //             if ( response.authenticated ) {
        //     //                 saveTask(taskForm);
        //     //             } else {
        //     //                 bootbox.confirm('Войти через соцсеть?', function(result) {
        //     //                     if ( !result ) return false;
        //     //                     goAuth(serviceType, response.serviceName, response.jsArguments).done(function() {
        //     //                         saveTask(taskForm);
        //     //                     }).fail(function() {
        //     //                         alert('WTF!!!');
        //     //                     });
        //     //                 });
        //     //             }
        //     //         }).fail(function(error) {
        //     //             console.error(error);
        //     //         });
        //     //         return false;
        //     //     });
        //     // });
        //
        //     e.stopPropagation();
        // });


        // var likesManager = new LikesManager();
        // likesManager.init();


        $(document).on('click', '.do-task', function(e) {
            e.preventDefault();
            var cont = $(this).closest('.task-item');

            var task = new Task(
                cont.data('id'),
                cont.data('service_type'),
                $(this).attr('href'));
            task.check();
            e.stopPropagation();
        });
    });



}) (jQuery);
