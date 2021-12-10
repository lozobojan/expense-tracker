<?php 

    include './db_connect.php';
    include './auth.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ExpenseTracker - početna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="./style.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/fontawesome.min.css">
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body>

    <div class="container pt-5">
      
      <div class="row">
          <div class="col-3">
              <h5>Troškovi koje pratite</h5>
              <?php 

                $sql_types = "SELECT 
                                    et.id,
                                    et.name,
                                    uet.user_id 
                                from expense_types et 
                                left join user_expense_type uet on et.id = uet.expense_type_id and uet.user_id = $currentUserId
                                order by et.name ASC    
                            ";
                $res_types = mysqli_query($dbconn, $sql_types);

                while($type = mysqli_fetch_assoc($res_types)){
                    
                    $type_id = $type['id'];
                    $type_name = $type['name'];
                    
                    $checked = "";
                    if(!is_null($type['user_id'])) $checked = "checked";

                    echo "<div class=\"row\" >";
                    echo "  <div class=\"col-2\"> <input type=\"checkbox\" id=\"chk_type_$type_id\" $checked onchange=\"save($type_id)\" > </div>";
                    echo "  <div class=\"col-10\"> <label for=\"chk_type_$type_id\" >$type_name</label> </div>";
                    echo "</div>";
                }

              ?>
          </div>
          <div class="col-9">
              <h5>Dodavanje novog troška</h5>
              <form action="./expenses/add_new.php" method="POST">
                  <div class="row">
                      <div class="col-3">
                          <label for="amountInput">Iznos:</label>
                          <input type="number" step="0.01" class="form-control" name="amount" id="amountInput" >
                      </div>
                      <div class="col-3">
                            <label for="dateInput">Datum:</label>
                            <input type="date" class="form-control" name="date" id="dateInput" >
                      </div>
                      <div class="col-3">
                          <label for="selectType">Tip troška:</label>
                          <select name="expense_type_id" class="form-control" id="selectType" onchange="getSubtypes()"></select>
                      </div>
                      <div class="col-3">
                            <label for="selectSubtype">Podtip troška:</label>
                            <select name="expense_subtype_id" class="form-control" id="selectSubtype"></select>
                      </div>
                  </div>

                  <div class="row mt-3">
                      <div class="col-3 offset-9">
                        <button class="btn btn-success w-100">Sačuvaj</button>
                      </div>
                  </div>
              </form>
          </div>
      </div>

      <div class="row mt-4">
            
            <div class="col-3 pt-5">
                <canvas id="pieChart" width="400" height="400"></canvas>
                <h6 class="text-end">Ukupno: <span id="expensesTotal"></span> €</h6>
            </div>

            <!-- last 10 expenses table -->
            <div class="col-9 table-responsive">
                <h5>
                    Poslednjih
                        <form action="dashboard.php" method="GET" id="selectLimitForm">
                            <select name="limit" id="selectLimit" onchange="changeLimit()">
                                <?php 
                                    $lengths = ["5" => 5,"10" => 10,"15" => 15,"20" => 20, "Sve" => -1];
                                    foreach($lengths as $key => $length){
                                        isset($_GET['limit']) && $_GET['limit'] == $length ? $selected = "selected" : $selected = "";
                                        echo "<option value=\"$length\" $selected >$key</option>";
                                    }
                                ?>
                            </select>
                        </form> 
                    troškova
                </h5>
                <table class="table table-stripped table-hover mt-3" >
                    <thead>
                        <tr>
                            <th>Iznos</th>
                            <th>Datum</th>
                            <th>Tip</th>
                            <th>Podtip</th>
                            <th>Broj fajlova</th>
                            <th>Pridruženi fajlovi</th>
                            <th>Dodaj fajl</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                            if(isset($_GET['limit']) && $_GET['limit'] != -1) $limit_sql = " LIMIT  ".$_GET['limit'];
                            elseif(isset($_GET['limit']) && $_GET['limit'] == -1) $limit_sql = "" ;
                            else $limit_sql = "LIMIT 5";

                            $sql_expenses = "SELECT  
                                                e.id,
                                                e.amount,
                                                e.`date`,
                                                et.name as `type`,
                                                coalesce(es.name, '') as subtype,
                                                (select count(*) from attachments a where a.expense_id = e.id) as attachments_count
                                            FROM expenses e 
                                            join expense_types et on e.expense_type_id = et.id 
                                            left join expense_subtypes es on e.expense_subtype_id = es.id 
                                            WHERE user_id = $currentUserId ORDER BY e.date DESC $limit_sql";
                            $res_expenses = mysqli_query($dbconn, $sql_expenses);

                            while($expense = mysqli_fetch_assoc($res_expenses)){
                                $disabledBtn = $expense['attachments_count'] > 0 ? "" : "disabled";
                                echo "<tr>";
                                echo "  <td>".number_format($expense['amount'], 2)."</td>";
                                echo "  <td>".date("d.m.Y", strtotime($expense['date']))."</td>";
                                echo "  <td>{$expense['type']}</td>";
                                echo "  <td>{$expense['subtype']}</td>";
                                echo "  <td>{$expense['attachments_count']}</td>";
                                echo "  <td><a class=\"btn btn-primary btn-sm $disabledBtn\" onclick='showAttachments({$expense['id']})' >prikaži</a></td>";
                                echo "  <td><a class=\"btn btn-success btn-sm\" onclick='addNewAttachment({$expense['id']})' >dodaj</a></td>";
                                echo "</tr>";
                            }

                        ?>
                    </tbody>
                </table>
            </div>

      </div>
                            
      <?php include "./expenses/attachments_modal.php"; ?>
      <?php include "./expenses/new_attachment_modal.php"; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.2/chart.min.js"></script>
    <script src="./assets/js/dashboard_chart.js"></script>
    <script src="./assets/js/dashboard.js"></script>

</body>
</html>