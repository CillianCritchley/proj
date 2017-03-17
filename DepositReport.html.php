<?php session_start();
if((isset($_SESSION['errorVarCustReport']) || isset($_SESSION['customerID'])) && $_SERVER['HTTP_REFERER'] != 'http://localhost/proj/DepositReport.html.php')
{
    session_unset();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="Cillian.css" type="text/css">
    <style>
        form{
            margin: 1em;
            max-width: 500px;
        }
        label{
            display: inline-block;
            width: 9em;
            margin-right: 1em;
            margin-top : 1em;
            text-align: right;
        }


    </style>
    <script>
        function  populate()
        {
            var sel = document.getElementById("listbox");
            var result;
            result = sel.options[sel.selectedIndex].value;
            var personDetails = result.split(',');
            document.getElementById("customerID").value = personDetails[0];
            document.getElementById("customerIDHide").value = personDetails[0];
            document.getElementById("firstname").value = personDetails[1];
            document.getElementById("surname").value = personDetails[2];
            document.getElementById("addressLine1").value = personDetails[4];
            document.getElementById("addressLine2").value = personDetails[5];
            document.getElementById("addTown").value = personDetails[6];
            document.getElementById("addCounty").value = personDetails[7];
            document.getElementById("dateOfBirth").value = personDetails[3];

            return false;
        }


        function dateCheck() {
            var fromDate = Date.parse(document.getElementById("searchFrom").value);
            var toDate = Date.parse(document.getElementById("searchTo").value);
            var now = new Date();
            if (now < fromDate) {
                alert("Search From Date cannot be in the future");
                return false;
            }
            else if (now < toDate) {
                alert("cannot search into the future");
                return false;
            }
            else if (fromDate > toDate)
            {
                alert("Search From date cannot be greater than Search To Date");
                return false;
            }
            else{
            return true;
            }

            }
        function formCheck(check)
        {
            /*if the value pulled from the database for this customer for addressLine2 is empty
             don't output it.
             */
            if(document.getElementById("addressLine2").value == "")
            {
                var address = document.getElementById("addressLine1").value + "\n                          "
                    + document.getElementById("addTown").value + "\n                          "
                    + document.getElementById("addCounty").value;
            }
            else {
                var address = document.getElementById("addressLine1").value + "\n                          "
                    + document.getElementById("addressLine2").value + "\n                          "
                    + document.getElementById("addTown").value + "\n                          "
                    + document.getElementById("addCounty").value;
            }
            if(check == "reset")
            {
                return true;
            }
            else{
                return confirm("Customer Id:     " + document.getElementById("customerID").value +
                    " \nName :              " +  document.getElementById("firstname").value + " " +  document.getElementById("surname").value +
                    "\nDate of Birth:    " + document.getElementById("dateOfBirth").value +
                    "\nAddress :           " +  address +
                    "\n\n  Please confirm this is the correct Customer"
                ) ;

            }

        }
        function checkEmpty(button)
        {
            var cus = document.getElementById("customerID").value;

            if(button == "searchCustomer" && cus == "")
            {
                alert("cannot submit empty field");
                return false;
            }
            else{
                return true;
            }
        }

        window.onload = function(){

            document.getElementById('listbox').selectedIndex = -1;
            <?php if(isset($_SESSION['errorVarCustReport'])) { ?> alert("Customer ID " + <?php echo $_SESSION['customerID'] ?> +
                    " does not exist");  <?php session_unset();}?>

        }
    </script>


</head>
<body>
<div id="top">

<h1> View Deposit Account</h1>
<h4> Please select a person from the list or search by Customer Number </h4>
</div>
<div id="mid">
<div id="left">
    <form  action="DepositReport.php"  onsubmit="return checkEmpty(this.submited);" method="post">
    <table>
        <tr> <td>
                <font size="5">  Select Name From List </font> </td> </tr>
        <tr>
            <td> <?php include 'reportcustnamesearch.php'; ?> Or
            </td> </tr>
        <tr> <td>  <label for "customerID" > Search By Customer ID </label>
            </td> </tr>

        <tr> <td>    <input class="InputAddOn-field"  type = "text" pattern="[0-9]+" title="numeric only" name = "customerID" id = "customerID"
                            value="<?php if(ISSET($_SESSION['customerID'])) echo htmlspecialchars($_SESSION['customerID'])?>">
            </td></tr>
        <tr> <td>      <button type="submit" onclick="this.form.submited=this.name;" name="searchCustomer" id="search" class="InputAddOn-item"> Search by Customer Number</button>
            </td></tr>
        <tr> <td> </td></tr>


    </table>
    </form>
</div>
<div id="midleft">

<form  action="DepositReport.php"   onsubmit="return formCheck(this.submited);" method="post">
    <input  type = "hidden" name = "customerIDHide" id = "customerIDHide"
           value="<?php if(ISSET($_SESSION['customerID'])) echo $_SESSION['customerID']?>">
    <label for "amendfirstname">First Name </label>
    <input readonly type = "text" name = "firstname" id = "firstname"
           value="<?php if(ISSET($_SESSION['firstname'])) echo $_SESSION['firstname'] ?>">
    <label for "amendlastname">Surname </label>
    <input readonly type = "text" name = "surname" id = "surname"
           value="<?php if(ISSET($_SESSION['surname'])) echo $_SESSION['surname'] ?>">
    <label for "amendDOB">Date of Birth </label>
    <input readonly type = "text" name = "dateOfBirth" id = "dateOfBirth" title = "format is dd-mm-yyyy"
           value="<?php if(ISSET($_SESSION['dateOfBirth']))  {
               $date= date_create($_SESSION['dateOfBirth']); $date = date_format($date,"d-m-Y"); echo $date; }?>">
    <label for "addressLine1">Address Line 1</label>
    <input readonly type = "text" name = "addressLine1" id = "addressLine1"
           value="<?php if(ISSET($_SESSION['addressLine1'])) echo $_SESSION['addressLine1'] ?>">
    <label for "addressLine2">Address Line 2 </label>
    <input readonly type = "text" name = "addressLine2" id = "addressLine2"
           value="<?php if(ISSET($_SESSION['addressLine2'])) echo $_SESSION['addressLine2'] ?>">
    <label for "addTown">Town </label>
    <input readonly type = "text" name = "addTown" id = addTown
           value="<?php if(ISSET($_SESSION['addTown'])) echo $_SESSION['addTown'] ?>">
    <label for "addCounty">County </label>
    <input readonly type = "text" name = "addCounty" id = "addCounty"
           value="<?php if(ISSET($_SESSION['addCounty'])) echo $_SESSION['addCounty'] ?>">
    <br><br>
    <input type="submit"  name="confirm" id="confirm" onclick="this.form.submited=this.value;" value="Confirm Customer">
    <input type="submit"  name="reset" id="reset" onclick="this.form.submited=this.value;" value="reset">

