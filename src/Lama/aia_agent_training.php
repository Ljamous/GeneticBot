<?php include './header.php';
require_once './db/agents.php'; 

if(isset($_SESSION["agentid"]))
  $agent_info = get_agent($_SESSION["agentid"]);
else{
  $_SESSION["agentid"] = $_GET["agentid"];
  $agent_info = get_agent($_SESSION["agentid"]);
}
 
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            
          </div>
          <div class="col-sm-6">

          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

     <!-- Main content -->
     <section class="content">
     <div class="row">
          <div class="col-12 col-sm-11 mx-auto">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="tables_views-tab" data-toggle="pill" href="#tables_views" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Tables / Views</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="examples-tab" data-toggle="pill" href="#examples" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Examples</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="rules-tab" data-toggle="pill" href="#rules" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Rules</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="context-tab" data-toggle="pill" href="#context" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">Context</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <form method="post" action="aia_agent_training.php">
                  <div style="text-align: right;">
                    <a href="http://54.37.195.109:8501" target="_blank" class="btn btn-info" title="Preview"><i class="fas fa-search"></i></a>
                    <a href="delete_agent_confirm.php?agentid=<?php echo $_SESSION["agentid"] ?>" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a>
                    <button type="submit" class="btn btn-primary"  name="btn_save" title="Save"><i class="fa fa-save"></i></button>
                  </div>
                <br/>
                <div class="tab-content" id="custom-tabs-four-tabContent">
                  <div class="tab-pane fade show active" id="tables_views" role="tabpanel" aria-labelledby="tables_views-tab">
                    <textarea class="form-control" rows="15" name="agent_tables_views" spellcheck="false">
                        <?php echo $agent_info["agent_tables_views"]; ?>
                    </textarea>
                  </div>
                  <div class="tab-pane fade" id="examples" role="tabpanel" aria-labelledby="examples-tab">
                    <textarea class="form-control" rows="15" name="agent_examples" spellcheck="false">
                        <?php echo $agent_info["agent_examples"]; ?>
                    </textarea>
                  </div>
                  <div class="tab-pane fade" id="rules" role="tabpanel" aria-labelledby="rules-tab">
                    
                     <textarea class="form-control" rows="15" name="agent_rules" spellcheck="false">
                        <?php 
                          echo $agent_info["agent_rules"];
                        ?>
                    </textarea>
                     
                    
                  </div>
                  <div class="tab-pane fade" id="context" role="tabpanel" aria-labelledby="context-tab">
                    <?php
                        $filename = 'Context.txt';

                        // Check if the file exists
                        if (file_exists($filename)) {
                            // Read the file content and store it in a variable
                            $fileContent = file_get_contents($filename);

                            // Convert special HTML characters to HTML entities to prevent XSS attacks
                            $safeContent = htmlspecialchars($fileContent);

                            // Display the content
                            echo nl2br($safeContent); // nl2br converts newlines to <br> tags for HTML display
                        } 
                      ?>
                  </div>
                </div>                
                </form>
              </div>
              <!-- /.card -->
            </div>
          </div>
        </div>
    </section>
</div>

<?php 
if(isset($_POST["btn_save"])){
  edit_agent($_POST["agent_tables_views"], $_POST["agent_examples"],
              $_POST["agent_rules"], $_SESSION["agentid"]);
  
  edit_context_file($_POST["agent_tables_views"], $_POST["agent_examples"],
                      $_POST["agent_rules"], $_SESSION["agentid"], $_SESSION["userid"]);

  echo '<script> window.location = "aia_agent_training.php?agentid='.$_SESSION["agentid"].'" </script>';
  
}
?>

<?php include './footer.php'?>