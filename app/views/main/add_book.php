<div class="layout-color container white-bg" id="container">
    <div class="page-header">
        <h2> Add Book </h2>
    </div>

    <form role="form" id="add-book-form" class="form-horizontal well" action="/main/addBook" method="post"
          enctype="multipart/form-data">
        <div class="form-left">
            <div class="form-group form-horizontal hidden">
                <button type="button" class="btn btn-default col-sm-1 control-label margin-get-btn">GET</button>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="isbn" placeholder="Get ISBN">
                    <span class="isbn"> </span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="getBookName">ISBN</label>

                <div class="col-sm-7">
                    <input type="text" class="form-control" name="isbn" placeholder="ISBN">
                    <span class="icon"> </span>
                </div>
            </div>

            <div id="group_book_name" class="form-group has-feedback required">
                <label class="col-sm-3 control-label" for="getBookName">Book Name</label>

                <div class="col-sm-7">
                    <input type="text" class="form-control" id="book_name" name="book_name"
                           placeholder="Type Book Name..." required="required">
                    <span class="icon"> </span>
                </div>
            </div>

            <div class="form-group">
                <label for="getVolume" class="col-sm-3 control-label">Volume</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control form-margin" id="volume" name="volume"
                           placeholder="Type Book Volume...">
                </div>
            </div>

            <div class="form-group">
                <label for="getAuthor" class="col-sm-3 control-label">Author</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control form-margin" id="author" name="author"
                           placeholder="Type Author's Name...">
                </div>
            </div>

            <div class="form-group">
                <label for="getPublisher" class="col-sm-3 control-label">Publisher</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control form-margin" id="publisher" name="publisher"
                           placeholder="Type Publisher...">
                </div>
            </div>

            <div class="form-group has-feedback" id="date">
                <label for="getPublishedDate" class="col-sm-3 control-label">Date</label>

                <div class="col-sm-7">
                    <input type="text" class="form-control form-margin" id="published_date" name="published_date"
                           placeholder="Type Publishing Date...">
                    <span class="dateIcon"> </span>
                </div>
            </div>

            <div class="form-group">
                <label for="getLanguage" class="col-sm-3 control-label">Language</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control form-margin" id="language" name="language"
                           placeholder="Type Language...">

                </div>
            </div>

            <div class="form-group">
                <div>
                    <label class="col-sm-3 control-label">
                        Type
                    </label>

                    <div class="col-sm-9">
                        <div class="btn-group" data-toggle="buttons">
                            <label id="hardbound" class="btn btn-primary active">
                                <input type="radio" name="optionsRadios" value="hardbound" checked> Hardbound
                            </label>
                            <label class="btn btn-primary pdf">
                                <input type="radio" name="optionsRadios" id="pdf" value="pdf"> PDF
                            </label>
                        </div>
                        <br>
                        <br>

                        <div class="pdf-link">
                            <input type="text" class="form-control hide_content form-margin link_id" name="link_id[]"
                                   placeholder="Enter Link ID...">
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
                    <input type="text" class="form-control form-margin" id="tag1"
                           name="tag1" placeholder="Type Tags...">
                </div>
            </div>

            <div class="row">
                <label class="col-sm-3 control-label" for="getInfo">Information</label>

                <div class="col-sm-2">
                    <textarea class="info" id="text-area" name="book_info"></textarea>
                    <input type="hidden" name="callback" value="json">
                </div>
            </div>
        </div>

        <div class="form-right">
            <div class="row">
                <div class="imageContainer col-md-4" id="pic-margin">
                    <div class="col-xs-10">
                        <img alt="400x200" class="imageSize book-preview" id="photo-preview"
                             onerror="this.src='/book_covers/no_cover.jpg'" src="/book_covers/upload.jpg">
                        <div class="form-group">
                            <br>
                            <label for="getPhoto">Browse book covers</label>
                            <input type="file" id="photo" name="photo">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-control">
            <button type="submit" class="btn btn-success" style="font-family: arial">
                <span class="glyphicon glyphicon-plus"></span> Add Book
            </button>
        </div>
    </form>
</div>