</form>
</div>
    <div id="rightReport">
<div id="righttop">
<?php if(ISSET($_SESSION['resultsReport']) && (count($_SESSION['resultsReport'])) > 0 )
{
$tempARR = $_SESSION['resultsReport'];
?>   <form action='DepositReport.php' onsubmit="return dateCheck();" name="depositReport" id="depositReport" method='post'>
        <input  type = "hidden" name = "customerIDHide2" id = "customerIDHide2"
                value="<?php if(ISSET($_SESSION['customerID'])) echo htmlspecialchars($_SESSION['customerID'])?> ">
        <table>
		<tr> <th> Select</th><th></th><th> Account ID</th><th>Balance</th><th> Date Opened </th> </tr>
<?php



foreach ($tempARR as $row)
{

?>
    <!-- table row for each row in the $tempARR array -->
    <tr>
            <!-- give each radio button the value of the account ID associated with this row -->
        <?php echo "<td> <input type=\"radio\" name='radio' value=".$row['depositAccountID'].">  </td>";

        foreach ($row as $rowItem) {
            // fill each column of the row
            echo
                "<td>" . $rowItem . "</td>";

        }   //inner loop
        // end of table row
        echo "</tr>";


        }    // outer loop
        echo "</table> 
        
				</form> " ;


        }
        else if(ISSET($_SESSION['resultsReport']) && (count($_SESSION['resultsReport'])) == 0 )
        {
            echo "Customer has no Deposit Accounts";
        }
        ?>

</div>
        <div id="righttopleft">
            <input type = 'submit'  name='genReport' form="depositReport" value="generate report">
            <label for="searchFrom"> Search From</label>
                    <input type = "date"  name="searchFrom" id="searchFrom" placeholder="optional" pattern="^\d{4}-\d\d-\d\d$" title="yyyy-mm-dd"  form="depositReport">
            <label for="searchTo"> Search To</labeL>
                <input type = "date"  name="searchTo" id="searchTo" placeholder="optional" pattern="^\d{4}-\d\d-\d\d$" title="yyyy-mm-dd" form="depositReport"> </div>
<div id="rightbottom">
    <div id="reportHeaderDiv">
        <?php
        if(isset($_SESSION['trans'])) {
            ?>
            <TABLE>
                <TR>
                    <TD> First Half of Text</TD>
                </TR>
                <tr>
                    <TD> Image</TD>
                    <TD> Second Half of Text</TD>
                </TR>
            </TABLE>
            <?php
        }
        ?>
    </div>
    <div id="reportContentDiv">

               <?php
               if(isset($_SESSION['trans']))
               {
                    ?> <table>
                        <tr>
                            <th> Transaction ID</th>
                            <th> Amount</th>
                            <th> Date</th>
                            <th> Type</th>
                        </tr>


                        <?php
                        foreach ($_SESSION['trans'] as $transrow) {
                            echo "<tr>";
                            foreach($transrow as $transactionindex)
                            {
                            echo " 
                            <td> " . $transactionindex . "</td>
                          
                          ";

                        }
                        }

                        echo "</tr></table> </td> </tr>";
}



                        ?>

    </div>




</div>

            </div>  <!-- div righttop -->

    </div> <!-- div right -->
</body>
</html>
