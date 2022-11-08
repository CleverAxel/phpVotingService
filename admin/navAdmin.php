<?php
    function navAdmin(string $activeLink){
        $linkNav = [
            [
                "link" => "./index.php",
                "icon" => "fa-solid fa-house",
                "name" => "Index dashboard"
            ],
            [
                "link" => "./createActivity.php",
                "icon" => "fa-solid fa-file-circle-plus",
                "name" => "Ajouter une activité"
            ],
            [
                "link" => "./allActivities.php",
                "icon" => "fa-solid fa-newspaper",
                "name" => "Toutes les activités"
            ],
            [
                "link" => "./detailsActivity.php",
                "icon" => "fa-solid fa-info",
                "name" => "Détails de l'activité"
            ],
            [
                "link" => "./resultOfVotes.php",
                "icon" => "fa-solid fa-ranking-star",
                "name" => "Résultat des votes"
            ],
            [
                "link" => "./nuke.php",
                "icon" => "fa-solid fa-bomb",
                "name" => "/!\ NUKE /!\\",
            ]
        ];
        echo '<ul>';
        foreach ($linkNav as $link) {
            $active = "";
            if($activeLink == $link["link"]){
                $active = "active";
            }else{
                $active = "";
            }
            echo 
            '
            <li>
                <a href="'. $link["link"] .'" class="linkNav '. $active .'">
                    <div class="containerIcon">
                        <i class="'. $link["icon"] .'"></i>
                        <h5>'. $link["name"] .'</h5>
                    </div>
                </a>
            </li>
            ';
        }
        echo '</ul>';
    }

?>