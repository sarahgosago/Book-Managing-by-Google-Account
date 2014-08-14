<?php

class MainController extends AppController
{
    public $google;

    public function __construct($name)
    {
        $this->google = new Google();
        parent::__construct($name);
    }

    public function index()
    {
        header("location: /main/login");
    }

    public function logout()
    {
        $this->google->logoutGoogle();
        $username = MySession::get("username");
        session_destroy();
        header("location: /main/login");
        $this->set(get_defined_vars());
    }

    public function login()
    {
        $this->view->layout = "layouts/nonav";
        $data = array();
        $data['login_error'] = "";
        if (!isset($_GET['error'])) {
            if (isset($_POST['submit'])) {
                $this->redirect($this->google->createUrl());
            }

            if (isset($_GET['code'])) {
                $url = $this->google->hasCode();
                header('Location: ' . filter_var($url, FILTER_SANITIZE_URL));
            }

            if (isset($_SESSION['token']) && $this->google->hasToken()) {
                $google_data = $this->google->hasToken();
                $email = $google_data['email'];
                if (Staff::validateEmail($email)) {
                    $user = Staff::getUserInfoByEmail($email);
                    $user_type = Admin::checkIfAdmin($email);

                    $username = $user->firstname . " " . $user->lastname;

                    MySession::set("current_user", $user->username);
                    MySession::set("user_id", $user->id);
                    MySession::set("username", $username);
                    MySession::set("user_type", $user_type);

                    if (isset($user_type)) {
                        $this->redirect("/main/manage");
                    } else {
                        $this->redirect("/main/home");
                    }
                } else {
                    $this->redirect("/main/login?error=404");
                }
            } else if (isset($_SESSION['token'])) {
                $data['login_error'] = "Email not found";
                $this->google->logoutGoogle();
            }
        } else {
            $this->google->logoutGoogle();
        }
        $this->set($data);
        $this->set(get_defined_vars($data));
    }

    public function logs()
    {
        if (!MySession::get("user_id")) {
            header("location: /main/home");
        }

        $book_transactions = BookTransaction::getAllTransactions();
        $this->set(get_defined_vars());
    }

    public function home()
    {
        $user_id = MySession::get("user_id");

        if (!$user_id) {
            header("location: /main/login");
        }

        $this->view->layout = "layouts/users";

        $filters = array("name" => Param::get("book_name"),
            "sort" => Param::get("sort_by"),
            "tag" => Param::get("tag_val"),
            "type" => Param::get("filter", ''),
            "all" => false,
            "start" => Param::get("start", 0));

        if ($filters['start']) {

            if ($filters["tag"]) {
                $books = Book::getBooksByTag($filters);
            } else {
                $books = Book::findBookByName($filters);
            }

            foreach ($books as $key => $book) {
                $book_source = 'book_covers/' . $book['book_id'] . '.jpg';
                $books[$key]["file_exists"] = file_exists($book_source);
            }

            header('Content-Type: text/html; charset=UTF-8');
            echo json_encode($books);
            exit;

        } else {

            if ($filters['tag']) {
                $books = Book::getBooksByTag($filters);
                $filters["all"] = true;
                $all_books = Book::getBooksByTag($filters);
            } else {
                $books = Book::findBookByName($filters);
                $filters["all"] = true;
                $all_books = Book::findBookByName($filters);
            }

            $data["total_books_count"] = count($all_books);
            $data["book_filter"] = $filters['type'];
            $data["book_name"] = $filters['name'];
            $data["sorting"] = $filters['sort'];
            $data["tag_val"] = $filters['tag'];
            $data["books"] = $books;

            $this->set($data);
        }

        $tag_listed = Book::getUniqueTags($filters['type']);
        $options = Book::getOptions();
        $this->set(get_defined_vars());
    }

