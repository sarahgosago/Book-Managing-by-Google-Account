$(document).ready(function () {

    $("#overlay").hide();
    $("#loader").hide();
    $("#show-short-info").hide();
    $("#book-name-searched").val($("#searched-book-name").val());

    var max_books = $("#max-book-count").val();
    var showed_books = $("#current-book").val();
    var s_next = 0;
    var sort_by = $("#sort_by").val();
    var scroll_lock = 0;
    var book_count = $("#book-count").val();
    var book_name = $("#searched-book-name").val();
    var filter = ($("#book-filter").val() == "" ? "all" : $("#book-filter").val());
    var tag = $("#tag").val();
    var on_filter = "all";
    var book_id = 0;

    $(".search-tag").html((tag ? '<span class="remove-tag glyphicon glyphicon-remove"></span> '+tag : '&nbsp;'));
    $("#filter-" + filter.toLocaleLowerCase()).addClass("active");

    $(".remove-tag").click(function() {
        tag = "";
        redirect_filter(filter);
    });

    make_books();

    $(document).on("mouseover mouseout", ".borrower", function () {
        $(this).parent().find(".show-borrower").fadeToggle();
    })

    document.addEventListener("touchend", ScrollEnd, false);

    function ScrollEnd() {
        if (($(window).scrollTop() + $(window).height() + 200) >= $(document).height() && scroll_lock != 1 && book_count >= 5) {
            render_books();
        }
    }

    function render_books(){
        scroll_lock = 1;
        $("#book-short-info").hide();
        s_next = s_next + 30;
        sort_by = $("#sort_by").val();

        filter = $("#book-filter").val();
        tag = $("#tag").val();

        $.ajax({
            type: "GET",
            url: window.location.pathname + "&start=" + s_next + "&sort_by=" + sort_by + "&tag_val=" + tag + "&filter=" + filter,
            dataType: "json"
        }).done(function (data) {
                if (data == "") {
                    scroll_lock = 1;
                } else {
                    scroll_lock = 0;
                    $("#overlay").show();
                    $("#loader").show();

                    next_set(data);
                    if (on_filter == "pdf") {
                        $("#filter-pdf").click();
                    } else if (on_filter == "bound") {
                        $("#filter-bound").click();
                    }
                }
            });
    }

    $("#view-comments").click(function () {
        if (!$("#book-preview-info").hasClass("full-screen")) {
            $(".read_links").removeClass("btns-overlay");
            $(".read_links").addClass("btns-fullscreen");
            show_comments($(this).attr("data-book-id"));
            showFullScreen();
        } else {
            hideFullScreen();
        }
    });

    $("#hide-book-info").click(function () {
        $("#book-preview-info").removeClass("full-screen");
        var $comments = $("#view-comments");
        $comments.html('<span class="glyphicon glyphicon-chevron-up"></span> Show Comments');
        $(".book").show();
        $("#comment-lists").slideUp();
        $("#book-preview-info").fadeOut();
        $("#book-full-screen-info").fadeOut();
    });

    $("#hide-short-info").click(function () {
        $("#show-short-info").fadeIn();
        $("#book-short-info").css("bottom", "-80px");
    });

    $("#show-short-info").click(function () {
        $("#show-short-info").fadeOut();
        $("#book-short-info").css("bottom", "0");
    });

    function redirect_filter(filter) {
        if (filter && filter!= 'all') {
            window.location = '/main/home?tag_val=' + tag + '&filter=' + filter + '&book_name=' + book_name + '&sort_by=' + sort_by;
        } else {
            window.location = '/main/home?tag_val=' + tag + '&book_name=' + book_name + '&sort_by=' + sort_by;
        }
    }

    $(".filters").click(function () {
        redirect_filter($(this).attr("filter"));
    });

    $(window).scroll(function () {
        if (on_filter == "all") {
            $(".book").show();
        }

        var comments = $("#comment-lists").hasClass("display");

        if (comments == false) {

            $("#book-preview-info").fadeOut();
            $(".down").removeClass("down");

            if ($(window).scrollTop() + $(window).height() >= $(document).height() && scroll_lock != 1 && book_count >= 5) {
                render_books();
            }
        }
    });

    function show_comments($book_id) {
        $("#book-preview-info").addClass("full-screen");
        var current_id = $("#current-user-id").val();
        $.ajax({
            url: '/main/comments?book_id=' + $book_id,
            method: 'GET',
            success: function (data) {
                $('div.comment').html('');
                $.each(data, function (key, book) {
                    var comment_append = '<div class="per-comment"><div class="book-commentator btn-sm"><span class="glyphicon glyphicon-chevron-right">' +
                        '</span>' + book.staff_name + '<span class="comment-time">' + book.comment_date + '</span></div>' +
                        '<div class="book-comments selected-comment" contenteditable="false">' + book.comment + '</div><input type="hidden" name="callback" value="json">';
                    if (book.staff_id == current_id) {
                        comment_append += '<div class="comment-options"><a class="edit-comment touch" data-comment-book-id="' + book.book_id + '" data-edit-comment-id="' + book.comment_id + '" style="color: #afafaf; font-size: 10px;"><span class="glyphicon glyphicon-pencil"></span> Edit</a>&nbsp&nbsp&nbsp&nbsp&nbsp<a class="delete-comment touch" data-delete-comment-id="' + book.comment_id + '" style="color: #afafaf; font-size: 10px;"><span class="glyphicon glyphicon-remove-circle"></span> Delete</a></div></div>';
                    }
                    else if (book.staff_id != current_id) {
                        comment_append += '</div>';
                    }
                    $("div.comment").append(comment_append);
                });
            },
            error: function (jxhr, msg, err) {
                console.log("Failed request.");
            }
        });
    }

    function get_overlay(color, data) {
        return '<div class="book-overlay show-title ' + color + '"><br><h6>' + data + '</h6></div>';
    }

    function next_set(data) {
        var next_books = "";
        var overlay = "";
        var color = "";

        showed_books = parseInt(showed_books) + parseInt(data.length);

        $(".search-results span").html(showed_books + "/" + max_books);

        $.each(data, function (num, book) {

            var title_cropped = book.book_title;
            var title = book.book_title;

            if (title.length > 70) {
                title_cropped = title.substr(0, 70) + "...";
            }

            overlay = '<div class="book-overlay show-title"><br><h6>' + title_cropped + '</h6></div>';

            if (book.is_disabled == 1) {
                overlay = '<div class="book-overlay"><br><br><br><h6>DISABLED</h6></div>';
                color = "overlay-blue";
                cover_opacity = 'cover-opacity';
            } else if (book.is_available == 0) {
                overlay = '<div class="book-overlay"><br><br><br><h6>Not Available</h6></div>';
                color = "overlay-red";
                cover_opacity = 'cover-opacity';
            } else {
                overlay = "";
                color = "";
                cover_opacity = "";
            }

            switch (sort_by) {
                case 'book_title':
                    overlay = get_overlay(color, title_cropped);
                    break;
                case 'book_author':
                    overlay = get_overlay(color, (book.book_author ? book.book_author : 'no author'));
                    break;
                case 'published_date':
                    overlay = get_overlay(color, book.published_date);
                    break;
                case 'rate':
                    overlay = get_overlay(color, str_repeat('<span class="glyphicon glyphicon-star"></span>', (book.avg_rate ? parseInt(book.avg_rate) : 0)));
                    break;
                default :
                    if (!book.file_exists) {
                        overlay = '<div class="book-overlay show-title"><br><h6>' + title_cropped + '</h6></div>';
                        overlay = get_overlay(color, title_cropped);
                    }

                    break;
            }

            if (num % 6 == 0) {
                if (num == 0) {
                    next_books += '<div class="book-row">';
                } else if (num == data.length - 1) {
                    next_books += '</div>';
                } else {
                    next_books += '</div>';
                    next_books += '<div class="book-row">';
                }
                //FIRST BOOK ON ROW

                if (data.length == num + 1 && num != 0) {
                    next_books += '<div class="book-row">';
                    next_books += makeBookSlot(book, overlay);
                    next_books += '</div>';
                    console.log(data.length + 'last-book #: ' + num);
                } else {
                    console.log(data.length + '1book #: ' + num);
                    next_books += makeBookSlot(book, overlay);
                }
            } else {
                if (num == data.length - 1) {
                    //LAST BOOK ON ROW
                    next_books += makeBookSlot(book, overlay);
                    next_books += '</div>';
                } else {
                    //OTHER BOOK ON ROW
                    next_books += makeBookSlot(book, overlay);
                }
            }
        });

        $("#book-stand").append(next_books);

        setTimeout(function () {
            $("#overlay").hide();
            $("#loader").hide();
        }, 500);

        make_books();
    }

    function makeBookSlot(book, overlay) {

        var lend_info = '';
        if (book.is_available == 0) {
            lend_info = '<div class="on-slot">' +
                '<span class="glyphicon glyphicon-user borrower btn-warning" title="' + book.lender + '"></span>' +
                '<span class="show-borrower">Borrowed By: ' + book.lender + '</span>' +
                '</div>';
        }

        return  '<div class="book-slot">' +
            '<a class="book book-pop-up ' + book.type.toLowerCase() + '" book_id="' + book.book_id + '" inventory_id = "' + book.inventory_id + '" lender="' + book.lender + '" link_id="' + book.link_id + '" type="' + book.type + '" published_date="' + book.published_date + '" language="' + book.language + '"  publisher="' + book.publisher + '" isbn="' + book.isbn + '" book_info="' + book.book_info + '" book_title="' + book.book_title + '"  book_author="' + book.book_author + '"  data-toggle="modal">' +
            '<img class="book-size" onerror="this.src=\'/book_covers/no_cover.jpg\'" src="/book_covers/' + book.book_id + '.jpg">' +
            overlay +
            '<div class="book-type-pdf"><span class="' + (book.type.toLowerCase() == "pdf" ? "pdf-marker" : "") + '"></span></div>' +
            '</a>' +
            lend_info +
            '</div>';
    }

    function str_repeat(input, multiplier) {
        return Array(multiplier + 1).join(input);
    }

    function make_books() {
        $(".book-pop-up").hover(function () {
            $(".book-show-type").hide();
            $(this).parent().find(".book-show-type").show();
            if ($("#book-preview-info").is(':visible') == false) {
                $("#book-short-info").fadeIn();
                $("#book-short-info .book_cover").attr("src", $(this).attr("src"));
                $("#book-short-info .title h4").html($(this).attr("book_title"));
                $("#book-short-info .author").html($(this).attr("book_author"));
                $("#book-short-info .inventory-id").html($(this).attr("inventory_id"));
            }
        });

        $(document).on("click touchstart",".book-pop-up",function(){

            $("#book-short-info").hide();
            $(".down").removeClass("down");
            $(this).addClass("down");
            $("#book-preview-info").show();

            var book_title = $(this).attr("book_title");
            var book_author = $(this).attr("book_author");
            var book_isbn = $(this).attr("isbn");
            var book_publisher = $(this).attr("publisher");
            var book_language = $(this).attr("language");
            var book_type = $(this).attr("type");
            var book_link = $(this).attr("link_id");
            var book_date = $(this).attr("published_date");
            var book_info = $(this).attr("book_info");
            var book_lender = $(this).attr("lender");
            var book_id = $(this).attr("book_id");

            setBookRate(book_id);
            setFavoriteDisplay(book_id);

            $("#view-comments").attr("data-book-id", book_id);
            $("#comment-lists").hide();

            $("#book-preview-info .title h2").html(book_title);

            $("#book-preview-info .author").html(book_author);
            if (book_author != "") {
                $("#author-info").show();
                $(".author").show();
            } else {
                $("#author-info").hide();
                $(".author").hide();
            }

            $("#book-preview-info .isbn").html(book_isbn);
            if (book_isbn != "") {
                $("#isbn-info").show();
                $(".isbn").show();
            } else {
                $("#isbn-info").hide();
                $(".isbn").hide();
            }

            $("#book-preview-info .publisher").html(book_publisher);
            if (book_publisher != "") {
                $("#pub-info").show();
                $(".publisher").show();
            } else {
                $("#pub-info").hide();
                $(".publisher").hide();
            }

            $("#book-preview-info .language").html(book_language);
            if (book_language != "") {
                $("#lang-info").show();
                $(".language").show();
            } else {
                $("#lang-info").hide();
                $(".language").hide();
            }

            $("#book-preview-info .type").html(book_type);
            $("#book-preview-info .type").attr('href', book_type);

            if (book_type != "") {
                $("#type-info").show();
                $(".type").show();
            } else {
                $("#type-info").hide();
                $(".type").hide();
            }
            console.log(book_lender);
            $("#book-preview-info .lender").html(book_lender);
            if (typeof book_lender !== 'undefined' && book_lender != 'null' && book_lender != "") {
                $("#lender-info").show();
                $(".lender").show();
            } else {
                $("#lender-info").hide();
                $(".lender").hide();
            }


            $("#book-preview-info .lender").html(book_lender);
            if (typeof book_lender !== 'undefined' && book_lender != 'null' && book_lender != "") {
                $("#lender-info").show();
                $(".lender").show();
            } else {
                $("#lender-info").hide();
                $(".lender").hide();
            }

            var links = book_link.split(",");
            var multi_link = "";
            var overlay_link = "";

            $.each(links, function (key, val) {
                if (links.length > 1) {
                    multi_link += '<a class="btn btn-primary btn-sm button btn-links" href="' + val + '" target="_blank">Part ' + (key + 1) + '</a>&nbsp;';
                    overlay_link += '<a class="btn btn-primary btn-sm button btn-part" href="' + val + '" target="_blank">Part ' + (key + 1) + '</a>&nbsp;';
                    $(".hide_content").show();
                } else {
                    multi_link += '<a class="btn btn-primary btn-xs" href="' + val + '" target="_blank">Read Book</a>';
                    $(".hide_content").hide();
                }


            });

            if (links.length > 1) {
                $(".read_links").html(overlay_link);
                $(".link_id").append(multi_link);
            }

            $("#book-preview-info .link_id").html(multi_link);
            if (book_link != "") {
                $("#link-info").show();
                $(".link_id").show();

            } else {
                $("#link-info").hide();
                $(".link_id").hide();
            }

            $("#book-preview-info .link_id").attr('href', book_link);
            $("#book-preview-info .cover-link").attr("href", (book_link?book_link:'#'));

            $("#book-preview-info .date").html(book_date);
            if (book_date != "" && book_date != "0000") {
                $("#date-info").show();
                $(".date").show();
            } else {
                $("#date-info").hide();
                $(".date").hide();
            }

            $("#book-preview-info .book_cover").attr("src", $(this).find("img").attr("src"));
            $("#book-preview-info .info").attr('href', book_info);

            if (book_info != "" && book_info != " ") {
                $("#book-info").show();
                $(".info").show();
            } else {
                $("#book-info").hide();
                $(".info").hide();
            }

            $("#book-short-info").hide();
            $("#comment").attr("data-book-id", $(this).attr("book_id"));
        });
    }

    $(document).on("click", "a.edit-comment", function () {
        var book_comments = $(this).parents(".per-comment").find('.book-comments');
        book_comments.attr("contenteditable", "true");

        $(this).parents(".per-comment").find('.book-comments').addClass("edit-comment-color");

        var comment_id = $(this).data("edit-comment-id");
        var book_id = $(this).data("comment-book-id");

        book_comments.on('keypress', function (e) {
            var key = e.which || e.keyCode;
            if (key == 13) {
                var comment = book_comments.text();

                editComment(comment, comment_id);
                show_comments(book_id);
            }
        });
    });

    $(document).on("click", "a.delete-comment", function () {
        var comment_id = $(this).data("delete-comment-id");
        var book_id = $("#comment").attr("data-book-id");

        deleteComment(comment_id);
        show_comments(book_id);
    });

    $(".save-comment").click(function (e) {
        e.preventDefault();
        var comment = $("#comment").val();
        var book_id = $("#comment").attr("data-book-id");
        var has_error = false;

        if (comment.length <= 5) {
            alert('Type something or more.');
            $('#comment').addClass('has-error');
            has_error = true;
        } else {
            insertComment(book_id, comment);
            show_comments(book_id);
        }
    });
});

