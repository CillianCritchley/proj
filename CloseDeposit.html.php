
<?php session_start();
/*
 * if a session variable exists storing a customerID(the actual value or variable isn't important, it's just
 * to account for an actual session variable existing and customerID is the most common) and the referrer
 * is any page other than this one, unset the session. Before this the session variables would populate the form
 * fields on every page regardless of which page they were created as a result of. It didn't look good.
 * func.php is a file with some php functions stored in it
 */
if(isset($_SESSION['customerID']) && $_SERVER['HTTP_REFERER'] != 'http://localhost/proj/CloseDeposit.html.php')
{
    session_unset();
}
include 'func.php';
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="Cillian.css" type="text/css">

    <style>

        label{
            display: inline-block;
            width: 9em;
            margin-right: 1em;
            margin-top : 1em;
            text-align: right;
        }
    </style>

    <script type="text/JavaScript" src="cillianscript.js">
        </script>

</head>
<body >
<div id="top">
<h1> Close Deposit Account</h1>

    <h4> Please select a person from the list or search by Customer Number </h4>
</div>
<div id="mid">
                <!-- checkEmpty() function is located in cillian.js. it ensures that a value must be entered
                 into an appropriate field before the form can be submitted -->
        <div id="left" > <form class="form1" action="CloseDeposit.php"   onsubmit="return checkEmpty(this.submited);" method="post">
            <table>
                <tr> <td>
                        <font size="5">  Select Name From List </font> </td> </tr>
                <tr>
                    <td> <?php include 'closecustnamesearch.php'; ?> Or
                    </td> </tr>
                <tr> <td>  <label for "customerID" > Search By Customer ID </label>
                    </td> </tr>

                <tr> <td>    <input class="textfield"  pattern="[0-9]{1,}" title="numeric only"
                                    type = "text" name = "customerID" id = "customerID"
                                    value="<?php if(ISSET($_SESSION['customerID']))
                                        echo htmlspecialchars($_SESSION['customerID'])?>">
                    </td></tr>
                <tr> <td>      <button type="submit" class="formitem" onclick="this.form.submited=this.name;"
                                       name="searchCustomer" id="searchCustomer" class="InputAddOn-item"> Search by Customer Number</button>
                    </td></tr>
                <tr> <td>     <tr> <td>  <label for "accountID" > Search By Account ID </label>
                    </td> </tr></td></tr>
                <tr> <td>    <input class="textfield"  pattern="[0-9]{1,}" title="numeric only"
                                    type = "text" name = "accID" id = "accID"
                                    value="<?php if(ISSET($_SESSION['accID']))
                                        echo htmlspecialchars($_SESSION['accID'])?>">
                    </td></tr>

                <tr> <td>      <button type="submit" class="formitem" onclick="this.form.submited=this.name;" name="searchAccount"
                                       id="searchAccount" class="InputAddOn-item"> Search by Account Number</button>
                    </td></tr>
            </table>
        </form> </div>

<div id="midleft">

