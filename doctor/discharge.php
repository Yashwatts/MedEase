<?php
include("header.php");
include("connection.php");
if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['send'])){
    $user_id=$_POST['user_id'];
    $fee=$_POST['fee'];
    $des=$_POST['des'];

    if(empty($fee) || empty($des)){
        echo"<script>alert('Fee and Description are required.');</script>";

    }else{
        $doc=$_SESSION['doctor'];
        $fname=$_POST['full_name'];
        $query="INSERT INTO income (doctor,patient,date_discharge,amount_paid,description,user_id) VALUES ('$doc','$fname',NOW(),'$fee','$des','$user_id')";
        $res=mysqli_query($connect,$query);

        if($res){
            echo"<script>alert('Invoice sent successfully.');</script>";

            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }else{
            echo"<script>alert('Error sending invoice.');</script>";
        }

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discharge page</title>  
</head>
<body>
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="text-dark my-2">
                <h5>Appointment Form</h5>
                <div class="row">
                    <div class="col-md-3" style="margin-left:-30px;margin-top:-40px;">
                        <?php include("sidenav.php");?>
                    </div>
    
    <div class="col-md-10">
        <?php
        if(isset($_GET["id"])){
            $id=$_GET["id"];
            $query= "SELECT * FROM appointment WHERE id='$id'";
            $res=mysqli_query($connect,$query);
            if($res && mysqli_num_rows($res)> 0){
                $row=mysqli_fetch_array($res);
            }
        }

        ?>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered" style="margin-left:px; width:350px;">
                        <tr>
                            <td colspan="2" class="text-center">Appointment Details</td>
                        </tr>
                        <tr>
                            <td>Firstname</td>
                            <td><?php echo isset($row['full_name']) ? $row['full_name'] : 'N/A'; ?></td>
                        </tr>
                        
                        <tr>
                            <td>Gender</td>
                            <td><?php echo isset($row['gender']) ? $row['gender'] : 'N/A'; ?></td>
                        </tr>

                        <tr>
                            <td>Phone No</td>
                            <td><?php echo isset($row['phone']) ? $row['phone'] : 'N/A'; ?></td>
                        </tr>

                        <tr>
                            <td>Appointment Date</td>
                            <td><?php echo isset($row['appointment_date']) ? $row['appointment_date'] : 'N/A'; ?></td>
                        </tr>

                        <tr>
                            <td>Symtoms</td>
                            <td><?php echo isset($row['symptoms']) ? $row['symptoms'] : 'N/A'; ?></td>
                        </tr>
                    </table>

                </div>
                <form method="post">

                    
                    <label>Patient Name</label>
                    <input type="text" name="full_name" class="form-control" autocomplete="off" placeholder="Enter Patient Full Name">
                    <input type="hidden" name="user_id" value="<?php echo $row['user_id']?>;">
                    
                    <label>Fee</label>
                    <input type="number" name="fee" class="form-control" autocomplete="off">
                    
                    
                    <label>Description</label>
                    <input type="text" name="des" class="form-control" autocomplete="off">
                    
                    <input type="submit" name="send" value="Send Invoice">
                </form>
                    
                </div>
        </div>
    </div>               





                </div>
            </div>

        </div>
    </div>
</body>
</html>

