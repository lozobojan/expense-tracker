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

            <!-- last 10 expenses table -->
            <div class="col-9 offset-3 table-responsive">
                <h5>Poslednjih 10 troškova</h5>
                <table class="table table-stripped table-hover mt-3" >
                    <thead>
                        <tr>
                            <th>Iznos</th>
                            <th>Datum</th>
                            <th>Tip</th>
                            <th>Podtip</th>
                            <th>Pridruženi fajlovi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                            $sql_expenses = "SELECT  
                                                e.id,
                                                e.amount,
                                                e.`date`,
                                                et.name as `type`,
                                                coalesce(es.name, '') as subtype
                                            FROM expenses e 
                                            join expense_types et on e.expense_type_id = et.id 
                                            left join expense_subtypes es on e.expense_subtype_id = es.id 
                                            WHERE user_id = $currentUserId ORDER BY e.date DESC LIMIT 10";
                            $res_expenses = mysqli_query($dbconn, $sql_expenses);

                            while($expense = mysqli_fetch_assoc($res_expenses)){
                                echo "<tr>";
                                echo "  <td>".number_format($expense['amount'], 2)."</td>";
                                echo "  <td>".date("d.m.Y", strtotime($expense['date']))."</td>";
                                echo "  <td>{$expense['type']}</td>";
                                echo "  <td>{$expense['subtype']}</td>";
                                echo "  <td><a class=\"btn btn-primary btn-sm\" onclick='showAttachments({$expense['id']})' >prikaži</a></td>";
                                echo "</tr>";
                            }

                        ?>
                    </tbody>
                </table>
            </div>

      </div>
                            
      <?php include "./expenses/attachments_modal.php"; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" ></script>
    <script>
        
        window.addEventListener("load", () => {
            loadTypes();
        });

        function save(type_id){
            let url = "./users/add_remove_type.php";
            let formData = new FormData();
            formData.append('type_id', type_id);

            fetch(url, { method: 'POST', body: formData })
                .then(function (response) {
                    return response.text();
                }).then(function (body) {
                    console.log(body);

                    loadTypes();
            });


        }

        async function getSubtypes(){
            let type_id = document.getElementById('selectType').value;
            let response = await fetch('./types/get_subtypes.php?type_id='+type_id);
            let subtypes = await response.json();

            let subtypeOptions = "";
            subtypes.forEach( (subtype) => {
                subtypeOptions += `<option value="${subtype.id}" >${subtype.name}</option>`;
            });

            document.getElementById('selectSubtype').innerHTML = subtypeOptions;
        }

        async function loadTypes(){
            let response = await fetch("./users/load_types.php");
            let types = await response.json();

            let typeOptions = "<option value=\"\">- odaberite tip -</option>";
            types.forEach( (type) => {
                typeOptions += `<option value="${type.id}" >${type.name}</option>`;
            });

            document.getElementById('selectType').innerHTML = typeOptions;
        }

        async function showAttachments(expense_id){
            let response = await fetch("./expenses/get_attachments.php?expense_id="+expense_id);
            let attachments = await response.json();

            let tableBody = "";
            attachments.forEach((attachment) => {
                let downloadBtn = `<a download href="${attachment.file_path}" class="btn btn-sm btn-primary" >preuzmi</a>`;
                tableBody += `<tr><td>${attachment.description}</td><td>${downloadBtn}</td></tr>`;
            });

            document.getElementById("attachmentsTableBody").innerHTML = tableBody;
            let attachmentModal = new bootstrap.Modal(document.getElementById('attachmentsModal'));
            attachmentModal.show();
        }

    </script>
</body>
</html>