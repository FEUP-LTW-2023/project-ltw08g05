<?php
function getAllFAQ(PDO $connection){
    $stmt = $connection->prepare('SELECT * FROM FAQ');
    $stmt->execute();
    $faqs = $stmt->fetchAll();
    
    return $faqs;
}

function output_FAQ($faq){ ?>
    <h2><?php echo $faq['question']; ?></h2>
    <p><?php echo $faq['answer']; ?></p>
    <?php } ?>

<?php

function output_FAQ_list($faqs){?>
        <section id="FAQ">
          <?php foreach($faqs as $faq) output_FAQ($faq); ?>
        </section>
      <?php } ?>
