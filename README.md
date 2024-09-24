# Biblio Esprit

![Logo Symfony](https://symfony.com/images/logos/header-logo.svg)

## Description

This project aims to automate the process of reserving travel events. It provides an easy-to-use platform for users to browse, select, and book travel events seamlessly, with features for event management, user registration, and automated booking confirmation. The system is designed to handle a wide variety of travel events, such as tours, special offers, and packages, ensuring a smooth reservation experience for both users and administrators.

## Table des matières

- [Installation](#installation)
- [Utilisation](#utilisation)

## Installation

Follow these steps to install and set up the project on your local development environment:

Prerequisites
PHP 7.4 or higher
Composer
Symfony CLI (optional)
MySQL or PostgreSQL

```bash
git clone https://github.com/maramsaoudi/EpicJourneys
cd EpicJourneys
composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

## Utilisation

```bash
php bin/console server:start
Ou
symfony serve
# Ouvrez votre navigateur et accédez à http://localhost:8000
```