function showFullScreen() {
    $("#book-preview-info").addClass("full-screen");
    $("#view-comments").html('<span class="glyphicon glyphicon-chevron-down"></span> Hide Comments');
    $("#comment-lists").addClass("display");
    $("#comment-lists").slideDown();
    $("#book-full-screen-info").fadeIn();
    $("#book-full-screen-info").html($("#book-text-info").html());
}
function hideFullScreen() {
    $("#view-comments").html('<span class="glyphicon glyphicon-chevron-up"></span> Show Comments');
    $(".read_links").addClass("btns-overlay");
    $(".read_links").removeClass("btns-fullscreen");
    $("#book-preview-info").removeClass("full-screen");
    $("#comment-lists").removeClass("display");
    $("#comment-lists").slideUp();
    $("#book-full-screen-info").fadeOut();
}

function insertComment(book_id, comment) {
    $.post('/main/insertComment', {book_id: book_id, comment: comment }, function (data) {
        $("#comment").val("");
        alert(data.message);
    });
}

function deleteComment(comment_id) {
    if (confirm("Are you sure you want to delete this comment?")) {
        $.post('/main/deleteComment', {comment_id: comment_id, callback: 'json' }, function () {
        });
    }
}

function editComment(comment, comment_id) {
    $.post('/main/editComment', {comment_id: comment_id, comment: comment, callback: 'json' }, function () {
    });
}

