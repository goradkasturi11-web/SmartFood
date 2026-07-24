<!DOCTYPE html>
<html>
<head>
<title>Generate Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="container mt-5">

<h2 class="text-center">
Generate Report
</h2>

<form method="GET" action="index.php">

<input type="hidden" name="route" value="admin-reports">


<div class="mb-3">
<label>Start Date</label>
<input type="date" name="start_date" class="form-control" required>
</div>


<div class="mb-3">
<label>End Date</label>
<input type="date" name="end_date" class="form-control" required>
</div>


<button class="btn btn-info">
Generate Report
</button>

</form>

</body>
</html>