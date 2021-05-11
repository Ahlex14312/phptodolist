<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$task_title = $task_content= "";
$task_title_err = $task_content_err  = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Check input errors before inserting in database
    if(empty($task_title_err) && empty($task_content_err)){
        // Prepare an update statement
        $sql = "UPDATE tasks SET task_title=?, task_content=? WHERE id =?";
 
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_task_title, $param_task_content, $param_id);
            
            // Set parameters
            $param_task_title = $task_title;
            $param_task_content = $task_content;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $mysqli->close();
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM tasks WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $result = $stmt->get_result();
                
                if($result->num_rows == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $task_title = $row["task_title"];
                    $task_content = $row["task_content"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        $stmt->close();
        
        // Close connection
        $mysqli->close();
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($task_title_err)) ? 'has-error' : ''; ?>">
                            <label>Title</label>
                            <input type="text" name="task_title" class="form-control" value="<?php echo $task_title; ?>">
                            <span class="help-block"><?php echo $task_title_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($task_content_err)) ? 'has-error' : ''; ?>">
                            <label>Content</label>
                            <textarea name="task_content" class="form-control"><?php echo $task_content; ?></textarea>
                            <span class="help-block"><?php echo $task_content_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>