function setBookRate(book_id) {
    var $book_rating = $("div#book_rating.rateit");

    $.ajax({
        url: '/main/getBookRating',
        method: 'POST',
        data: {book_id: book_id},
        success: function (data) {
            updateRatingDisplay($book_rating, book_id, data);
        },
        error: function (jxhr, msg, err) {
            console.log("Failed request.");
        }
    });
}

function updateRatingDisplay($rating_el, book_id, data) {
    if (data.user_rate) {
        $("#my_rate").html("You've rated it : <b id='user_rate'>" + data.user_rate + "</b>");
    } else {
        $("#my_rate").html("You have not rated it yet.");
    }
    $rating_el.rateit("value", data.avg_rate);
    $("#raters_count").text(data.raters_count);
    $("#rate_book_id").val(book_id);
}

function setFavoriteDisplay(book_id) {
    $.post('/main/getHaveFavorite', {book_id: book_id}, function (result) {
        console.log(result);
        if (result.success) {
            $('span#total_favor').text(result.total_favor);
            $favorite = $("#favorite");
            if (result.is_favorite) {
                $favorite.removeClass('undo-favorite').addClass('favorite');
                $favorite.attr('title', 'Favorited');
            } else {
                $favorite.removeClass('favorite').addClass('undo-favorite');
                $favorite.attr('title', 'Favorite');
            }
        }
    });
}