    public function favorite()
    {
        $this->view->layout = "layouts/users";

        $start = Param::get("start");
        $user_id = MySession::get('user_id');
        $books = UserFavoriteBook::getFavorite($user_id);
        $type = Param::get("filter", '');

        if ($start) {
            $this->printJson($books);
        } else {
            $data["total_books_count"] = count($books);
            $data["books"] = $books;
            $data["book_name"] = '';
            $data["sorting"] = '';
            $data["tag_name"] = '';
            $data["tag_val"] = '';
            $data["book_filter"] = '';
            $this->set($data);
        }

        $tag_listed = Book::getUniqueTags($type);
        $options = Book::getOptions();

        $this->set($data);
        $this->set(get_defined_vars());
        $this->render('home');
    }

    public function manage()
    {
        if (!MySession::get("user_type")) {
            header("location: /main/home");
        }
        $id_delete = Param::get("id");
        $disable_id = Param::get("disable_id");
        $enable_id = Param::get("enable_id");
        $staffs = Staff::getAll();
        $options = array();

        foreach ($staffs as $staff) {
            $username = $staff->firstname . " " . $staff->lastname;
            $options [] = array("value" => $username, "data" => $staff->id);
        }

        if ($id_delete) {
            Book::deleteBook($id_delete);
        }

        if ($disable_id) {
            Book::disableBook($disable_id);
        }

        if ($enable_id) {
            Book::enableBook($enable_id);
        }

        error_log('callback = ' . Param::get('callback'));


        $book_results = Book::getAllBooks();
        $this->set(get_defined_vars());

    }

    public function lendBook()
    {
        if (!MySession::get("user_type")) {
            header("location: /main/home");
        }

        $book_id = Param::get("book_id");
        $lender = Param::get("lender");
        $staff_id = Param::get("staff_id");

        if (!$book_id || !$lender) {
            throw new Exception('Name should not be empty.');
        }

        $data = array('success' => false, 'message' => 'There is problem in lending!');
        if (Book::lendBook($book_id, $lender)) {
            $book_title = Book::findBookName($book_id);
            BookTransaction::lendingInfo($book_id, $lender, $staff_id, $book_title);
            $data['success'] = true;
            $data['message'] = "The lending process is successful!";
        }

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }


    public function returnBook()
    {
        if (!MySession::get("user_type")) {
            header("location: /main/home");
        }

        $book_id = Param::get("book_id");
        $lender = Param::get("lender2");
        $staff_id = Param::get("staff_id2");

        header('Content-Type: application/json');
        $data = array('success' => false, 'message' => 'There is problem in returning!');
        if (!$book_id || !$lender) {
            error_log('BID: ' . $book_id . ' | LENDER: ' . $lender);
            $data['message'] = 'Name should not be empty.';
        } elseif (Book::returnBook($book_id, $lender)) {
            $book_title = Book::findBookName($book_id);
            BookTransaction::returningInfo($book_id, $lender, $staff_id, $book_title);
            $data['success'] = true;
            $data['message'] = "The returning process is successful!";
        }
        echo json_encode($data);
        exit;
    }

    public function addAdmin()
    {
        if (!MySession::get("user_type")) {
            header("location: /main/home");
        }

        $email = Param::get("username");
        $admins = Admin::getAllAdmins();
        $id = Param::get("id");
        $staffs = Staff::getAll();
        $options = array();

        foreach ($staffs as $staff) {
            $username = $staff->firstname . " " . $staff->lastname;
            $options [] = array("value" => $username, "data" => $staff->username);
        }

        $data = array('success' => false, 'message' => 'Not a username of a staff!');

        if (!$email) {
            error_log('UN: ' . $email);
            $data['message'] = 'Please Fill Out the Email.';
        } elseif (Admin::checkIfAdmin($email)) {
            $data['message'] = 'Already an admin!';
        } elseif ($email) {
            $user = Staff::getUserInfoByEmail($email);
            if ($user) {

                $username  = $user->username;
                $firstname = $user->firstname;
                $lastname  = $user->lastname;

                $res = Admin::addAdmin($username, $firstname, $lastname);
                if ($res) {
                    $data['success'] = true;
                    $data['message'] = "The adding of admin is successful!";
                } else {
                    $data['message'] = "The adding of admin is unsuccessful! Please try again.";
                }
            }
        }

        if ($id) {
            Admin::deleteAdmin($id);
        }

        if (Param::get('callback')) {
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }

        error_log('callback = ' . Param::get('callback'));

        $this->set($data);
        $this->set(get_defined_vars());
        $this->render('add_admin');
    }

