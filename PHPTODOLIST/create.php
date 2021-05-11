<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$task_title = "";
$task_content = "";
$task_title_err = "";
 $task_content_err  = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  
    // Check input errors before inserting in database
    if(empty($task_title_err) && empty($task_content_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO tasks (task_title, task_content) VALUES (?, ?)";
 
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $task_title, $param_task_content);
            
            // Set parameters
            $param_task_title = $task_title;
            $param_task_content = $task_content;
        
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>