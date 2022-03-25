# Parcours OpenClassrooms: Développeur d'application PHP/Symfony

## Projet 7: Créer un web service exposant une API
-----------------------------------------------

## Description

The goal of this project was to create an API for a phone company named **Bilemo**.  
The company does not intent on selling its products through a website, but rather directly to resellers, who would then
present them to their own customers.

As a consultant for this company, I had to expose a few endpoints for the future resellers,
namely: 
- Being able to connect to the API via OAuth or JWT.
- Once connected, the reseller must:
    - Be able to access the list of all Bilemo products (**Phones**)
    - Be able to access the details of one of the **Phones**
    - Be able to access the list of all its **Customers**
    - Be able to add, update and delete one of its **Customers**


**NOTA BENE :**  
The back end has been realised in **PHP 8.1**, **Symfony 6.0.3** and **APIPlatform 2.6**

## Table of contents

- [Installation](#Installation)
    - [Prerequisites](#Prerequisites)
        - [Git](#Git)
        - [Docker](#Docker)
        - [Docker-compose](#Docker-Compose)
    - [Clone](#clone)

- [Configuration](#configuration)
- [Getting started](#getting-started)
- [Api usage](#api-usage)

## Installation

### Prerequisites

#### Git

To be able to locally use this project, you will need to install [Git](https://git-scm.com/) on your machine.  
Follow the installation instructions [here](https://git-scm.com/downloads) depending on your Operating system.

#### Docker

This project runs 3 separate applications each in their own containers:

1. The PostgreSql DataBase
2. The Caddy Server
3. The PHP/Symfony application itself

Each is based upon its own Docker Image.  
To have them run locally, you will need to install [Docker](https://www.docker.com/) on your machine.  
Follow the installation instructions [here](https://docs.docker.com/get-docker/) for most OS
or [here](https://wiki.archlinux.org/title/Docker) for Archlinux.

#### Docker Compose

As defined on the documentation:
> Compose is a tool for defining and running multi-container Docker applications.

Since it is our case in this project, we also chose to use compose to build the complete project.  
You can see how to install it [here](https://docs.docker.com/compose/install/)

### Clone

Move to the parent directory where you wish to clone the project.

```shell
git clone https://github.com/JacquesDurand/p7_oc_bilemo.git
```

Then move into the newly cloned directory

```shell
cd p6_oc_bilemo
```

## Configuration

This project relies on the use of environment variables, which act as *secrets*. These are for instance the database
connection information.  
To override the examples given in `.env` or `.env.dist`, create your local file:

```shell
cp .env.dist .env.local
```

Then open your newly created **.env.local** with [your favorite text editor](https://neovim.io/) and replace the different *"
CHANGEME"* values by your own.


## Getting Started

### Launching the project

Now that everything has been configured, let us get into it !  
Still at the root of **p7_oc_bilemo**, run :

```shell
make install
```
This will: 
- Check that **Docker** and **Docker-Compose** are installed on your machine.
- Check that the necessary ports ( 80 and 443 for the web server, and 49154 for the database) are not already used.
- Build the Docker image.
- Start the containers.
- Wait for the containers to accept TCP connection.
- Create the database and fill it with fixtures.
- Generate the keypair used for building and verifying the JWT


If everything went fine, you should be able to navigate to [localhost](http://localhost:80) and start interacting with the API.

If not, please do not hesitate to [submit an issue](https://github.com/JacquesDurand/p7_oc_bilemo/issues/new) and I'll get
back to you *ASAP*.

## API Usage

The API comes with an **OpenAPI** documentation (formerly **Swagger**) that should
be self-explanatory, but do not forget:

To be able to access any of the resources, you must be authenticated first.
I created two fixtures account with non-randomized credentials to facilitate this part:

One **Administrator** (with ROLE_ADMIN) and the following credentials:
```json
{
    "username": "admin@admin.com",
    "password": "admin"
}
```
One **Reseller** (who can only GET **Phones** and CRUD on its own **Customers**):

```json
{
    "username": "admin",
    "password": "admin"
}
```
| *Note*: |
|---------|
If somehow the admin **Reseller** did not get any **Customer** attached during fixture loading,
```shell
make db-reset
```
might help.
