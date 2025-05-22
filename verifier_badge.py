import serial
import requests
import time

PORT_SERIE = 'COM6'  
VITESSE = 9600
URL_PHP = 'http://localhost/BedMed/badge/verifier.php?uid='

def nettoyer_uid(ligne):
    return ligne.replace("UID tag :", "").replace(" ", "").strip().upper()

def verifier(uid):
    try:
        r = requests.get(URL_PHP + uid, timeout=3)
        return r.text.strip()
    except Exception as e:
        print("[ERREUR HTTP] ", e)
        return "ERREUR"

def main():
    try:
        ser = serial.Serial(PORT_SERIE, VITESSE, timeout=1)
        time.sleep(2)
        print("[INFO] Port série ouvert")

        while True:
            ligne = ser.readline().decode('utf-8').strip()
            if ligne.startswith("UID tag :"):
                print("-> UID reçu :", ligne)
                uid = nettoyer_uid(ligne)
                print("-> UID nettoyé :", uid)

                resultat = verifier(uid)
                print("-> Résultat :", resultat)

                ser.write((resultat + '\n').encode())

    except KeyboardInterrupt:
        print("Arrêté par l'utilisateur.")
    finally:
        ser.close()

if __name__ == '__main__':
    main()