<?php
include("../app/config.php");
$calls_controller = new CallsController();
$statsAndLast = $calls_controller->getStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>Statistical Data of Calls</title>
</head>
<body>
<h1>Statistical Data of Calls</h1>
<hr/>

<h2 id="show_form">Add Data ˅</h2>
<p id="form">
  <input type="file" id="file" name="file"><br/>
  <label>CSV File only</label><br/><br/>
  <button id="upload">Send</button>
  <span id="message"></span>
</p>
<hr/>
<h2>Statistical Table</h2>
    <table id="customers">
      <tr>
        <th>Customer ID</th>
        <th>Number of calls within same continent</th>
        <th>Total Duration of calls within same continent</th>
        <th>Total Numbers of all calls</th>
        <th>Total Duration of all calls</th>
      </tr>
      <?php
      foreach($statsAndLast->stats as $stat) 
      {
        print("<tr>");
        print("<td>". $stat['customer_id'] . "</td>");
        print("<td>". $stat['total_calls_same_continent'] . "</td>");
        print("<td>". $stat['total_duration_same_continent'] . "</td>");
        print("<td>". $stat['total_calls'] . "</td>");
        print("<td>". $stat['total_duration'] . "</td>");
        print("</tr>");
      }
      ?>
</table>
<input type="hidden" id="last_inserted" value="<?php print($statsAndLast->last_inserted);?>" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="assets/script.js"></script>
</body>
</html>