<?php

  include 'includes/head.php';

  include 'includes/nav.php';

  include 'includes/scripts.php';

  echo '
  <div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">
      <div class="container">
        <br><br>
        <h1 class="header center teal-text text-lighten-2">GeneFreq</h1>
        <div class="row center">
          <h5 class="header col s12 light">Predicting Cancer Risk using Low Frequency Genomic Variant Calls</h5>
        </div>
        <div class="row center">
          <a href="/server/register.php" id="download-button" class="btn-large waves-effect waves-light blue accent-2">Register Today!</a>
        </div>
        <br><br>

      </div>
    </div>
    <div class="parallax"><img src="/images/genes.jpg" alt="Unsplashed background img 1"></div>
  </div>


  <div class="container">
    <div class="section">

      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center brown-text"><i class="material-icons">assignment</i></h2>
            <h5 class="center">Mission</h5>

            <p class="light" style="text-align:center">To provide an accessible and intuitive platform for the interpretation of whole genome sequencing data and clearly indentifying the chief determinants or potential contributors to a pathology and providing this information in an ways that everyone can understand.
</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center brown-text"><i class="material-icons">person_pin</i></h2>
            <h5 class="center">Individualized</h5>

            <p class="light" style="text-align:center">Results are meant to be easily interpreted and targeted for the specific individual.
            It\'s easy - Just set your working directory and watch it go to work and display results that don\'t require medical school
            education to understand your risks. </p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center brown-text"><i class="material-icons">settings</i></h2>
            <h5 class="center">Collaborate</h5>

            <p class="light" style="text-align:center"> Want another pair of eyes on your results? Easily share information with
            fellow healthcare providers to broaden the scope of diagnosis and understand what that means for your patients and you. </p>
          </div>
        </div>
      </div>

    </div>
  </div>



  <div class="container">
    <div class="section">
      <div class="row">
        <div class="col s12 center">
          <h3><i class="mdi-content-send brown-text"></i></h3>
          <h4>Contact Us</h4>
            <a href="contact.php" id="download-button" class="btn-large waves-effect waves-light blue accent-2">Contact Us</a>
        </div>
      </div>
    </div>
  </div>

  ';

  include 'includes/footer.php';


?>
