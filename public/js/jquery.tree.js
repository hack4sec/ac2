(function($) {
    $.fn.tree_structure = function(options) {
        var defaults = {
            'add_option': true,
            'edit_option': true,
            'delete_option': true,
            'confirm_before_delete': true,
            'animate_option': [true, 5],
            'fullwidth_option': false,
            'align_option': 'center',
            'draggable_option': true
        };
        return this.each(function() {
            if (options)
                $.extend(defaults, options);
            var add_option = defaults['add_option'];
            var edit_option = defaults['edit_option'];
            var delete_option = defaults['delete_option'];
            var confirm_before_delete = defaults['confirm_before_delete'];
            var animate_option = defaults['animate_option'];
            var fullwidth_option = defaults['fullwidth_option'];
            var align_option = defaults['align_option'];
            var draggable_option = defaults['draggable_option'];
            var vertical_line_text = '<span class="vertical"></span>';
            var horizontal_line_text = '<span class="horizontal"></span>';
            var add_action_text = add_option == true ? '<span class="add_action" title="Click for Add"></span>' : '';
            var edit_action_text = edit_option == true ? '<span class="edit_action" title="Click for Edit"></span>' : '';
            var delete_action_text = delete_option == true ? '<span class="delete_action" title="Click for Delete"></span>' : '';
            var highlight_text = '<span class="highlight" title="Click for Highlight | dblClick"></span>';
            var class_name = $(this).attr('class');
            var event_name = 'pageload';
            if (align_option != 'center')
                $('.' + class_name + ' li').css({'text-align': align_option});
            if (fullwidth_option) {
                var i = 0;
                var prev_width;
                var get_element;
                $('.' + class_name + ' li li').each(function() {
                    var this_width = $(this).width();
                    if (i == 0 || this_width > prev_width) {
                        prev_width = $(this).width();
                        get_element = $(this);
                    }
                    i++;
                });
                var loop = get_element.closest('ul').children('li').eq(0).nextAll().length;
                var fullwidth = parseInt(0);
                for ($i = 0; $i <= loop; $i++) {
                    fullwidth += parseInt(get_element.closest('ul').children('li').eq($i).width());
                }
                $('.' + class_name + '').closest('div').width(fullwidth);
            }
            $('.' + class_name + ' li.thide').each(function() {
                $(this).children('ul').hide();
            });
            function prepend_data(target) {
                target.prepend(vertical_line_text + horizontal_line_text).children('div').prepend(add_action_text + delete_action_text + edit_action_text);
                if (target.children('ul').length != 0)
                    target.hasClass('thide') ? target.children('div').prepend('<b class="thide tshow"></b>') : target.children('div').prepend('<b class="thide"></b>');
                target.children('div').prepend(highlight_text);
            }
            function draw_line(target) {
                var tree_offset_left = $('.' + class_name + '').offset().left;
                tree_offset_left = parseInt(tree_offset_left, 10);
                var child_width = target.children('div').outerWidth(true) / 2;
                var child_left = target.children('div').offset().left;
                if (target.parents('li').offset() != null)
                    var parent_child_height = target.parents('li').offset().top;
                vertical_height = (target.offset().top - parent_child_height) - target.parents('li').children('div').outerHeight(true) / 2;
                target.children('span.vertical').css({'height': vertical_height, 'margin-top': -vertical_height, 'margin-left': child_width, 'left': child_left - tree_offset_left});
                if (target.parents('li').offset() == null) {
                    var width = 0;
                } else {
                    var parents_width = target.parents('li').children('div').offset().left + (target.parents('li').children('div').width() / 2);
                    var current_width = child_left + (target.children('div').width() / 2);
                    var width = parents_width - current_width;
                }
                var horizontal_left_margin = width < 0 ? -Math.abs(width) + child_width : child_width;
                target.children('span.horizontal').css({'width': Math.abs(width), 'margin-top': -vertical_height, 'margin-left': horizontal_left_margin, 'left': child_left - tree_offset_left});
            }
            if (animate_option[0] == true) {
                function animate_call_structure() {
                    $timeout = setInterval(function() {
                        animate_li();
                    }, animate_option[1]);
                }
                var length = $('.' + class_name + ' li').length;
                var i = 0;
                function animate_li() {
                    prepend_data($('.' + class_name + ' li').eq(i));
                    draw_line($('.' + class_name + ' li').eq(i));
                    i++;
                    if (i == length) {
                        i = 0;
                        clearInterval($timeout);
                    }
                }
            }
            function call_structure() {
                $('.' + class_name + ' li').each(function() {
                    if (event_name == 'pageload')
                        prepend_data($(this));
                    draw_line($(this));
                });
            }
            animate_option[0] ? animate_call_structure() : call_structure();
            event_name = 'others';
            $(window).resize(function() {
                call_structure();
            });
            $(document).on("click", '.' + class_name + ' b.thide', function() {
                $(this).toggleClass('tshow');
                $(this).closest('li').toggleClass('thide').children('ul').toggle();
                call_structure();
            });
            $(document).on("hover", '.' + class_name + ' li > div', function(event) {
                if (event.type == 'mouseenter' || event.type == 'mouseover') {
                    $('.' + class_name + ' li > div.current').removeClass('current');
                    $('.' + class_name + ' li > div.children').removeClass('children');
                    $('.' + class_name + ' li > div.parent').removeClass('parent');
                    $(this).addClass('current');
                    $(this).closest('li').children('ul').children('li').children('div').addClass('children');
                    $(this).closest('li').closest('ul').closest('li').children('div').addClass('parent');
                    $(this).children('span.highlight, span.add_action, span.delete_action, span.edit_action').show();
                } else {
                    $(this).children('span.highlight, span.add_action, span.delete_action, span.edit_action').hide();
                }
            });
            $(document).on("click", '.' + class_name + ' span.highlight', function() {
                $('.' + class_name + ' li.highlight').removeClass('highlight');
                $('.' + class_name + ' li > div.parent').removeClass('parent');
                $('.' + class_name + ' li > div.children').removeClass('children');
                $(this).closest('li').addClass('highlight');
                $('.highlight li > div').addClass('children');
                var _this = $(this).closest('li').closest('ul').closest('li');
                find_parent(_this);
            });
            $(document).on("click", '.' + class_name + ' span.highlight', function() {
                if (fullwidth_option)
                    $('.' + class_name + '').parent('div').parent('div').scrollLeft(0);
                $('.' + class_name + ' li > div').not(".parent, .current, .children").closest('li').addClass('tnone');
                $('.' + class_name + ' li div b.thide.tshow').closest('div').closest('li').children('ul').addClass('tshow');
                $('.' + class_name + ' li div b.thide').addClass('tnone');
                if ($('.back_btn').length == 0) {
                    $('body').prepend('<img src="images/back.png" class="back_btn" />');
                }
                call_structure();
                $('.back_btn').click(function() {
                    $('.' + class_name + ' ul.tshow').removeClass('tshow');
                    $('.' + class_name + ' li.tnone').removeClass('tnone');
                    $('.' + class_name + ' li div b.thide').removeClass('tnone');
                    $(this).remove();
                    call_structure();
                });
            });
            function find_parent(_this) {
                if (_this.length > 0) {
                    _this.children('div').addClass('parent');
                    _this = _this.closest('li').closest('ul').closest('li');
                    return find_parent(_this);
                }
            }
            if (add_option) {
                $(document).on("click", '.' + class_name + ' span.add_action', function() {
                    if ($('form.add_data').length > 0)
                        $('form.add_data').remove();
                    if ($('form.edit_data').length > 0)
                        $('form.edit_data').remove();
                    var _this = $(this);
                    if (_this.closest('div').children('form.add_data').length == 0) {
                        var addquery = '<form class="add_data" method="post" action=""><img class="close" src="images/close.png" /><input type="text" class="first_name" name="first_name" placeholder="first name"><input type="checkbox" name="showhideval" id="hide" /><label for="hide">Hide Child Nodes</label><input type="submit" class="submit" name="submit" value="Submit"></form>';
                        _this.parent('div').append(addquery);
                        if ((_this.closest('div').children('form').offset().top + _this.closest('div').children('form').outerHeight()) > $(window).height()) {
                            _this.closest('div').children('form').css({'margin-top': -_this.closest('div').children('form').outerHeight()});
                        }
                        if ((_this.closest('div').children('form').offset().left + _this.closest('div').children('form').outerWidth()) > $(window).width()) {
                            _this.closest('div').children('form').css({'margin-left': -_this.closest('div').children('form').outerWidth()});
                        }
                        _this.closest('div').children('form').children('input.first_name').focus();
                        _this.closest('div').closest('li').closest('ul').children('li').children('div').addClass('zindex');
                    }
//                    var data = "action=addform";
//                    $.ajax({
//                        type: 'POST',
//                        url: 'ajax.php',
//                        data: data,
//                        success: function(data) {
//                            var addquery = data;
//                            
//                        }
//                    });
                    $(document).on("click", "input.submit", function(event) {
                        var _addthis = $(this);
                        var ajax_add_id;
                        event.preventDefault();
                        var parentid = _addthis.closest('div').attr('id');
//                        var data = "action=add&parentid=" + parentid + "&";
//                        data += _addthis.closest('form').serialize();
                        _addthis.closest("li").before("<img src='images/load.gif' class='load' />");
                        $(document).off("click", "input.submit");
                        ajax_add_id = 12345;
                        var html_value = '<li>' + vertical_line_text + horizontal_line_text + '<div id="' + ajax_add_id + '">' + highlight_text + add_action_text + delete_action_text + edit_action_text + "<span class='first_name'>" + _addthis.closest('form').find('input.first_name').val() + "</span>" + '</div></li>';
                        _addthis.closest('li').children('ul').length > 0 ? _addthis.closest('li').children('ul').append(html_value) : _addthis.closest('li').append('<ul>' + html_value + '</ul>');
                        _addthis.closest('form.add_data').closest('div').children('span.highlight, span.add_action, span.delete_action, span.edit_action').hide();
                        _addthis.closest('form.add_data').remove();
                        $('li > div.zindex').removeClass('zindex');
                        call_structure();
                        draggable_event();
                        $("img.load").remove();
//                        $.ajax({
//                            type: 'POST',
//                            url: 'ajax.php',
//                            data: data,
//                            success: function(data) {
//                                $(document).off("click", "input.submit");
//                                ajax_add_id = data;
//                                var html_value = '<li>' + vertical_line_text + horizontal_line_text + '<div id="' + ajax_add_id + '">' + highlight_text + add_action_text + delete_action_text + edit_action_text + "<span class='first_name'>" + _addthis.closest('form').find('input.first_name').val() + "</span>" + '</div></li>';
//                                _addthis.closest('li').children('ul').length > 0 ? _addthis.closest('li').children('ul').append(html_value) : _addthis.closest('li').append('<ul>' + html_value + '</ul>');
//                                _addthis.closest('form.add_data').closest('div').children('span.highlight, span.add_action, span.delete_action, span.edit_action').hide();
//                                _addthis.closest('form.add_data').remove();
//                                $('li > div.zindex').removeClass('zindex');
//                                call_structure();
//                                draggable_event();
//                                $("img.load").remove();
//                            }
//                        });
                    });
                    $(document).on("click", "img.close", function() {
                        $(this).closest('form.add_data').closest('div').children('span.highlight, span.add_action, span.delete_action, span.edit_action').hide();
                        $(this).closest('form.add_data').remove();
                        $('li > div.zindex').removeClass('zindex');
                    });
                });
            }
            if (edit_option) {
                $(document).on("click", '.' + class_name + ' span.edit_action', function() {
                    if ($('form.add_data').length > 0)
                        $('form.add_data').remove();
                    if ($('form.edit_data').length > 0)
                        $('form.edit_data').remove();
                    var edit_string = $(this).closest('div').clone();
                    if (edit_string.children('span.highlight').length > 0)
                        edit_string.children('span.highlight').remove();
                    if (edit_string.children('span.delete_action').length > 0)
                        edit_string.children('span.delete_action').remove();
                    if (edit_string.children('span.add_action').length > 0)
                        edit_string.children('span.add_action').remove();
                    if (edit_string.children('span.edit_action').length > 0)
                        edit_string.children('span.edit_action').remove();
                    if (edit_string.children('b.thide').length > 0)
                        edit_string.children('b.thide').remove();
                    var checked_val = $(this).closest('li').hasClass('thide') ? 'checked' : '';
                    var edit_ele_id = $(this).closest("div").attr("id");
                    var _this = $(this);
                    var editquery = '<form class="edit_data" method="post" action=""><img class="close" src="images/close.png" /><input type="text" class="first_name" name="first_name" value="" placeholder="first name"><input type="checkbox"  value="" name="showhideval" id="hide" /><label for="hide">Hide Child Nodes</label><input type="submit" class="edit" name="submit" value="submit"></form>';
                    if (_this.closest('div').children('form.edit_data').length == 0) {
                        _this.closest('div').append(editquery);
                        if ((_this.closest('div').children('form').offset().top + _this.closest('div').children('form').outerHeight()) > $(window).height()) {
                            _this.closest('div').children('form').css({'margin-top': -_this.closest('div').children('form').outerHeight()});
                        }
                        if ((_this.closest('div').children('form').offset().left + _this.closest('div').children('form').outerWidth()) > $(window).width()) {
                            _this.closest('div').children('form').css({'margin-left': -_this.closest('div').children('form').outerWidth()});
                        }
                        _this.closest('div').children('form').children('input.first_name').select();
                        _this.closest('div').closest('li').closest('ul').children('li').children('div').addClass('zindex');
                    }
//                    var data = "action=editform&edit_ele_id=" + edit_ele_id + "";
//                    $.ajax({
//                        type: 'POST',
//                        url: 'ajax.php',
//                        data: data,
//                        success: function(data) {
//                            var editquery = data;
//                            if (_this.closest('div').children('form.edit_data').length == 0) {
//                                _this.closest('div').append(editquery);
//                                if ((_this.closest('div').children('form').offset().top + _this.closest('div').children('form').outerHeight()) > $(window).height()) {
//                                    _this.closest('div').children('form').css({'margin-top': -_this.closest('div').children('form').outerHeight()});
//                                }
//                                if ((_this.closest('div').children('form').offset().left + _this.closest('div').children('form').outerWidth()) > $(window).width()) {
//                                    _this.closest('div').children('form').css({'margin-left': -_this.closest('div').children('form').outerWidth()});
//                                }
//                                _this.closest('div').children('form').children('input.first_name').select();
//                                _this.closest('div').closest('li').closest('ul').children('li').children('div').addClass('zindex');
//                            }
//                        }
//                    });
                    $(document).on("click", "input.edit", function(event) {
                        var _editthis = $(this);
                        event.preventDefault();
//                        var data = "action=edit&id=" + _editthis.closest('div').attr('id') + "&";
//                        data += _editthis.closest('form').serialize();
                        _editthis.closest("li").before("<img src='images/load.gif' class='load' />");
                        $(document).off("click", "input.edit");
                        if (_editthis.closest('form').find('input:checked').length > 0) {
                            if (_editthis.closest('li').hasClass('thide') == false) {
                                _editthis.closest('div').find('b.thide').trigger('click');
                            }
                        } else {
                            if (_editthis.closest('li').hasClass('thide')) {
                                _editthis.closest('div').find('b.thide').trigger('click');
                            }
                        }
                        var element_target = _editthis.closest('form.edit_data').closest('div');
                        var edit_html = "";
                        edit_html += "<span class='first_name'>" + _editthis.closest('form').find('input.first_name').val() + "</span>";
                        element_target.children('span.edit_action').nextAll().remove();
                        if (element_target.text().length > 0)
                            element_target.html(element_target.html().replace(element_target.text(), ''));
                        element_target.append(edit_html);
                        element_target.children('span.highlight, span.add_action, span.delete_action, span.edit_action').hide();
                        $('li > div.zindex').removeClass('zindex');
                        call_structure();
                        $("img.load").remove();
//                        $.ajax({
//                            type: 'POST',
//                            url: 'ajax.php',
//                            data: data,
//                            success: function(data) {
//                                $(document).off("click", "input.edit");
//                                if (_editthis.closest('form').find('input:checked').length > 0) {
//                                    if (_editthis.closest('li').hasClass('thide') == false) {
//                                        _editthis.closest('div').find('b.thide').trigger('click');
//                                    }
//                                } else {
//                                    if (_editthis.closest('li').hasClass('thide')) {
//                                        _editthis.closest('div').find('b.thide').trigger('click');
//                                    }
//                                }
//                                var element_target = _editthis.closest('form.edit_data').closest('div');
//                                var edit_html = "";
//                                edit_html += "<span class='first_name'>" + _editthis.closest('form').find('input.first_name').val() + "</span>";
//                                element_target.children('span.edit_action').nextAll().remove();
//                                if (element_target.text().length > 0)
//                                    element_target.html(element_target.html().replace(element_target.text(), ''));
//                                element_target.append(edit_html);
//                                element_target.children('span.highlight, span.add_action, span.delete_action, span.edit_action').hide();
//                                $('li > div.zindex').removeClass('zindex');
//                                call_structure();
//                                $("img.load").remove();
//                            }
//                        });
                    });
                    $(document).on("click", "img.close", function() {
                        $(this).closest('form.edit_data').closest('div').children('span.highlight, span.add_action, span.delete_action, span.edit_action').hide();
                        $(this).closest('form.edit_data').remove();
                        $('li > div.zindex').removeClass('zindex');
                    });
                });
            }
            if (delete_option) {
                $(document).on("click", '.' + class_name + ' span.delete_action', function() {
                    var _deletethis = $(this);
                    var target_element = $(this).closest('li').closest('ul').closest('li');
                    confirm_message = 1;
                    if (confirm_before_delete) {
                        var confirm_text = $(this).closest('li').children('ul').length === 0 ? "Deleat This ?" : "Deleat This with\nAll Child Element ?";
                        confirm_message = confirm(confirm_text);
                    }
                    if ($(this).closest('div').attr('id') == 1) {
                        alert("You cant delete root person");
                    } else {
                        if (confirm_message) {
                            $(this).closest('li').addClass('ajax_delete_all');
                            ajax_delete_id = Array();
                            ajax_delete_id.push($(this).closest('div').attr('id'));
                            $('.ajax_delete_all li').each(function() {
                                ajax_delete_id.push($(this).children('div').attr('id'));
                            });
                            $(this).closest('li').removeClass('ajax_delete_all');
                            //var data = "action=delete&id=" + ajax_delete_id + "";
                            $(this).closest("li").before("<img src='images/load.gif' class='load' />");
                            $("img.load").remove();
                            _deletethis.closest('li').fadeOut().remove();
                            call_structure();
                            if (target_element.children('ul').children('li').length == 0)
                                target_element.children('ul').remove();
//                            $.ajax({
//                                type: 'POST',
//                                url: 'ajax.php',
//                                data: data,
//                                success: function(data) {
//                                    $("img.load").remove();
//                                    _deletethis.closest('li').fadeOut().remove();
//                                    call_structure();
//                                    if (target_element.children('ul').children('li').length == 0)
//                                        target_element.children('ul').remove();
//                                }
//                            });
                        }
                    }
                });
            }
            if (draggable_option) {
                function draggable_event() {
                    droppable_event();
                    $('.' + class_name + ' li > div').draggable({
                        cursor: 'move',
                        distance: 40,
                        zIndex: 5,
                        revert: true,
                        revertDuration: 100,
                        snap: '.tree li div',
                        snapMode: 'inner',
                        start: function(event, ui) {
                            $('li.li_children').removeClass('li_children');
                            $(this).closest('li').addClass('li_children');
                        },
                        stop: function(event, ul) {
                            droppable_event();
                        }
                    });
                }
                function droppable_event() {
                    $('.' + class_name + ' li > div').droppable({
                        accept: '.tree li div',
                        drop: function(event, ui) {
                            $('div.check_div').removeClass('check_div');
                            $('.li_children div').addClass('check_div');
                            if ($(this).hasClass('check_div')) {
                                alert('Cant Move on Child Element.');
                            } else {
//                                var data = "action=drag&id=" + $(ui.draggable[0]).attr('id') + "&parentid=" + $(this).attr('id') + "";
//                                $.ajax({
//                                    type: 'POST',
//                                    url: 'ajax.php',
//                                    data: data,
//                                    success: function(data) {
//                                    }
//                                });
                                $(this).next('ul').length == 0 ? $(this).after('<ul><li>' + $(ui.draggable[0]).attr({'style': ''}).closest('li').html() + '</li></ul>') : $(this).next('ul').append('<li>' + $(ui.draggable[0]).attr({'style': ''}).closest('li').html() + '</li>');
                                $(ui.draggable[0]).closest('ul').children('li').length == 1 ? $(ui.draggable[0]).closest('ul').remove() : $(ui.draggable[0]).closest('li').remove();
                                call_structure();
                                draggable_event();
                            }
                        }
                    });
                }
                $('.' + class_name + ' li > div').disableSelection();
                draggable_event();
            }
        });
    };
})(jQuery);