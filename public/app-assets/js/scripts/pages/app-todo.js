/*=========================================================================================
    File Name: app-todo.js
    Description: app-todo
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

'use strict';

$(function () {
    var taskTitle,
        selectedTags,
        textDate,
        textDesc,
        textBadge,
        favoriteStarImp = 0,
        update_id = 0,
        flatPickr = $('.task-due-date'),
        newTaskModal = $('.sidebar-todo-modal'),
        newTaskForm = $('#form-modal-todo'),
        favoriteStar = $('.todo-item-favorite'),
        modalTitle = $('.modal-title'),
        addBtn = $('.add-todo-item'),
        addTaskBtn = $('.add-task button'),
        updateTodoItem = $('.update-todo-item'),
        updateBtns = $('.update-btn'),
        taskDesc = $('#task-desc'),
        taskAssignSelect = $('#task-assigned'),
        taskTag = $('#task-tag'),
        overlay = $('.body-content-overlay'),
        menuToggle = $('.menu-toggle'),
        sidebarToggle = $('.sidebar-toggle'),
        sidebarLeft = $('.sidebar-left'),
        sidebarMenuList = $('.sidebar-menu-list'),
        todoFilter = $('#todo-search'),
        sortAsc = $('.sort-asc'),
        sortDesc = $('.sort-desc'),
        todoTaskList = $('.todo-task-list'),
        todoTaskListWrapper = $('.todo-task-list-wrapper'),
        listItemFilter = $('.list-group-filters'),
        noResults = $('.no-results'),
        checkboxId = 100,
        isRtl = $('html').attr('data-textdirection') === 'rtl';

    var assetPath = '../../../app-assets/';
    if ($('body').attr('data-framework') === 'laravel') {
        assetPath = $('body').attr('data-asset-path');
    }

    // if it is not touch device
    if (!$.app.menu.is_touch_device()) {
        if (sidebarMenuList.length > 0) {
            var sidebarListScrollbar = new PerfectScrollbar(sidebarMenuList[0], {
                theme: 'dark'
            });
        }
        if (todoTaskListWrapper.length > 0) {
            var taskListScrollbar = new PerfectScrollbar(todoTaskListWrapper[0], {
                theme: 'dark'
            });
        }
    }
    // if it is a touch device
    else {
        sidebarMenuList.css('overflow', 'scroll');
        todoTaskListWrapper.css('overflow', 'scroll');
    }

    // Add class active on click of sidebar filters list
    if (listItemFilter.length) {
        listItemFilter.find('a').on('click', function () {
            if (listItemFilter.find('a').hasClass('active')) {
                listItemFilter.find('a').removeClass('active');
            }
            $(this).addClass('active');
        });
    }

    // Init D'n'D
    var dndContainer = document.getElementById('todo-task-list');
    if (typeof dndContainer !== undefined && dndContainer !== null) {
        dragula([dndContainer], {
            moves: function (el, container, handle) {
                return handle.classList.contains('drag-icon');
            }
        });
    }

    // Main menu toggle should hide app menu
    if (menuToggle.length) {
        menuToggle.on('click', function (e) {
            sidebarLeft.removeClass('show');
            overlay.removeClass('show');
        });
    }

    // Todo sidebar toggle
    if (sidebarToggle.length) {
        sidebarToggle.on('click', function (e) {
            e.stopPropagation();
            sidebarLeft.toggleClass('show');
            overlay.addClass('show');
        });
    }

    // On Overlay Click
    if (overlay.length) {
        overlay.on('click', function (e) {
            sidebarLeft.removeClass('show');
            overlay.removeClass('show');
            $(newTaskModal).modal('hide');
        });
    }

    // Assign task
    function assignTask(option) {
        if (!option.id) {
            return option.text;
        }
        var $person =
            '<div class="media align-items-center">' +
            '<img class="d-block rounded-circle mr-50" src="' +
            $(option.element).data('img') +
            '" height="26" width="26" alt="' +
            option.text +
            '">' +
            '<div class="media-body"><p class="mb-0">' +
            option.text +
            '</p></div></div>';

        return $person;
    }

    // Task Assign Select2
    if (taskAssignSelect.length) {
        taskAssignSelect.wrap('<div class="position-relative"></div>');
        taskAssignSelect.select2({
            placeholder: 'Unassigned',
            dropdownParent: taskAssignSelect.parent(),
            templateResult: assignTask,
            templateSelection: assignTask,
            escapeMarkup: function (es) {
                return es;
            }
        });
    }

    // Task Tags
    if (taskTag.length) {
        taskTag.wrap('<div class="position-relative"></div>');
        taskTag.select2({
            placeholder: 'Select tag'
        });
    }

    // Favorite star click
    if (favoriteStar.length) {
        $(favoriteStar).on('click', function () {
            $(this).toggleClass('text-warning');
            favoriteStarImp = 1;
        });
    }

    // Flat Picker
    if (flatPickr.length) {
        flatPickr.flatpickr({
            // dateFormat: 'Y-m-d',
            dateFormat: 'm-d-Y',
            defaultDate: 'today',
            onReady: function (selectedDates, dateStr, instance) {
                if (instance.isMobile) {
                    $(instance.mobileInput).attr('step', null);
                }
            }
        });
    }

    // Todo Description Editor
    if (taskDesc.length) {
        var todoDescEditor = new Quill('#task-desc', {
            bounds: '#task-desc',
            modules: {
                formula: true,
                syntax: true,
                toolbar: '.desc-toolbar'
            },
            placeholder: 'Write Your Description',
            theme: 'snow'
        });
    }

    // On add new item button click, clear sidebar-right field fields
    if (addTaskBtn.length) {
        addTaskBtn.on('click', function (e) {
            addBtn.removeClass('d-none');
            updateBtns.addClass('d-none');
            modalTitle.text('Add Task');
            $('.todo-item-favorite').attr('task-id', '0');
            $('.todo-item-favorite').removeClass('text-warning');
            // flatPickr.val('');
            taskTag.val('').trigger('change');
            // newTaskModal.modal('show');
            sidebarLeft.removeClass('show');
            overlay.removeClass('show');
            newTaskModal.find('.new-todo-item-title').val('');
            var quill_editor = taskDesc.find('.ql-editor');
            quill_editor[0].innerHTML = '';
        });
    }

    // Add New ToDo List Item

    // To add new todo form
    if (newTaskForm.length) {
        newTaskForm.validate({
            ignore: '.ql-container *', // ? ignoring quill editor icon click, that was creating console error
            rules: {
                todoTitleAdd: {
                    required: true
                },
                'task-assigned': {
                    required: false
                },
                'task-due-date': {
                    required: true
                }
            }
        });

        newTaskForm.on('submit', function (e) {
            e.preventDefault();
            var isValid = newTaskForm.valid();
            if (isValid) {
                checkboxId++;
                var assignedTo = $('#task-assigned').val(),
                    todoBadge = '',
                    membersImg = {
                        'Phill Buffer': assetPath + 'images/portrait/small/avatar-s-3.jpg',
                        'Chandler Bing': assetPath + 'images/portrait/small/avatar-s-1.jpg',
                        'Ross Geller': assetPath + 'images/portrait/small/avatar-s-4.jpg',
                        'Monica Geller': assetPath + 'images/portrait/small/avatar-s-6.jpg',
                        'Joey Tribbiani': assetPath + 'images/portrait/small/avatar-s-2.jpg',
                        'Rachel Green': assetPath + 'images/portrait/small/avatar-s-11.jpg'
                    };

                var todoTitle = $('.sidebar-todo-modal .new-todo-item-title').val();



                var date1 = $('.sidebar-todo-modal .task-due-date').val();
                // var date = "2021-05-29";

                // var d = new Date(date1);

                // var date = new Date(date1);
                // var newDate = date.toString('dd-MM-yy');


                var dateAr = date1.split('-');
                console.log(dateAr);
                // var newDate = dateAr[1] + '-' + dateAr[2] + '-' + dateAr[0].slice(-2);
                var newDate = dateAr[2] + '-' + dateAr[0] + '-' + dateAr[1];

                // console.log("date: " + date1);
                // console.log("newDate: " + newDate);

                var date = newDate,
                    selectedDate = new Date(date),
                    month = new Intl.DateTimeFormat('en', { month: 'short' }).format(selectedDate),
                    day = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(selectedDate),
                    todoDate = month + ' ' + day;

                // Badge calculation loop
                var selected = $('.task-tag').val();


                var badgeColor = {
                    Team: 'primary',
                    Low: 'success',
                    Medium: 'warning',
                    High: 'danger',
                    Update: 'info'
                };
                $.each(selected, function (index, value) {
                    todoBadge += '<div class="badge badge-pill badge-light-' + badgeColor[value] + ' mr-50">' + value + '</div>';
                });
                // HTML Output
                if (todoTitle != '') {
                    $(todoTaskList).prepend(
                        '<li class="todo-item">' +
                        '<div class="todo-title-wrapper">' +
                        '<div class="todo-title-area">' +
                        feather.icons['more-vertical'].toSvg({ class: 'drag-icon' }) +
                        '<div class="title-wrapper">' +
                        '<div class="custom-control custom-checkbox">' +
                        '<input value="' + checkboxId + '" type="checkbox" class="custom-control-input" id="customCheck' +
                        checkboxId +
                        '" />' +
                        '<label class="custom-control-label" for="customCheck' +
                        checkboxId +
                        '"></label>' +
                        '</div>' +
                        '<span class="todo-title">' +
                        todoTitle +
                        '</span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="todo-item-action">' +
                        '<div class="badge-wrapper mr-1">' +
                        todoBadge +
                        '</div>' +
                        '<small class="text-nowrap text-muted mr-1">' +
                        todoDate +
                        '</small>' +
                        '</div>' +
                        '</div>' +
                        '</li>'
                    );
                }

                /*--------------------------------------------------------*/
                /*--------------------------------------------------------*/
                var quill_editor = taskDesc.find('.ql-editor');
                // quill_editor[0].innerHTML = '';
                var taskData = {
                    favoriteStar: favoriteStarImp,
                    assignedTo: assignedTo,
                    todoTitle: todoTitle,
                    date: date,
                    selected: selected,
                    taskDesc: quill_editor[0].innerHTML
                };
                saveDbTask(taskData);
                // console.log(taskData);
                /*--------------------------------------------------------*/
                /*--------------------------------------------------------*/
                toastr['success']('Data Saved', '💾 Task Action!', {
                    closeButton: true,
                    tapToDismiss: false,
                    rtl: isRtl
                });
                $(newTaskModal).modal('hide');
                overlay.removeClass('show');
            }
        });
    }

    // Task checkbox change
    todoTaskListWrapper.on('change', '.custom-checkbox', function (event) {
        var $this = $(this).find('input');
        var task_id = $(this).find('input').val();
        var task_status = 0;
        if ($this.prop('checked')) {
            task_status = 1;
            $this.closest('.todo-item').addClass('completed');
            toastr['success']('Task Completed', 'Congratulations!! 🎉', {
                closeButton: true,
                tapToDismiss: false,
                rtl: isRtl
            });
        } else {
            $this.closest('.todo-item').removeClass('completed');
        }
        var active_tap = $('.sidebar-menu-list').find('a.active').attr('data-filter-name');
        // console.log(active_tap);
        if (active_tap == 'My Task' || active_tap == 'Important' || active_tap == 'Deleted') {
        } else {
            $('#customCheck' + task_id).closest('.todo-item').remove();
        }
        taskCompleted(task_id, task_status);
    });
    todoTaskListWrapper.on('click', '.custom-checkbox', function (event) {
        event.stopPropagation();
    });

    $(document).on('click', '.complete-todo-item', function (e) {
        var task_id = $(this).attr('task-id');
        var task_status = $(this).attr('task-status');
        taskCompleted(task_id, task_status);

        if (task_status == 0) {
            $('#customCheck' + task_id).prop('checked', false);
            $('#customCheck' + task_id).closest('.todo-item').removeClass('completed');
        } else {
            $('#customCheck' + task_id).prop('checked', true);
            $('#customCheck' + task_id).closest('.todo-item').addClass('completed');
        }
        newTaskModal.modal('hide');
    });

    $(document).on('click', '.todo-item-favorite', function (e) {
        var task_id = $(this).attr('task-id');
        var fav_status = $(this).attr('status');

        // console.log(task_id);
        // console.log(fav_status);
        if (task_id != 0) {
            var active_tap = $('.sidebar-menu-list').find('a.active').attr('data-filter-name')
            if (active_tap == 'My Task' || active_tap == 'Completed') {
            } else {
                $('#customCheck' + task_id).closest('.todo-item').remove();
            }
            markFavorite(task_id, fav_status);
            newTaskModal.modal('hide');
        }

    });

    $(document).on('click', '.todo_filter', function (e) {
        $('.todo_filter').removeClass('active');
        $(this).addClass('active');
        var filter_name = $(this).attr('data-filter-name');
        var filter_val = $(this).attr('data-filter-val');

        // console.log(filter_name);
        // console.log(filter_val);
        filterTask(filter_name, filter_val);
    });

    $(document).on('click', '.del_btn', function (e) {
        var id = $(this).attr('data-id');
        var del_status = $(this).attr('del-status');
        var msg_text = 'Are you sure you want to delete?';
        if (del_status == 0) {
            msg_text = 'Do you want to undelete this task?';
        }
        if (confirm(msg_text)) {
            $('#customCheck' + id).closest('.todo-item').remove();
            deleteTask(id, del_status);
        }
        newTaskModal.modal('hide');

    });

    // To open todo list item modal on click of item
    $(document).on('click', '.todo-task-list-wrapper .todo-item', function (e) {
        newTaskModal.modal('show');
        addBtn.addClass('d-none');
        updateBtns.removeClass('d-none');
        update_id = $(this).find('.update_id').html();


        var del = $(this).find('.del_val').html();
        if (del == 0) {
            // $(this).find('.del_val').html('1');
            $('.del_btn').attr('data-id', update_id);
            $('.del_btn').attr('del-status', 1);
            $('.del_btn').html('Delete');
        } else {
            // $(this).find('.del_val').html('0');
            $('.del_btn').attr('data-id', update_id);
            $('.del_btn').attr('del-status', 0);
            $('.del_btn').html('Restore');
        }


        var fav = $(this).find('.important_val').html();
        if (fav == 0) {
            $(this).find('.important_val').html('1');
            newTaskModal.find('.todo-item-favorite').removeClass('text-warning');
            newTaskModal.find('.todo-item-favorite').attr('status', '1');
            newTaskModal.find('.todo-item-favorite').attr('task-id', update_id);
        } else {
            $(this).find('.important_val').html('0');
            newTaskModal.find('.todo-item-favorite').addClass('text-warning');
            newTaskModal.find('.todo-item-favorite').attr('status', '0');
            newTaskModal.find('.todo-item-favorite').attr('task-id', update_id);
        }

        if ($(this).hasClass('completed')) {
            modalTitle.html(
                '<button type="button" task-status="0" task-id="' + update_id + '" class="btn btn-sm btn-outline-success complete-todo-item waves-effect waves-float waves-light">Mark Uncomplete</button>'
            );
        } else {
            // data-dismiss="modal"
            modalTitle.html(
                '<button type="button" task-status="1" task-id="' + update_id + '" class="btn btn-sm btn-outline-secondary complete-todo-item waves-effect waves-float waves-light">Mark Complete</button>'
            );
        }
        /*-------------------------------------------------------------*/
        /*-------------------------------------------------------------*/
        var selectedtaskTag = $(this).find('.selected_tags').html();
        // console.log(selectedtaskTag);
        var selectedtaskTag = selectedtaskTag.split(",");
        var tags = '['
        for (var j = 0; j < selectedtaskTag.length; j++) {
            tags += '"' + selectedtaskTag[j] + '",';
        }
        tags = tags.slice(0, -1)
        tags += ']';
        tags = JSON.parse(tags);
        // console.log(tags);
        taskTag.val(tags).trigger('change');
        // taskTag.val(['Low', 'Update']).trigger('change');
        /*-------------------------------------------------------------*/
        /*-------------------------------------------------------------*/
        // taskTag.val('').trigger('change');
        var quill_editor = $('#task-desc .ql-editor'); // ? Dummy data as not connected with API or anything else

        // console.log(quill_editor[0].innerHTML);
        // console.log(taskDesc.html('<p>sdasdsad</p>'));

        // quill_editor[0].innerHTML = 'Chocolate cake topping bonbon jujubes donut sweet wafer. Marzipan gingerbread powder brownie bear claw. Chocolate bonbon sesame snaps jelly caramels oat cake.';
        quill_editor[0].innerHTML = $(this).find('.text_desc').html();
        // taskDesc.html = quill_editor[0].innerHTML;
        // taskDesc.html(quill_editor[0].innerHTML)
        // console.log($(this).find('.text_desc').html());
        taskTitle = $(this).find('.todo-title');
        selectedTags = $(this).find('.selected_tags');
        textDate = $(this).find('.text-muted');
        textDesc = $(this).find('.text_desc');
        textBadge = $(this).find('.text_badge');
        var $title = $(this).find('.todo-title').html();

        // apply all variable values to fields
        newTaskForm.find('.new-todo-item-title').val($title);
    });

    // Updating Data Values to Fields
    if (updateTodoItem.length) {
        updateTodoItem.on('click', function (e) {
            var isValid = newTaskForm.valid();
            e.preventDefault();
            /*if (isValid) {
                var $edit_title = newTaskForm.find('.new-todo-item-title').val();
                $(taskTitle).text($edit_title);

                toastr['success']('Data Saved', '💾 Task Action!', {
                    closeButton: true,
                    tapToDismiss: false,
                    rtl: isRtl
                });
                $(newTaskModal).modal('hide');
            }*/


            if (isValid) {
                checkboxId++;
                var assignedTo = $('#task-assigned').val(),
                    todoBadge = '',
                    membersImg = {
                        'Phill Buffer': assetPath + 'images/portrait/small/avatar-s-3.jpg',
                        'Chandler Bing': assetPath + 'images/portrait/small/avatar-s-1.jpg',
                        'Ross Geller': assetPath + 'images/portrait/small/avatar-s-4.jpg',
                        'Monica Geller': assetPath + 'images/portrait/small/avatar-s-6.jpg',
                        'Joey Tribbiani': assetPath + 'images/portrait/small/avatar-s-2.jpg',
                        'Rachel Green': assetPath + 'images/portrait/small/avatar-s-11.jpg'
                    };

                // var todoTitle = $('.sidebar-todo-modal .new-todo-item-title').val();
                var todoTitle = newTaskForm.find('.new-todo-item-title').val();
                var todoDate = newTaskForm.find('.task-due-date').val();
                // var todoDesc = newTaskForm.find('.new-todo-item-title').val();
                var todoselectedTags = newTaskForm.find('.task-tag').val();

                var quill_editor = taskDesc.find('.ql-editor');
                var todoDesc = quill_editor[0].innerHTML;

                // console.log(quill_editor);
                // console.log(quill_editor[0].innerHTML);
                // console.log(todoselectedTags);



                var date1 = todoDate;
                var dateAr = date1.split('-');
                var newDate = dateAr[2] + '-' + dateAr[0] + '-' + dateAr[1];
                todoDate = newDate;




                var dateObj = new Date(todoDate);
                var month = ('0' + (dateObj.getMonth() + 1)).slice(-2);
                var day = ('0' + dateObj.getDate()).slice(-2);
                var year = dateObj.getFullYear();
                var monthName = dateObj.toLocaleString('default', { month: 'long' });
                // console.log(dateObj);
                // console.log(" month: " + month);
                // console.log(" day: " + day);
                // console.log(" year: " + year);
                var todoDateShow = monthName + ' ' + day;


                $(taskTitle).text(todoTitle);
                $(selectedTags).text(todoselectedTags);
                // $(textDate).text(todoDate);
                $(textDate).text(todoDateShow);
                $(textDesc).text(todoDesc);

                var badgeColor = {
                    Team: 'primary',
                    Low: 'success',
                    Medium: 'warning',
                    High: 'danger',
                    Update: 'info'
                };

                $.each(todoselectedTags, function (index, value) {
                    todoBadge += '<div class="badge badge-pill badge-light-' + badgeColor[value] + ' mr-50">' + value + '</div>';
                });
                $(textBadge).html(todoBadge);

                /*--------------------------------------------------------*/
                /*--------------------------------------------------------*/
                var taskData = {
                    update_id: update_id,
                    favoriteStar: favoriteStarImp,
                    assignedTo: assignedTo,
                    todoTitle: todoTitle,
                    date: todoDate,
                    selected: todoselectedTags,
                    taskDesc: todoDesc
                };
                // console.log(taskData);
                updateDbTask(taskData, update_id);
                /*--------------------------------------------------------*/
                /*--------------------------------------------------------*/


                // console.log(todoBadge);
                // HTML Output
                /*if (todoTitle != '') {
                    $(todoTaskList).prepend(
                        '<li class="todo-item">' +
                        '<div class="todo-title-wrapper">' +
                        '<div class="todo-title-area">' +
                        feather.icons['more-vertical'].toSvg({class: 'drag-icon'}) +
                        '<div class="title-wrapper">' +
                        '<div class="custom-control custom-checkbox">' +
                        '<input type="checkbox" class="custom-control-input" id="customCheck' +
                        checkboxId +
                        '" />' +
                        '<label class="custom-control-label" for="customCheck' +
                        checkboxId +
                        '"></label>' +
                        '</div>' +
                        '<span class="todo-title">' +
                        todoTitle +
                        '</span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="todo-item-action">' +
                        '<div class="badge-wrapper mr-1">' +
                        todoBadge +
                        '</div>' +
                        '<small class="text-nowrap text-muted mr-1">' +
                        todoDate +
                        '</small>' +
                        '</div>' +
                        '</div>' +
                        '</li>'
                    );
                }*/
                toastr['success']('Data updated', '💾 Task Action!', {
                    closeButton: true,
                    tapToDismiss: false,
                    rtl: isRtl
                });
                $(newTaskModal).modal('hide');
                overlay.removeClass('show');
                window.location.reload();
            }


        });
    }

    // Sort Ascending
    if (sortAsc.length) {
        sortAsc.on('click', function () {
            todoTaskListWrapper
                .find('li')
                .sort(function (a, b) {
                    return $(b).find('.todo-title').text().toUpperCase() < $(a).find('.todo-title').text().toUpperCase() ? 1 : -1;
                })
                .appendTo(todoTaskList);
        });
    }
    // Sort Descending
    if (sortDesc.length) {
        sortDesc.on('click', function () {
            todoTaskListWrapper
                .find('li')
                .sort(function (a, b) {
                    return $(b).find('.todo-title').text().toUpperCase() > $(a).find('.todo-title').text().toUpperCase() ? 1 : -1;
                })
                .appendTo(todoTaskList);
        });
    }

    // Filter task
    if (todoFilter.length) {
        todoFilter.on('keyup', function () {
            var value = $(this).val().toLowerCase();
            if (value !== '') {
                $('.todo-item').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
                var tbl_row = $('.todo-item:visible').length; //here tbl_test is table name

                //Check if table has row or not
                if (tbl_row == 0) {
                    if (!$(noResults).hasClass('show')) {
                        $(noResults).addClass('show');
                    }
                } else {
                    $(noResults).removeClass('show');
                }
            } else {
                // If filter box is empty
                $('.todo-item').show();
                if ($(noResults).hasClass('show')) {
                    $(noResults).removeClass('show');
                }
            }
        });
    }

    // For chat sidebar on small screen
    if ($(window).width() > 992) {
        if (overlay.hasClass('show')) {
            overlay.removeClass('show');
        }
    }
});

$(window).on('resize', function () {
    // remove show classes from sidebar and overlay if size is > 992
    if ($(window).width() > 992) {
        if ($('.body-content-overlay').hasClass('show')) {
            $('.sidebar-left').removeClass('show');
            $('.body-content-overlay').removeClass('show');
            $('.sidebar-todo-modal').modal('hide');
        }
    }
});
