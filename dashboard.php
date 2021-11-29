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
                          <select name="expense_type_id" class="form-control" id="selectType" onchange="getSubtypes()">
                              <option value="">- odaberite tip -</option>
                              <?php 
                              
                                $sql_types = "SELECT 
                                                et.id,
                                                et.name,
                                                uet.user_id 
                                            from expense_types et 
                                            join user_expense_type uet on et.id = uet.expense_type_id and uet.user_id = $currentUserId
                                            order by et.name ASC
                                ";
                                $res_types = mysqli_query($dbconn, $sql_types);

                                while($type = mysqli_fetch_assoc($res_types)){
                                    
                                    $type_id = $type['id'];
                                    $type_name = $type['name'];
                                    
                                    echo "<option value=\"$type_id\" >$type_name</option>";
                                }

                              ?>
                          </select>
                      </div>
                      <div class="col-3">
                            <label for="selectSubtype">Podtip troška:</label>
                            <select name="expense_subtype_id" class="form-control" id="selectSubtype"></select>
                      </div>
                  </div>
              </form>
          </div>
      </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" ></script>
    <script>

        function save(type_id){
            let url = "./users/add_remove_type.php";
            let formData = new FormData();
            formData.append('type_id', type_id);

            fetch(url, { method: 'POST', body: formData })
                .then(function (response) {
                    return response.text();
                }).then(function (body) {
                    console.log(body);
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

    </script>
</body>
</html>