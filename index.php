<?php
declare(strict_types = 1);

use objectClass\tools\Tools;

require(__DIR__ . "/Layout/layoutHTML.php");
require(__DIR__ . "/provider/AppProvider.php");
Tools::checkIfUserGotCookieToVote();
declareHTML([
    "path" => "./",
    "stylesheet" =>[
        "style.css",
        "header.css",
        "fontIcons/css/fontawesome.css",
        "fontIcons/css/brands.css",
        "fontIcons/css/solid.css",
        "index.css",
    ],
    "title" => "Tirage au sort"
]);?>



<main>
    <section class="welcomeSection">
        <div>
            <section class="containerTitle">
                <h2>Hello world !</h2>
                <p>Bienvenue sur un site sans nom pour voter pour votre projet préféré où vous verrez les détails ci-dessus!</p>
            </section>
        </div>
    </section>

    <section class="explanation">
        <div class="centerExplanation">
            <section class="containerTitle">
                <h2>Comment est-ce que cela marche ?</h2>
                <i class="fa-solid fa-question"></i>
            </section>

            <div class="mainContainerParagraph">
                <section class="containerParagraph">
                    <div class="styleBorder"></div>
                    <div class="styleBorder"></div>
                    <div class="styleBorder"></div>
                    <div class="styleBorder"></div>
                    <p>Comment cela marche-t-il ? Rien de plus simple !</p>
                    <p>
                        Vous aurez plusieurs projets pour lequels vous allez pouvoir voter tels que la démocratie le souhaite, mais
                        il y a des règles, histoire de ne pas bourrer les urnes.
                    </p>
                    <h3><i class="fa-solid fa-book"></i> Les règles.</h3>
                    <ul>
                        <li>Vous ne pouvez voter qu'une fois par projet</li>
                        <li>Mais vous pouvez voter pour plusieurs projets différents.</li>
                    </ul>
                    <p>
                        Exemple : vous ne pouvez voter qu'une fois pour le projet #1 néanmoins vous pouvez toujours
                        voter pour le projet #2, #3, #4 etc...
                    </p>
                    <p>
                        Alors comment voter ? Grâce à un QR code, ou un simple bouton que vous n'allez pas pouvoir manquer !
                    </p>
                </section>
            </div>
        </div>

        <div class="containerShapeDivider">
            <div class="custom-shape-divider-bottom-1667323244">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
                </svg>
            </div>
        </div>

    </section>

    <section class="linkToProjects">
        <a href="./allActivities.php">
            <button class="push-button-3d">découvrir les projets</button>
        </a>
    </section>
</main>

<?php endHTML();?>