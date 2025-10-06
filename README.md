# 🧢 STUBBORN  
> **Site e-commerce de vente de sweats – Projet Symfony**

<p align="center">
  <img src="public/images/Logo.png" alt="Logo Stubborn" width="200"/>
</p>

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
- ✉️ Envoi d’emails avec **Mailer DSN**

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

## 📁 Structure du projet

```
STUBBORN/
├── assets/
│   ├── controllers/
│   └── styles/
├── config/
│   └── packages/
├── public/
│   ├── images/
│   └── index.php
├── src/
│   ├── Controller/
│   ├── DataFixtures/
│   ├── Entity/
│   ├── Form/
│   ├── Repository/
│   └── Security/
├── templates/
│   ├── checkout/
│   ├── home/
│   ├── panier/
│   ├── products/
│   ├── register/
│   └── base.html.twig
├── .env.example
└── README.md
```

---

## ⚙️ Installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/mystogan78/Stubborn.git

# 2. Installer les dépendances
composer install

# 3. Lancer le serveur Symfony
symfony server:start --port=8000
```

---

## 💳 Paiement de test Stripe

Pour tester le paiement, utilisez la carte suivante :

- **Numéro** : 4242 4242 4242 4242  
- **Expiration** : 12/34  
- **CVC** : 123  

---

## 🧾 Fonctionnalités principales

✅ Inscription et connexion utilisateur  
✅ Ajout et suppression d’articles dans le panier  
✅ Calcul automatique du total  
✅ Paiement sécurisé via Stripe  
✅ Enregistrement automatique des commandes  
✅ Affichage de l’historique des commandes  

---

## ⚙️ Configuration de l'environnement

Le projet utilise un fichier d’environnement `.env.local` pour les variables sensibles (base de données, clés API, etc.).  
👉 Ce fichier **n’est pas inclus dans le dépôt Git** pour des raisons de sécurité.

Un modèle est fourni :  
➡️ **`.env.example`**

### Étapes à suivre

```bash
# Dupliquer le modèle
cp .env.example .env.local
```

Ensuite, ouvrez le fichier `.env.local` et ajoutez vos informations :

```env
APP_ENV=dev
APP_DEBUG=1
APP_SECRET=votre_cle_secrete

DATABASE_URL="mysql://root:@127.0.0.1:3306/stubborn?serverVersion=8.0&charset=utf8mb4"

STRIPE_PUBLIC_KEY=pk_test_votre_cle_publique
STRIPE_SECRET_KEY=sk_test_votre_cle_secrete

MAILER_DSN=null://null
```

Puis videz le cache :
```bash
php bin/console cache:clear
```

---

## 👨‍💻 Auteur

**Ibrahim**  
Projet réalisé dans le cadre de la formation **Développeur Web**  
📦 GitHub : [mystogan78](https://github.com/mystogan78)

---

## 📜 Licence

Ce projet est distribué sous licence **MIT**.  
Vous êtes libre de le modifier et de le réutiliser à des fins éducatives.

