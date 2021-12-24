<?php 

    include './db_connect.php';
    include './auth.php';
    $currentPage = "reports";

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

    <?php include('./navbar.php'); ?>

    <div class="container pt-5">
      
      <div class="row">
          <div class="col-2">
              <input type="date" class="form-control" placeholder="Datum od" name="date_from" id="date_from">
          </div>
          <div class="col-2">
            <input type="date" class="form-control" placeholder="Datum do" name="date_to" id="date_to">
          </div>
          <div class="col-2">
            <input type="number" step="0.01" class="form-control" placeholder="Iznos od" name="amount_from" id="amount_from">
          </div>
          <div class="col-2">
            <input type="number" step="0.01" class="form-control" placeholder="Iznos do" name="amount_to" id="amount_to">
          </div>
          <div class="col-2">
             <select name="expense_type_id" class="form-control" id="selectType"></select>
          </div>
          <div class="col-2">
              <button class="btn btn-primary w-100" onclick="showReport()">Generiši izvještaj</button>
          </div>
      </div>
      <div class="row mt-4">
          <div class="col-3 offset-9 text-end">
              <input type="checkbox" name="group_by_type" id="group_by_type">
              <label for="group_by_type"> grupisano po tipu</label>
          </div>
      </div>

      <div class="row mt-4" id="result-div">
          <div class="d-none col-12 table-responsive" id="table-wrapper">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tip troška</th>
                        <th id="report_date_th" >Datum</th>
                        <th>Iznos</th>
                    </tr>
                </thead>
                <tbody id="reportTableBody">

                </tbody>
            </table>

            <div class="row mt-3">
                <div class="col-3">
                    <form action="./reports/export_xlsx.php" method="GET">
                        <input type="hidden" name="grouped" id="groupedChk" value="0">
                        <button class="btn btn-success w-100">Eksportuj u XLSX</button>
                    </form>
                </div>
            </div>
          </div>
      </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" ></script>
    <script src="./assets/js/app.js"></script>

    <script>
        window.addEventListener("load", () => {
            loadTypes();
        });

        async function showReport(){

            let grouped = document.getElementById("group_by_type").checked;
            let url = "./reports/generate_report.php";
            if(grouped) url = "./reports/generate_report_grouped.php";

            let formData = new FormData();

            if(document.getElementById("amount_from").value < 0 || document.getElementById("amount_to").value < 0){
                alert("Pogrešni podaci! Iznos ne može biti negativan.");
                return;
            }

            formData.append('date_from', document.getElementById("date_from").value);
            formData.append('date_to', document.getElementById("date_to").value);
            formData.append('amount_from', document.getElementById("amount_from").value);
            formData.append('amount_to', document.getElementById("amount_to").value);
            formData.append('expense_type_id', document.getElementById("selectType").value);

            let response = await fetch(url, { method: 'POST', body: formData });
            let reportData = await response.json();

            displayReportTable(reportData, grouped);
        }

        function displayReportTable(reportData, grouped = false){
            let rows = '';
            let total = 0;

            if(grouped) document.getElementById("report_date_th").classList.add("d-none");
            else document.getElementById("report_date_th").classList.remove("d-none");

            reportData.forEach((data) => {

                let dateColumn = "";
                if(!grouped) dateColumn = `<td>${data.date}</td>`;

                rows += `<tr><td>${data.type_name}</td>${dateColumn}<td>${parseFloat(data.amount).toFixed(2)} €</td></tr>`;
                total += parseFloat(data.amount);

            });
            let colspan = '';
            if(!grouped) colspan = 'colspan="2"';
            rows += `<tr class="row-total"><td ${colspan}>Ukupno: </td><td>${total.toFixed(2)} €</td></tr>`;

            if(reportData.length > 0){
                document.getElementById("reportTableBody").innerHTML = rows;
                document.getElementById("table-wrapper").classList.remove("d-none");
            }else{
                document.getElementById("table-wrapper").classList.add("d-none");
                showNoDataWarning();
            }
        }

        function showNoDataWarning(){
            let alert = `<div class="alert alert-warning text-center col-12">Nema podataka za zadati kriterijum!</div>`;
            document.getElementById("result-div").innerHTML += alert;
        }

        document.getElementById("group_by_type").addEventListener('change', () => {
            if(document.getElementById("group_by_type").checked){
                document.getElementById("groupedChk").value = 1;
            }
            else{
                document.getElementById("groupedChk").value = 0;
            }
        });
    </script>

</body>
</html>