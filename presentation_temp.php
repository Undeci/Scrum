<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        
    </head>
    <body>
        <div>
            
            <h1>Simulatie</h1>

            <?php foreach ($simMatrix as $key => $day) { ?>
                
                    <table border="1">
                        <thead><h2>Day: <?php echo $key ?></h2></thead><tbody>
                            <?php for ($i = 0; $i < $_SESSION["dimension"]; $i++) { ?>
                        <tr>          <?php 
                                    for ($j = 0; $j < $_SESSION["dimension"]; $j++) {
                                        ?><td style="width: 2em; height: 2em"><?php echo '<span title="' . 'lifeforce: ' . $day[$i][$j]['life'] . '">' .  $day[$i][$j]["type"] . '</span>'?> </td><?php }
                                    ?>
                                </tr><?php }
                                ?>
                    </tbody>
                </table>
            </div>

            <?php
            }
            ?>
    </body>
</html>
