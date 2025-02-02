<script>
    function telechargerVideo(event) {
        event.preventDefault(); // Empêche le rechargement de la page
        const url = document.querySelector('input[name="youtube_url"]').value;

        // Afficher l'indicateur de chargement
        const overContent = document.querySelector('.overContent');
        overContent.style.flexDirection = 'column';

        const loadingIndicator = document.getElementById('loadingIndicator');
        loadingIndicator.style.display = 'block';

        // Désactiver le bouton pour empêcher plusieurs clics
        const button = document.querySelector('button[type="submit"]');
        button.disabled = true;

        fetch('telecharger.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'youtube_url=' + encodeURIComponent(url),
        })
            .then(response => response.json()) // convert la réponse JSON
            .then(data => {
                if (data.success) {
                    const { titre_video, file_url } = data;

                    // affiche le titre de la vidéo et prépare un lien de téléchargement
                    console.log(`Titre de la vidéo : ${titre_video}`);
                    const a = document.createElement('a');
                    a.href = file_url; // on donne le path
                    a.download = `${titre_video}.mp4`; // on donne un nom au fichier
                    document.body.appendChild(a);
                    a.click(); // simulation de click pour lancer le téléchargement
                    a.remove();

                    // masquer l'indicateur de chargement et réactiver le bouton
                    loadingIndicator.style.display = 'none';
                    button.disabled = false;
                } else {
                    console.error(data.error);
                    alert('Erreur : ' + data.error);
                    loadingIndicator.style.display = 'none';
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }
</script>