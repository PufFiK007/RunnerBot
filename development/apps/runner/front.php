<style>
  *, *:before, *:after {
    box-sizing: border-box;
  }
  body {
    padding: 24px;
    font-family: 'Source Sans Pro', sans-serif;
    margin: 0;
  }
  h1, h2, h3, h4, h5, h6 {
    margin: 0;
  }
  .container {
    max-width: 1000px;
    margin-right: auto;
    margin-left: auto;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
  }
  .table {
    width: 100%;
    border: 1px solid #eee;
  }
  .table-header {
    display: flex;
    width: 100%;
    background: #000;
    padding: 18px 0;
  }
  .table-row {
    display: flex;
    width: 100%;
    padding: 18px 0;
  }
  .table-row:nth-of-type(odd) {
    background: #eee;
  }
  .table-data, .header__item {
    flex: 1 1 20%;
    text-align: center;
  }
  .header__item {
    text-transform: uppercase;
  }
  .filter__link {
    color: white;
    text-decoration: none;
    position: relative;
    display: inline-block;
    padding-left: 24px;
    padding-right: 24px;
  }
  .filter__link::after {
    content: '';
    position: absolute;
    right: -18px;
    color: white;
    font-size: 12px;
    top: 50%;
    transform: translateY(-50%);
  }
  .filter__link.desc::after {
    content: '(desc)';
  }
  .filter__link.asc::after {
    content: '(asc)';
  }
</style>



<div class="container">
  
  <div class="table">
    <div class="table-header">
      <div class="header__item"><a id="student" class="filter__link" href="#">Студент</a></div>
      <div class="header__item"><a id="lecturer" class="filter__link filter__link--number" href="#">Преподаватель</a></div>
      <div class="header__item"><a id="subject" class="filter__link filter__link--number" href="#">Предмет</a></div>
      <div class="header__item"><a id="data" class="filter__link filter__link--number" href="#">Дата</a></div>
      <div class="header__item"><a id="mark" class="filter__link filter__link--number" href="#">Оценка</a></div>
    </div>
	<div class="table-content">
                <?php    
          $json = file_get_contents( "https://runner.pnit.od.ua/get/result", false);
          $data = json_decode($json, TRUE)['data'];
					foreach($data as $row) {
						echo '<div class="table-row">';
						echo '<div class="table-data">'.$row['student'].'</div>';
						echo '<div class="table-data">'.$row['lecturer'].'</div>';
						echo '<div class="table-data">'.$row['subject'].'</div>';
						echo '<div class="table-data">'.$row['date'].'</div>';
						echo '<div class="table-data">'.$row['mark'].'</div>';
						echo '</div>';
               		 }
                ?>
            </div>
    </div>  
  </div>
</div>