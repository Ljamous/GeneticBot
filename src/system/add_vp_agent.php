<?php include './header.php';
       require_once './db/vp_agents.php';

       if(isset($_GET["vpagentid"])){
        $_SESSION["vpagentid"] = $_GET["vpagentid"];
        $vpagent_info = get_vp_agent($_SESSION["vpagentid"]);
       }
      else{
          $vpagent_info = get_agent($_SESSION["vpagentid"]);
      }
?>

  <!-- Content Wrapper. Contains page content -->
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
    <div class="card card-info mx-auto" style="width:80%;">
              <div class="card-header" style="background-color:#489AB9">
                <h3 class="card-title">New VisionPay Agent</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" method="post" action="add_vp_agent.php">
                <div class="card-body">
                <div class="form-group row">
                    <label for="agent_name" class="col-sm-2 col-form-label">Agent Name</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="agent_name" placeholder="Agent Name" required
                      value="<?php echo isset($vpagent_info['agent_name']) ? $vpagent_info['agent_name'] : ''   ?>" 
                      />
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="sample_context" class="col-sm-2 col-form-label">Sample Context</label>
                    <div class="col-sm-12">
                    <textarea class="form-control" rows="15" name="sample_context" spellcheck="false">
<?php echo isset($vpagent_info['sample_context']) ? $vpagent_info['sample_context'] : show_text_files("sample_vp_context.txt"); ?>
                    </textarea>
                    </div>
                  </div> 
                  
                  <div class="form-group row">
                    <label for="sample_json" class="col-sm-2 col-form-label">Sample JSON</label>
                    <div class="col-sm-12">
                    <textarea class="form-control" rows="15" name="sample_json" spellcheck="false">
<?php echo isset($vpagent_info['sample_json']) ? $vpagent_info['sample_json'] : show_text_files("sample_vp_json.txt"); ?>
                    </textarea>
                    </div>
                  </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <?php 
                        if($_SESSION["vpagentid"] > 0){
                          echo '
                              <a href="http://54.37.195.109:8501" target="_blank" class="btn btn-info" title="Preview"><i class="fas fa-search"></i></a>
                              <a href="delete_vp_agent_confirm.php?vpagentid='. $_SESSION["vpagentid"] . '" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a>
                          ';
                        }
                    ?>
                    
                    <button type="submit" class="btn btn-primary"  name="btn_save" title="Save"><i class="fa fa-save"></i></button>
                    <a href="./home.php" class="btn btn-default float-right">Cancel</a>
                </div>
                <!-- /.card-footer -->
              </form>
              
            </div>
            <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php

      if(isset($_POST["btn_save"])){
        if($_SESSION["vpagentid"] == 0){
          add_vp_agent($_POST["agent_name"], $_POST["sample_context"], $_POST["sample_json"], $_SESSION["userid"]);  
          echo '<script>
              window.location = "agent_added.php?vp=1";
            </script>';
        }
      else{
        edit_vp_agent($_POST["agent_name"], $_POST["sample_context"], 
                      $_POST["sample_json"], $_SESSION["vpagentid"]);
        echo '<script>
                window.location = "add_vp_agent.php?vpagentid='.$_SESSION["vpagentid"].'";
              </script>';
      }
    }
    ?>


<?php include './footer.php'?>


