<?php

session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
if ($_POST['category']) {
    $bookcat = 1;
    $category_id = $_POST['category'];
    include"db.php";

    mysql_query("SET NAMES 'utf8'");
    mysql_query("SET CHARACTER SET utf8 ");
    $query_pag_data = "select a.author_id, b.book_id, k.category_id, b.title, k.name, b.create_date, b.description, b.price, b.avg_rating, a.first_name, a.last_name, a.date_of_birth, a.country from book b, author a, book_author d, category k, book_category m where b.book_id = d.book_id and a.author_id = d.author_id and b.book_id = m.book_id and k.category_id = m.category_id and k.category_id = '$category_id' order by b.book_id";
    $query_cat_name = "select name from category where category_id = '$category_id'";
    $result_pag_data = mysql_query($query_pag_data) or die('Database error' . mysql_error());
    $result_cat_name = mysql_query($query_cat_name) or die('Database error' . mysql_error());
    $msg = "";

    if (mysql_num_rows($result_pag_data) > 0) {
        $msg .= '<div class="category_name_disp"> Wybrana kategoria: <strong>' . mysql_fetch_array($result_cat_name)['name'] . '</strong></div>';
        while ($row = mysql_fetch_array($result_pag_data)) {

            $msg .=
                    '
<div class="col-md-12 book_display">
   <div class="row">
      <div id="commentbox1'. $row['book_id'] .'" class="col-md-6 book_content1">
         <h3>' . $row['title'] . '</h3>
      </div>
      <div id="commentbox2'. $row['book_id'] .'" class="col-md-6 book_content2">
         <div class="autor_category"> <a class="author_info" value="' . $row['author_id'] . '">' . $row['first_name'] . ' ' . $row['last_name'] . '</a> ( ' . $row['create_date'] . ' ) Kategoria:  <a class="cat_info sort_book" value="' . $row['category_id'] . '">' . $row['name'] . '</a></div>
      </div>
   </div>
</div>
<div class="col-md-12 book_display2">
   <div class="col-md-2 book_content2">
      <img src="images/covers/' . $row['book_id'] . '.jpg"/ class="img-responsive book_cover">
   </div>
   <div class="col-md-7 book_content4 ">
      <div class="more">' . $row['description'] . '</div>
   </div>
   <div class="col-md-3 book_content3">
      <div class="col-md-12">
         <div class="row">
            <div class="price"> Cena: <span class="single_price">' . $row['price'] . ' zł</span></div>
         </div>
      </div>
      <div class="col-md-12">
         <div class="row">
            <div id="catbook' . $bookcat++ . '" value="' . $row['avg_rating'] . '" class="rating"></div>
         </div>
      </div>
      <div class="col-md-12">
         <div class="row">';
            if ($_SESSION['auth'] == true && $_SESSION['login'] == true) {
                $msg .='<button value="' . $row['book_id'] . '" class="add_purchase"> Dodaj do koszyka <i class="fa fa-shopping-cart"></i></button>';
            } else {
                $msg .='<button class="add_purchase disabled" data-placement="right" data-toggle="tooltip" title="Musisz być zalogowany!"> Dodaj do koszyka <i class="fa fa-shopping-cart"></i></button>';
            }
            $msg .='
		 </div>
      </div>
      <div class="col-md-12">
         <div class="row">
            <button value="' . $row['book_id'] . '" class="add_comment"> Recenzje <span class="caret"></span></button>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="review" id="bookcomment' . $row['book_id'] . '"></div>';
        }
    } else {
        $msg .= '
	<div class="nothing_here"> <i class="fa fa-frown-o fa-5x"></i> <br/><br/>
	Przykro nam, niestety nie mamy dostępnych książek w wybranej kategorii.
	</div>
	';
    }
    echo $msg = "<div class='data'>" . $msg . "</div>";
}

