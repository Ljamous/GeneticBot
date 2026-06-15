<?php include './header.php'?>

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
      </div>
    </section>

    <section class="content">
    <div class="error-page" style="width:65%">
        
        <br/><br/>
        <div class="error-content">
          <h2><i class="fas fa-exclamation-triangle text-danger"></i> Do you want to delete this agent ?</h2>

            <div style="text-align: right; width:90%">
                <br/><br/>
                <a href="home.php" class="btn btn-info">No</a>
                 &nbsp;&nbsp;&nbsp;
                <a href="delete_agent.php?vpagentid=<?php echo $_GET["vpagentid"] ?>" class="btn btn-danger">Yes</a>
            </div>
        </div>
      </div>
    </section>

</div>

<?php include './footer.php'?>