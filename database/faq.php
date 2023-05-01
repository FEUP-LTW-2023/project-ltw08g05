<?php
/**
 * @note prepare - prevent SQL injection attacks
 * @note fetch - get the query results
 * @note PDO::FETCH_ASSOC will ensure that the query results are returned as an associative array indexed by column name.
 */
function getAllFAQ(PDO $dbConnection){
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        $stmt = $dbConnection->prepare('SELECT * FROM FAQ');
        $stmt->execute();
        $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Oops, we've got a problem related to database connection:";
        ?> <br> <?php
        echo $e->getMessage();
      }

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
