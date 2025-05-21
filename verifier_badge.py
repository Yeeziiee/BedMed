import serial
import requests
import time

# Configuration
PORT_SERIE = 'COM6'  # À adapter selon ton système (ex : /dev/ttyUSB0 sous Linux)
VITESSE = 9600
URL_API = 'http://localhost/arduino/verif.php?uid='

def nettoyer_uid(brut):
    # Supprime les espaces et convertit en majuscules
    return brut.strip().replace(" ", "").upper()

def verifier_uid(uid):
    url = URL_API + uid
    try:
        response = requests.get(url, timeout=3)
        return response.text.strip()
    except Exception as e:
        print("Erreur lors de l’appel HTTP:", e)
        return "ERREUR"

def main():
    try:
        ser = serial.Serial(PORT_SERIE, VITESSE, timeout=1)
        print("Connexion série établie sur", PORT_SERIE)
        time.sleep(2)  # Laisse le temps à l’Arduino de démarrer

        while True:
            ligne = ser.readline().decode('utf-8').strip()
            if ligne.startswith("UID tag :"):
                print(">> UID reçu :", ligne)
                uid_hex = nettoyer_uid(ligne.replace("UID tag :", ""))
                print(">> UID nettoyé :", uid_hex)

                resultat = verifier_uid(uid_hex)
                print(">> Résultat :", resultat)

                # (Optionnel) Tu peux renvoyer le résultat à l’Arduino :
                # ser.write((resultat + '\n').encode())

    except serial.SerialException:
        print(f"Erreur : port série {PORT_SERIE} inaccessible.")
    except KeyboardInterrupt:
        print("Arrêt manuel.")
    finally:
        if 'ser' in locals() and ser.is_open:
            ser.close()
            print("Port série fermé.")

if __name__ == '__main__':
    main()
