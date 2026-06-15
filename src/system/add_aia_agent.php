<?php include './header.php'?>

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
                <h3 class="card-title">New AiA Agent</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" method="post" action="add_aia_agent.php">
                <div class="card-body">
                <div class="form-group row">
                    <label for="agent_name" class="col-sm-2 col-form-label">Agent Name</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="agent_name" placeholder="Agent Name" required>
                    </div>
                    <label for="agent_type" class="col-sm-2 col-form-label">Agent Type</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="agent_type" required>
                          <option value="1">SAP HANA</option>
                          <option value="2">MS SQL Server</option>
                          <option value="3">Oracle</option>
                          <option value="4">MySQL</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="db_username" class="col-sm-2 col-form-label">User Name</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="db_username" placeholder="User Name" required>
                    </div>
                    <label for="db_password" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-4">
                      <input type="password" class="form-control" name="db_password" placeholder="Password" required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="db_host" class="col-sm-2 col-form-label">Host</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="db_host" placeholder="Host" required>
                    </div>
                    <label for="db_name" class="col-sm-2 col-form-label">Database Name</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="db_name" placeholder="Database Name" required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="db_port" class="col-sm-2 col-form-label">Port Number</label>
                    <div class="col-sm-2">
                      <input type="number" class="form-control" name="db_port" placeholder="Port Number" required>
                    </div>
                  </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info" style="background-color:#2A2F77" name="add_agent" id="add_agent">Add</button>
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
      require_once './db/agents.php';

      if(isset($_POST["add_agent"])){
        add_aia_agent($_POST["agent_name"], $_POST["agent_type"], $_POST["db_username"],
        $_POST["db_password"], $_POST["db_host"], $_POST["db_name"], $_POST["db_port"], $_SESSION["userid"]);  
        echo '<script>
              window.location = "agent_added.php";
            </script>';
      }
    ?>

<?php include './footer.php'?>


