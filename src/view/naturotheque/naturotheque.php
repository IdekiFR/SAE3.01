<body>
    <div class="contener1">
    
        <h1> Naturothèque </h1>
        
        <div class='flex-contenaire'>
            <?php
                foreach($users as $values){
                $age = date_diff(date_create($values['dnaissance']), date_create('now'))->y;
                echo"<div class='flex-box'>
                    <div class='card-top'>
                        <img class= 'image' src={$values['photo_profil']} alt=''>
                    </div>
                    <div class='carteInfo'>
                        <h2>{$values['prenom']} {$values['nom']}</h2>
                        <h3>Age : $age  &nbsp;&nbsp;  sexe : {$values['sexe']} </h3>
                    </div>
                        <button  class='btn'>Découvrir</button>
                    <div>
                        <p>Débloquez ce fichier et bénéficiez d’un accès illimité à plus de 76 235 images Premium. 
                        </p>
                    </div>
                    <div class='description'>
                        <h3> Taille de la naturothèque </h3>
                        <p>9 espèces enregistrées</p>
                    </div>

                </div>";
                
            }
            ?>   
        </div>
</body>



