<div class="layout-color container white-bg" id="container">
    <div class="page-header">
        <h2> Update Book </h2>
    </div>

    <form role="form" id="update-book-form" class="form-horizontal" action="/main/updateBook" method="post"
          enctype="multipart/form-data">
        <div class="form-left well">
            <div class="form-group form-horizontal hidden">
                <button type="button" class="btn btn-default col-sm-1 control-label margin-get-btn">GET</button>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="isbn" placeholder="Get ISBN" value="<?php eh($isbn) ?>">
                    <span class="isbn"> </span>
                </div>
            </div>

            <div id="group_book_name" class="form-group has-feedback required">
                <label class="col-sm-3 control-label" for="getBookName">ISBN</label>

                <div class="col-sm-7">
                    <input type="text" class="form-control" id="book_name" name="isbn" placeholder="ISBN"
                           required="required" value="<?php eh($isbn) ?>">
                    <span class="icon"> </span>
                </div>
            </div>

            <div id="group_book_name" class="form-group has-feedback required">
                <label class="col-sm-3 control-label" for="getBookName">Book Name</label>

                <div class="col-sm-7">
                    <input type="text" class="form-control" id="book_name" name="book_title"
                           placeholder="Type Book Name..." required="required" value="<?php eh($book_title) ?>">
                    <span class="icon"> </span>
                </div>
            </div>

            <div class="form-group">
                <label for="getVolume" class="col-sm-3 control-label">Volume</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control form-margin" id="volume" name="volume"
                           placeholder="Type Book Volume..." value="<?php eh($volume) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="getAuthor" class="col-sm-3 control-label">Author</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control form-margin" id="author" name="book_author"
                           placeholder="Type Author's Name..." value="<?php eh($book_author) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="getPublisher" class="col-sm-3 control-label">Publisher</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control form-margin" id="publisher" name="publisher"
                           placeholder="Type Publisher..." value="<?php eh($publisher) ?>">
                </div>
            </div>

            <div class="form-group has-feedback" id="date">
                <label for="getPublishedDate" class="col-sm-3 control-label">Date</label>

                <div class="col-sm-7">
                    <input type="text" class="form-control form-margin" id="published_date" name="published_date"
                           placeholder="Type Publishing Date..." value="<?php eh($published_date) ?>">
                    <span class="dateIcon"> </span>
                </div>
            </div>

            <div class="form-group">
                <label for="getLanguage" class="col-sm-3 control-label">Language</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control form-margin" id="language" name="language"
                           placeholder="Type Language..." value="<?php eh($language) ?>">
                </div>
            </div>

            <div class="form-group">
                <div>
                    <label class="col-sm-3 control-label">
                        Type
                    </label>

                    <div class="col-sm-9">
                        <div class="btn-group" data-toggle="buttons">
                            <label id="hardbound" class="btn btn-primary
                            <?php if (strtolower($type) == 'hardbound') echo "active" ?>">
                                <input type="radio" name="optionsRadios"
                                       value="hardbound" <?php if (strtolower($type) == 'hardbound') echo "checked" ?>>
                                Hardbound
                            </label>
                            <label class="btn btn-primary pdf <?php if (strtolower($type) == 'pdf') echo "active" ?>">
                                <input type="radio" name="optionsRadios" id="pdf"
                                       value="pdf" <?php if (strtolower($type) == 'pdf') echo "checked" ?>> PDF
                            </label>

                        </div>
                        <br><br>
                        <div class="pdf-link hide_content">
                            <?php $links = explode(',', $link_id);
                            $link_line = "";
                            foreach ($links as $link) {
                                $link_line = '<div class ="link-pdf">
                                              <input type="text"
                                              class="form-control form-margin hide_content pdf_link_id"
                                              name="link_id[]" placeholder="Enter Link ID..." value="' . $link . '">
                                              <button type="button" class="btn btn-danger btn-group-sm
                                              btn-inline glyphicon glyphicon-minus hide_content delete-link-btn
                                              delete-link-btn-style"></button><br></div>';
                                echo $link_line;
                            }
                            ?>
                        </div>
                        <button type="button" id="add-link-btn-style"
                                class="btn btn-success btn-group-sm glyphicon glyphicon-plus hide_content add-link-btn">

                                </button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="getTags" class="col-sm-3 control-label">Tags</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control form-margin" id="tag2" name="tag2" placeholder="Type Tags..."
                           value="<?php eh($tags) ?>">
                </div>
            </div>

            <div class="row">
                <label class="col-sm-3 control-label" for="getInfo">Information</label>

                <div class="col-sm-2">
                    <textarea class="info" id="text-area" name="book_info"><?php eh($book_info) ?></textarea>
                    <input type="hidden" name="callback" value="json">
                </div>
            </div>
        </div>

        <div class="form-right">
            <div class="row">
                <div class="imageContainer col-md-4" id="pic-margin">
                    <div class="">
                        <img alt="400x200" class="imageSize book-preview" id="photo-preview"
                             onerror="this.src='/book_covers/no_cover.jpg'" src="/book_covers/<?php eh($book_id) ?>.jpg">
                        <div class="form-group">
                            <label for="getPhoto">Browse book covers</label>
                            <input type="file" id="photo" name="photo">
                        </div>
                    </div>
                </div>
                <div>
                    <input type="hidden" name="book_id" id="book_id" value="<?php eh($book_id) ?>">
                    <button type="submit" class="btn btn-success margin-save-update-btn" id="save-book">
                        <span class="glyphicon glyphicon-ok"></span> Update
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>