<form  class="form1" action="CloseDeposit.php" onsubmit="return formCheck(this.submited);" id="ConfirmReset" method="post">
<!--   if the session variables associated with the information related to these fields exist, output the values
stored to the field. Fields are readonly so this is purely for user information. -->
    <input type = "hidden" class="textfield" name = "customerIDHide" id = "customerIDHide"
           value="<?php if(ISSET($_SESSION['customerID'])) echo htmlspecialchars($_SESSION['customerID'])?>">
    <label for "amendfirstname">First Name </label>
    <input readonly type = "text" class="textfield" name = "firstname" id = "firstname"
           value="<?php if(ISSET($_SESSION['firstname'])) echo $_SESSION['firstname'] ?>">
    <label for "amendlastname">Surname </label>
    <input readonly type = "text" class="textfield" name = "surname" id = "surname"
           value="<?php if(ISSET($_SESSION['surname'])) echo $_SESSION['surname'] ?>">
    <label for "amendDOB">Date of Birth </label>
    <input readonly type = "text" class="textfield" name = "dateOfBirth" id = "dateOfBirth" title = "format is dd-mm-yyyy"
           value="<?php if(ISSET($_SESSION['dateOfBirth']))  {
               $date= date_create($_SESSION['dateOfBirth']); $date = date_format($date,"d-m-Y"); echo $date; }?>">
    <label for "addressLine1">Address Line 1</label>
    <input readonly type = "text" class="textfield" name = "addressLine1" id = "addressLine1"
           value="<?php if(ISSET($_SESSION['addressLine1'])) echo $_SESSION['addressLine1'] ?>">
    <label for "addressLine2">Address Line 2 </label>
    <input readonly type = "text" class="textfield" name = "addressLine2" id = "addressLine2"
           value="<?php if(ISSET($_SESSION['addressLine2'])) echo $_SESSION['addressLine2'] ?>">
    <label for "addTown">Town </label>
    <input readonly type = "text" class="textfield" name = "addTown" id = addTown
           value="<?php if(ISSET($_SESSION['addTown'])) echo $_SESSION['addTown'] ?>">
    <label for "addCounty">County </label>
    <input readonly type = "text" class="textfield"  name = "addCounty" id = "addCounty"
           value="<?php if(ISSET($_SESSION['addCounty'])) echo $_SESSION['addCounty'] ?>">
    <br><br>
    <input type="submit"  class="formitem" onclick="this.form.submited=this.value;"  name="confirm" id="confirm" value="Confirm Customer">

        <input type="submit" class="formitem" onclick="this.form.submited=this.value;" name="reset" id="reset"  value="reset">

<br><br><br>

</form>
</div>
<div id="right">
<div id="rightleft">
<?php
/*
 * if $_SESSION['results'] exists and has a size greater than 0 this means a list of deposit accounts was created
 * and assigned to it so it's safe to try and fill a form and table with details from it.
 * The ['results'] variable holds a 2d array, so two foreach loops are used. The first ($tempARR as $row) assigns
 * the values of the relevant deposit account ID, the index position in the array and the account Balance to hidden
 * input fields in the form associated with the Close button. These values are posted over to CloseDeposit.php.
 * The second foreach loop fills each particular row with the details of the account
 */
if(ISSET($_SESSION['results']) && (count($_SESSION['results'])) > 0 )
{
    $tempARR = $_SESSION['results'];
    echo "<table style=\"width:100%\" >
		<tr> <th width='20%'> Close Account </th><th  width='20%'> Account ID</th><th width='20%'>Balance</th><th width='20%'> Date Opened </th> </tr>";



    {
        $index=0;
        foreach($tempARR as $row)
        {

           ?>
                            <!-- depositAccountID and index are required to close the accounts, balance
                             is sent over to provide information in an error message if required .
                             a confirmation message pops up asking the user to confirm they wish to close the
                             selected account -->
         <tr>  <td class="centerTable"> <form id="form2" onsubmit="return confirm('are you sure you want to close account' +
                         ' <?php echo $row['depositAccountID'] ?> ?');" action="CloseDeposit.php" method="post">

                    <input type="hidden" id="depAccID" name="depAccID" value="<?php echo $row['depositAccountID']; ?>">
                    <input type="hidden" id="balance" name="balance" value="<?php echo $row['balance']; ?>">
                    <input type="hidden" id="index" name="index" value="<?php echo $index; ?>">
                     <input type="submit" class="formitem" value="Close" id="closeAcc" name="closeAcc"
                            title="Click here to close the Account">
                </form></td>
    <?php

            foreach($row as $rowItem)
            {
                echo
                    "<td class='centerTable'>".$rowItem."</td>";

            }
            echo "</tr>";
            ?>
            <tr> <td>


            </td></tr>

    <?php
            $index++;
        }
        echo "</table>";





 }
}
/*
 * If the user searches for a customerID and confirms the customer details but the customer has no deposit accounts
 * this message will appear in an alert box and also on the screen where the above table would otherwise be.
 */
else if(ISSET($_SESSION['results']) && (count($_SESSION['results'])) == 0 )
{
    echo "<script> alert(\"Customer has no Deposit Accounts\") </script> 
            Customer has no Deposit Accounts";
}

?>
</div>  <!-- div id ="rightleft"> -->
</div> <!--  <div id="right"> -->

    </div>  <!-- <div id="mid"> -->

</body>
</html>
