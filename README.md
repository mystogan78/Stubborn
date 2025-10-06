# 🧢 STUBBORN  
> **Site e-commerce de vente de sweats – Projet Symfony**

![Symfony](https://img.shields.io/badge/Symfony-7.3-black?style=for-the-badge&logo=symfony)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?style=for-the-badge&logo=php)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple?style=for-the-badge&logo=bootstrap)
![Stripe](https://img.shields.io/badge/Stripe-API-blueviolet?style=for-the-badge&logo=stripe)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

---

## 🛍️ Description du projet

**STUBBORN** est un site e-commerce permettant la vente en ligne de sweats de la marque *Stubborn*.  
Ce projet a été développé dans le cadre d’une **formation de développeur web** avec **Symfony 7.3**.  
L’objectif : proposer une boutique fonctionnelle avec authentification, panier et paiement sécurisé.

---

## 🚀 Fonctionnalités

- 👤 Création de compte utilisateur *(inscription / register)*
- 🔐 Connexion sécurisée *(login / logout)*
- 🛒 Gestion complète du panier
- 💳 Paiement en ligne via **Stripe**
- 📦 Consultation des commandes
- 🧰 Accès administrateur *(Back-office)*
- ✉️ Envoi de mails avec **Mailer DSN**

---

## 🛠️ Technologies utilisées

| Technologie | Version | Description |
|--------------|----------|-------------|
| **Symfony** | 7.3 | Framework PHP moderne |
| **PHP** | 8.2.12 | Langage backend |
| **Composer** | 2.8.10 | Gestionnaire de dépendances |
| **Bootstrap** | 5.3 | Design responsive |
| **Stripe API** | - | Paiement en ligne |
| **XAMPP** | - | Serveur local de développement |

---

## 🗂️ Structure du projet


## 📁 Structure du projet<br>

### STUBBORN/<br>
##### ASSET<br>
├── controllers/<br>
├── styles/<br>
##### CONFIG/<br>
├── packages/<br>
##### PUBLIC/<br>
├──images/<br>
##### SRC/<br>
├── controller/<br>
├── dataFixtures/<br>
├── entity/<br>
├── form/<br>
├── repository/<br>
├── security/<br>
##### TEMPLATES/<br>
├── checkout/<br>
├── home/<br>
├── loginformauthticator/<br>
├── panier/<br>
├── products/<br>
├── register/<br>
├── base.html.twig/<br>
⚙️.env<br>
💲.env.dev.local<br>
🔡README.md<br>


---

## ⚙️ Installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/mystogan78/MONPORTOFOLIO.git

# 2. Installer les dépendances
composer install

# 3. Lancer le serveur Symfony
symfony server:start --port=8000

💳 Paiement de test Stripe

Pour tester le paiement, utilisez la carte suivante :

Numéro : 4242 4242 4242 4242  
Date d’expiration : 12/34  
CVC : 123  

🧾 Exemple de fonctionnalités

✅ Inscription et connexion utilisateur
✅ Ajout et suppression d’articles dans le panier
✅ Calcul automatique du total
✅ Paiement sécurisé via Stripe
✅ Enregistrement automatique des commandes
✅ Affichage de l’historique des commandes

👨‍💻 Auteur

Ibrahim

Projet réalisé dans le cadre de la formation Développeur Web.
GitHub – mystogan78

📜 Licence

Ce projet est distribué sous licence MIT.
Vous êtes libre de le modifier et de le réutiliser à des fins éducatives.