    public function addBook()
    {
        if (!MySession::get("user_type")) {
            header("location: /main/home");
        }

        $isbn = Param::get("isbn");
        $book_name = trim(Param::get("book_name"));
        $author = Param::get("author");
        $volume = Param::get("volume");
        $publisher = Param::get("publisher");
        $published_date = Param::get("published_date");
        $language = Param::get("language");
        $tags = Param::get("tag1");
        $book_info = Param::get("book_info");
        $type = Param::get("optionsRadios");
        $link_id = ($type == 'pdf') ? implode(',', Param::get("link_id")) : '';

        $data = array('success' => false, 'message' => 'There is problem in adding a book!');
        if (!$book_name) {
            error_log('BN: ' . $book_name);
            $data['message'] = 'Please Fill Out the Book Name.';
        } else {
            $book_id = Book::addBook($book_name, $volume, $author, $publisher, $published_date, $language, $type, $tags, $book_info, $link_id, $isbn);
            $photo = array_key_exists('photo', $_FILES) ? $_FILES['photo'] : '';
            if ($this->saveImage($photo, $book_id)) {
                $data['success'] = true;
                $data['message'] = "The adding of book is successful!";
                Book::insertInventoryIdToNewlyAddedBook();
            } else {
                $data['message'] = "The adding of book is unsuccessful! Please try again.";
            }
        }

        if (Param::get('callback')) {
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }

        error_log('callback = ' . Param::get('callback'));

        $this->set($data);
        $this->render('add_book');
    }

    public function updateBook()
    {
        if (!MySession::get("user_type")) {
            header("location: /main/home");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $book_data = Param::params();
            $book_data['type'] = Param::get("optionsRadios");
            $book_data['tags'] = Param::get("tag2");
            $book_data['link_id'] = ($book_data['type'] == 'pdf') ? implode(',', Param::get("link_id")) : '';
            $book_data['book_id'] = $book_data['book_id'];
            $book_data['book_title'] = trim($book_data['book_title']);

            foreach (array('tag2', 'optionsRadios', 'callback', 'page_next', 'dc_action', 'photo') as $key_to_unset) {
                unset($book_data[$key_to_unset]);
            }

            $data = array('success' => false, 'message' => 'There is problem in updating a book!');

            if (!$book_data['book_title']) {
                error_log('BN: ' . $book_data['book_title']);
                $data['message'] = 'Please Fill Out the Book Name.';
            } else {
                $book_id = Book::updateBook($book_data);
                $photo = array_key_exists('photo', $_FILES) ? $_FILES['photo'] : '';
                if ($this->saveImage($photo, $book_id)) {
                    $data['success'] = true;
                    $data['message'] = "The updating of book is successful!";
                } else {
                    $data['message'] = "The updating of book is unsuccessful! Please try again.";
                }
            }

            if (Param::get('callback')) {
                header('Content-Type: application/json');
                echo json_encode($data);
                exit;
            }
        } else {
            $data = Book::findBookById(Param::get("book_id"));

        }

        error_log('callback = ' . Param::get('callback'));

        $this->set($data);
        $this->render('update_book');
    }

