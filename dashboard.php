<?php
ob_start();
session_start();
require_once 'server/config.php';

 include 'includes/head.php';
 include 'includes/scripts.php';
 echo '
 <body>
   <nav class="white" role="navigation">
     <div class="nav-wrapper container">
       <a id="logo-container" href="/index.php" class="brand-logo">GeneFreq</a>
       <ul class="right hide-on-med-and-down">
       <li><a href="/server/logout.php">Logout</a></li>
       </ul>

       <ul id="nav-mobile" class="side-nav">
         <li><a href="/server/logout.php">Logout</a></li>
       </ul>
       <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
     </div>
   </nav>';

echo '
<div id="index-banner" class="parallax-container">
  <div class="section no-pad-bot">
    <div class="container">
      <br><br>
      <h1 class="header center teal-text text-lighten-2">';
      $sessionId_variable = $_SESSION['user'];
      $query = "SELECT userId, userName, userEmail FROM users WHERE userId=$sessionId_variable";
      $res=mysql_query($query);
      $row=mysql_fetch_array($res);
      $count = mysql_num_rows($res);
      echo $row['userName'];
      //echo $query;

      echo '\'s Dashboard</h1>
      <div class="row center">
        <h5 class="header col s12 light"> </h5>
      </div>
      <br><br>

    </div>
  </div>
  <div class="parallax"><img src="/images/header.jpeg" alt="Unsplashed background img 1"></div>
</div>

<div class="container">
  <div class="section">

    <!--   Icon Section   -->
    <div class="row">
      <div class="col s12 m4">
        <div class="icon-block">
          <h2 class="center brown-text"><i class="material-icons">library_add</i></h2>
          <h5 class="center">Upload your Files</h5>
          <!-- Modal Trigger -->
          <a class="waves-effect waves-light btn modal-trigger" href="#modal1" style="display: block; margin: auto; width: 40%;">Instructions</a>
        </div>
      </div>

      <div class="col s12 m4">
        <div class="icon-block">
          <h2 class="center brown-text"><i class="material-icons">play_circle_filled</i></h2>
          <h5 class="center">Press the Play button below</h5>
        </div>
      </div>

      <div class="col s12 m4">
        <div class="icon-block">
          <h2 class="center brown-text"><i class="material-icons">insert_chart</i></h2>
          <h5 class="center">View your results!</h5>

        </div>
      </div>
    </div>

    <div class="row">
    <form class="col s12 m4" target="">
        <div class="input-field">
          <i class="material-icons prefix">assignment</i>
          <textarea id="filepath" class="materialize-textarea"></textarea>
          <label for="icon_prefix2">File Path</label>
        </div>
        <button class="btn btn-block btn-primary blue accent-2" style="margin:auto" id="file_name" type="button" onclick="getData()">Enter</button>

        <!-- Modal Structure -->
        <div id="modal1" class="modal">
          <div class="modal-content">
            <h4>Instructions for Entering the Working Directory</h4>
            <ul>
              <li>Normal folder -> containing fastq files from sequencer of \‘normal\’ reads</li>
              <li>Mars folder -> containing fastq files from sequence of \‘alternate\’ reads</li>
              <li>Reference fasta file</li>
              <li>cancer_genes.csv (downloaded from <a href="http://cancer.sanger.ac.uk/census/"> http://cancer.sanger.ac.uk/census/</a>)</li>
              <li>BWA Mem software repo (download and install according to <a href="https://github.com/lh3/bwa">https://github.com/lh3/bwa</a>)</li>
              <li>Strelka software repo (download and install according to <a href="https://github.com/genome-vendor/strelka"> https://github.com/genome-vendor/strelka</a>)</li>
            </ul>
          </div>
          <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
          </div>
        </div>
    </form>


    <script type="text/javascript">
        $(\'#file_name\').click(function() {
          var text = $(\'textarea#filepath\').val();
          //send to server and process response
        });
    </script>

      <div class="col s12 m4">
        <div class="center-btn">
          <a class="btn-floating btn-large waves-effect waves-light green" id="run_code"><i class="material-icons">play_arrow</i></a>
          <br>
          <br>
          <div class="progress">
              <div class="indeterminate"></div>
          </div>

        </div>

       </div>

      <script type="text/javascript">
          $(\'#run_code\').click(function() {
            var text = $(\'textarea#filepath\').val();

            $(\'#run_code\').hide();
            $(\'.progress\').show();


            $.ajax({
                 type:"POST",
                 url: "r_code.php",
                 data: "file_path=" + text,
                 success: function(data){

                   $(\'#run_code\').show();
                   $(\'.progress\').hide();

                    $.getScript("/js/transport.js");
                   //do stuff after the AJAX calls successfully completes
             }

             });


            //send to server and process response
          });
      </script>

    </div>
    <br>
    <br>
    <br>
    <div class="row">
      <div class="col s12 m6 ">
        <h5 style="text-align:center"> Organize your Data! </h5>

        <div class ="description">

              <select style="display: block; margin: auto; width: 40%;">
                <option id="item" value="demand">(QSS) - Quality Score</option>
                <option id="item" value="bus">Percentage of Cancer</option>
              </select>
                <br>
                <br>
                <br>
                  <!--<li><input type="checkbox" class="checkbox" name="check" > </li>
                  <li>Sort by percent change</li> -->
                  <input type="checkbox" class="checkbox" id="decrease" />
                  <label for="decrease">Sort By Decreasing</label>
                  <br>
                  <br>
                  <br>
                  <input type="checkbox" class="checkbox" id="increase" />
                  <label for="increase">Sort By Increasing</label>

              </div>

      </div>
      <br>
      <div id="chart-container"></div>

    </div> <!--description-->

  </div>
</div>




';

?>
<?php ob_end_flush(); ?>
