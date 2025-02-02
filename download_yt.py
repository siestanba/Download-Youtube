import yt_dlp
import sys
import re
import os

def nettoyer_nom_fichier(nom):
    return re.sub(r'[^a-zA-Z0-9 _-]', '', nom)

def telecharger_video_youtube(url):
    """Télécharge une vidéo YouTube dans le dossier Téléchargements."""
    CACHE_DIR = "/var/www/html/sebastian.cafe/public_html/cache"
    DOWNLOAD_DIR = "/var/www/html/sebastian.cafe/public_html/downloads"
    COOKIES_FILE = "/var/www/html/sebastian.cafe/public_html/config/cookies.txt"

    try:
        with yt_dlp.YoutubeDL({
            'quiet': True,
            'cookiefile': COOKIES_FILE
        }) as ydl:
            # Extraire les informations de la vidéo
            info = ydl.extract_info(url, download=False)
            original_title = info['title']
            clean_title = nettoyer_nom_fichier(original_title)
            clean_path = os.path.join(DOWNLOAD_DIR, f"{clean_title}.mp4")


    
            options = {
                'format': 'mp4/bestvideo+bestaudio',
                'cachedir': CACHE_DIR,
                'outtmpl': clean_path,
                #'cookiesfrombrowser': ('chrome',),  # ou 'firefox', selon votre navigateur
                'cookiefile': COOKIES_FILE
            }
            with yt_dlp.YoutubeDL(options) as ydl_download:
                ydl_download.download([url])
            print(f"Téléchargement terminé. Vidéo sauvegardée dans : {DOWNLOAD_DIR}, {clean_title}")

    except Exception as e:
        print(f"Erreur : {e}")

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage : python telecharger.py <URL_YOUTUBE>")
        sys.exit(1)
    url = sys.argv[1]
    telecharger_video_youtube(url)