    private function saveImage($file, $new_file_name)
    {
        if (!$file) {
            return true;
        }

        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if ((($file["type"] == "image/gif")
                || ($file["type"] == "image/jpeg")
                || ($file["type"] == "image/jpg")
                || ($file["type"] == "image/pjpeg")
                || ($file["type"] == "image/x-png")
                || ($file["type"] == "image/png"))
            && ($file["size"] < 2000000)
            && in_array($extension, $allowedExts)
        ) {
            if ($file["error"] > 0) {

                return false;
            } else {
                if (file_exists("upload/" . $file["name"])) {
                    echo $file["name"] . " already exists. ";
                } else {
                    $destination_file = BOOK_COVERS . $new_file_name . ".jpg";
                    move_uploaded_file($file["tmp_name"], $destination_file);
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public function saveRating()
    {
        $book_id = Param::get('book_id');
        $rate = Param::get('rate');
        $user_id = MySession::get('user_id');

        try {
            if (UserBookRating::isAlreadyRated($book_id, $user_id)) {
                UserBookRating::update($book_id, $rate, $user_id);
            } else {
                UserBookRating::create($book_id, $rate, $user_id);
            }
            $this->printJson(array_merge(array("success" => true), UserBookRating::getBookRateInfo($book_id, $user_id)));
        } catch (Exception $e) {
            $this->printJson(array("success" => false));
        }
    }

    public function getBookRating()
    {
        $book_id = Param::get('book_id');
        $book_rate = UserBookRating::getBookRateInfo($book_id, MySession::get('user_id'));

        $this->printJson($book_rate);
    }

    public function getHaveFavorite()
    {
        $book_id = Param::get('book_id');
        $user_id = MySession::get('user_id');
        $this->printJson(
            array(
                'success' => true,
                'total_favor' => UserFavoriteBook::countInfavor($book_id),
                'is_favorite' => UserFavoriteBook::isFavorite($book_id, $user_id)
            )
        );
    }

    public function makeFavorite()
    {
        $book_id = Param::get('book_id');
        $user_id = MySession::get('user_id');

        if (!UserFavoriteBook::isFavorite($book_id, $user_id)) {
            UserFavoriteBook::makeFavorite($book_id, $user_id);
        }
        $this->printJson(array('success' => true, 'total_favor' => UserFavoriteBook::countInfavor($book_id)));
    }

    public function undoFavorite()
    {
        $book_id = Param::get('book_id');
        $user_id = MySession::get('user_id');

        UserFavoriteBook::undoFavorite($book_id, $user_id);
        $this->printJson(
            array(
                'success' => true,
                'total_favor' => UserFavoriteBook::countInfavor($book_id)
            )
        );
    }

    public function insertComment()
    {
        $user_id = MySession::get("user_id");
        $book_id = Param::get("book_id");
        $comment = htmlspecialchars(Param::get("comment"));

        $data = array('success' => false, 'message' => 'There is a problem in adding your comment!');
        if ($comment) {
            $res = Comment::insertComment($book_id, $user_id, $comment);
            if ($res) {
                $data["success"] = true;
                $data["message"] = "Comment Added!";
            }
        }
        $this->printJson($data);
    }

    public function getComments()
    {
        $book_id = Param::get("book_id");
        $comment_results = Comment::getByBookId($book_id);

        $this->printJson($comment_results);
    }

    public function deleteComment()
    {
        $comment_id = Param::get("comment_id");
        $result = Comment::deleteById($comment_id);

        error_log('callback = ' . Param::get('callback'));

        $this->printJson($result);
    }

    public function editComment()
    {
        $comment = Param::get("comment");
        $comment_id = Param::get("comment_id");
        $result = Comment::editById($comment, $comment_id);

        $this->printJson($result);
    }

    public function comments()
    {
        if (!MySession::get("user_id")) {
            header("location: /main/login");
        }

        $book_id = Param::get("book_id");
        $comment_results = Comment::getByBookId($book_id);
        $staff_lists = Staff::getAll();

        $staffs = array();
        foreach ($staff_lists as $lists) {
            $staffs[$lists->id] = $lists->firstname . " " . $lists->lastname;
        }

        foreach ($comment_results as $index => $comment_res) {
            $comment_results[$index]['staff_name'] = $staffs[$comment_res["staff_id"]];
            $comment_results[$index]["comment"] = $this->showGlyph($comment_res['comment']);
        }

        $this->printJson($comment_results);
    }

    public function showGlyph($comment)
    {
        $glyph = str_replace(":like:", "<span class='glyphicon glyphicon-thumbs-up'></span>", $comment);
        $glyph = str_replace(":love:", "<span class='glyphicon glyphicon-heart'></span>", $glyph);
        $glyph = str_replace(":star:", "<span class='glyphicon glyphicon-star'></span>", $glyph);
        $glyph = str_replace(":book:", "<span class='glyphicon glyphicon-book'></span>", $glyph);
        return $glyph;
    }
}
